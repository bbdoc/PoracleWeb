# Migrating PoracleWeb to the PoracleNG v2 API

**Status:** implemented, 2026-10-09; updated 2026-10-24 for the PoracleNG 5.4.0 v2 changes (this document matches what was built)
**Target:** PoracleNG (v5+) only. PoracleJS has no v2 API and is not supported.

**Minimum PoracleNG:** 5.4.0 (currently the `develop` branch). PoracleWeb relies on:

- jfberry/PoracleNG#248: `DELETE /api/v2/humans/{id}/location`, and v2 tracking distance clamped to `[tracking] max_distance`;
- jfberry/PoracleNG#249: v2 `ping` on every rule type, and a canonical diff so wildcard-alias rows don't duplicate on GET then POST;
- jfberry/PoracleNG#303: v2's own typed human and profile shapes, `latitude`/`longitude` in every request, and edits that keep the rule's `uid`;
- jfberry/PoracleNG#328: the bulk `PATCH` of rule settings.

Earlier v2 builds reject PoracleWeb's location requests (they expected `lat`/`lon`) and have no bulk `PATCH`.

## Goal

Replace every direct read and write of the Poracle database with calls to PoracleNG's `/api/v2`. When this is done:

- PoracleWeb no longer depends on the Poracle schema.
- PoracleNG validates every write.
- PoracleNG reloads its state after every write.
- New tracking types (`incident`, `maxbattle`, `fort`) are available to PoracleWeb.

The scanner DB (gym picker, gym names) is out of scope and stays as it is.

The v2 contract is the OpenAPI spec at `GET {api}/openapi.json`, with interactive docs at `{api}/docs`. When the spec and PoracleNG's markdown docs disagree, the spec is right.

## Decisions

| Topic | Decision |
|---|---|
| Backend support | PoracleNG only |
| Detecting v2 | Call `GET /health`. If the response has a `capabilities` object, the server is PoracleNG with v2. If not, stop with a clear "PoracleNG required" error. PoracleJS never returned `capabilities`. |
| Multiple Poracle instances | Supported. Each instance is `{name, api_address, api_secret}` (see §3). |
| Account migration (Discord → Telegram) | Copy the old account to the new one through the API: read everything, write it to the new id, then delete the old human (§6). |
| Clearing a location | `DELETE /v2/humans/{id}/location?profile=` (PoracleNG change). |
| `max_distance` | PoracleNG clamps every v2 distance to `[tracking] max_distance` (PoracleNG change). PoracleWeb also checks against `maxDistance` from `config/poracleWeb` before sending, so it can show a message. |

The clear-location and `max_distance` changes are PoracleNG#248. `ping` on every rule type and the canonical diff are PoracleNG#249.

## 1. API client: `include/poracle_client.php`

This is one class that replaces the `file_get_contents` calls scattered through the code.

- It uses curl and supports GET, POST, PUT, PATCH and DELETE.
- Request bodies are JSON, and every request sends `X-Poracle-Secret`.
- Each instance of the class is bound to one Poracle instance.
- Successful responses are decoded JSON.
- Responses with status 400 or above are parsed as RFC 9457 `problem+json`. The client throws a `PoracleApiException` that carries `status`, `detail` and `errors[]` (`message`, `location`, `value`).
  - `actions/*.php` turn this exception into the existing session flash/redirect messages.
- Requests have a short timeout. A failure to connect is shown as "Poracle unavailable"; the page should not hang.
- The class exposes typed helpers for the operations below, so pages never build URL paths themselves.

## 2. Reading data: the snapshot

`GET /v2/humans/{id}/tracking?all_profiles=true` returns the human, all tracking rules (each with its `profile_no`), profiles, saved locations, summaries and mutes.

PoracleWeb fetches it **once per request**, memoized in a function such as `poracle_snapshot()`. It is reloaded only after a write in the same request.

These existing reads become in-PHP computations over the snapshot:

| Today | Source |
|---|---|
| `header.php` profile, area, location, `enabled`/`admin_disable`, `min(clean)` per type | snapshot |
| `include/toplinks.php` counts, `dashboard.php` counts | snapshot, filtered by the current profile |
| `default_distance()` (most common distance per type) | snapshot |
| `modal/distance_*_modal.php` (distinct distances) | snapshot |
| Every `pages/display/*.php` list, including the pokemon search filter (`$search_sql`) | snapshot, filtered in PHP |
| Registration check (`header.php:186`) | `GET /v2/humans/{id}`: 404 means unregistered |
| `set_locale()` reading `humans.language` | snapshot `human.language` |

The `human` resource keeps v1-style encodings: `area` and `community_membership` are JSON strings, and `enabled` and `admin_disable` are ints. Rules use v2 encodings: wildcards come back as `null`, there are enum strings, and `clean`/`edit`/`summary` are booleans.

### Pages render v2 rules natively; null = any

The display pages and edit modals read v2 rules directly. A `null` field means "any". There is no legacy-row adapter, and `$monster_defaults` and the old sentinel literals (`9000`, `team == 4`, `min_iv == -1` and so on) are removed.

## 3. Multiple instances

