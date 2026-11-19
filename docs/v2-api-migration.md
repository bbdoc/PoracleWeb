# Migrating PoracleWeb to the PoracleNG v2 API

**Status:** plan, 2026-10-09
**Target:** PoracleNG (v5+) only. PoracleJS has no v2 API and is no longer supported once this lands.

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

The clear-location and `max_distance` changes are on PoracleNG branch `feat/v2-location-clear-max-distance`.

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

### Adapter first, rewrite later

The display pages and edit modals check DB conventions directly (`pokemon_id == 9000`, `team == 4`, `clean & 1`, `min_iv == -1`). Phase 1 adds `include/v2_legacy_rows.php`, which converts v2 rules back into the old row shape so these pages can switch to the snapshot without other changes. Phase 2 rewrites each page to use v2 fields natively as its type is migrated, and the adapter is deleted at the end.

## 3. Multiple instances

Today, `config.env.php` lets `$dbname` hold several databases, but `api_address` and `api_secret` are single `pweb_settings` rows.

- Replace these with an instance list: `{name, api_address, api_secret}`. It is configured in `config.php`/env and editable on the server settings page.
- `session.php` finds the logged-in user's instance by calling `GET /v2/humans/{id}` against each instance. That replaces the per-DB `SELECT * FROM humans`.
  - It stores `$_SESSION['instance']` in place of `$_SESSION['dbname']`.
- Admin lists call `GET /v2/humans?type=` on every instance and merge the results, tagging each row with its instance. This covers `manage_users`, `manage_channels` and `admin_sync`.
- Impersonation (`admin_connect.php`) looks the target up with `GET /v2/humans?id=X` on each instance.
- `pweb_settings` currently lives inside the Poracle DB. While migrating, keep reading it through a separate connection used only for settings. Moving settings out of the Poracle DB (to a file, SQLite or env) can come later; after phase 5 it is the only remaining Poracle-DB dependency.

## 4. Writing tracking rules

### Operations

| PoracleWeb action | v2 call |
|---|---|
| Add (any type) | `POST /v2/humans/{id}/tracking/{type}?profile={p}` with an **array** body, one element per selected item. Report `created` and `unchanged`; an item in `unchanged` means the rule already exists. |
| Edit one rule (form) | `GET …/{type}/{uid}`, merge the form fields into the returned rule, then `PUT …/{type}/{uid}`. **PUT replaces the whole rule** (fields left out reset to defaults) and returns a **new uid**. |
| Delete one | `DELETE …/{type}/{uid}` |
| Delete all on a profile | Take the uids from the snapshot, then `DELETE …/{type}?uid=1,2,3` |
| Set distance on all; cleaning toggles | Take the rules from the snapshot, change the **one** field, then `POST` the array back (one request per type). See *Bulk update rule* below. |

Every item operation is scoped by `(human, uid)`. This fixes today's ability to edit or delete another user's rule by posting their uid.

### Bulk update rule

`POST` upserts. A posted rule counts as an **update** only if it matches a stored rule on every field except exactly **one** updatable field. The updatable fields are:

- `distance`, `clean` and `template` on every type;
- `min_iv` on pokemon;
- `slot_changes` and `battle_changes` on gym.

If two or more fields differ, or the difference is in any other field (including `ping`), the posted rule is inserted as a **new** rule and the old one is kept. So:

- Bulk operations change exactly one field per request. "Set distance" and "toggle clean" are separate requests.
- Edit forms always use PUT.
- **Check before phase 2.** POST an unmodified GET result of real rules that the old PoracleWeb wrote, and confirm the response is entirely `unchanged`. Any rule that comes back as `created` is a column the v2 schema doesn't round-trip, and it has to be resolved first.

### Field mapping (form → v2)

Common to all types:

- Never send sentinel values. Leave the field out for "any".
- `clean` is now three booleans, `clean`, `edit` and `summary`. Today's cleaning toggle overwrites the whole bitmask and wipes the `edit` and `summary` bits; the v2 booleans fix that.
- `template`: leave it out to use the server default. PoracleWeb no longer needs to fill in `defaultTemplateName`.
- `ping` is a string.
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
| New | `incident`, `maxbattle`, `fort`: new add/list pages (phase 4) |

`reloadPokemon()` and `/api/tracking/pokemon/refresh` are removed, because every v2 write triggers a reload.

## 5. Humans, profiles, locations

| PoracleWeb action | v2 call |
|---|---|
| Global alerts toggle (`actions/profile.php`) | `POST /v2/humans/{id}/enable` or `POST /v2/humans/{id}/disable` |
| Language | `POST /v2/humans/{id}/language {language}`. The server checks it against the configured locales. |
| Set location (`actions/set_location.php`) | `POST /v2/humans/{id}/location?profile={p} {lat, lon}`. Geocoding stays in PoracleWeb. A 403 means the location is outside the user's allowed areas. |
| Delete location | `DELETE /v2/humans/{id}/location?profile={p}`, then a bulk `distance: 0` POST on **all** types for that profile. Today's code only resets 5 types. |
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

- **Channel sync** (`actions/channel_sync.php`): read the source snapshot. For each target, bulk-delete its rules per type, then POST the source rules **without `uid`**. This copies every type and every field, unlike today's partial `INSERT…SELECT`. Targets can be on any instance.
- **Account migration** (`actions/migrate.php`). The Telegram login hash **must** be verified first; today it is not.
  1. Read the old human, with the snapshot including all profiles.
  2. `POST /v2/humans` to create the new id, with the same name, language, areas and community.
  3. Recreate the profiles, then POST each profile's rules, then the saved locations.
  4. `DELETE /v2/humans/{old}` only after every step succeeds. If a step fails, delete the partly created new human and report the error.
- **Admin authorization stays PoracleWeb's job.** The API secret can act on any human.
  - Add admin checks to every `actions/*.php` that needs one. Today, none of them check, including `server_settings`, `channel_sync` and `migrate`.
  - In `admin_connect.php`, check that a delegated admin is allowed to manage the target.

## 7. Phases

| Phase | Scope | Exit criterion |
|---|---|---|
| 0 | API client, instance list and settings UI, v2 detection, error rendering | Login and session work through the API on one or more instances |
| 1 | Snapshot plus legacy-row adapter; switch every read (§2) | No `SELECT` against Poracle tables outside `actions/` |
| 2 | Tracking writes in this order: pokemon (with quick-pick) → raid/egg → quest → invasion + incident → lure, nest, gym. Rewrite display pages natively and drop the adapter per type. | No tracking-table SQL left |
| 3 | Humans, profiles, locations, areas, language, roles (§5) | No `humans`/`profiles` SQL left |
| 4 | Admin lists, impersonation, channel sync, migration (§6); new type pages (incident, maxbattle, fort) | — |
| 5 | Remove the Poracle `mysqli` connection (only `pweb_settings` remains) and `$min_poracle_version` | Poracle DB credentials only used for settings, or not at all |

## Risks

- **Bulk round-trip fidelity.** A column missing from the v2 schema produces duplicate rules (see §4). Run the check before phase 2.
- **New uids.** PUT, and updates made through POST, return new uids. Pages must re-read or use the returned uid; they can't keep the old one.
- **Request count.** A page now costs one snapshot request. The header runs on every page, so the snapshot must be memoized, not fetched separately in each include.
- **The `human` resource's legacy encodings** (JSON-string `area`, int flags) may change in a future v2 revision. Parse them in one place only.
