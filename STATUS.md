# STATUS

## Prototype Goal

Confirm base platform requirements and workability: Docker orchestration, Caddy reverse proxy/SSL, PHP environment, Git-based deployment, and basic CLI tooling via Artisan commands.

## Stage

**Prototype (POC)** - Familiarization and validation of core platform capabilities.

## Completed

- [x] Docker socket access via docker-socket-proxy (dproxy container running on :2375)
- [x] Caddy container with bind-mounted Caddyfile (responding on :80)
- [x] Minimal PHP container (jcadima/laravel:8.2) with reverse proxy working
- [x] Added alpine/git container. Runs on demand, invoke with exec and git action (git command is inferred)
- [x] Create test repo: git@github.com:nikalas/static-site.git
- [x] Git-based site deployment via dproxy API (alpine/git container, git command inferred)

## In Progress

- [ ] Basic Docker orchestration Artisan commands
    - [ ] DockerProxyService.php - wraps dproxy HTTP calls
    - [ ] nest:container create <image> <cmd> - create ephemeral container
    - [ ] nest:container start <id> - start container
    - [ ] nest:container logs <id> - get container logs
- [ ] Caddy API integration for reverse proxy

## Implementation Plan

### Phase 1: Docker Orchestration via dproxy (Artisan Commands)

Build a service to interact with dproxy API for container management:

**Services:**
- `app/Services/DockerProxyService.php` - wraps dproxy HTTP calls

**Commands:**
- `php artisan nest:container create <image> <command>` - Create ephemeral container
- `php artisan nest:container start <id>` - Start container  
- `php artisan nest:container logs <id>` - Get container logs

**dproxy API pattern:**
1. `POST /containers/create` with `"AutoRemove": true` in HostConfig
2. `POST /containers/{id}/start`
3. Poll status or get logs

### Phase 2: Git Deployment Command

**Command:** `php artisan nest:deploy <repo> <site-name>`

**Flow:**
1. Create named volume for site: `<site-name>_site`
2. Create+start alpine/git container with:
   - Mount volume to `/site`
   - Clone/pull repo
   - AutoRemove: true
3. Return success/failure

### Phase 3: Caddy API Integration

**Command:** `php artisan nest:route add <site-name> <target>`

**Implementation:**
- Caddy API on `localhost:2019` (from caddy container network)
- POST to `/config/apps/http/servers/srv0/routes` to add dynamic route

### Phase 4: Combined Deploy

**Command:** `php artisan nest:site create <repo> <site-name> --type=static|dynamic`

- Runs Phase 2 (git deploy)
- Runs Phase 3 (add Caddy route)
- Stores site config in database

---

## Verified

| Date | Method | Result |
|------|--------|--------|
| 2026-04-10 | `docker compose up -d && curl` | All endpoints responding (hi, static, app *.localhost) |
| 2026-04-10 | dproxy API /containers/create + /start | Git pull via alpine/git container working |

## Finally

Tear down prototype completely and start from scratch.