Today, `config.env.php` lets `$dbname` hold several databases, but `api_address` and `api_secret` are single `pweb_settings` rows.

- An instance is a database listed in `$dbname`. Its `api_address` and `api_secret` come from that database's `pweb_settings` rows, edited on the server settings page.
- `session.php` finds the logged-in user's instance by calling `GET /v2/humans/{id}` against each instance, skipping instances that error. That replaces the per-DB `SELECT * FROM humans`.
  - It stores `$_SESSION['instance']` in place of `$_SESSION['dbname']`.
- Admin lists call `GET /v2/humans?type=` on every instance and merge the results, tagging each row with its instance. This covers `manage_users`, `manage_channels` and `admin_sync`.
- Impersonation (`admin_connect.php`) looks the target up with `GET /v2/humans?id=X` on each instance.
- `pweb_settings` currently lives inside the Poracle DB. While migrating, keep reading it through a separate connection used only for settings. Moving settings out of the Poracle DB (to a file, SQLite or env) can come later; after phase 5 it is the only remaining Poracle-DB dependency.

## 4. Writing tracking rules

### Operations

| PoracleWeb action | v2 call |
|---|---|
| Add (any type) | `POST /v2/humans/{id}/tracking/{type}?profile={p}` with an **array** body, one element per selected item. Report `created` and `unchanged`; an item in `unchanged` means the rule already exists. |
| Edit one rule (form) | `GET …/{type}/{uid}`, merge the form fields into the returned rule, then `PUT …/{type}/{uid}`. **PUT replaces the whole rule** (fields left out reset to defaults) and the rule keeps its `uid`. Fields the form doesn't show are kept by the merge. A `409` means the edit would make the rule duplicate another one; it is shown as an error. Edits to `:user` destinations do not clear `ping`. |
| Delete one | `DELETE …/{type}/{uid}` |
| Delete all on a profile | Take the uids from the snapshot, then `DELETE …/{type}?uid=1,2,3` |
| Set distance on all; cleaning toggles | Take the uids from the snapshot, then `PATCH …/{type}?profile={p} {uids, set:{field: value}}` (one request per type). See *Bulk update rule* below. |

Every item operation is scoped by `(human, uid)`. This fixes today's ability to edit or delete another user's rule by posting their uid.

### Bulk update rule

`PATCH …/tracking/{type}` with `{"uids":[…],"set":{…}}` changes delivery settings (`distance`, `clean`, `edit`, `summary`, `template`, `ping`) on the listed rules. Each rule keeps its `uid` and every field not named. In `set`, `null` means "leave as stored", so PoracleWeb clears a setting by sending its "off" value: `distance: 0` (match by area) or `clean: false`.

A bulk distance leaves out rules that carry their own scope: a rule tied to a saved location (`override_location_label`) must keep a radius, and a rule limited to `override_areas` can't have one. Removing the location therefore leaves saved-location rules as they are.

The response has one outcome per rule: `updated`, `unchanged` or `refused`. A rule is refused when the change would make it duplicate another rule (`conflict`), or when it no longer exists (`not_found`). Refusals don't stop the rest of the request. PoracleWeb applies the whole change and then shows how many rules were left alone, and why.

Adding rules still uses `POST`, which upserts: a posted rule that exactly matches a stored one is `unchanged`, and one that differs in a single updatable field updates that rule in place.

### Field mapping (form → v2)

Common to all types:

