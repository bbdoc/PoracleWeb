# PoracleWeb → PoracleNG v2 API Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Stop reading and writing the Poracle database directly. PoracleWeb will do everything through the PoracleNG `/api/v2` API, working with v2 rules as they come back, where `null` means "any". PoracleWeb will no longer hard-code values like 9000, -1 or 4096.

**Architecture:**
- **API client.** One curl-based client, `PoracleApi`, has one instance per Poracle instance. An instance is one of the databases listed in `$dbname`; its API address and secret come from that database's `pweb_settings`.
- **Reads.** All reads come from one snapshot per request, fetched with `GET /api/v2/humans/{id}/tracking?all_profiles=true` and memoized. Display pages render the v2 rules directly.
- **Writes.** Writes go through small functions that take a `PoracleApi` instance as a parameter, so tests can pass in a fake one. Pure "form → v2 rule" builders sit between the `$_POST` data and those functions.
- **Upstream changes.** Two PoracleNG changes come first. They expose `ping` on v2, and they make the v2 diff ignore which of several equivalent "any" values a row happens to store.

**Tech Stack:**
- PHP 8.1 (Docker `php:8.1-cli` for tests, `php:8.1-apache` in production) with the curl extension.
- PHPUnit 9 via Composer (Docker `composer:2`).
- PoracleNG (Go) for the two upstream tasks.

**Spec:** `docs/v2-api-migration.md`, as amended by the decisions below. Task 22 writes them back into the spec.

## Decisions taken after the spec was written (these override the spec)

