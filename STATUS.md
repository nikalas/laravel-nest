# STATUS

## Prototype Goal

Confirm base platform requirements and workability: Docker orchestration, Caddy reverse proxy/SSL, PHP environment, Git-based deployment, and basic CLI tooling via Artisan commands.

## Stage

**Prototype (POC)** - Familiarization and validation of core platform capabilities.

## Completed

- [x] Docker socket access via docker-socket-proxy (dproxy container running on :2375)
- [x] Caddy container with bind-mounted Caddyfile (responding on :80)
- [x] Minimal PHP container (jcadima/laravel:8.2) with reverse proxy working

## In Progress

- [ ] Git-based site deployment with shared volume
    - [ ] Test repo: git@github.com:nikalas/static-site.git
    - [ ] Manually pull repo with git_site container
    - [ ] If pull fails, clone the repo with git_site container
    - [ ] Configure git_site container to clone repo on startup
    - [ ] Verify content appears in shared volume
    - [ ] Confirm Caddy serves static files at http://static.localhost
- [ ] Basic Docker orchestration Artisan commands
- [ ] Caddy API integration for reverse proxy

## Finally

Tear down prototype completely and start from scratch.

## Verified

| Date | Method | Result |
|------|--------|--------|
| 2026-04-10 | `docker compose up -d && curl` | All endpoints responding (hi, static, app *.localhost) |