- Never send sentinel values. Leave the field out for "any".
- `clean` is now three booleans, `clean`, `edit` and `summary`. Today's cleaning toggle overwrites the whole bitmask and wipes the `edit` and `summary` bits; the v2 booleans fix that.
- `template`: leave it out to use the server default. PoracleWeb no longer needs to fill in `defaultTemplateName`.
- `ping` is a string, and is available on every rule type (PoracleNG#249).
- `distance`: `0` means use areas, not a radius.

| Type | Changes |
|---|---|
| pokemon | `noiv` becomes "omit `min_iv`" (no more `-1`). `size: -1` becomes "omit `size`/`max_size`". PvP uses `pvp_ranking_league` (`500`/`1500`/`2500`), `pvp_ranking_best`/`pvp_ranking_worst`, and `pvp_ranking_cap`. Omit `max_*` ceilings. `gender` is a string enum. Quick-pick presets build the same bodies. |
| raid | By level: `{level: n}`. By boss: `{pokemon_id, form}`. **Mega is now stored** through `evolution`. Drop `team: 4`; leave `team` out for any team. `gym_id`, and `rsvp_changes` as `none`/`rsvp`/`rsvp_only`. |
| egg | `{level, gym_id?, rsvp_changes?}` |
| quest | `reward_type` (`7` pokemon, `2` item, `12` mega energy, `4` candy) plus `reward`, and `form` for pokemon rewards. Now possible: `shiny`, `amount`, and `reward` left out (meaning "all of this category"). |
| invasion | `grunt_type` is the lowercased name PoracleWeb already sends, with `gender`. `$other_grunt_types` (`gold-stop`, `kecleon`, `showcase`) are pokestop events and **move to the `incident` type** (`display_type`). |
| lure | `lure_id` (`0` means any lure) |
| nest | `pokemon_id`, `form`, `min_spawn_avg` |
| gym | `team` enum, `slot_changes`, `battle_changes`. Now possible: `gym_id`. |
| New | `incident` has add/list pages. `maxbattle` and `fort` have **no** add or list pages (not built); they are still carried through channel sync, account migration and the location-clear distance reset. |

`reloadPokemon()` and `/api/tracking/pokemon/refresh` are removed, because every v2 write triggers a reload.

## 5. Humans, profiles, locations

| PoracleWeb action | v2 call |
|---|---|
| Global alerts toggle (`actions/profile.php`) | `POST /v2/humans/{id}/enable` or `POST /v2/humans/{id}/disable` |
| Language | `POST /v2/humans/{id}/language {language}`. The server checks it against the configured locales. |
| Set location (`actions/set_location.php`) | `POST /v2/humans/{id}/location?profile={p} {latitude, longitude}`. Geocoding stays in PoracleWeb. A 403 means the location is outside the user's allowed areas. |
| Delete location | `DELETE /v2/humans/{id}/location?profile={p}`, then a bulk `PATCH` of `distance: 0` on **all 11** types for that profile. Previously only 5 types were reset. |
| Areas | `POST /v2/humans/{id}/areas?profile={p} {areas:[…]}`. Names are matched against the fences the user may select; unmatched names come back in `rejected`. This replaces the hand-built JSON. |
| Available areas | `GET /v2/humans/{id}/areas` |
| Create profile | `POST /v2/humans/{id}/profiles {name}`. The response includes `profile_no`. The gap-filling query is removed. |
| Rename profile | `PATCH /v2/humans/{id}/profiles/{n} {name}` |
| Delete profile | `DELETE /v2/humans/{id}/profiles/{n}`. The server deletes all of that profile's tracking, which fixes today's orphaned nest and gym rows. |
| Activate profile | `POST /v2/humans/{id}/profile {profile_no}` |
| View a profile (no activation) | Session only, but check it against the snapshot's `profiles` |
| Roles | `GET /v2/humans/{id}/roles`, `POST`/`DELETE /v2/humans/{id}/roles/{roleId}` |
| Delegated admin rights | `GET /v2/humans/{id}/admin-roles` |

These endpoints stay on the existing unversioned `/api/*`:

- `config/poracleWeb`
- `config/templates`
- `geofence/*` maps and hashes
- `masterdata/grunts`

## 6. Admin features

- **Channel sync** (`actions/channel_sync.php`): read the viewed profile's rules from the source snapshot. For each target, bulk-delete the rules of its **active** profile per type, then POST the source rules **without `uid`**. This copies all 11 types and every field, unlike the old partial `INSERT…SELECT`. Targets can be on any instance.
- **Raid "delete all"** is per profile.
- **Gym cleaning toggle** has its own checkbox.
- **Account migration** (`actions/migrate.php`) goes through the API. The Telegram login is verified first, and the migration is bound to the session that started it.
  1. Read the old human, with the snapshot including all profiles.
  2. `POST /v2/humans` to create the new id, with the same name, language, areas and community.
  3. Recreate the profiles, then the saved locations, then POST each profile's rules. If the active profile has no `profiles` entry (common for humans that came from PoracleJS), it is recreated from the human's own area and location. Rules left on a deleted profile can never match, so they are not copied.
  4. `DELETE /v2/humans/{old}` only after every step succeeds. If a step fails, the partly created new human is deleted (rolled back) and the error is reported.
- **Admin authorization stays PoracleWeb's job.** The API secret can act on any human.
  - Every admin action (including `server_settings`, `channel_sync` and `migrate`) requires admin rights.
  - In `admin_connect.php`, a delegated admin can only impersonate destinations they manage.

## 7. Phases

All phases are implemented, with one exception: the add/list pages for `maxbattle` and `fort` were not built.

| Phase | Scope |
|---|---|
| 0 | API client, instance handling, v2 detection, error rendering |
| 1 | Snapshot; every read switched (§2) |
| 2 | Tracking writes for all types; display pages render v2 rules natively |
| 3 | Humans, profiles, locations, areas, language, roles (§5) |
| 4 | Admin lists, impersonation, channel sync, migration (§6); `incident` page |
| 5 | Poracle `mysqli` connection removed (only `pweb_settings` remains) |

## Tests

PHPUnit runs via Docker (see the README, "Running tests"). An opt-in integration suite runs against a live PoracleNG and is skipped unless `PORACLE_TEST_API` is set.

## Risks

- **Copy round-trip fidelity.** Channel sync and the migration POST rules read back from a GET; a column missing from the v2 schema would produce duplicate rules. PoracleNG#249 fixes the known case, and the integration suite checks it.
- **Request count.** A page now costs one snapshot request. The header runs on every page, so the snapshot must be memoized, not fetched separately in each include.
- **Profiles without a `profiles` entry.** A legacy human's active profile may have no entry. The cleaning toggles and the migration take profile numbers from the human and the rules as well as `profiles`, so these rules are not skipped.