1. **No legacy-row adapter.** Pages and actions work with v2 rules directly, so `null` is the wildcard. Delete `$monster_defaults` and every sentinel literal; forms show "any" as an empty field.
2. **`ping` is added to v2 upstream** (Task 1). Today v2 always stores `""`. That erases every user's ping on PUT and makes a bulk POST create a duplicate rule.
3. **Upstream canonical diff** (Task 2). A stored pokemon row can hold `pvp_ranking_best=0` or `pvp_ranking_worst=0` (PoracleWeb's own `$monster_defaults` writes `pvp_ranking_best=0`), or `rarity=0` or `size=0`. v2 reads all of these as `null`, but writes `null` back as 1, 4096 or -1. The diff then sees the rule as new, so a GET → POST of an unchanged rule inserts a duplicate. In the local DB this affects 48 of 90 monster rules. The fix makes the diff compare stored rows in canonical form.
4. **Instances = databases in `$dbname`**, as today. Each instance's API address and secret come from its own `pweb_settings`. Nothing changes in configuration.
5. **Bulk changes** (set distance on all, cleaning toggles) are done as GET snapshot → change one field → one POST per type. This is safe once Task 2 has landed.
6. **Account migration** reads the old account and writes it to the new id through the API. **Admin checks** are added to every privileged action.
7. **PoracleNG only.** If `/health` has no `capabilities` object, PoracleWeb refuses to start a session.

## Global Constraints

- PHP code must run on PHP 8.1. Composer says `php >= 7.0`; don't use 8.x-only syntax (no named arguments, no enums, no `match`, no readonly). `??` and arrays are fine.
- Every v2 request sends `X-Poracle-Secret`. Booleans in query strings are sent as `true`/`false`. Tracking-create bodies are always a JSON **array**.
- **Never send a sentinel.** A filter at "any" is either left out or sent as `null`.
- Every tracking call passes `?profile=` explicitly (the profile being viewed, `$_SESSION['profile']`). Never rely on the server's choice of active profile.
- Removing a rule from a request body: strip the response-only keys `uid`, `description` and `profile_no` before PUT or POST.
- Match the existing code style: procedural PHP, 3-space indent in the root and `include/` files, 2-space indent in `actions/`, `i8ln()` for every user-visible string, and redirects of the form `$redirect_url?type=display&page=<page>&return=<code>`.
- **PoracleWeb branch:** `feat/v2-api-migration`, based on `docs/v2-api-migration-plan`, in a worktree. Push only to the `fork` remote (`jfberry/PoracleWeb`), never to `origin` (bbdoc).
- **PoracleNG branch:** `feat/v2-ping-canonical-diff` off `origin/develop`, in a worktree. Pushing to PoracleNG `origin` is fine.
- The user has an uncommitted change to `pages/display/server_settings.php` on their main checkout. Task 21 edits that file, so keep the edit to the minimum described there.

**Commands used throughout:**

| Purpose | Command |
|---|---|
| Unit tests | `docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit` |
| Single test | `docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit --filter <name>` |
| Lint one file | `docker run --rm -v "$PWD":/app -w /app php:8.1-cli php -l <file>` |
| Composer | `docker run --rm -v "$PWD":/app -w /app composer:2 composer <args>` |
| Integration tests (opt-in) | `docker run --rm -v "$PWD":/app -w /app -e PORACLE_TEST_API=http://host.docker.internal:3030 -e PORACLE_TEST_SECRET=<secret> -e PORACLE_TEST_HUMAN=<id> php:8.1-cli vendor/bin/phpunit --testsuite integration` |

## Review Focus

These are the five failure modes most likely to hurt real users. Each has a test pinned in the task that owns it.

1. **Editing a rule must not lose fields the form doesn't show.** This covers `edit`, `summary`, `override_areas` and `ping` on users with no ping box. The edit is GET → merge → PUT, so the form's fields overwrite and everything else is kept. Pinned in Task 6 (`testUpdateKeepsFieldsTheFormDoesNotSend`).
2. **Bulk "set distance" or "toggle clean" must not duplicate rules.** This includes rules first written by the bot or by the old PoracleWeb with alias values. Pinned in Task 2 (Go) and Task 8 (integration, `testSetFieldOnLegacyAliasRowsCreatesNoDuplicates`).
3. **An API error must show as a message, never a white page or a partial write followed by "success".** Pinned in Task 4 (problem+json parsing), and in Tasks 10–17, where each action wraps its calls with `poracle_fail()`.
4. **The profile being viewed (not the active one) is the one changed.** Pinned in Task 6 (`testAddSendsExplicitProfile`) and by the `?profile=` constraint.
5. **A form field left at "any" sends nothing.** For example, an empty max IV or `size_-1` must not send 100 or -1. Pinned in Task 7 (`testBlankFieldsAreOmitted`, `testSizeAnyOmitsBoth`).

---

# Part A — PoracleNG upstream (repo `~/GolandProjects/PoracleNG`)

Set up once before starting Part A:

```bash
cd ~/GolandProjects/PoracleNG && git fetch origin
git worktree add .worktrees/v2-ping-canonical-diff -b feat/v2-ping-canonical-diff origin/develop
cd .worktrees/v2-ping-canonical-diff/processor
```

PR #248 (`feat/v2-location-clear-max-distance`) touches the same `translateV2*` functions, one line away. Whichever PR merges second will need a trivial rebase.

### Task 1: Expose `ping` on every v2 tracking rule

**Files:**
- Modify: the request and rule structs plus the `translateV2*` and `*RowToRule`/`ToRule` functions in `processor/internal/api/v2_pokemon.go`, `v2_raid.go`, `v2_egg.go`, `v2_quest.go`, `v2_invasion.go`, `v2_incident.go`, `v2_lure.go`, `v2_nest.go`, `v2_gym.go`, `v2_fort.go` and `v2_maxbattle.go`
- Test: `processor/internal/api/v2_ping_test.go` (new)
- Modify: `processor/internal/api/testdata/openapi.golden.json` (regenerated), `CHANGELOG.md`, `docs/v1-to-v2-migration-guide.md`

**Interfaces:**
- Produces: every v2 rule (request and response) gets an optional `ping` string. When it's left out or empty, `""` is stored; on read, `""` comes back as `null`.

- [ ] **Step 1: Write the failing test.** Use the existing pokemon test harness (`newV2PokemonTestAPI` in `v2_pokemon_test.go`, which returns the router, the store, the pushes and a restore func) together with `roundTripRule`, `singleCreatedUID` and `getOneRule`.

```go
package api

import (
	"net/http"
	"testing"
)

func TestV2Pokemon_PingRoundTrip(t *testing.T) {
	r, ms, _, restore := newV2PokemonTestAPI(t)
	defer restore()

	w := v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", `[{"pokemon_id":25,"ping":"<@&123>"}]`)
	if w.Code != http.StatusOK {
		t.Fatalf("expected 200, got %d: %s", w.Code, w.Body.String())
	}
	uid := singleCreatedUID(t, v2DecodeBody(t, w))
	rows, _ := ms.SelectByIDProfile("u1", 1)
	if len(rows) != 1 || rows[0].Ping != "<@&123>" {
		t.Fatalf("ping not stored: %+v", rows)
	}
	rule := getOneRule(t, r, "/api/v2/humans/u1/tracking/pokemon", uid)
	if rule["ping"] != "<@&123>" {
		t.Fatalf("ping not returned: %v", rule)
	}
}

func TestV2Pokemon_PingNullWhenEmpty(t *testing.T) {
	r, _, _, restore := newV2PokemonTestAPI(t)
	defer restore()
	w := v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", `[{"pokemon_id":25}]`)
	uid := singleCreatedUID(t, v2DecodeBody(t, w))
	rule := getOneRule(t, r, "/api/v2/humans/u1/tracking/pokemon", uid)
	if v, ok := rule["ping"]; !ok || v != nil {
		t.Fatalf("expected present-but-null ping, got %v (present=%v)", v, ok)
	}
}

// Re-posting a rule with the same ping must diff as unchanged (before this
// change the stored ping was always "" so it could never match a pinged rule).
func TestV2Pokemon_PingSurvivesRoundTrip(t *testing.T) {
	r, _, _, restore := newV2PokemonTestAPI(t)
	defer restore()
	v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", `[{"pokemon_id":25,"ping":"@here"}]`)
	w := v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", `[{"pokemon_id":25,"ping":"@here"}]`)
	body := v2DecodeBody(t, w)
	if len(v2RulesArray(t, body, "unchanged")) != 1 || len(tryRules(body, "created")) != 0 {
		t.Fatalf("expected the identical rule to be unchanged: %v", body)
	}
}
```

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `go test ./internal/api/ -run 'TestV2Pokemon_Ping' -v`
  - Expected: FAIL with `422`, because `ping` is an unknown body field.

- [ ] **Step 3: Implement it for all 11 types.**

  Add this field to each type's rule struct, next to `Template`:

```go
	Ping *string `json:"ping,omitempty" nullable:"true" maxLength:"255" doc:"Text appended to the alert, typically Discord role/user mentions. Omit for none. Returned as null when empty."`
```

  In each `translateV2<Type>`, replace `Ping: "", // server-managed` with:

```go
		Ping:                  valueOr(req.Ping, ""),
```

  In each `*RowToRule` function, add:

```go
		Ping:                  ptrUnless(row.Ping, ""),
```

  Also remove the "ping is server-managed" / "ping is always stored \"\"" sentences from the comments in those files.

  Confirm the DB column length before you keep `maxLength:"255"`. Run `grep -rn "ping" processor/internal/db/migrations/*.sql | head`, and set `maxLength` to the column size.

- [ ] **Step 4: Run the tests and regenerate the golden file.**

```bash
go test ./internal/api/ -run 'TestV2Pokemon_Ping' -v
UPDATE_GOLDEN=1 go test ./internal/api/ -run TestOpenAPIGolden
go test ./...
```

  Expected: everything PASSES. The golden diff should only add `ping` properties and remove the old "server-managed" wording.

- [ ] **Step 5: Update the docs and commit.**
  - In `CHANGELOG.md`, under `## [Unreleased]` → `### Added`: "**v2 tracking `ping`.** Every v2 rule type accepts and returns an optional `ping` (mention text). Previously v2 stored `\"\"`, which erased pings set by the bot or v1 on any PUT."
  - In `docs/v1-to-v2-migration-guide.md`, add a bullet to the "Type-specific changes" section: "**`ping`** — optional string on every type; null when empty."

```bash
git add -A && git commit -m "feat(api): expose ping on v2 tracking rules"
```

### Task 2: Canonical diff for pokemon wildcard aliases

**Files:**
- Modify: `processor/internal/api/v2_tracking.go`, adding a `Canonicalize` field to `v2TrackingType`. Apply it in `v2HandleCreate` (the function containing `store.ApplyDiff(typ.Store(deps), human.ID, existing, ...)`) and in `v2HandlePut`'s duplicate check.
- Modify: `processor/internal/api/v2_pokemon.go`, adding `canonicalizePokemonRow` and setting it on the pokemon type registration.
- Test: `processor/internal/api/v2_canonical_test.go` (new)

**Interfaces:**
- Produces: `func canonicalizePokemonRow(row *db.MonsterTrackingAPI)`, which maps `Rarity 0→-1`, `Size 0→-1`, `PVPRankingBest 0→1` and `PVPRankingWorst 0→4096`. These are exactly the alias pairs that `pokemonRowToRule` projects to `null` through `ptrUnlessAny`.

- [ ] **Step 1: Write the failing test.**

```go
package api

import (
	"net/http"
	"testing"

	"github.com/pokemon/poracleng/processor/internal/db"
)

// A row stored with the alias wildcards (0 instead of 1/4096/-1) must diff as
// UNCHANGED against its own GET → POST round trip, and as UPDATED (not a new
// rule) when only distance changes.
func TestV2Pokemon_AliasRowRoundTripsUnchanged(t *testing.T) {
	r, ms, _, restore := newV2PokemonTestAPI(t)
	defer restore()
	row := db.MonsterTrackingAPI{
		ID: "u1", ProfileNo: 1, PokemonID: 0, Form: 0, Costume: 9000,
		MinIV: 0, MaxIV: 100, MaxCP: 9000, MaxLevel: 55, MaxATK: 15, MaxDEF: 15, MaxSTA: 15,
		MaxWeight: 9000000, Rarity: 0, MaxRarity: 6, Size: 0, MaxSize: 5,
		PVPRankingBest: 0, PVPRankingWorst: 0, Template: "1",
	}
	if _, err := ms.Insert(&row); err != nil {
		t.Fatal(err)
	}
	list := v2DecodeBody(t, v2DoReq(t, r, http.MethodGet, "/api/v2/humans/u1/tracking/pokemon", ""))
	rules := v2RulesArray(t, list, "rules")
	same := cloneWithoutUID(rules[0])

	body := v2DecodeBody(t, v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", mustJSONArray(t, same)))
	if len(tryRules(body, "created")) != 0 || len(tryRules(body, "unchanged")) != 1 {
		t.Fatalf("unchanged round trip should be unchanged, got %v", body)
	}

	same["distance"] = 500
	body = v2DecodeBody(t, v2DoReq(t, r, http.MethodPost, "/api/v2/humans/u1/tracking/pokemon", mustJSONArray(t, same)))
	if len(tryRules(body, "created")) != 0 || len(tryRules(body, "updated")) != 1 {
		t.Fatalf("distance-only change should be an update, got %v", body)
	}
	rows, _ := ms.SelectByIDProfile("u1", 1)
	if len(rows) != 1 {
		t.Fatalf("expected exactly one rule after update, got %d", len(rows))
	}
}
```

  Add a small helper in the same file if `v2_pokemon_test.go` doesn't already have one:

```go
func mustJSONArray(t *testing.T, rule map[string]any) string {
	t.Helper()
	b, err := json.Marshal([]map[string]any{rule})
	if err != nil {
		t.Fatal(err)
	}
	return string(b)
}
```

  Add `"encoding/json"` to the imports.

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `go test ./internal/api/ -run TestV2Pokemon_AliasRowRoundTripsUnchanged -v`
  - Expected: FAIL with "unchanged round trip should be unchanged" (`created` has 1 entry).

- [ ] **Step 3: Implement it.**

  In `v2_tracking.go`, add a field to `v2TrackingType`, after `Filter`:

```go
	// Canonicalize, when non-nil, rewrites a STORED row's alias wildcard values
	// to the value Translate stores for the same "any" (e.g. pvp_ranking_best
	// 0 → 1). Applied to the existing rows fed into the create/PUT diff only,
	// so a GET → POST of a row stored with an alias diffs as unchanged instead
	// of inserting a duplicate. The stored rows themselves are not rewritten.
	Canonicalize func(*T)
```

  Add this method below `scopedRows`:

```go
// diffRows returns scopedRows with Canonicalize applied, for diffing only.
func (typ v2TrackingType[Req, T]) diffRows(deps *TrackingDeps, humanID string, profileNo int) ([]T, error) {
	rows, err := typ.scopedRows(deps, humanID, profileNo)
	if err != nil || typ.Canonicalize == nil {
		return rows, err
	}
	for i := range rows {
		typ.Canonicalize(&rows[i])
	}
	return rows, nil
}
```

  In `v2HandleCreate`, change `existing, err := typ.scopedRows(deps, human.ID, profileNo)` to `existing, err := typ.diffRows(deps, human.ID, profileNo)`. Make the same change to the duplicate-check `existing` in `v2HandlePut`.

  In `v2_pokemon.go`, add:

```go
// canonicalizePokemonRow maps the alias wildcards that pokemonRowToRule
// projects to null (ptrUnlessAny) onto the value translateV2Pokemon stores
// for null, so diffing treats them as the same rule.
func canonicalizePokemonRow(row *db.MonsterTrackingAPI) {
	if row.Rarity == 0 {
		row.Rarity = -1
	}
	if row.Size == 0 {
		row.Size = -1
	}
	if row.PVPRankingBest == 0 {
		row.PVPRankingBest = 1
	}
	if row.PVPRankingWorst == 0 {
		row.PVPRankingWorst = 4096
	}
}
```

  Set `Canonicalize: canonicalizePokemonRow,` in the pokemon `v2TrackingType` literal inside `RegisterV2TrackingPokemon`.

  Check no other type uses `ptrUnlessAny` with `grep -n "ptrUnlessAny" internal/api/*.go`. Today only pokemon does. If another type does, give it its own canonicalizer the same way.

- [ ] **Step 4: Run the tests.**
  - Run: `go test ./internal/api/ -run TestV2Pokemon_Alias -v && go test ./...`
  - Expected: PASS. The golden file doesn't change, since `Canonicalize` isn't part of the schema.

- [ ] **Step 5: Commit, push and open the PR.**

```bash
git add -A && git commit -m "fix(api): v2 diff treats stored pokemon wildcard aliases as their canonical value"
git push -u origin feat/v2-ping-canonical-diff
gh pr create --base develop --title "feat(api): v2 ping + canonical diff for wildcard aliases" --body "<summary of Tasks 1–2, test plan, and 🤖 Generated with [Claude Code](https://claude.com/claude-code)>"
```

  Then call `mcp__t3-code__link_pull_request` with the PR URL.

---

# Part B — PoracleWeb foundations (repo `~/dev/PoracleWeb`)

Set up once before starting Part B:

```bash
cd ~/dev/PoracleWeb && git fetch fork
git worktree add .worktrees/v2-api-migration -b feat/v2-api-migration docs/v2-api-migration-plan
cd .worktrees/v2-api-migration
```

### Task 3: Test harness

**Files:**
- Modify: `composer.json`, `.gitignore`
- Create: `phpunit.xml`, `tests/bootstrap.php`, `tests/unit/SmokeTest.php`

- [ ] **Step 1: Fix `.gitignore`.** The existing `test*` rule ignores `tests/`. Append:

```
!/tests/
!/tests/**
vendor/
.phpunit.result.cache
```

- [ ] **Step 2: Add PHPUnit.** Run:

```bash
docker run --rm -v "$PWD":/app -w /app composer:2 composer require --dev phpunit/phpunit:^9.6 --ignore-platform-req=ext-mysqli
```

  Expected: `vendor/bin/phpunit` exists.

- [ ] **Step 3: Create `phpunit.xml`.**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php" colors="true" failOnWarning="true">
  <testsuites>
    <testsuite name="unit"><directory>tests/unit</directory></testsuite>
    <testsuite name="integration"><directory>tests/integration</directory></testsuite>
  </testsuites>
</phpunit>
```

  `defaultTestSuite` isn't available in 9.6 XML, so the plain `vendor/bin/phpunit` command runs both suites. Integration tests skip themselves when `PORACLE_TEST_API` is unset (see Task 8).

- [ ] **Step 4: Create `tests/bootstrap.php`.**

```php
<?php
// Library files under test must only define functions/classes (no side effects on include).
require_once __DIR__ . '/../vendor/autoload.php';
foreach (array('poracle_api.php', 'poracle_tracking.php', 'poracle_forms.php', 'poracle_admin.php') as $f) {
   $path = __DIR__ . '/../include/' . $f;
   if (file_exists($path)) {
      require_once $path;
   }
}
```

  Also create `tests/unit/SmokeTest.php`:

```php
<?php
use PHPUnit\Framework\TestCase;

class SmokeTest extends TestCase
{
   public function testHarnessRuns()
   {
      $this->assertTrue(true);
   }
}
```

- [ ] **Step 5: Run the tests and commit.**
  - Run: `docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit`
  - Expected: `OK (1 test, 1 assertion)`.
  - Add a "Running tests" section to `README.md` with the command from Global Constraints.

```bash
git add .gitignore composer.json composer.lock phpunit.xml tests README.md
git commit -m "test: add PHPUnit harness run via docker php:8.1-cli"
```

  `composer.lock` is gitignored. Leave it ignored and use `git add -f` only if the repo owner wants it locked. By default, don't commit it.

### Task 4: `PoracleApi` client

**Files:**
- Create: `include/poracle_api.php`
- Test: `tests/unit/PoracleApiTest.php`

**Interfaces:**
- Produces:
  - `class PoracleApiException extends RuntimeException`, with public `$status` (int, 0 = transport failure), `$detail` (string), `$errors` (array of `{message, location, value}`) and `userMessage(): string`.
  - `class PoracleApi`:
    - `__construct(string $base, string $secret, callable $transport = null)`
    - `get($path, array $query = [])`
    - `post($path, $body = null, array $query = [])`
    - `put($path, $body, array $query = [])`
    - `patch($path, $body, array $query = [])`
    - `delete($path, array $query = [])`
    - `request($method, $path, array $query = [], $body = null)`
    - `static buildQuery(array $query): string`
  - Every method returns the decoded JSON (an array, or null for an empty body) and throws `PoracleApiException` on transport failure or status ≥ 400.
  - `$path` is absolute from the host, for example `/api/v2/humans/1/tracking` or `/health`.
  - The transport signature is `function (string $method, string $url, array $headers, ?string $body): array` and returns `array(int $status, ?string $body)`.

- [ ] **Step 1: Write the failing tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

class PoracleApiTest extends TestCase
{
   private $calls = array();

   private function api(array $responses)
   {
      $calls = &$this->calls;
      return new PoracleApi('http://poracle:3030/', 's3cret', function ($method, $url, $headers, $body) use (&$calls, &$responses) {
         $calls[] = compact('method', 'url', 'headers', 'body');
         return array_shift($responses);
      });
   }

   public function testGetSendsSecretAndDecodes()
   {
      $api = $this->api(array(array(200, '{"rules":[]}')));
      $this->assertSame(array('rules' => array()), $api->get('/api/v2/humans/1/tracking/lure'));
      $this->assertSame('GET', $this->calls[0]['method']);
      $this->assertSame('http://poracle:3030/api/v2/humans/1/tracking/lure', $this->calls[0]['url']);
      $this->assertContains('X-Poracle-Secret: s3cret', $this->calls[0]['headers']);
   }

   public function testBooleansInQueryAreTrueFalse()
   {
      $this->assertSame('all_profiles=true&silent=false&profile=2', PoracleApi::buildQuery(array('all_profiles' => true, 'silent' => false, 'profile' => 2)));
   }

   public function testPostEncodesJsonBody()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      $api->post('/api/v2/humans/1/tracking/lure', array(array('lure_id' => 501)), array('profile' => 1));
      $this->assertSame('[{"lure_id":501}]', $this->calls[0]['body']);
      $this->assertContains('Content-Type: application/json', $this->calls[0]['headers']);
      $this->assertStringEndsWith('?profile=1', $this->calls[0]['url']);
   }

   public function testProblemJsonBecomesException()
   {
      $api = $this->api(array(array(422, '{"title":"Unprocessable Entity","status":422,"detail":"validation failed","errors":[{"message":"expected integer","location":"body[0].lure_id","value":"x"}]}')));
      try {
         $api->post('/api/v2/humans/1/tracking/lure', array(array('lure_id' => 'x')));
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame(422, $e->status);
         $this->assertSame('validation failed (body[0].lure_id: expected integer)', $e->userMessage());
      }
   }

   public function testProblemWithoutDetailUsesTitle()
   {
      $api = $this->api(array(array(404, '{"title":"Not Found","status":404}')));
      $this->expectException(PoracleApiException::class);
      $this->expectExceptionMessage('Not Found');
      $api->get('/api/v2/humans/nobody');
   }

   public function testV1ErrorShape()
   {
      $api = $this->api(array(array(400, '{"status":"error","message":"User not found"}')));
      try {
         $api->get('/api/config/poracleWeb');
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame('User not found', $e->userMessage());
      }
   }

   public function testTransportFailureIsStatusZero()
   {
      $api = $this->api(array(array(0, null)));
      try {
         $api->get('/health');
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame(0, $e->status);
         $this->assertSame('Poracle unavailable', $e->userMessage());
      }
   }

   public function testEmptyBodyReturnsNull()
   {
      $api = $this->api(array(array(204, '')));
      $this->assertNull($api->delete('/api/v2/humans/1/location'));
   }
}
```

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit --filter PoracleApiTest`
  - Expected: ERROR, `Class "PoracleApi" not found`.

- [ ] **Step 3: Implement `include/poracle_api.php`.**

```php
<?php

// HTTP client for the PoracleNG API. Transport failures and any status >= 400
// are thrown as PoracleApiException so actions can report them in one place.

class PoracleApiException extends RuntimeException
{
   public $status;
   public $detail;
   public $errors;

   public function __construct($status, $detail, array $errors = array())
   {
      parent::__construct($detail, (int) $status);
      $this->status = (int) $status;
      $this->detail = $detail;
      $this->errors = $errors;
   }

   // The detail plus each field error, for showing to the user.
   public function userMessage()
   {
      $parts = array();
      foreach ($this->errors as $e) {
         $location = isset($e['location']) ? $e['location'] . ': ' : '';
         $parts[] = $location . (isset($e['message']) ? $e['message'] : '');
      }
      return $parts ? $this->detail . ' (' . implode('; ', $parts) . ')' : $this->detail;
   }
}

class PoracleApi
{
   private $base;
   private $secret;
   private $transport;

   // $transport: function ($method, $url, array $headers, $body) returning
   // array($status, $responseBody). Tests inject a fake; production uses curl.
   public function __construct($base, $secret, $transport = null)
   {
      $this->base = rtrim($base, '/');
      $this->secret = $secret;
      $this->transport = $transport ? $transport : array($this, 'curlTransport');
   }

   public function get($path, array $query = array()) { return $this->request('GET', $path, $query); }
   public function post($path, $body = null, array $query = array()) { return $this->request('POST', $path, $query, $body); }
   public function put($path, $body, array $query = array()) { return $this->request('PUT', $path, $query, $body); }
   public function patch($path, $body, array $query = array()) { return $this->request('PATCH', $path, $query, $body); }
   public function delete($path, array $query = array()) { return $this->request('DELETE', $path, $query); }

   public function request($method, $path, array $query = array(), $body = null)
   {
      $url = $this->base . $path;
      if ($query) {
         $url .= '?' . self::buildQuery($query);
      }
      $headers = array('Accept: application/json', 'X-Poracle-Secret: ' . $this->secret);
      $payload = null;
      if ($body !== null) {
         $payload = json_encode($body);
         $headers[] = 'Content-Type: application/json';
      }

      list($status, $raw) = call_user_func($this->transport, $method, $url, $headers, $payload);
      if ((int) $status === 0) {
         throw new PoracleApiException(0, 'Poracle unavailable');
      }
      $json = ($raw === null || $raw === '') ? null : json_decode($raw, true);
      if ($status >= 400) {
         throw self::errorFrom((int) $status, $json, $raw);
      }
      return $json;
   }

   // v2 rejects "1"/"" for booleans: send true/false.
   public static function buildQuery(array $query)
   {
      foreach ($query as $key => $value) {
         if (is_bool($value)) {
            $query[$key] = $value ? 'true' : 'false';
         }
      }
      return http_build_query($query);
   }

   private static function errorFrom($status, $json, $raw)
   {
      if (is_array($json) && (isset($json['detail']) || isset($json['title']))) {
         // RFC 9457 problem+json (v2 and the huma-served /api/* endpoints)
         $detail = (isset($json['detail']) && $json['detail'] !== '') ? $json['detail'] : $json['title'];
         $errors = (isset($json['errors']) && is_array($json['errors'])) ? $json['errors'] : array();
         return new PoracleApiException($status, $detail, $errors);
      }
      if (is_array($json) && isset($json['message'])) {
         // legacy v1 {status:"error", message}
         return new PoracleApiException($status, $json['message']);
      }
      return new PoracleApiException($status, ($raw === null || $raw === '') ? 'HTTP ' . $status : substr($raw, 0, 200));
   }

   public function curlTransport($method, $url, array $headers, $body)
   {
      $ch = curl_init($url);
      curl_setopt_array($ch, array(
         CURLOPT_CUSTOMREQUEST => $method,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => $headers,
         CURLOPT_CONNECTTIMEOUT => 5,
         CURLOPT_TIMEOUT => 15,
      ));
      if ($body !== null) {
         curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      }
      $raw = curl_exec($ch);
      $status = ($raw === false) ? 0 : (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
      curl_close($ch);
      return array($status, $raw === false ? null : $raw);
   }
}
```

- [ ] **Step 4: Run the tests.** Use the Step 2 command. Expected: PASS (8 tests).

- [ ] **Step 5: Commit.**

```bash
git add include/poracle_api.php tests/unit/PoracleApiTest.php
git commit -m "feat: PoracleApi client with problem+json errors"
```

### Task 5: Instances, v2 detection, and session bootstrap

**Files:**
- Create: `include/poracle_session.php`. This holds the request-bound helpers: `poracle_api()`, `poracle_api_for()`, `poracle_instance_settings()`, `poracle_find_instance()` and `poracle_fail()`. It is not unit-tested because it needs mysqli and `$_SESSION`.
- Modify: `include/poracle_tracking.php`. Create the file in this task, containing only the pure `poracle_supports_v2()` and `poracle_human_path()`.
- Modify: `session.php` (whole file), `include/return_messages.php` (new return codes), `include/db_connect.php` (include the new files)
- Test: `tests/unit/PoracleSessionLogicTest.php`

**Interfaces:**
- Produces, in `include/poracle_tracking.php`:
  - `poracle_supports_v2(array $health = null): bool` returns true when `$health['capabilities']` is an array.
  - `poracle_human_path(string $id): string` returns `'/api/v2/humans/' . rawurlencode($id)`.
  - `poracle_find_human_instance(array $apis, string $id): ?string`. `$apis` maps instance name → `PoracleApi`. It returns the first instance whose `GET /api/v2/humans/{id}` succeeds. On a 404 it tries the next instance; any other error is rethrown.
- Produces, in `include/poracle_session.php`:
  - `poracle_instance_names(): array` (from `$dbname`)
  - `poracle_instance_settings(string $instance): array` returns `['api_address'=>..., 'api_secret'=>...]`, read from that DB's `pweb_settings` and cached statically.
  - `poracle_api_for(string $instance): PoracleApi` (cached)
  - `poracle_api(): PoracleApi`, for `$_SESSION['dbname']`
  - `poracle_fail(string $page, Exception $e): void` stores `$_SESSION['api_error']` and redirects to `?type=display&page=$page&return=api_error`. When `$page` is `''`, it redirects to `?return=api_error`.

- [ ] **Step 1: Write the failing tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

class PoracleSessionLogicTest extends TestCase
{
   private function fakeApi($status, $body)
   {
      return new PoracleApi('http://x', 's', function () use ($status, $body) { return array($status, $body); });
   }

   public function testSupportsV2NeedsCapabilities()
   {
      $this->assertTrue(poracle_supports_v2(array('status' => 'ok', 'capabilities' => array('buttons' => true))));
      $this->assertFalse(poracle_supports_v2(array('status' => 'ok')));
      $this->assertFalse(poracle_supports_v2(null));
   }

   public function testHumanPathEncodes()
   {
      $this->assertSame('/api/v2/humans/a%2Fb', poracle_human_path('a/b'));
   }

   public function testFindHumanInstanceSkips404()
   {
      $apis = array(
         'poracle_a' => $this->fakeApi(404, '{"title":"Not Found","status":404,"detail":"human not found"}'),
         'poracle_b' => $this->fakeApi(200, '{"human":{"id":"42"}}'),
      );
      $this->assertSame('poracle_b', poracle_find_human_instance($apis, '42'));
   }

   public function testFindHumanInstanceNullWhenNowhere()
   {
      $apis = array('poracle_a' => $this->fakeApi(404, '{"title":"Not Found","status":404}'));
      $this->assertNull(poracle_find_human_instance($apis, '42'));
   }

   public function testFindHumanInstanceRethrowsOtherErrors()
   {
      $this->expectException(PoracleApiException::class);
      poracle_find_human_instance(array('a' => $this->fakeApi(0, null)), '42');
   }
}
```

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `... phpunit --filter PoracleSessionLogicTest`
  - Expected: ERROR, undefined function.

- [ ] **Step 3: Implement it.**

  `include/poracle_tracking.php`. This task creates the file; Task 6 adds to it.

```php
<?php

include_once __DIR__ . '/poracle_api.php';

// PoracleNG reports capabilities on /health; PoracleJS never did, so their
// presence is the v2 check.
function poracle_supports_v2($health)
{
   return is_array($health) && isset($health['capabilities']) && is_array($health['capabilities']);
}

function poracle_human_path($id)
{
   return '/api/v2/humans/' . rawurlencode($id);
}

// The instance (Poracle database name) the human is registered on, or null.
function poracle_find_human_instance(array $apis, $id)
{
   foreach ($apis as $instance => $api) {
      try {
         $api->get(poracle_human_path($id));
         return $instance;
      } catch (PoracleApiException $e) {
         if ($e->status !== 404) {
            throw $e;
         }
      }
   }
   return null;
}
```

  `include/poracle_session.php`:

```php
<?php

include_once __DIR__ . '/poracle_tracking.php';

// An instance is one Poracle database named in $dbname; its API address and
// secret live in that database's pweb_settings.

function poracle_instance_names()
{
   global $dbname;
   return array_values(array_filter(array_map('trim', explode(',', $dbname))));
}

function poracle_instance_settings($instance)
{
   global $dbhost, $dbport, $dbuser, $dbpass;
   static $cache = array();
   if (!isset($cache[$instance])) {
      $settings = array('api_address' => '', 'api_secret' => '');
      $db = new mysqli($dbhost . ":" . $dbport, $dbuser, $dbpass, $instance);
      if (!$db->connect_errno) {
         $result = $db->query("SELECT setting, value FROM pweb_settings WHERE setting IN ('api_address','api_secret')");
         while ($result && $row = $result->fetch_assoc()) {
            $settings[$row['setting']] = $row['value'];
         }
         $db->close();
      }
      $cache[$instance] = $settings;
   }
   return $cache[$instance];
}

function poracle_api_for($instance)
{
   static $apis = array();
   if (!isset($apis[$instance])) {
      $s = poracle_instance_settings($instance);
      $apis[$instance] = new PoracleApi($s['api_address'], $s['api_secret']);
   }
   return $apis[$instance];
}

function poracle_api()
{
   return poracle_api_for($_SESSION['dbname']);
}

// Every instance's client, keyed by instance name.
function poracle_all_apis()
{
   $apis = array();
   foreach (poracle_instance_names() as $instance) {
      $apis[$instance] = poracle_api_for($instance);
   }
   return $apis;
}

// Report an API failure to the user and stop.
function poracle_fail($page, Exception $e)
{
   global $redirect_url;
   $_SESSION['api_error'] = ($e instanceof PoracleApiException) ? $e->userMessage() : $e->getMessage();
   $target = ($page === '') ? "$redirect_url?return=api_error" : "$redirect_url?type=display&page=$page&return=api_error";
   header("Location: $target");
   exit();
}
```

  In `include/db_connect.php`, add `include_once __DIR__ . "/poracle_session.php";` after the `include_once "uicons_repo.php";` line.

  Rewrite `session.php`. Keep the admin block (current lines 36-48) and the delegated-admin block (current lines 142-194) as they are, except for the changes listed below. Replace everything else:

```php
<?php

include_once "./config.php";
include_once "./include/functions.php";
include_once "./include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

// Find the instance this human is registered on.

try {
   $instance = poracle_find_human_instance(poracle_all_apis(), $_SESSION['id']);
} catch (PoracleApiException $e) {
   $instance = null;
   $no_api = "True";
}
if ($instance !== null) {
   $_SESSION['dbname'] = $instance;
} elseif (!isset($_SESSION['dbname'])) {
   $_SESSION['dbname'] = poracle_instance_names()[0];
}
$api = poracle_api();

// ... (unchanged) Set Admin Variables block ...

// Require PoracleNG (v2 API)

try {
   $health = $api->get('/health');
   if (!poracle_supports_v2($health)) {
      session_destroy();
      header("Location: $redirect_url?return=error_api_not_poracleng");
      exit();
   }
   $json = $api->get('/api/config/poracleWeb');
} catch (PoracleApiException $e) {
   if (!isset($_SESSION['admin_id'])) {
      session_destroy();
      header("Location: $redirect_url?return=error_no_api");
      exit();
   }
   $no_api = "True";
   $json = array();
}

if (!empty($json)) {
   // ... the existing $_SESSION[...] = $json[...] assignments (lines 84-101), unchanged ...
}

if ( empty($_SESSION['maxDistance']) ) { $_SESSION['maxDistance'] = 10726000; }

// Areas this human may select

try {
   $areas = $api->get(poracle_human_path($_SESSION['id']) . '/areas');
   $_SESSION['areas'] = $areas['areas'];
} catch (PoracleApiException $e) {
   if ($e->status == 404) {
      header("Location: " . rtrim($redirect_url, '/') . "/");
      exit();
   }
   if (!isset($_SESSION['admin_id'])) {
      session_destroy();
      header("Location: $redirect_url?return=error_api_nok");
      exit();
   }
   $no_api = "True";
}

// Templates

try {
   $_SESSION['templates'] = $api->get('/api/config/templates');
} catch (PoracleApiException $e) {
   $_SESSION['templates'] = array();
}

// Delegated admin

try {
   $json = $api->get(poracle_human_path($_SESSION['id']) . '/admin-roles');
   $json['status'] = 'ok';
} catch (PoracleApiException $e) {
   $json = array('status' => 'error');
}
// ... (unchanged) delegated block starting `if ( $json['status']=="ok" ) {` ...

set_locale();
```

  Before keeping this, check two things against `/openapi.json` (`curl -s http://localhost:3030/openapi.json | python3 -m json.tool | less`):
  - The exact response keys of `GET /api/config/poracleWeb`. Drop the `$json['status']=="ok"` check if that endpoint no longer sends `status`.
  - The shape of `GET /v2/humans/{id}/admin-roles` (`V2AdminRolesOutputBody` has `admin`). The delegated block reads `$json['admin']`.

  In `include/return_messages.php`, add two blocks next to the `error_no_api` block (same markup):
  - `error_api_not_poracleng`: `i8ln("PoracleWeb requires PoracleNG")`, plus a `<br>`, plus `i8ln("Please contact an admin!")`.
  - `api_error`: `i8ln("You Request couldn't not be handled")`, plus a `<br>`, plus `htmlspecialchars($_SESSION['api_error'] ?? '')`. Then `unset($_SESSION['api_error'])`.

- [ ] **Step 4: Run the tests and lint.**

```bash
docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit
for f in session.php include/poracle_session.php include/poracle_tracking.php include/db_connect.php include/return_messages.php; do docker run --rm -v "$PWD":/app -w /app php:8.1-cli php -l $f; done
```

  Expected: PASS, and `No syntax errors detected` for each file.

- [ ] **Step 5: Commit.**

```bash
git add include/poracle_session.php include/poracle_tracking.php include/db_connect.php include/return_messages.php session.php tests/unit/PoracleSessionLogicTest.php
git commit -m "feat: find the user's Poracle instance and require PoracleNG via the API"
```

### Task 6: Snapshot, rule access, and tracking operations

**Files:**
- Modify: `include/poracle_tracking.php` (append)
- Modify: `include/poracle_session.php` (append the session wrappers)
- Test: `tests/unit/PoracleTrackingTest.php`

**Interfaces:**
- Produces, as pure functions in `poracle_tracking.php`:
  - `poracle_compact(array $rule): array`. Drops keys whose value is null.
  - `poracle_rule_body(array $rule): array`. Strips `uid`, `description` and `profile_no`, then compacts.
  - `poracle_rules_in(array $snapshot, string $type, ?int $profile): array`. Rules of `$type` filtered to `profile_no == $profile`; a null `$profile` returns all.
  - `poracle_all_clean(array $rules): ?string`. Returns `'1'` if every rule has `clean` true, `'0'` if any doesn't, and null if there are no rules.
  - `poracle_common_distance(array $rules): int`. The most frequent distance, where null counts as 0; 0 when there are no rules.
  - `poracle_shared_distance(array $rules): int`. The distance if every rule has the same one, otherwise 0.
  - `poracle_track_add(PoracleApi $api, string $human, string $type, array $rules, int $profile): array`
  - `poracle_track_update(PoracleApi $api, string $human, string $type, int $uid, array $changes, int $profile): array`. Does GET → `array_merge($rule, $changes)` → PUT.
  - `poracle_track_delete(PoracleApi $api, string $human, string $type, int $uid, int $profile): array`
  - `poracle_track_delete_uids(PoracleApi $api, string $human, string $type, array $uids, int $profile): ?array`. Returns null without making a request when `$uids` is empty.
  - `poracle_track_set_field(PoracleApi $api, string $human, string $type, array $rules, string $field, $value, int $profile): ?array`. Re-POSTs `$rules` with `$field` set to `$value`; null `$value` means "any". Returns null when `$rules` is empty.
- Produces, as session wrappers in `poracle_session.php`:
  - `poracle_snapshot(): array`. Memoized for the request.
  - `poracle_snapshot_invalidate(): void`
  - `poracle_rules(string $type, $profile = 'view'): array`. `'view'` means `$_SESSION['profile']`; null means every profile.
- v2 type names: `pokemon`, `raid`, `egg`, `quest`, `invasion`, `incident`, `lure`, `nest`, `gym`, `fort`, `maxbattle`.

- [ ] **Step 1: Write the failing tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

class PoracleTrackingTest extends TestCase
{
   private $calls = array();

   private function api(array $responses)
   {
      $calls = &$this->calls;
      return new PoracleApi('http://p', 's', function ($method, $url, $headers, $body) use (&$calls, &$responses) {
         $calls[] = array('method' => $method, 'url' => $url, 'body' => $body === null ? null : json_decode($body, true));
         return array_shift($responses);
      });
   }

   private $snapshot = array('tracking' => array(
      'lure' => array(
         array('uid' => 1, 'profile_no' => 1, 'lure_id' => 501, 'distance' => null, 'clean' => true),
         array('uid' => 2, 'profile_no' => 2, 'lure_id' => 502, 'distance' => 500, 'clean' => null),
         array('uid' => 3, 'profile_no' => 1, 'lure_id' => 0, 'distance' => 500, 'clean' => true),
      ),
   ));

   public function testRulesInFiltersByProfile()
   {
      $this->assertSame(array(1, 3), array_column(poracle_rules_in($this->snapshot, 'lure', 1), 'uid'));
      $this->assertCount(3, poracle_rules_in($this->snapshot, 'lure', null));
      $this->assertSame(array(), poracle_rules_in($this->snapshot, 'nest', 1));
   }

   public function testAllClean()
   {
      $this->assertSame('1', poracle_all_clean(poracle_rules_in($this->snapshot, 'lure', 1)));
      $this->assertSame('0', poracle_all_clean(poracle_rules_in($this->snapshot, 'lure', null)));
      $this->assertNull(poracle_all_clean(array()));
   }

   public function testDistances()
   {
      $all = poracle_rules_in($this->snapshot, 'lure', null);
      $this->assertSame(500, poracle_common_distance($all));
      $this->assertSame(0, poracle_shared_distance($all));
      $this->assertSame(500, poracle_shared_distance(array(array('distance' => 500), array('distance' => 500))));
      $this->assertSame(0, poracle_common_distance(array()));
   }

   public function testRuleBodyStripsResponseKeysAndNulls()
   {
      $this->assertSame(array('lure_id' => 501, 'clean' => true),
         poracle_rule_body(array('uid' => 9, 'profile_no' => 1, 'description' => 'x', 'lure_id' => 501, 'distance' => null, 'clean' => true)));
   }

   public function testAddSendsExplicitProfile()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      poracle_track_add($api, '42', 'lure', array(array('lure_id' => 501, 'distance' => null)), 2);
      $this->assertSame('POST', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure?profile=2', $this->calls[0]['url']);
      $this->assertSame(array(array('lure_id' => 501)), $this->calls[0]['body']);
   }

   public function testUpdateKeepsFieldsTheFormDoesNotSend()
   {
      $api = $this->api(array(
         array(200, '{"rules":[{"uid":7,"lure_id":501,"distance":100,"clean":true,"edit":true,"override_areas":["north"],"ping":"@here","template":null}]}'),
         array(200, '{"created":[],"updated":[{"uid":8}],"unchanged":[]}'),
      ));
      poracle_track_update($api, '42', 'lure', 7, array('distance' => 250, 'clean' => null, 'ping' => '@here'), 1);
      $this->assertSame('GET', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure/7?profile=1', $this->calls[0]['url']);
      $this->assertSame('PUT', $this->calls[1]['method']);
      $this->assertSame(array('lure_id' => 501, 'distance' => 250, 'edit' => true, 'override_areas' => array('north'), 'ping' => '@here'), $this->calls[1]['body']);
   }

   public function testDeleteUidsSkipsEmpty()
   {
      $api = $this->api(array());
      $this->assertNull(poracle_track_delete_uids($api, '42', 'lure', array(), 1));
      $this->assertSame(array(), $this->calls);
   }

   public function testDeleteUidsJoinsWithCommas()
   {
      $api = $this->api(array(array(200, '{"deleted":[]}')));
      poracle_track_delete_uids($api, '42', 'lure', array(1, 3), 1);
      $this->assertSame('DELETE', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure?uid=1%2C3&profile=1', $this->calls[0]['url']);
   }

   public function testSetFieldRepostsEveryRuleWithOneChange()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      $rules = poracle_rules_in($this->snapshot, 'lure', 1);
      poracle_track_set_field($api, '42', 'lure', $rules, 'distance', 750, 1);
      $this->assertSame(array(
         array('lure_id' => 501, 'distance' => 750, 'clean' => true),
         array('lure_id' => 0, 'distance' => 750, 'clean' => true),
      ), $this->calls[0]['body']);
   }

   public function testSetFieldNullMeansAny()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      poracle_track_set_field($api, '42', 'lure', array(array('uid' => 3, 'profile_no' => 1, 'lure_id' => 0, 'clean' => true)), 'clean', null, 1);
      $this->assertSame(array(array('lure_id' => 0)), $this->calls[0]['body']);
   }
}
```

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `... phpunit --filter PoracleTrackingTest`
  - Expected: ERROR, undefined function `poracle_rules_in`.

- [ ] **Step 3: Implement it.** Append to `include/poracle_tracking.php`:

```php
function poracle_compact(array $rule)
{
   return array_filter($rule, function ($value) { return $value !== null; });
}

// A rule read from the API, made sendable: response-only keys removed, and
// null ("any") fields left out.
function poracle_rule_body(array $rule)
{
   unset($rule['uid'], $rule['description'], $rule['profile_no']);
   return poracle_compact($rule);
}

function poracle_rules_in(array $snapshot, $type, $profile)
{
   $rules = isset($snapshot['tracking'][$type]) ? $snapshot['tracking'][$type] : array();
   if ($profile === null) {
      return $rules;
   }
   $out = array();
   foreach ($rules as $rule) {
      if ((int) $rule['profile_no'] === (int) $profile) {
         $out[] = $rule;
      }
   }
   return $out;
}

// '1' when every rule cleans, '0' when one does not, null when there are no
// rules (the values the old SELECT min(clean) gave the cleaning toggles).
function poracle_all_clean(array $rules)
{
   if (!$rules) {
      return null;
   }
   foreach ($rules as $rule) {
      if (empty($rule['clean'])) {
         return '0';
      }
   }
   return '1';
}

function poracle_common_distance(array $rules)
{
   $counts = array();
   foreach ($rules as $rule) {
      $d = (int) (isset($rule['distance']) ? $rule['distance'] : 0);
      $counts[$d] = (isset($counts[$d]) ? $counts[$d] : 0) + 1;
   }
   if (!$counts) {
      return 0;
   }
   arsort($counts);
   return (int) key($counts);
}

function poracle_shared_distance(array $rules)
{
   $distances = array();
   foreach ($rules as $rule) {
      $distances[(int) (isset($rule['distance']) ? $rule['distance'] : 0)] = true;
   }
   return count($distances) === 1 ? (int) key($distances) : 0;
}

function poracle_tracking_path($human, $type)
{
   return poracle_human_path($human) . '/tracking/' . $type;
}

function poracle_track_add(PoracleApi $api, $human, $type, array $rules, $profile)
{
   return $api->post(poracle_tracking_path($human, $type), array_map('poracle_compact', $rules), array('profile' => (int) $profile));
}

// PUT replaces the whole rule, so start from the stored rule and overwrite
// only what the form changed; fields the form does not show are kept.
function poracle_track_update(PoracleApi $api, $human, $type, $uid, array $changes, $profile)
{
   $path = poracle_tracking_path($human, $type) . '/' . (int) $uid;
   $query = array('profile' => (int) $profile);
   $current = $api->get($path, $query);
   $body = poracle_rule_body(array_merge($current['rules'][0], $changes));
   return $api->put($path, $body, $query);
}

function poracle_track_delete(PoracleApi $api, $human, $type, $uid, $profile)
{
   return $api->delete(poracle_tracking_path($human, $type) . '/' . (int) $uid, array('profile' => (int) $profile));
}

function poracle_track_delete_uids(PoracleApi $api, $human, $type, array $uids, $profile)
{
   if (!$uids) {
      return null;
   }
   return $api->delete(poracle_tracking_path($human, $type), array('uid' => implode(',', array_map('intval', $uids)), 'profile' => (int) $profile));
}

// Set one field on many rules in one request: POST upserts, and a rule that
// differs from its stored self in a single updatable field is updated in place.
function poracle_track_set_field(PoracleApi $api, $human, $type, array $rules, $field, $value, $profile)
{
   if (!$rules) {
      return null;
   }
   $bodies = array();
   foreach ($rules as $rule) {
      $rule[$field] = $value;
      $bodies[] = poracle_rule_body($rule);
   }
   return $api->post(poracle_tracking_path($human, $type), $bodies, array('profile' => (int) $profile));
}
```

  Append to `include/poracle_session.php`:

```php
// The logged-in human's full tracking snapshot, fetched once per request.
function poracle_snapshot($invalidate = false)
{
   static $snapshot = null;
   if ($invalidate) {
      $snapshot = null;
      return null;
   }
   if ($snapshot === null) {
      $snapshot = poracle_api()->get(poracle_human_path($_SESSION['id']) . '/tracking', array('all_profiles' => true));
   }
   return $snapshot;
}

function poracle_snapshot_invalidate()
{
   poracle_snapshot(true);
}

// Rules of one type: 'view' = the profile being viewed, null = every profile.
function poracle_rules($type, $profile = 'view')
{
   if ($profile === 'view') {
      $profile = (int) $_SESSION['profile'];
   }
   return poracle_rules_in(poracle_snapshot(), $type, $profile);
}
```

- [ ] **Step 4: Run the tests.** `... phpunit`. Expected: PASS.

- [ ] **Step 5: Commit.**

```bash
git add include/poracle_tracking.php include/poracle_session.php tests/unit/PoracleTrackingTest.php
git commit -m "feat: tracking snapshot and v2 rule operations"
```

### Task 7: Form → v2 rule builders

**Files:**
- Create: `include/poracle_forms.php`
- Test: `tests/unit/PoracleFormsTest.php`

**Interfaces:**
- Produces pure functions. Every builder returns arrays that may contain null values; null means "any" or off. `poracle_track_add` compacts them before sending, and `poracle_track_update` merges them so the nulls clear fields.

  General helpers:

  | Function | Returns |
  |---|---|
  | `form_radio(array $post, string $prefix): ?string` | The value of the first POST *value* that starts with `$prefix`, minus the prefix |
  | `form_int(array $post, string $key): ?int` | The value as an int, or null when it's missing or blank |
  | `form_common(array $post): array` | Keys `distance`, `clean`, `template`, `ping` |

  Type builders. Each returns its per-type rule list, or for edits an array of changes:

  | Function | Returns |
  |---|---|
  | `form_pokemon_filters(array $post, array $pvpMinCp, int $defaultCap): array` | Pokemon filter fields. `$pvpMinCp` maps league → min CP |
  | `form_pokemon_rules(array $post, array $pvpMinCp, int $defaultCap): array` | From the `mon_<id>` keys |
  | `form_raid_rules(array $post): array` | `['egg' => [...], 'raid' => [...]]` |
  | `form_quest_rules(array $post): array` | Quest rules |
  | `form_invasion_rules(array $post): array` | `['invasion' => [...], 'incident' => [...]]` |
  | `form_lure_rules(array $post): array` | Lure rules |
  | `form_nest_rules(array $post): array` | Nest rules |
  | `form_gym_rules(array $post): array` | Gym rules |

  Lookups:
  - `poracle_team_enum(int $team): string`. 0 harmony, 1 mystic, 2 valor, 3 instinct, anything else any.
  - `poracle_team_id(?string $enum): int`, the inverse; null or `any` gives 4. This is for icon and colour lookups only.
  - `poracle_gender_enum(int $g): ?string`. 1 male, 2 female, 3 genderless, otherwise null.
  - `poracle_gender_id(?string $enum): int`, the inverse; null gives 0. For display and radio pre-selection.
  - `PORACLE_INCIDENTS = array(7 => 'gold-stop', 8 => 'kecleon', 9 => 'showcase')`. These are the PokestopEvent ids and match `$other_grunt_types`.

- [ ] **Step 1: Write the failing tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

class PoracleFormsTest extends TestCase
{
   private $minCp = array(500 => 0, 1500 => 1400, 2500 => 2350);

   public function testRadioUsesPrefixNotLtrim()
   {
      $this->assertSame('-1', form_radio(array('size' => 'size_-1'), 'size_'));
      $this->assertSame('1', form_radio(array('x' => 'clean_1'), 'clean_'));
      $this->assertNull(form_radio(array(), 'clean_'));
   }

   public function testCommonFields()
   {
      $this->assertSame(array('distance' => 250, 'clean' => true, 'template' => 'big', 'ping' => '<@&1>'),
         form_common(array('distance' => '250', 'clean' => 'clean_1', 'template' => 'big', 'content' => ' <@&1> ')));
      $this->assertSame(array('distance' => null, 'clean' => null, 'template' => null, 'ping' => null),
         form_common(array('distance' => '0', 'clean' => 'clean_0', 'template' => '', 'content' => '')));
   }

   public function testQuickPickBareCleanValue()
   {
      $this->assertTrue(form_common(array('clean' => '1'))['clean']);
   }

   public function testBlankFieldsAreOmitted()
   {
      $f = poracle_compact(form_pokemon_filters(array('min_iv' => '', 'max_iv' => '', 'max_cp' => '', 'league' => '0', 'gender' => 'gender_0', 'size' => 'size_-1'), $this->minCp, 0));
      $this->assertSame(array('min_iv' => 0), $f);
   }

   public function testNoIvLeavesMinIvOut()
   {
      $f = poracle_compact(form_pokemon_filters(array('noiv' => 'on', 'min_iv' => '90'), $this->minCp, 0));
      $this->assertArrayNotHasKey('min_iv', $f);
   }

   public function testSizeAnyOmitsBoth()
   {
      $f = form_pokemon_filters(array('size' => 'size_-1'), $this->minCp, 0);
      $this->assertNull($f['size']);
      $this->assertNull($f['max_size']);
      $f = form_pokemon_filters(array('size' => 'size_3'), $this->minCp, 0);
      $this->assertSame(3, $f['size']);
      $this->assertSame(3, $f['max_size']);
   }

   public function testQuickPickPlainSize()
   {
      $f = form_pokemon_filters(array('size' => '1', 'max_size' => '1'), $this->minCp, 0);
      $this->assertSame(array(1, 1), array($f['size'], $f['max_size']));
      $f = form_pokemon_filters(array('size' => '5'), $this->minCp, 0);
      $this->assertSame(array(5, null), array($f['size'], $f['max_size']));
   }

   public function testPvpFieldsOnlyWithLeague()
   {
      $f = poracle_compact(form_pokemon_filters(array('league' => '1500', 'pvp_ranking_worst' => '10', 'pvp_ranking_best' => '', 'cap' => 'cap_50'), $this->minCp, 0));
      $this->assertSame(array('min_iv' => 0, 'pvp_ranking_league' => 1500, 'pvp_ranking_worst' => 10, 'pvp_ranking_min_cp' => 1400, 'pvp_ranking_cap' => 50), $f);
      $f = poracle_compact(form_pokemon_filters(array('league' => '0', 'pvp_ranking_worst' => '10'), $this->minCp, 0));
      $this->assertArrayNotHasKey('pvp_ranking_worst', $f);
   }

   public function testPvpDefaultCap()
   {
      $f = form_pokemon_filters(array('league' => '2500'), $this->minCp, 50);
      $this->assertSame(50, $f['pvp_ranking_cap']);
   }

   public function testGenderAndForm()
   {
      $f = form_pokemon_filters(array('gender' => 'gender_2', 'form' => 'form_46'), $this->minCp, 0);
      $this->assertSame('female', $f['gender']);
      $this->assertSame(46, $f['form']);
   }

   public function testPokemonRulesFromCheckboxes()
   {
      $rules = form_pokemon_rules(array('mon_25' => 'on', 'mon_133' => 'on', 'min_iv' => '90'), $this->minCp, 0);
      $this->assertSame(array(25, 133), array_column($rules, 'pokemon_id'));
      $this->assertSame(90, $rules[0]['min_iv']);
   }

   public function testPokemonEverythingIsNullIdAndStops()
   {
      $rules = form_pokemon_rules(array('mon_0' => 'on', 'mon_25' => 'on'), $this->minCp, 0);
      $this->assertCount(1, $rules);
      $this->assertNull($rules[0]['pokemon_id']);
   }

   public function testRaidRules()
   {
      $out = form_raid_rules(array('egg_5' => 'on', 'raid_5' => 'on', 'mon_150_0' => 'on', 'mon_6_0_1' => 'on', 'gym_id' => 'ALL', 'rsvp' => '1'));
      $this->assertCount(1, $out['egg']);
      $this->assertSame(5, $out['egg'][0]['level']);
      $this->assertNull($out['egg'][0]['gym_id']);
      $this->assertSame('rsvp', $out['egg'][0]['rsvp_changes']);
      $this->assertSame(5, $out['raid'][0]['level']);
      $this->assertNull($out['raid'][0]['pokemon_id']);
      $this->assertSame(150, $out['raid'][1]['pokemon_id']);
      $this->assertNull($out['raid'][1]['form']);
      $this->assertSame(1, $out['raid'][2]['evolution']);
   }

   public function testQuestRules()
   {
      $rules = form_quest_rules(array('mon_1_0' => 'on', 'item_1' => 'on', 'energy_0' => 'on', 'candy_25' => 'on'));
      $this->assertSame(array(7, 2, 12, 4), array_column($rules, 'reward_type'));
      $this->assertSame(array(1, 1, null, 25), array_column($rules, 'reward'));
   }

   public function testInvasionSplitsIncidents()
   {
      $out = form_invasion_rules(array('grunt_2_water' => 'on', 'grunt_0_showcase' => 'on', 'grunt_0_gold-stop' => 'on', 'grunt_0_big_boss' => 'on'));
      $this->assertSame(array('water', 'big boss'), array_column($out['invasion'], 'grunt_type'));
      $this->assertSame('female', $out['invasion'][0]['gender']);
      $this->assertSame(array(9, 7), array_column($out['incident'], 'display_type'));
   }

   public function testLureNestGym()
   {
      $this->assertSame(array(501, 506), array_column(form_lure_rules(array('lure_501' => 'on', 'lure_506' => 'on')), 'lure_id'));
      $nest = form_nest_rules(array('nest_1' => 'on', 'min_spawns' => '0'));
      $this->assertSame(1, $nest[0]['pokemon_id']);
      $this->assertNull($nest[0]['min_spawn_avg']);
      $gym = form_gym_rules(array('gym_1' => 'on', 'slot' => 'slot_1', 'battle' => 'battle_0'));
      $this->assertSame('mystic', $gym[0]['team']);
      $this->assertTrue($gym[0]['slot_changes']);
      $this->assertNull($gym[0]['battle_changes']);
   }

   public function testEnumLookups()
   {
      $this->assertSame(4, poracle_team_id(null));
      $this->assertSame(2, poracle_team_id('valor'));
      $this->assertSame(0, poracle_gender_id(null));
      $this->assertSame(3, poracle_gender_id('genderless'));
   }
}
```

- [ ] **Step 2: Run it and confirm it fails.**
  - Run: `... phpunit --filter PoracleFormsTest`
  - Expected: ERROR, undefined function.

- [ ] **Step 3: Implement `include/poracle_forms.php`.**

```php
<?php

// Build v2 tracking rules from PoracleWeb's form posts. null = "any"/off:
// poracle_track_add leaves nulls out, poracle_track_update uses them to clear.

const PORACLE_INCIDENTS = array(7 => 'gold-stop', 8 => 'kecleon', 9 => 'showcase');

function form_radio(array $post, $prefix)
{
   foreach ($post as $value) {
      if (is_string($value) && strpos($value, $prefix) === 0) {
         return substr($value, strlen($prefix));
      }
   }
   return null;
}

function form_int(array $post, $key)
{
   if (!isset($post[$key]) || $post[$key] === '' || !is_numeric($post[$key])) {
      return null;
   }
   return (int) $post[$key];
}

function form_common(array $post)
{
   $clean = form_radio($post, 'clean_');
   if ($clean === null && isset($post['clean'])) {
      $clean = (string) $post['clean'];    // quick pick posts a bare 0/1
   }
   $distance = form_int($post, 'distance');
   $ping = isset($post['content']) ? trim($post['content']) : '';
   return array(
      'distance' => $distance ? $distance : null,
      'clean' => $clean === '1' ? true : null,
      'template' => (isset($post['template']) && $post['template'] !== '') ? $post['template'] : null,
      'ping' => $ping !== '' ? $ping : null,
   );
}

function poracle_team_enum($team)
{
   $teams = array(0 => 'harmony', 1 => 'mystic', 2 => 'valor', 3 => 'instinct');
   return isset($teams[(int) $team]) ? $teams[(int) $team] : 'any';
}

function poracle_team_id($enum)
{
   $ids = array('harmony' => 0, 'mystic' => 1, 'valor' => 2, 'instinct' => 3);
   return isset($ids[$enum]) ? $ids[$enum] : 4;
}

function poracle_gender_enum($gender)
{
   $genders = array(1 => 'male', 2 => 'female', 3 => 'genderless');
   return isset($genders[(int) $gender]) ? $genders[(int) $gender] : null;
}

function poracle_gender_id($enum)
{
   $ids = array('male' => 1, 'female' => 2, 'genderless' => 3);
   return isset($ids[$enum]) ? $ids[$enum] : 0;
}

function form_pokemon_filters(array $post, array $pvpMinCp, $defaultCap)
{
   $f = array(
      'min_iv' => !empty($post['noiv']) ? null : (form_int($post, 'min_iv') !== null ? form_int($post, 'min_iv') : 0),
      'max_iv' => form_int($post, 'max_iv'),
      'min_cp' => form_int($post, 'min_cp'),
      'max_cp' => form_int($post, 'max_cp'),
      'min_level' => form_int($post, 'min_level'),
      'max_level' => form_int($post, 'max_level'),
      'min_weight' => form_int($post, 'min_weight'),
      'max_weight' => form_int($post, 'max_weight'),
      'atk' => form_int($post, 'atk'),
      'def' => form_int($post, 'def'),
      'sta' => form_int($post, 'sta'),
      'max_atk' => form_int($post, 'max_atk'),
      'max_def' => form_int($post, 'max_def'),
      'max_sta' => form_int($post, 'max_sta'),
      'pvp_ranking_league' => null,
      'pvp_ranking_best' => null,
      'pvp_ranking_worst' => null,
      'pvp_ranking_min_cp' => null,
      'pvp_ranking_cap' => null,
   );

   $league = form_int($post, 'league');
   if ($league) {
      $minCp = form_int($post, 'pvp_ranking_min_cp');
      if ($minCp === null) {
         $minCp = isset($pvpMinCp[$league]) ? (int) $pvpMinCp[$league] : 0;
      }
      $cap = form_radio($post, 'cap_');
      $cap = ($cap !== null) ? (int) $cap : (int) $defaultCap;
      $f['pvp_ranking_league'] = $league;
      $f['pvp_ranking_best'] = form_int($post, 'pvp_ranking_best');
      $f['pvp_ranking_worst'] = form_int($post, 'pvp_ranking_worst');
      $f['pvp_ranking_min_cp'] = $minCp ? $minCp : null;
      $f['pvp_ranking_cap'] = $cap ? $cap : null;
   }

   $gender = form_radio($post, 'gender_');
   $f['gender'] = $gender === null ? null : poracle_gender_enum($gender);

   $form = form_radio($post, 'form_');
   $f['form'] = ($form !== null && (int) $form > 0) ? (int) $form : null;

   $size = form_radio($post, 'size_');
   if ($size !== null) {
      $f['size'] = ((int) $size > 0) ? (int) $size : null;
      $f['max_size'] = $f['size'];
   } else {
      // quick pick posts bare size / max_size
      $f['size'] = form_int($post, 'size') > 0 ? form_int($post, 'size') : null;
      $f['max_size'] = form_int($post, 'max_size') > 0 ? form_int($post, 'max_size') : null;
   }
   return $f;
}

function form_pokemon_rules(array $post, array $pvpMinCp, $defaultCap)
{
   $base = array_merge(form_common($post), form_pokemon_filters($post, $pvpMinCp, $defaultCap));
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'mon_') !== 0) {
         continue;
      }
      $id = (int) substr($key, 4);
      $rules[] = array_merge($base, array('pokemon_id' => $id ? $id : null));
      if ($id === 0) {
         break;    // "all pokemon" replaces any individual picks
      }
   }
   return $rules;
}

function form_raid_rules(array $post)
{
   $common = form_common($post);
   $gym = (isset($post['gym_id']) && $post['gym_id'] !== '' && $post['gym_id'] !== 'ALL') ? $post['gym_id'] : null;
   $rsvpNames = array('1' => 'rsvp', '2' => 'rsvp_only');
   $rsvp = (isset($post['rsvp']) && isset($rsvpNames[$post['rsvp']])) ? $rsvpNames[$post['rsvp']] : null;
   $shared = array_merge($common, array('gym_id' => $gym, 'rsvp_changes' => $rsvp));

   $out = array('egg' => array(), 'raid' => array());
   foreach ($post as $key => $value) {
      if (strpos($key, 'egg_') === 0) {
         $out['egg'][] = array_merge($shared, array('level' => (int) substr($key, 4)));
      } elseif (strpos($key, 'raid_') === 0) {
         $out['raid'][] = array_merge($shared, array('level' => (int) substr($key, 5), 'pokemon_id' => null));
      } elseif (strpos($key, 'mon_') === 0) {
         $parts = explode('_', $key);    // mon_<id>_<form>[_<mega>]
         $form = isset($parts[2]) ? (int) $parts[2] : 0;
         $mega = isset($parts[3]) ? (int) $parts[3] : 0;
         $out['raid'][] = array_merge($shared, array(
            'pokemon_id' => (int) $parts[1],
            'form' => $form ? $form : null,
            'evolution' => $mega ? $mega : null,
         ));
      }
   }
   return $out;
}

function form_quest_rules(array $post)
{
   $common = form_common($post);
   $types = array('item_' => 2, 'energy_' => 12, 'candy_' => 4);
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'mon_') === 0) {
         $parts = explode('_', $key);    // mon_<id>_<form>
         $form = isset($parts[2]) ? (int) $parts[2] : 0;
         $rules[] = array_merge($common, array('reward_type' => 7, 'reward' => (int) $parts[1], 'form' => $form ? $form : null));
         continue;
      }
      foreach ($types as $prefix => $rewardType) {
         if (strpos($key, $prefix) === 0) {
            $reward = (int) substr($key, strlen($prefix));
            $rules[] = array_merge($common, array('reward_type' => $rewardType, 'reward' => $reward ? $reward : null));
         }
      }
   }
   return $rules;
}

function form_invasion_rules(array $post)
{
   $common = form_common($post);
   $incidentIds = array_flip(PORACLE_INCIDENTS);
   $out = array('invasion' => array(), 'incident' => array());
   foreach ($post as $key => $value) {
      if (strpos($key, 'grunt_') !== 0) {
         continue;
      }
      $gender = (int) substr($key, 6, 1);    // grunt_<gender>_<name>
      $name = strtolower(str_replace('_', ' ', substr($key, 8)));
      if (isset($incidentIds[$name])) {
         $out['incident'][] = array_merge($common, array('display_type' => $incidentIds[$name]));
      } else {
         $out['invasion'][] = array_merge($common, array('grunt_type' => $name, 'gender' => poracle_gender_enum($gender)));
      }
   }
   return $out;
}

function form_lure_rules(array $post)
{
   $common = form_common($post);
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'lure_') === 0) {
         $rules[] = array_merge($common, array('lure_id' => (int) substr($key, 5)));
      }
   }
   return $rules;
}

function form_nest_rules(array $post)
{
   $common = form_common($post);
   $minSpawns = form_int($post, 'min_spawns');
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'nest_') === 0) {
         $id = (int) substr($key, 5);
         $rules[] = array_merge($common, array('pokemon_id' => $id ? $id : null, 'min_spawn_avg' => $minSpawns ? $minSpawns : null));
      }
   }
   return $rules;
}

function form_gym_rules(array $post)
{
   $common = form_common($post);
   $flags = array(
      'slot_changes' => form_radio($post, 'slot_') === '1' ? true : null,
      'battle_changes' => form_radio($post, 'battle_') === '1' ? true : null,
   );
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'gym_') === 0 && $key !== 'gym_id') {
         $rules[] = array_merge($common, $flags, array('team' => poracle_team_enum(substr($key, 4))));
      }
   }
   return $rules;
}
```

  Confirm `poracle_compact` is available, because the tests call it. `tests/bootstrap.php` loads `poracle_tracking.php` before `poracle_forms.php`.

- [ ] **Step 4: Run the tests.** `... phpunit`. Expected: PASS.

- [ ] **Step 5: Commit.**

```bash
git add include/poracle_forms.php tests/unit/PoracleFormsTest.php
git commit -m "feat: build v2 tracking rules from PoracleWeb form posts"
```

### Task 8: Integration tests against a live PoracleNG

**Files:**
- Create: `tests/integration/PoracleLiveTest.php`
- Modify: `README.md` (document how to run it)

**Prerequisites:**
- PoracleNG is running from the Part A branch, for example `cd ~/GolandProjects/PoracleNG/.worktrees/v2-ping-canonical-diff && make` and then `./start.sh`, or `go run ./processor/cmd/processor` with the repo's `config/config.toml`, listening on :3030.
- A test human exists. Create it with `curl -X POST -H "X-Poracle-Secret: $S" -H 'Content-Type: application/json' localhost:3030/api/v2/humans -d '{"id":"pweb-it-1","name":"pweb integration","type":"webhook"}'`.

- [ ] **Step 1: Write the tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

// Opt-in: needs PORACLE_TEST_API, PORACLE_TEST_SECRET, PORACLE_TEST_HUMAN.
class PoracleLiveTest extends TestCase
{
   private $api;
   private $human;

   protected function setUp(): void
   {
      if (!getenv('PORACLE_TEST_API')) {
         $this->markTestSkipped('PORACLE_TEST_API not set');
      }
      $this->api = new PoracleApi(getenv('PORACLE_TEST_API'), getenv('PORACLE_TEST_SECRET'));
      $this->human = getenv('PORACLE_TEST_HUMAN');
      foreach (array('pokemon', 'lure') as $type) {
         $rules = $this->api->get(poracle_tracking_path($this->human, $type), array('profile' => 1))['rules'];
         poracle_track_delete_uids($this->api, $this->human, $type, array_column($rules, 'uid'), 1);
      }
   }

   private function rules($type)
   {
      $snap = $this->api->get(poracle_human_path($this->human) . '/tracking', array('all_profiles' => true));
      return poracle_rules_in($snap, $type, 1);
   }

   public function testHealthHasCapabilities()
   {
      $this->assertTrue(poracle_supports_v2($this->api->get('/health')));
   }

   public function testAddEditKeepsPingAndEditFlag()
   {
      poracle_track_add($this->api, $this->human, 'lure', array(array('lure_id' => 501, 'ping' => '@here', 'edit' => true)), 1);
      $rule = $this->rules('lure')[0];
      poracle_track_update($this->api, $this->human, 'lure', $rule['uid'], array('distance' => 300), 1);
      $after = $this->rules('lure');
      $this->assertCount(1, $after);
      $this->assertSame('@here', $after[0]['ping']);
      $this->assertTrue($after[0]['edit']);
      $this->assertSame(300, $after[0]['distance']);
   }

   public function testSetFieldOnLegacyAliasRowsCreatesNoDuplicates()
   {
      // Simulates rows the old PoracleWeb wrote (pvp best/worst stored as 0) by
      // posting the explicit values v2 would otherwise read back as null.
      poracle_track_add($this->api, $this->human, 'pokemon', array(array('pokemon_id' => 25, 'min_iv' => 0), array('pokemon_id' => 133)), 1);
      $before = $this->rules('pokemon');
      poracle_track_set_field($this->api, $this->human, 'pokemon', $before, 'distance', 400, 1);
      poracle_track_set_field($this->api, $this->human, 'pokemon', $this->rules('pokemon'), 'clean', true, 1);
      $after = $this->rules('pokemon');
      $this->assertCount(2, $after);
      $this->assertSame(array(400, 400), array_column($after, 'distance'));
      $this->assertSame(array(true, true), array_column($after, 'clean'));
   }

   public function testProblemJsonOnBadBody()
   {
      try {
         poracle_track_add($this->api, $this->human, 'lure', array(array('lure_id' => 'x')), 1);
         $this->fail('expected 422');
      } catch (PoracleApiException $e) {
         $this->assertSame(422, $e->status);
         $this->assertNotSame('', $e->userMessage());
      }
   }
}
```

  `testSetFieldOnLegacyAliasRowsCreatesNoDuplicates` only exercises the true legacy-alias case on a DB row written with 0s. Before closing the task, also check by hand against the local `poracle` DB:

```sql
INSERT INTO monsters (id, profile_no, pokemon_id, pvp_ranking_best, pvp_ranking_worst, rarity, size, ...)
```

  Insert a row for the test human with `pvp_ranking_best=0` and `pvp_ranking_worst=0`, using the same columns as an existing row. Then run `testSetFieldOnLegacyAliasRowsCreatesNoDuplicates`'s two `set_field` calls and confirm the rule count is unchanged.

- [ ] **Step 2: Run the tests.**
  - Run: the integration command from Global Constraints, with `PORACLE_TEST_HUMAN=pweb-it-1`.
  - Expected: PASS (4 tests).
  - Also run `... phpunit` without the env vars. Expected: the integration tests show as SKIPPED.

- [ ] **Step 3: Check the snapshot keys.** Run `curl -s -H "X-Poracle-Secret: $S" 'localhost:3030/api/v2/humans/pweb-it-1/tracking?all_profiles=true' | python3 -m json.tool | head -40`. Confirm that:
  - the `tracking` keys are the v2 type names;
  - each rule carries `profile_no`.

  If either is not true, fix `poracle_rules_in` and its unit test before continuing.

- [ ] **Step 4: Commit.**

```bash
git add tests/integration README.md && git commit -m "test: opt-in integration tests against a live PoracleNG"
```

---

# Part C — Reads

### Task 9: Header, toplinks, dashboard, locale, distances, and modals from the snapshot

**Files:**
- Modify: `header.php:33-182`, `header.php:199-214`, `header.php:275-292`
- Modify: `include/toplinks.php:4-56`, `pages/display/dashboard.php:4-58`
- Modify: `include/functions.php:389-411` (`set_locale`) and `include/functions.php:581-605` (`default_distance`)
- Modify: `modal/distance_{pokemons,raids,quests,invasions,lures,nests,gyms}_modal.php:11-22`, `modal/areas_modal.php:5-21`, `modal/edit_profile_modal.php:28-33,36-59,89-99`
- Test: `tests/unit/PoracleTrackingTest.php`, adding `testHumanAndProfileView`

**Interfaces:**
- Consumes (Task 6): `poracle_snapshot()`, `poracle_rules()`, `poracle_all_clean()`, `poracle_common_distance()`, `poracle_shared_distance()`.
- Produces, a pure function in `poracle_tracking.php`:
  - `poracle_profile_view(array $snapshot, int $profile): array` returns `['name', 'area' (array), 'latitude', 'longitude', 'profile_count', 'current_profile_no', 'enabled', 'admin_disable', 'disabled_date', 'language']`.
  - It takes the profile fields from the matching `profiles[]` entry and falls back to `human` when there is none.
  - `area` is decoded from the JSON string with `json_decode(..., true)`, and is `[]` when invalid.
- Produces: `poracle_type_for_table(string $table): array`. It maps the legacy page names `monsters`, `raid`, `egg`, `quest`, `invasion`, `lures`, `nests` and `gym` to v2 types. The mapping is pokemon, raid, egg, quest, invasion plus incident, lure, nest and gym. It's used by `default_distance()` and the distance modals.

- [ ] **Step 1: Write the failing test.** Add to `PoracleTrackingTest`:

```php
   public function testHumanAndProfileView()
   {
      $snap = array(
         'human' => array('current_profile_no' => 1, 'area' => '["north"]', 'latitude' => 1.5, 'longitude' => 2.5, 'enabled' => 1, 'admin_disable' => 0, 'disabled_date' => null, 'language' => null),
         'profiles' => array(
            array('profile_no' => 1, 'name' => 'Home', 'area' => '["north"]', 'latitude' => 1.5, 'longitude' => 2.5),
            array('profile_no' => 2, 'name' => 'Work', 'area' => '["south","east"]', 'latitude' => 3.0, 'longitude' => 4.0),
         ),
      );
      $v = poracle_profile_view($snap, 2);
      $this->assertSame(array('Work', array('south', 'east'), 3.0, 4.0, 2, 1),
         array($v['name'], $v['area'], $v['latitude'], $v['longitude'], $v['profile_count'], $v['current_profile_no']));
      $this->assertSame(array('north'), poracle_profile_view($snap, 9)['area']);
      $this->assertNull(poracle_profile_view($snap, 9)['name']);
      $this->assertSame(array('pokemon'), poracle_type_for_table('monsters'));
      $this->assertSame(array('invasion', 'incident'), poracle_type_for_table('invasion'));
   }
```

- [ ] **Step 2: Run it and confirm it fails.** `... phpunit --filter testHumanAndProfileView`. Expected: ERROR.

- [ ] **Step 3: Implement the functions.** Append to `poracle_tracking.php`:

```php
function poracle_type_for_table($table)
{
   $map = array('monsters' => array('pokemon'), 'raid' => array('raid'), 'egg' => array('egg'), 'quest' => array('quest'),
      'invasion' => array('invasion', 'incident'), 'lures' => array('lure'), 'nests' => array('nest'), 'gym' => array('gym'));
   return isset($map[$table]) ? $map[$table] : array();
}

function poracle_decode_areas($json)
{
   $areas = is_string($json) ? json_decode($json, true) : $json;
   return is_array($areas) ? $areas : array();
}

function poracle_profile_view(array $snapshot, $profile)
{
   $human = $snapshot['human'];
   $view = array(
      'name' => null, 'area' => poracle_decode_areas($human['area']),
      'latitude' => $human['latitude'], 'longitude' => $human['longitude'],
      'profile_count' => count($snapshot['profiles']), 'current_profile_no' => (int) $human['current_profile_no'],
      'enabled' => (int) $human['enabled'], 'admin_disable' => (int) $human['admin_disable'],
      'disabled_date' => $human['disabled_date'], 'language' => $human['language'],
   );
   foreach ($snapshot['profiles'] as $p) {
      if ((int) $p['profile_no'] === (int) $profile) {
         $view['name'] = $p['name'];
         $view['area'] = poracle_decode_areas($p['area']);
         $view['latitude'] = $p['latitude'];
         $view['longitude'] = $p['longitude'];
      }
   }
   return $view;
}
```

  Add a session helper to `poracle_session.php`. It concatenates the rules of every v2 type behind a legacy table name, for all profiles or one:

```php
function poracle_table_rules($table, $profile = 'view')
{
   $rules = array();
   foreach (poracle_type_for_table($table) as $type) {
      $rules = array_merge($rules, poracle_rules($type, $profile));
   }
   return $rules;
}
```

- [ ] **Step 4: Rewrite the read sites.** Each item replaces the SQL block at the given lines.

  **`header.php:33-182`** becomes:

```php
if (isset($_SESSION['id'])) {
   try {
      $snapshot = poracle_snapshot();
   } catch (PoracleApiException $e) {
      $snapshot = null;
   }
}
if (!empty($snapshot)) {
   if (!isset($_SESSION['profile'])) {
      $_SESSION['profile'] = (int) $snapshot['human']['current_profile_no'];
   }
   $profile_view = poracle_profile_view($snapshot, $_SESSION['profile']);
   $_SESSION['number_of_profiles'] = $profile_view['profile_count'];
   $_SESSION['profile_name'] = $profile_view['name'] !== null ? $profile_view['name'] : i8ln("Default");
   $_SESSION['current_profile'] = $profile_view['current_profile_no'];

   // Cleaning toggles span every profile, as before
   $all_mon_cleaned = poracle_all_clean(poracle_table_rules('monsters', null));
   $all_raid_cleaned = poracle_all_clean(array_merge(poracle_table_rules('raid', null), poracle_table_rules('egg', null)));
   $all_quest_cleaned = poracle_all_clean(poracle_table_rules('quest', null));
   $all_invasion_cleaned = poracle_all_clean(poracle_table_rules('invasion', null));
   $all_lures_cleaned = poracle_all_clean(poracle_table_rules('lures', null));
   $all_nests_cleaned = poracle_all_clean(poracle_table_rules('nests', null));
   $all_gyms_cleaned = poracle_all_clean(poracle_table_rules('gym', null));

   $area_set = json_encode($profile_view['area']);
   $latitude = $profile_view['latitude'];
   $longitude = $profile_view['longitude'];
   $enabled = $profile_view['enabled'];
   $admin_disable = $profile_view['admin_disable'];
   $disabled_date = $profile_view['disabled_date'];
}
```

  **`header.php:199-214`** (registration check): a human is registered when `!empty($snapshot)`. When `$enable_admin_dis == "False"` and an admin is impersonating someone else, they also need `$snapshot['human']['admin_disable'] == 0`. Keep the existing redirect to `$redirect_page`.

  **`header.php:275-292`** (the IV + PvP warning): loop `poracle_rules('pokemon', null)`. Set `$config_alarm` when any rule has `pvp_ranking_league !== null` and at least one of these is non-null: `min_iv`, `max_iv`, `atk`, `def`, `sta`, `max_atk`, `max_def`, `max_sta`.
  - The old query counted `min_iv > 0`. In v2, a `min_iv` of 0 is returned as `0`, not null. So also require `min_iv > 0` when that is the only IV field set.

  **`include/toplinks.php:4-56`** and **`pages/display/dashboard.php:4-58`**: set each counter from `count(poracle_table_rules('<table>'))`.
  - In toplinks, `$num_raid_tracked` is raid plus egg.
  - In the dashboard, raid and egg stay separate.
  - In the dashboard, `$num_invasion_tracked` now includes incidents.

  **`set_locale()`**: replace the SELECT with:

```php
   try {
      $language = poracle_snapshot()['human']['language'];
   } catch (PoracleApiException $e) {
      $language = null;
   }
   if (!empty($language)) { $_SESSION['locale'] = $language; }
```

  **`default_distance($table)`**:
  - Keep the whitelist check.
  - Return `poracle_common_distance(poracle_table_rules($table, null))`, with the existing `defaultDistance` fallback.
  - Add `global $disable_areas;` so the fallback actually runs.

  **Distance modals**: replace the GROUP BY block with:
  - `$distance_set = poracle_shared_distance(poracle_table_rules('<table>', null));`
  - for raids: `poracle_shared_distance(array_merge(poracle_table_rules('raid', null), poracle_table_rules('egg', null)))`.

  **`modal/areas_modal.php:5-21`**: `$existing_area = poracle_profile_view(poracle_snapshot(), $_SESSION['profile'])['area'];`

  **`modal/edit_profile_modal.php`**:
  - `$active_profile = poracle_snapshot()['human']['current_profile_no'];`
  - loop over `poracle_snapshot()['profiles']`, which has the same keys as the old SELECT;
  - the "no profiles" check becomes `count(poracle_snapshot()['profiles']) == 0`.

- [ ] **Step 5: Verify.**

```bash
docker run --rm -v "$PWD":/app -w /app php:8.1-cli vendor/bin/phpunit
for f in header.php include/toplinks.php include/functions.php pages/display/dashboard.php modal/*.php; do docker run --rm -v "$PWD":/app -w /app php:8.1-cli php -l $f; done
grep -nE '\$conn->(prepare|query)' header.php include/toplinks.php pages/display/dashboard.php modal/*.php include/functions.php
```

  Expected: tests PASS, no syntax errors, and the grep prints **nothing**.

- [ ] **Step 6: Commit.**

```bash
git add -A && git commit -m "feat: header, counters, locale and modals read from the v2 snapshot"
```

---

# Part D — Tracking types (one task per type: display + edit modal + action + add page)

Every task in Part D follows the same pattern. Each task repeats it with its own specifics.

- **Display page.** Replace `$stmt = $conn->prepare("SELECT * FROM <table> ...")` … `get_result()` … `while ($row = $result->fetch_assoc())` with `foreach (<rules expression> as $row)`, and `$result->num_rows` with `count(...)`. Sort with `usort` using the same ORDER BY key. Then update every `$row[...]` comparison that relied on a stored sentinel:
  - `$row['distance'] <> '0'` and `> 0` become `!empty($row['distance'])`;
  - `$row['clean'] == '1'` becomes `!empty($row['clean'])`;
  - `$all_x_cleaned == '0'` stays as it is (it's still `'0'`, `'1'` or null);
  - `$row['ping'] <> ''` becomes `!empty($row['ping'])`;
  - template radio pre-selection becomes `$row['template'] ?? $_SESSION['defaultTemplateName']`.
- **Edit modal.** Pre-fill from `$row`, where null means empty or the "any" option. Add a hidden `rule_type` input only where a page mixes two v2 types (invasion and incident).
- **Action.** Each SQL branch is replaced by a call wrapped in `try { … } catch (PoracleApiException $e) { poracle_fail('<page>', $e); }`, followed by the same success redirect as before:

| Branch | Replacement |
|---|---|
| update | `poracle_track_update(poracle_api(), $_SESSION['id'], '<type>', (int) $_POST['uid'], <changes>, (int) $_SESSION['profile'])` |
| delete | `poracle_track_delete(...)` |
| add | `$res = poracle_track_add(...)`. If `$res['created']` and `$res['updated']` are empty and `$res['unchanged']` isn't, redirect with `return=duplicate`, as before. |
| delete_all | `poracle_track_delete_uids(..., array_column(poracle_rules('<type>'), 'uid'), ...)` |
| update_*_distance | `poracle_track_set_field(..., poracle_rules('<type>'), 'distance', form_common($_POST)['distance'], ...)` |

  Every action file must `include_once "../include/poracle_forms.php";`. `db_connect.php` already pulls in `poracle_session.php`.
- **Verification for each task.**
  - `php -l` on each touched file.
  - `grep -nE '\$conn->(prepare|query)' <files>` prints nothing.
  - Unit tests PASS.
  - A manual check on the running app (see "Manual verification" at the end): add, edit (change distance and ping), delete, set distance on all, delete all. The counts on the dashboard must match.

### Task 10: Lures (reference implementation)

**Files:**
- Modify: `pages/display/lure.php:102-267`, `modal/edit_lures_modal.php`, `actions/lures.php` (whole file)

**Interfaces:**
- Consumes:
  - from Task 6: `poracle_rules`, `poracle_track_*`
  - from Task 7: `form_common`, `form_lure_rules`
  - from Task 5: `poracle_fail`, `poracle_api`

- [ ] **Step 1: Rewrite `actions/lures.php`.**

```php
<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE LURES

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'lures') {
    try {
      poracle_track_update($api, $human, 'lure', (int) $_POST['uid'], form_common($_POST), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_update_lure");
    exit();
  }

  // DELETE LURES

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'lures') {
    try {
      poracle_track_delete($api, $human, 'lure', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lure");
    exit();
  }

  // ADD LURE

  if (isset($_POST['add_lures'])) {
    try {
      $res = poracle_track_add($api, $human, 'lure', form_lure_rules($_POST), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=lure&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_added_lures");
    exit();
  }

  // DELETE ALL LURES

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_lures') {
    try {
      poracle_track_delete_uids($api, $human, 'lure', array_column(poracle_rules('lure'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lures");
    exit();
  }

  // UPDATE ALL LURES DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_lures_distance') {
    try {
      poracle_track_set_field($api, $human, 'lure', poracle_rules('lure'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_update_lures_distance");
    exit();
  }

  include "./action_error.php";
```

  `poracle_track_add` posts an empty array if no checkbox was ticked, and v2 answers that with a 422. Guard against it: when `form_lure_rules($_POST)` is empty, redirect to `return=success_added_lures` without calling the API. The old code also did nothing in that case.

- [ ] **Step 2: Update `pages/display/lure.php:102-112`.** Replace it with:

```php
                                // Show Lures

                                $lures = poracle_rules('lure');
                                usort($lures, function ($a, $b) { return $a['lure_id'] - $b['lure_id']; });

                                if (count($lures) == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
                                }

                                foreach ($lures as $row) {
```

  In the loop body, change:
  - `$row['distance'] <> '0'` to `!empty($row['distance'])`;
  - `$row['distance'] > 0` to `!empty($row['distance'])`;
  - `$row['ping'] <> ''` to `!empty($row['ping'])`;
  - `$row['clean'] == '1'` to `!empty($row['clean'])`.

  Leave `lure_id` comparisons as they are (it's required, and 0 means all). Remove the trailing `$stmt->close();` if there is one.

- [ ] **Step 3: Update `modal/edit_lures_modal.php`.**
  - Pre-select the `clean_1` radio when `!empty($row['clean'])` and `clean_0` otherwise.
  - Pre-select the template radio when it equals `$row['template'] ?? $_SESSION['defaultTemplateName']`.
  - The ping textbox value is `htmlspecialchars($row['ping'] ?? '')`.
  - `include/edit_area_distance.php` reads `$row['distance']`. Change its use to `(int) ($row['distance'] ?? 0)`, since that include is shared by every edit modal.

- [ ] **Step 4: Verify** using the commands in the Part D preamble. Expected: lint clean, grep empty, and the manual check passes.

- [ ] **Step 5: Commit.**

```bash
git add actions/lures.php pages/display/lure.php modal/edit_lures_modal.php include/edit_area_distance.php
git commit -m "feat(lures): read and write lure tracking through the v2 API"
```

### Task 11: Nests

**Files:**
- Modify: `pages/display/nest.php:102-297`, `modal/edit_nests_modal.php`, `actions/nests.php` (whole file)

- [ ] **Step 1: Rewrite `actions/nests.php`** following the Task 10 structure, with:
  - type `'nest'` and page `'nest'`;
  - return codes `success_update_nest`, `success_delete_nest`, `success_added_nests`, `success_delete_nests`, `success_update_nests_distance`;
  - GET actions `delete_all_nests` and `update_nests_distance`;
  - update changes: `array_merge(form_common($_POST), array('min_spawn_avg' => form_int($_POST, 'min_spawns') ?: null))`;
  - add: `form_nest_rules($_POST)`.
- [ ] **Step 2: Update `pages/display/nest.php`.**
  - Use `$nests = poracle_rules('nest');` sorted by `(int) ($row['pokemon_id'] ?? 0)`.
  - Where the page builds icon or name lookups from `pokemon_id` and `form`, use `(int) ($row['pokemon_id'] ?? 0)` and `(int) ($row['form'] ?? 0)`.
  - Show `min_spawn_avg` only when it isn't null.
  - Apply the common comparison changes from the Part D preamble.
- [ ] **Step 3: Update `modal/edit_nests_modal.php`.** The `min_spawns` value is `(int) ($row['min_spawn_avg'] ?? 0)`. Apply the common pre-fill changes.
- [ ] **Step 4: Verify** as in the Part D preamble.
- [ ] **Step 5: Commit** with `feat(nests): nest tracking through the v2 API`.

### Task 12: Gyms

**Files:**
- Modify: `pages/display/gym.php:102-290`, `modal/edit_gyms_modal.php`, `actions/gyms.php`, `pages/add/gym.php`

- [ ] **Step 1: Rewrite `actions/gyms.php`** following the Task 10 structure, with:
  - type `'gym'` and page `'gym'`;
  - return codes `success_update_gym`, `success_delete_gym`, `success_added_gyms`, `success_delete_gyms`, `success_update_gyms_distance`;
  - GET actions `delete_all_gyms` and `update_gyms_distance`;
  - update changes: `array_merge(form_common($_POST), array('slot_changes' => form_radio($_POST, 'slot_') === '1' ? true : null, 'battle_changes' => form_radio($_POST, 'battle_') === '1' ? true : null))`;
  - add: `form_gym_rules($_POST)`.
- [ ] **Step 2: Update `pages/display/gym.php`.**
  - Use `$gyms = poracle_rules('gym');` sorted by `poracle_team_id($row['team'])`.
  - Every `get_gym_color($row['team'])` and `get_gym_name(...)` call, and any team icon built from `$row['team']`, gets `poracle_team_id($row['team'])` instead.
  - Check `slot_changes` and `battle_changes` with `!empty(...)`.
  - `gym_id` can be null; keep the existing `if ($row['gym_id'] ...)` guards, which already treat null as unset.
  - Apply the common comparison changes.
  - Add `include_once "./include/poracle_forms.php";` near the top of the page, since `poracle_team_id` lives there. Alternatively, include it once in `header.php`, which is cleaner because every display page needs it; do that and drop the per-page includes.
- [ ] **Step 3: Update `modal/edit_gyms_modal.php`.** Radios `slot_1`/`slot_0` and `battle_1`/`battle_0` are pre-checked from `!empty($row['slot_changes'])` and `!empty($row['battle_changes'])`. Apply the common pre-fill changes.
- [ ] **Step 4: Fix the gym cleaning-toggle bug where it lives.** This is the duplicate `nids_clean_toggle` name. In `pages/display/cleaning.php:202`, rename the gym checkbox to `gyms_clean_toggle`; Task 17 reads it.
- [ ] **Step 5: Verify and commit** with `feat(gyms): gym tracking through the v2 API`.

### Task 13: Invasions and incidents

**Files:**
- Modify: `pages/display/invasion.php:102-284`, `modal/edit_invasions_modal.php`, `modal/delete_invasions_modal.php`, `actions/invasions.php`

**Interfaces:**
- Consumes: `form_invasion_rules` and `PORACLE_INCIDENTS` from Task 7.
- Produces: the edit and delete forms for this page post a hidden `rule_type` set to `invasion` or `incident`.

- [ ] **Step 1: Rewrite `actions/invasions.php`** following the Task 10 structure.
  - Page `'invasion'`. Return codes: `success_update_invasion`, `success_delete_invasion`, `success_added_invasions`, `success_delete_invasions`, `success_update_invasions_distance`. GET actions: `delete_all_invasions` and `update_invasions_distance`.
  - Pick the type with `$type = (isset($_POST['rule_type']) && $_POST['rule_type'] === 'incident') ? 'incident' : 'invasion';`.
  - Update changes are `form_common($_POST)`. For `invasion` only, also merge `array('gender' => poracle_gender_enum((int) form_radio($_POST, 'gender_')))`.
  - Add: run `$built = form_invasion_rules($_POST);` and then `poracle_track_add` for each non-empty `$built['invasion']` and `$built['incident']`. It counts as a duplicate only when every call returned only `unchanged`.
  - "Delete all" and "set distance on all" run once for `invasion` and once for `incident`.
- [ ] **Step 2: Update `pages/display/invasion.php`.** Build a merged list:

```php
                                $invasions = array();
                                foreach (poracle_rules('invasion') as $rule) { $rule['rule_type'] = 'invasion'; $invasions[] = $rule; }
                                foreach (poracle_rules('incident') as $rule) {
                                   $rule['rule_type'] = 'incident';
                                   $rule['grunt_type'] = PORACLE_INCIDENTS[$rule['display_type']] ?? ('event ' . $rule['display_type']);
                                   $rule['gender'] = null;
                                   $invasions[] = $rule;
                                }
                                usort($invasions, function ($a, $b) { return strcmp($a['grunt_type'], $b['grunt_type']); });
```

  - Wherever the page passes `$row['gender']` to `get_grunt()` or compares it, use `poracle_gender_id($row['gender'])`.
  - Apply the common comparison changes.
- [ ] **Step 3: Update the modals.** Both the edit and delete modals add `<input type='hidden' name='rule_type' value='<?php echo $row['rule_type']; ?>'>`. The edit modal pre-checks `gender_<n>` from `poracle_gender_id($row['gender'])`, and hides the gender radios when `$row['rule_type'] === 'incident'`.
- [ ] **Step 4: Verify.** Add a showcase rule and a water grunt rule. Both must appear, edit and delete correctly. Then commit with `feat(invasions): invasion and incident tracking through the v2 API`.

### Task 14: Quests

**Files:**
- Modify: `pages/display/quest.php:103-735`, `modal/edit_quests_modal.php`, `actions/quests.php`

- [ ] **Step 1: Rewrite `actions/quests.php`** following the Task 10 structure, with:
  - type `'quest'` and page `'quest'`;
  - return codes `success_update_quest`, `success_delete_quest`, `success_added_quest`, `success_delete_quest`, `success_update_quests_distance`;
  - GET actions `delete_all_quests` and `update_quests_distance`;
  - update changes: `form_common($_POST)`;
  - add: `form_quest_rules($_POST)`.
- [ ] **Step 2: Update `pages/display/quest.php`.**
  - Fetch once with `$quests = poracle_rules('quest');`.
  - The "no alarm" check at 103-114 becomes `count($quests) == 0`.
  - Each of the four sections (reward_type 7, 2, 12 and 4) filters with `array_filter($quests, function ($r) { return $r['reward_type'] == 7; })` and sorts by `(int) ($r['reward'] ?? 0)`.
  - In the loops, read `$row['reward']` as `(int) ($row['reward'] ?? 0)` (0 = all of that category, as before) and `$row['form']` as `(int) ($row['form'] ?? 0)`.
  - Apply the common comparison changes.
- [ ] **Step 3: Update `modal/edit_quests_modal.php`** with the common pre-fill changes, reading `reward` and `form` as above.
- [ ] **Step 4: Verify and commit** with `feat(quests): quest tracking through the v2 API`.

### Task 15: Raids and eggs

**Files:**
- Modify: `pages/display/raid.php:105-661`, `modal/edit_raids_modal.php`, `modal/edit_eggs_modal.php`, `actions/raids.php`

- [ ] **Step 1: Rewrite `actions/raids.php`** following the Task 10 structure, with page `'raid'`.
  - **Update.** The type is `$_POST['type'] == 'eggs' ? 'egg' : 'raid'`. Changes are `form_common($_POST)`. Return codes: `success_update_raid` and `success_update_egg`.
  - **Delete.** Same type selection. Return codes: `success_delete_raid` and `success_delete_egg`.
  - **Add.** Run `$built = form_raid_rules($_POST);`, then `poracle_track_add` for each non-empty `$built['egg']` and `$built['raid']`. Return `success_added_raids`.
  - **delete_all_raids.** Delete the uids of `poracle_rules('raid')` and of `poracle_rules('egg')`, both for the **viewed profile only**. This fixes the bug where eggs were deleted on every profile. Return `success_delete_raids`.
  - **update_raids_distance.** `set_field` on raid and egg. Return `success_update_raids_distance`.
- [ ] **Step 2: Update `pages/display/raid.php`.**
  - The egg section: `poracle_rules('egg')`, sorted by `level`.
  - The by-level raid section: `array_filter(poracle_rules('raid'), function ($r) { return $r['pokemon_id'] === null; })`, sorted by `(int) ($r['level'] ?? 0)`. Show a `level` of null as `i8ln("ALL")`. Whatever the page did for level 90 now applies to null; keep 90 working too for eggs.
  - The by-boss section: `array_filter(poracle_rules('raid'), function ($r) { return $r['pokemon_id'] !== null; })`, sorted by `pokemon_id`, with `form` read as `(int) ($row['form'] ?? 0)`.
  - Show the `rsvp_changes` badge when it's `'rsvp'` or `'rsvp_only'`. The old code checked for 1 or 2.
  - Apply the common comparison changes.
- [ ] **Step 3: Update both edit modals** with the common pre-fill changes.
- [ ] **Step 4: Verify.** Include a mega boss, which now stores `evolution`. Commit with `feat(raids): raid and egg tracking through the v2 API`.

### Task 16: Pokemon and quick pick

**Files:**
- Modify: `pages/display/pokemon.php:8-576`, `modal/edit_pokemons_modal.php`, `actions/monsters.php`, `pages/add/pokemon.php` (the default values in the form fields), `pages/display/quick_pick.php:4-365`, `actions/quick_pick.php`
- Modify: `include/defaults.php`, removing `$monster_defaults`
- Modify: `include/poracle_tracking.php`, adding the pure helpers below
- Test: `tests/unit/PoracleTrackingTest.php`

**Interfaces:**
- Produces, as pure functions:
  - `poracle_pokemon_gen(?int $pokemonId): string`. Returns `'all'` for null, otherwise `'1'`…`'9'`, using the same ranges as `pokemon.php:36-45`: 1–151, 152–251, 252–386, 387–493, 494–649, 650–721, 722–809, 810–905, ≥906.
  - `poracle_filter_by_ids(array $rules, ?array $ids): array`. Keeps rules whose `pokemon_id` (null treated as 0) is in `$ids`; a null `$ids` keeps everything.
  - `poracle_quick_pick_match(array $rule, string $preset): bool`. The presets are `iv100`, `iv0`, `pvp500`, `pvp1500`, `pvp2500`, `xxs` and `xxl`. The signatures are:

  | Preset | Matches when |
  |---|---|
  | `iv100` | `pokemon_id === null && min_iv === 100` |
  | `iv0` | `pokemon_id === null && min_iv === 0 && max_iv === 0` |
  | `pvpN` | `pokemon_id === null && pvp_ranking_league === N && pvp_ranking_worst === 1` |
  | `xxs` | `pokemon_id === null && size === 1 && max_size === 1` |
  | `xxl` | `pokemon_id === null && size === 5 && max_size === null` |

- [ ] **Step 1: Write the failing tests.** Add to `PoracleTrackingTest`:

```php
   public function testPokemonGen()
   {
      $this->assertSame('all', poracle_pokemon_gen(null));
      $this->assertSame('1', poracle_pokemon_gen(151));
      $this->assertSame('2', poracle_pokemon_gen(152));
      $this->assertSame('9', poracle_pokemon_gen(1025));
   }

   public function testFilterByIds()
   {
      $rules = array(array('pokemon_id' => null), array('pokemon_id' => 25), array('pokemon_id' => 133));
      $this->assertCount(2, poracle_filter_by_ids($rules, array(0, 25)));
      $this->assertCount(3, poracle_filter_by_ids($rules, null));
   }

   public function testQuickPickMatch()
   {
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'min_iv' => 100), 'iv100'));
      $this->assertFalse(poracle_quick_pick_match(array('pokemon_id' => 25, 'min_iv' => 100), 'iv100'));
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'pvp_ranking_league' => 1500, 'pvp_ranking_worst' => 1), 'pvp1500'));
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'size' => 5, 'max_size' => null), 'xxl'));
      $this->assertFalse(poracle_quick_pick_match(array('pokemon_id' => null, 'size' => 5, 'max_size' => 5), 'xxs'));
   }
```

- [ ] **Step 2: Run it and confirm it fails.** Then implement the helpers in `poracle_tracking.php`:

```php
function poracle_pokemon_gen($pokemonId)
{
   if ($pokemonId === null || (int) $pokemonId === 0) {
      return 'all';
   }
   $ends = array(151, 251, 386, 493, 649, 721, 809, 905);
   foreach ($ends as $i => $end) {
      if ($pokemonId <= $end) {
         return (string) ($i + 1);
      }
   }
   return '9';
}

function poracle_filter_by_ids(array $rules, $ids)
{
   if ($ids === null) {
      return $rules;
   }
   return array_values(array_filter($rules, function ($r) use ($ids) {
      return in_array((int) ($r['pokemon_id'] ?? 0), $ids, true);
   }));
}

function poracle_quick_pick_match(array $rule, $preset)
{
   $get = function ($key) use ($rule) { return array_key_exists($key, $rule) ? $rule[$key] : null; };
   if ($get('pokemon_id') !== null) {
      return false;
   }
   switch ($preset) {
      case 'iv100': return $get('min_iv') === 100;
      case 'iv0': return $get('min_iv') === 0 && $get('max_iv') === 0;
      case 'pvp500': case 'pvp1500': case 'pvp2500':
         return $get('pvp_ranking_league') === (int) substr($preset, 3) && $get('pvp_ranking_worst') === 1;
      case 'xxs': return $get('size') === 1 && $get('max_size') === 1;
      case 'xxl': return $get('size') === 5 && $get('max_size') === null;
   }
   return false;
}
```

  Run the tests. Expected: PASS.

- [ ] **Step 3: Rewrite `actions/monsters.php`.**
  - Remove lines 8-39, the `$monster_defaults` and `$cap` defaulting; the builders now handle that.
  - Build the min-CP map: `$pvpMinCp = array(500 => $_SESSION['pvpFilterLittleMinCP'], 1500 => $_SESSION['pvpFilterGreatMinCP'], 2500 => $_SESSION['pvpFilterUltraMinCP']);`.
  - **Update.** Changes are `array_merge(form_common($_POST), form_pokemon_filters($_POST, $pvpMinCp, (int) $_SESSION['defaultPvpCap']))`.
  - **Add.** `form_pokemon_rules(...)` with the same arguments.
  - Type `'pokemon'`.
  - Keep `$gen` (`$gen = $_POST['gen'] ?? $_GET['gen'] ?? 'all'`) in every redirect, as before (`page=pokemon&gen=$gen&return=…`).
  - Return codes: `success_update_mons`, `success_delete_mons`, `success_added_mons`, `success_delete_mons`, `success_update_mons_distance`. GET actions: `delete_all_mons` and `update_mons_distance`.
  - Remove every `reloadPokemon()` call.
- [ ] **Step 4: Update `pages/display/pokemon.php`.**
  - Replace `execute_count_query` and the queries at 178-186, 231 and 235-239 with:
    - `$all = poracle_filter_by_ids(poracle_rules('pokemon'), $search_ids)`. Here `$search_ids` is the `get_matching_ids` result already computed at lines 10-21. Keep that computation, but stop building `$search_sql`.
    - Count each generation with `count(array_filter($all, function ($r) use ($g) { return poracle_pokemon_gen($r['pokemon_id']) === $g; }))`.
    - When there are more than 50 rules, page by `$_GET['gen']` using the same comparison.
    - Sort by `(int) ($r['pokemon_id'] ?? 0)`, then by `(int) ($r['form'] ?? 0)`.
  - In the card loop:
    - Show each filter only when it isn't null. The old code compared against `$monster_defaults` and stored sentinels; the new check is simply `!== null`.
    - `gender`: show the badge when it isn't null, using `poracle_gender_id()` for the icon lookup.
    - `size`: show it when it isn't null.
    - Show the PvP block when `pvp_ranking_league !== null`.
  - Apply the common comparison changes.
- [ ] **Step 5: Update `modal/edit_pokemons_modal.php`.**
  - Delete lines 5-16, which blanked values equal to the defaults; null is already blank.
  - Each input's `value` is `htmlspecialchars((string) ($row['<field>'] ?? ''))`.
  - The `noiv` box is checked when `$row['min_iv'] === null`.
  - `league` is pre-selected from `(int) ($row['pvp_ranking_league'] ?? 0)`.
  - `cap_<n>` is pre-selected from `(int) ($row['pvp_ranking_cap'] ?? $_SESSION['defaultPvpCap'])`.
  - `form_<n>` is pre-selected from `(int) ($row['form'] ?? 0)`.
  - `gender_<n>` is pre-selected from `poracle_gender_id($row['gender'])`.
  - `size_<n>` is pre-selected from `$row['size'] === null ? -1 : $row['size']`.
- [ ] **Step 6: Update `pages/add/pokemon.php`.** Every input whose default comes from `$monster_defaults` gets an empty `value=""` and a `placeholder` with the old default, for example `placeholder="100"`. Then delete `$monster_defaults` from `include/defaults.php`, and confirm with `grep -rn monster_defaults --include=*.php .`, which must print nothing.
- [ ] **Step 7: Rewrite quick pick.**
  - **`pages/display/quick_pick.php`:**
    - `$mons = poracle_rules('pokemon');`
    - `$mon_cleaned = poracle_all_clean($mons) === '1' ? 1 : 0;`
    - `$mon_distance` is the minimum of `(int) ($r['distance'] ?? 0)` over `$mons`, or 0 when there are none.
    - For each preset, replace the SELECT with a loop over `$mons` using `poracle_quick_pick_match($r, '<preset>')`. On a match, set `$found = 1` and `$uid = $r['uid']`.
    - Drop the hidden `pokemon_id=0` input: a missing `mon_` key means "all" in `form_pokemon_rules`. Add a hidden `mon_0=on` instead.
  - **`actions/quick_pick.php`:**
    - Add: `poracle_track_add($api, $human, 'pokemon', form_pokemon_rules($_POST, $pvpMinCp, (int) $_SESSION['defaultPvpCap']), $profile)`. Build `$pvpMinCp` as in Step 3.
    - Delete: `poracle_track_delete(... 'pokemon', (int) $_POST['uid'] ...)`.
    - Keep the redirects (`page=quick_pick&pick=...`).
- [ ] **Step 8: Verify.**
  - Run the unit tests, `php -l` and the grep gate.
  - Manual check: add Pikachu with min IV 90, edit it to set a PvP rank, use a quick pick, run the gen pager with more than 50 rules, and search by name.
  - Commit with `feat(pokemon): pokemon and quick-pick tracking through the v2 API`.

---

# Part E — Humans, profiles, locations

### Task 17: Alerts toggle, cleaning toggles, language, areas, location, profiles

**Files:**
- Modify: `actions/profile.php`, `actions/set_language.php`, `actions/areas.php`, `actions/set_location.php`, `actions/switch_profile.php`, `actions/roles.php`, `pages/display/manage_roles.php:8`

**Interfaces:**
- Consumes:
  - `poracle_api()`, `poracle_human_path()` and `poracle_fail()`;
  - `poracle_track_set_field()`, `poracle_rules()` and `poracle_type_for_table()`;
  - `poracle_snapshot()['profiles']`.
- All the PoracleNG endpoints are listed in spec §5. Clearing a location needs PoracleNG PR #248.

- [ ] **Step 1: `actions/profile.php`.**
  - **Alerts toggle:** `$api->post(poracle_human_path($human) . '/' . (isset($_POST['alerts_toggle']) ? 'enable' : 'disable'))`.
  - **Cleaning toggles:** for each `(checkbox, table)` pair, run the loop below. The pairs are:
    - `pokes_clean_toggle` → monsters
    - `re_clean_toggle` → raid and egg
    - `quests_clean_toggle` → quest
    - `invasions_clean_toggle` → invasion
    - `leures_clean_toggle` → lures
    - `nids_clean_toggle` → nests
    - `gyms_clean_toggle` → gym (renamed in Task 12)

```php
    foreach (poracle_type_for_table($table) as $type) {
      foreach (poracle_snapshot()['profiles'] as $p) {
        poracle_track_set_field($api, $human, $type, poracle_rules($type, (int) $p['profile_no']), 'clean', isset($_POST[$checkbox]) ? true : null, (int) $p['profile_no']);
      }
    }
```

  Wrap the whole action in `try`/`catch` with `poracle_fail('cleaning', $e)`, or `poracle_fail('', $e)` for the alerts form. Keep the existing success redirects.

- [ ] **Step 2: `actions/set_language.php`.** Use `$api->post(poracle_human_path($human) . '/language', array('language' => $_GET['lng']))`. On a 422, show `poracle_fail('', $e)`; v2 validates the locale. On success, set `$_SESSION['locale']` as before.

- [ ] **Step 3: `actions/areas.php`.**
  - Build `$areas` as a PHP array from the `area_*` keys, lowercased, with `%20` turned into a space.
  - Call `$res = $api->post(poracle_human_path($human) . '/areas', array('areas' => $areas), array('profile' => $profile));`.
  - If `$res['rejected']` isn't empty, store `$_SESSION['api_error'] = i8ln("Some areas were not accepted") . ': ' . implode(', ', $res['rejected']);` and redirect with `return=api_error`. Otherwise redirect with `success_update_areas`.

- [ ] **Step 4: `actions/set_location.php`.**
  - **Delete:**
    - `$api->delete(poracle_human_path($human) . '/location', array('profile' => $profile));`
    - then, for every type in `array('pokemon','raid','egg','quest','invasion','incident','lure','nest','gym','fort','maxbattle')`, run `poracle_track_set_field($api, $human, $type, poracle_rules($type), 'distance', null, $profile);` (all 11 types, not the old 5);
    - redirect with `success_delete_location`. This also fixes the old bug where the code checked POST and so never sent this code.
  - **Set:** keep the geocoding. Call `$api->post(poracle_human_path($human) . '/location', array('lat' => (float) $lat, 'lon' => (float) $lon), array('profile' => $profile));`. A 403 means the location is outside the user's allowed area: `poracle_fail('area', $e)`.

- [ ] **Step 5: `actions/switch_profile.php`.**
  - **view:** first check that `$_POST['profile']` is in `array_column(poracle_snapshot()['profiles'], 'profile_no')`. If it isn't, use `poracle_fail('profiles', new Exception(i8ln("Unknown profile")))`.
  - **activate:** `$api->post(poracle_human_path($human) . '/profile', array('profile_no' => (int) $_POST['profile']));`. The server copies the profile's area and location; PoracleWeb no longer does it.
  - **create:**
    - `$res = $api->post(poracle_human_path($human) . '/profiles', array('name' => $_POST['profile_name']));`
    - then `$_SESSION['profile'] = $res['profile_no'];`
    - For the first extra profile, the old code copied the human's area and location into it. Do that only when `count(profiles) == 1` before the call: run `POST areas?profile=<new>` with the profile view's area, and `POST location?profile=<new>` with its lat/lon.
  - **rename:** `$api->patch(poracle_human_path($human) . '/profiles/' . $profile, array('name' => $_POST['profile_name']));`
  - **delete:**
    - `$api->delete(poracle_human_path($human) . '/profiles/' . $profile);` (the server deletes all the tracking and reassigns the active profile);
    - `poracle_snapshot_invalidate();`
    - `$_SESSION['profile'] = (int) poracle_snapshot()['human']['current_profile_no'];`

- [ ] **Step 6: Roles.**
  - `pages/display/manage_roles.php:8`: `poracle_api()->get(poracle_human_path($_SESSION['id']) . '/roles')`. The response is `{guilds: …}`; check its shape against `/openapi.json` (`V2RolesOutputBody`) and adjust the page's loop keys to match.
  - `actions/roles.php`: when the GET `type` is `add`, call `$api->post(.../roles/{roleId})`; when it's `remove`, call `$api->delete(.../roles/{roleId})`.

- [ ] **Step 7: Verify.**
  - Lint, and run the grep gate on all seven files.
  - Manual check:
    - toggle alerts;
    - toggle cleaning, including gym, which must now follow its own checkbox;
    - change language, including an invalid one, which must show a message;
    - set and clear areas;
    - set and delete the location;
    - create, rename, view, activate and delete a profile, and confirm the nests and gym rules of the deleted profile are gone.
  - Commit with `feat: humans, profiles, locations, areas and roles through the v2 API`.

---

# Part F — Admin

### Task 18: Admin permission checks

**Files:**
- Create: `include/poracle_admin.php`
- Modify: `admin_connect.php`, `actions/server_settings.php`, `actions/channel_sync.php`, `actions/migrate.php`
- Test: `tests/unit/PoracleAdminTest.php`

**Interfaces:**
- Produces: `admin_may_manage(array $session, array $target): bool`. `$target` contains `id`, `type` and `name`.
  - True if `$session['admin_id']` is set.
  - True if `$session['poracle_admin']` is set.
  - True if the target type ends in `:user` and `$session['users_admin']` is set.
  - True if the target is a `discord:channel` whose id is in `$session['delegated_channels']['discord']['channels']`.
  - True if the target is a `webhook` whose **name** is in `$session['delegated_channels']['discord']['webhooks']`.
  - True if the target is a `telegram:channel` or `telegram:group` whose id is in `$session['delegated_channels']['telegram']['channels']`.
  - True if `$target['id'] === $session['delegated_id']`, so an admin can always switch back to themselves.
  - Otherwise false.

- [ ] **Step 1: Write the failing tests.**

```php
<?php
use PHPUnit\Framework\TestCase;

class PoracleAdminTest extends TestCase
{
   private function delegated()
   {
      return array('delegated_id' => '1', 'delegated_channels' => array(
         'discord' => array('channels' => array('100'), 'webhooks' => array('hook-a')),
         'telegram' => array('channels' => array('-200')),
      ));
   }

   public function testSuperAdminMayManageAnyone()
   {
      $this->assertTrue(admin_may_manage(array('admin_id' => '1'), array('id' => '9', 'type' => 'discord:user', 'name' => 'x')));
   }

   public function testDelegatedChannelAdminScopedToTheirChannels()
   {
      $s = $this->delegated();
      $this->assertTrue(admin_may_manage($s, array('id' => '100', 'type' => 'discord:channel', 'name' => 'c')));
      $this->assertFalse(admin_may_manage($s, array('id' => '101', 'type' => 'discord:channel', 'name' => 'c')));
      $this->assertTrue(admin_may_manage($s, array('id' => 'w', 'type' => 'webhook', 'name' => 'hook-a')));
      $this->assertTrue(admin_may_manage($s, array('id' => '-200', 'type' => 'telegram:group', 'name' => 'g')));
      $this->assertFalse(admin_may_manage($s, array('id' => '9', 'type' => 'discord:user', 'name' => 'u')));
   }

   public function testUsersAdminMayManageUsersOnly()
   {
      $s = array('users_admin' => 'True', 'delegated_id' => '1');
      $this->assertTrue(admin_may_manage($s, array('id' => '9', 'type' => 'telegram:user', 'name' => 'u')));
      $this->assertFalse(admin_may_manage($s, array('id' => '100', 'type' => 'discord:channel', 'name' => 'c')));
   }

   public function testMaySwitchBackToSelf()
   {
      $this->assertTrue(admin_may_manage($this->delegated(), array('id' => '1', 'type' => 'discord:user', 'name' => 'me')));
   }
}
```

- [ ] **Step 2: Run it and confirm it fails. Then implement `include/poracle_admin.php`.**

```php
<?php

// Whether the logged-in admin may act as $target (array with id, type, name).
function admin_may_manage(array $session, array $target)
{
   if (!empty($session['admin_id']) || !empty($session['poracle_admin'])) {
      return true;
   }
   if (isset($session['delegated_id']) && $target['id'] === $session['delegated_id']) {
      return true;
   }
   $type = $target['type'];
   if (substr($type, -5) === ':user') {
      return !empty($session['users_admin']);
   }
   $d = isset($session['delegated_channels']) ? $session['delegated_channels'] : array();
   $list = function ($platform, $key) use ($d) {
      return isset($d[$platform][$key]) ? array_map('strval', (array) $d[$platform][$key]) : array();
   };
   if ($type === 'discord:channel') {
      return in_array((string) $target['id'], $list('discord', 'channels'), true);
   }
   if ($type === 'webhook') {
      return in_array((string) $target['name'], $list('discord', 'webhooks'), true);
   }
   if ($type === 'telegram:channel' || $type === 'telegram:group') {
      return in_array((string) $target['id'], $list('telegram', 'channels'), true);
   }
   return false;
}
```

  Add `include/poracle_admin.php` to the include list in `tests/bootstrap.php` (it's already listed), and include it from `db_connect.php` next to `poracle_session.php`.

- [ ] **Step 3: Add the guards.**
  - At the top of `actions/server_settings.php` and `actions/channel_sync.php`:

```php
if (empty($_SESSION['admin_id'])) { header("Location: $redirect_url?return=invalid_auth"); exit(); }
```

  - `actions/migrate.php` is guarded in Task 20.
  - `admin_connect.php` is rewritten in Task 19 to call `admin_may_manage($_SESSION, $target)` before switching identity.

- [ ] **Step 4: Verify and commit.** Run the tests and lint. Commit with `fix: enforce admin rights in privileged actions`.

### Task 19: Admin lists, impersonation, and the API page

**Files:**
- Modify: `pages/display/manage_users.php:74-140`, `pages/display/manage_channels.php:51-238`, `admin_sync.php:93-245`, `admin_connect.php:25-121`, `pages/display/poracle_api.php:236-250`

**Interfaces:**
- Consumes: `poracle_all_apis()`, `poracle_api_for()`, `admin_may_manage()`.
- Endpoints: `GET /api/v2/humans?type=<type>` and `GET /api/v2/humans?id=a,b,c`. Both return `{humans: [{id, type, name, enabled, admin_disable, language, current_profile_no, last_checked, disabled_date, notes}]}`.

- [ ] **Step 1: The list pages.**
  - Replace each per-DB `mysqli` loop with `foreach (poracle_all_apis() as $instance => $api)`.
  - Inside it, call `$api->get('/api/v2/humans', array('type' => '<type>'))['humans']`, sorted by name with `usort` (the old ORDER BY).
  - For `telegram:channel` and `telegram:group`, make two calls and merge the results.
  - For `admin_sync.php`, drop the human whose `id == $_SESSION['id']`.
  - Keep the existing HTML and filters: `delegated_channels` membership for channels, and the webhook name for webhooks.
  - The checkbox name stays `target_<instance>|<id>`.
  - Wrap each instance in `try`/`catch`. A failing instance shows `i8ln("Could not connect to the API") . " ($instance)"` and the page carries on.
- [ ] **Step 2: `admin_connect.php`.**
  - Find the target with:
    - `foreach (poracle_all_apis() as $instance => $api) { $found = $api->get('/api/v2/humans', array('id' => $search_id))['humans']; if ($found) { $target = $found[0]; $target_instance = $instance; break; } }`
  - If there's no `$target`, redirect with `return=user_not_found`, as before.
  - If `!admin_may_manage($_SESSION, $target)`, redirect with `return=invalid_auth`.
  - Otherwise set:
    - `$_SESSION['dbname'] = $target_instance;`
    - `id`, `username` (from `name`), `type` and `notes` from `$target`
    - `$_SESSION['profile'] = $target['current_profile_no'];`
  - Reload areas with `poracle_api()->get(poracle_human_path($target['id']) . '/areas')['areas']`.
  - Keep the "switch back to self" branch (lines 94-110) as it is.
- [ ] **Step 3: `pages/display/poracle_api.php:236-250`.**
  - Make one call: `poracle_api()->get('/api/v2/humans', array('id' => implode(',', $_SESSION['poracle_admins'])))['humans']`.
  - Index the result by id.
  - Render the badges from that index. Show admins with no human record using only their id.
- [ ] **Step 4: Verify.**
  - Lint and run the grep gate on the five files.
  - Manual check: list users and channels, connect to a channel as a delegated admin (allowed), and try a channel outside the delegated list (must be refused).
  - Commit with `feat(admin): user and channel lists and impersonation through the v2 API`.

### Task 20: Channel sync and account migration

**Files:**
- Modify: `actions/channel_sync.php` (whole file), `actions/migrate.php` (whole file), `pages/display/migrate.php:46-92`, `telegram_auth.php:11-34`, `include/functions.php` (add `telegram_auth_valid`)
- Modify: `include/poracle_tracking.php`, adding the pure `poracle_copy_plan`
- Test: `tests/unit/PoracleTrackingTest.php`

**Interfaces:**
- Produces:
  - `telegram_auth_valid(array $params, string $botToken, int $now): bool`. This is the same HMAC check `telegram_auth.php` does today, plus the 86400-second expiry. Its unit test lives in `PoracleAdminTest`.
  - `poracle_copy_plan(array $snapshot, ?int $onlyProfile): array`. It turns a snapshot into `[['type' => t, 'profile_no' => n, 'rules' => [bodies…]], …]`. The bodies go through `poracle_rule_body`. A null `$onlyProfile` means every profile.

- [ ] **Step 1: Write the failing tests.** Add to `PoracleTrackingTest`:

```php
   public function testCopyPlanGroupsByTypeAndProfileWithoutUids()
   {
      $plan = poracle_copy_plan($this->snapshot, null);
      $this->assertSame(array(array('lure', 1, 2), array('lure', 2, 1)), array_map(function ($p) { return array($p['type'], $p['profile_no'], count($p['rules'])); }, $plan));
      $this->assertArrayNotHasKey('uid', $plan[0]['rules'][0]);
      $this->assertSame(array(array('lure', 2, 1)), array_map(function ($p) { return array($p['type'], $p['profile_no'], count($p['rules'])); }, poracle_copy_plan($this->snapshot, 2)));
   }
```

  Add to `PoracleAdminTest`:

```php
   public function testTelegramAuth()
   {
      $token = 'bot:token';
      $params = array('id' => '5', 'username' => 'u', 'auth_date' => '1000');
      $check = array();
      foreach ($params as $k => $v) { $check[] = "$k=$v"; }
      sort($check);
      $params['hash'] = hash_hmac('sha256', implode("\n", $check), hash('sha256', $token, true));
      $this->assertTrue(telegram_auth_valid($params, $token, 1500));
      $this->assertFalse(telegram_auth_valid($params, $token, 1000 + 86401));
      $params['id'] = '6';
      $this->assertFalse(telegram_auth_valid($params, $token, 1500));
   }
```

  `telegram_auth_valid` lives in `include/functions.php`, which has side effects (globals). So put it in `include/poracle_admin.php` instead, which the bootstrap loads. `telegram_auth.php` and `pages/display/migrate.php` call it from there.

- [ ] **Step 2: Run it and confirm it fails. Then implement both functions.**

```php
// poracle_tracking.php
function poracle_copy_plan(array $snapshot, $onlyProfile)
{
   $plan = array();
   foreach ($snapshot['tracking'] as $type => $rules) {
      $byProfile = array();
      foreach ($rules as $rule) {
         $p = (int) $rule['profile_no'];
         if ($onlyProfile === null || $p === (int) $onlyProfile) {
            $byProfile[$p][] = poracle_rule_body($rule);
         }
      }
      ksort($byProfile);
      foreach ($byProfile as $p => $bodies) {
         $plan[] = array('type' => $type, 'profile_no' => $p, 'rules' => $bodies);
      }
   }
   return $plan;
}
```

```php
// poracle_admin.php
function telegram_auth_valid(array $params, $botToken, $now)
{
   if (!isset($params['hash'], $params['auth_date'])) {
      return false;
   }
   $hash = $params['hash'];
   unset($params['hash']);
   $check = array();
   foreach ($params as $key => $value) {
      $check[] = $key . '=' . $value;
   }
   sort($check);
   $expected = hash_hmac('sha256', implode("\n", $check), hash('sha256', $botToken, true));
   return hash_equals($expected, $hash) && ($now - (int) $params['auth_date']) <= 86400;
}
```

  In `telegram_auth.php:11-34`, replace the inline check with this function. Use `invalid_auth` for a bad hash and `expired` for an old `auth_date`. To keep the two distinct, check the hash first: call the function with `$now = (int) $_GET['auth_date']`, then check the age separately.

- [ ] **Step 3: Rewrite `actions/channel_sync.php`.**

```php
<?php
  include_once "../config.php";
  include_once "../include/db_connect.php";

  if (empty($_SESSION['admin_id'])) { header("Location: $redirect_url?return=invalid_auth"); exit(); }

  if (isset($_POST['sync'])) {
    try {
      $plan = poracle_copy_plan(poracle_snapshot(), (int) $_SESSION['profile']);
      foreach ($_POST as $key => $value) {
        if (strpos($key, 'target_') !== 0) { continue; }
        list($target_instance, $target_id) = explode('|', substr($key, 7), 2);
        $target_instance = str_replace('_com', '.com', $target_instance);
        if (!in_array($target_instance, poracle_instance_names(), true)) { continue; }
        $target_api = poracle_api_for($target_instance);
        $target_profile = (int) $target_api->get(poracle_human_path($target_id))['human']['current_profile_no'];
        foreach (array('pokemon','raid','egg','quest','invasion','incident','lure','nest','gym','fort','maxbattle') as $type) {
          $existing = $target_api->get(poracle_tracking_path($target_id, $type), array('profile' => $target_profile))['rules'];
          poracle_track_delete_uids($target_api, $target_id, $type, array_column($existing, 'uid'), $target_profile);
        }
        foreach ($plan as $step) {
          $target_api->post(poracle_tracking_path($target_id, $step['type']), $step['rules'], array('profile' => $target_profile, 'silent' => true));
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('', $e);
    }
    header("Location: $redirect_url?return=success_channel_sync");
    exit();
  }

  include "./action_error.php";
```

  This syncs the source's **viewed profile** into each target's **active profile**, across all 11 types. That is a deliberate change from the old behaviour, which copied only 4 types and every profile row as-is. Record it in the commit message.

- [ ] **Step 4: Rewrite account migration.**

  **`pages/display/migrate.php:46-55`:**
  - Verify `telegram_auth_valid($_GET, $telegram_bot_token, time())`. If it fails, show the `invalid_auth` message and don't render the form.
  - Store `$_SESSION['migrate_telegram'] = array('id' => $_GET['id'], 'username' => $_GET['username'] ?? trim(($_GET['first_name'] ?? '') . ' ' . ($_GET['last_name'] ?? '')), 'avatar' => $_GET['photo_url'] ?? '');`.
  - The "already exists" check becomes: `try { poracle_api()->get(poracle_human_path($_GET['id'])); $exists = true; } catch (PoracleApiException $e) { $exists = ($e->status !== 404); }`.
  - Remove the `discord_id`, `telegram_id`, `telegram_user` and `telegram_avatar` hidden fields. The action reads the session instead.

  **`actions/migrate.php`:**

```php
<?php
  include_once "../config.php";
  include_once "../include/db_connect.php";

  if (isset($_POST['migrate']) && $_POST['migration_type'] == 'discord2telegram' && isset($_SESSION['migrate_telegram'])) {
    $api = poracle_api();
    $source = $_SESSION['id'];
    $target = $_SESSION['migrate_telegram'];
    $created = false;
    try {
      $snap = $api->get(poracle_human_path($source) . '/tracking', array('all_profiles' => true));
      $h = $snap['human'];
      $profiles = $snap['profiles'];
      usort($profiles, function ($a, $b) { return $a['profile_no'] - $b['profile_no']; });

      $api->post('/api/v2/humans', array_filter(array(
        'id' => $target['id'], 'name' => $target['username'], 'type' => 'telegram:user',
        'language' => $h['language'], 'area' => $h['area'], 'latitude' => $h['latitude'], 'longitude' => $h['longitude'],
        'community' => poracle_decode_areas($h['community_membership']), 'notes' => $h['notes'],
        'profile_name' => $profiles ? $profiles[0]['name'] : null,
      ), function ($v) { return $v !== null && $v !== '' && $v !== array(); }));
      $created = true;

      // Profile numbers on the new human: the first is created with the human;
      // the rest get the lowest free number, so map old → new.
      $map = array();
      foreach ($profiles as $i => $p) {
        if ($i === 0) {
          $map[$p['profile_no']] = 1;
        } else {
          $res = $api->post(poracle_human_path($target['id']) . '/profiles', array_filter(array('name' => $p['name'], 'active_hours' => $p['active_hours'])));
          $map[$p['profile_no']] = $res['profile_no'];
        }
        $n = $map[$p['profile_no']];
        $api->post(poracle_human_path($target['id']) . '/areas', array('areas' => poracle_decode_areas($p['area']), 'trusted' => true), array('profile' => $n));
        if ((float) $p['latitude'] != 0 || (float) $p['longitude'] != 0) {
          $api->post(poracle_human_path($target['id']) . '/location', array('lat' => (float) $p['latitude'], 'lon' => (float) $p['longitude']), array('profile' => $n));
        }
      }
      foreach ($snap['locations'] as $loc) {
        $api->post(poracle_human_path($target['id']) . '/locations', array('label' => $loc['label'], 'lat' => (float) $loc['latitude'], 'lon' => (float) $loc['longitude']));
      }
      foreach (poracle_copy_plan($snap, null) as $step) {
        $api->post(poracle_tracking_path($target['id'], $step['type']), $step['rules'], array('profile' => $map[$step['profile_no']], 'silent' => true));
      }
      $api->post(poracle_human_path($target['id']) . '/profile', array('profile_no' => $map[(int) $h['current_profile_no']] ?? 1));
      $api->delete(poracle_human_path($source));
    } catch (PoracleApiException $e) {
      if ($created) {
        try { $api->delete(poracle_human_path($target['id'])); } catch (PoracleApiException $ignored) {}
      }
      poracle_fail('', $e);
    }

    // ... existing session update (lines 64-83): set id/username/avatar/type from $target and re-evaluate admin, unchanged ...
    unset($_SESSION['migrate_telegram']);
    header("Location: $redirect_url?return=success_migrate&mig_source=Discord&mig_target=Telegram");
    exit();
  }

  include "./action_error.php";
```

  Check the field names against `/openapi.json` before finishing:
  - `V2CreateHumanBody.area` is documented as "JSON-array string", so send `$h['area']` as is.
  - `community` is an array.
  - The `locations` items in the snapshot have `label`, `latitude` and `longitude` (`V2LocationItem`).
  - Profile `active_hours` is an array. Send it only when non-empty.

- [ ] **Step 5: Verify.**
  - Run the unit tests and lint.
  - Manual check of the migration on a scratch Discord human that has 2 profiles, rules of 3 types and one saved location. Afterwards the Telegram human must have everything and the Discord human must be gone.
  - Manual check of channel sync from one channel to another: the target ends up with exactly the source's rules.
  - Commit with `feat(admin): channel sync and Discord→Telegram migration through the v2 API`.

---

# Part G — Cleanup

### Task 21: Remove the remaining Poracle-DB access and the PoracleJS leftovers

**Files:**
- Modify: `include/functions.php:497-515` (delete `reloadPokemon`)
- Modify: `include/defaults.php:27` (delete `$min_poracle_version`)
- Modify: `pages/display/server_settings.php`. Minimal edit only, because the user has local uncommitted changes to this file. Replace the PoracleJS GitHub `package.json` version check with the `/health` result: show `version` if present and "PoracleNG v2: yes/no" from `poracle_supports_v2()`.
- Modify: `session.php`. Drop `$_SESSION['poracleVersion']` if nothing reads it any more; check with `grep -rn poracleVersion --include=*.php .`.

- [ ] **Step 1: Delete `reloadPokemon` and `$min_poracle_version`.** Then run `grep -rnE 'reloadPokemon|min_poracle_version' --include=*.php .`. Expected: no output.

- [ ] **Step 2: Run the repo-wide grep gate.**

```bash
grep -rnE '\$conn->(prepare|query)|new mysqli' --include=*.php . | grep -v -E 'include/db_(rdm|mad)\.php|/vendor/|\.worktrees/'
```

  Expected: only these remain, all of them `pweb_settings` or connection setup:
  - `include/db_connect.php`
  - `include/poracle_session.php` (`poracle_instance_settings`)
  - `actions/server_settings.php`
  - `pages/display/server_settings.php`
  - the scanner-DB connections (`$scan_conn`) in `header.php` and `search_mons.php`

  Any other hit is a missed site: fix it.

- [ ] **Step 3: Update `server_settings.php`** as described above. Lint it.

- [ ] **Step 4: Run all tests.**
  - Unit tests: `... phpunit`. Expected: PASS.
  - Integration suite against the local PoracleNG: PASS.

- [ ] **Step 5: Commit.**

```bash
git commit -am "chore: remove direct Poracle DB access and PoracleJS version checks"
```

### Task 22: Bring the spec in line, then open the PR

**Files:**
- Modify: `docs/v2-api-migration.md`, `README.md`

- [ ] **Step 1: Update the spec.**
  - §2: replace "Adapter first, rewrite later" with "Pages render v2 rules natively; null = any".
  - §4: in the field-mapping notes, state that `ping` is on v2 (PoracleNG PR from Task 1).
  - §4 "Bulk update rule": add that the pokemon alias rows are canonicalized server-side (Task 2), and delete the "Check before phase 2" round-trip caveat.
  - §3: keep "instances = DBs in `$dbname`, API settings per DB".
  - Add a "Minimum PoracleNG" line naming the merged PRs: #248 and the Task 1–2 PR.

- [ ] **Step 2: Update `README.md`.** State that PoracleWeb now requires PoracleNG with the v2 API, that PoracleJS is unsupported, and how to run the tests.

- [ ] **Step 3: Commit and push to the fork, then open the PR against bbdoc `develop`.**

```bash
git add docs README.md && git commit -m "docs: align the v2 migration spec with the implementation"
git push -u fork feat/v2-api-migration
gh pr create --repo bbdoc/PoracleWeb --base develop --head jfberry:feat/v2-api-migration --title "Migrate PoracleWeb to the PoracleNG v2 API" --body "<summary, required PoracleNG PRs, behaviour changes (channel sync scope, eggs-delete fix, gym cleaning toggle, migration via API), test plan, and 🤖 Generated with [Claude Code](https://claude.com/claude-code)>"
```

  Then call `mcp__t3-code__link_pull_request` with the PR URL.

---

## Manual verification (run the app)

PoracleWeb pages need the Discord or Telegram login, so they can't be driven fully by automation. Use the real stack:

1. Run PoracleNG from the Part A branch, with PR #248 merged or cherry-picked, against the local DB.
2. Start PoracleWeb:

```bash
docker build -t poracleweb-dev . && docker run --rm -p 8080:80 \
  -e PORACLE_DB_HOST=host.docker.internal -e PORACLE_DB_DATABASE=poracle -e PORACLE_DB_USERNAME=dragonite -e PORACLE_DB_PASSWORD=... \
  -e REDIRECT_URL=http://localhost:8080 -e DISCORD_BOT_CLIENT_ID=... -e DISCORD_BOT_CLIENT_SECRET=... -e ADMIN_ID=<your discord id> poracleweb-dev
```

3. Check that `pweb_settings.api_address` points at `http://host.docker.internal:3030`.
4. Log in, then walk through each type page. Do add, edit, delete, delete all and set distance on all, and compare the rules with `GET /api/v2/humans/<id>/tracking` after each step.

## Self-review notes (for the executor)

- Spec §6 "check before phase 2" is replaced by Task 2 plus Task 8's integration test.
- `fort` and `maxbattle` get no add or list pages in this plan. Spec §7 phase 4 listed them as new pages; they're left out to keep the scope down. They are still included in the location-clear distance reset, channel sync and migration. If the user wants those pages, plan them separately.
