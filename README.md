#  Laravel Nest (working name)

Laravel Nest is a free, self contained tool for provisioning webapps and sites
locally.  It is essentially a lightweight, self hosted service providing many of
the features of Laravel forge, built with Laravel itself and distributed in
container form.

The main reason for creating this is that Forge relies on a cloud subscription
service, making it a less accessible choice for self hosting and managing
throwaway, dev- and staging sites in a homelab or workstation environment.

For now this is a completely private, single dev project built and used solely
for me, focusing on delivering a POC and later MPV, before _maybe_  opening
this up as an open OSS project.

## Status

The project is currently in the planning phase, deciding on feature set before
building a minimal POC. Actual implementation decisions will be made along the
way. We will start out with an absolute minimal prototype, then start over from
scratch, planning and implementing features one by one as we go.

## Workflow

We will adhere to a strict and truly agile workflow, with incremental and
iterative development.

## Features

These are the feature (in no particular order), based on Forge, we will
implement for our MVP.  For each feature we implement, concessions have to be
made in respect of supporting this full set. We will however _not_ base any
implementation details on how Forge do things. This will be a clean-room
re-implementation of selected features from scratch.

 - PHP environment management: Installs and manages PHP, web servers (Caddy/Nginx), and system packages so you don’t have to deal with OS-level setup.
 - Site deployment: Connect a Git repo and deploy automatically by polling (for now) or manually with one click. (only GitHub in first iteration)
 - Zero-downtime deployments: Deploy updates without taking your app offline using atomic release strategies. (green/blue folder swap strategy)
 - SSL management: Issue and auto-renew free TLS certificates (via Let’s Encrypt) with a single action.
 - Database provisioning: Create and manage MySQL/PostgreSQL databases directly from the UI. We will use shared database containers, but create isolated users/databases for each site. 
 - Environment configuration: Manage .env variables securely per site without SSH access.
 - Site isolation: Each site is sandboxed at the server level to avoid cross-project interference.
 - Quick site creation: Spin up new sites with predefined configs (PHP versions, domains, etc.).
 - Redirects and domain management: Configure domains, subdomains, and HTTP redirects easily.

## Future expansion

These are existing Forge features we might look to implement at a later stage.
No concessions should be made for these features at the moment. We will apply
an iterative refactoring workflow meaning we focus on the implementation
requirements only when we reach that stage.

 - Support different kinds of web stacks such as Node servers, static sites etc.
 - Allow for different git providers such as BitBucket and so on.
 - Allow selection of web server/reverse proxy such as Caddy/Nginx/Apache.
 - Team collaboration: Share access with team members and control permissions.
 - On-demand commands: Execute common server tasks (restart services, clear caches) via UI.
 - Custom scripts: Save and run reusable scripts across servers.
 - Queue and worker management: Configure and monitor background workers for jobs without manual supervisor setup.
 - Scheduler integration: Run Laravel’s task scheduler via a simple toggle instead of managing cron jobs manually.
 - User and SSH key management: Control server access by adding/removing users and SSH keys centrally.
 - Daemon management: Run and monitor long-running processes (e.g. queue workers, custom scripts).
 - Load balancer support: Distribute traffic across multiple servers for scaling applications.
 - Monitoring & notifications: Get alerts for server health, deployment failures, and downtime.
 - Backup integrations: Configure automated database backups to external storage providers.
 - API access: Automate Forge actions programmatically via its API.
 - Server metrics (basic): View CPU, memory, and disk usage without logging into the machine.
 - Multi-provider support: Manage servers across multiple cloud providers from one interface.

## Out-of-scope

These are existing Forge features (or not) we have decided _not_ to implement.

 - VPC / network support (where applicable): Launch servers within private networks for isolation.
 - Multi-tenant or org-level support. Each installation will be for a single user/team/organisation.

## Component, implementation and stack decisions currently locked in

At its core, the project is essentially a docker orchestrator, allowing for quick deployment and orchestration of webapps.

- The project will be built in Laravel
- The project will be shipped with/as a docker container
- The project will be given access to the docker daemon and deploy each site as a container.
- Individual sites will not have access to the docker daemon.
- We will bundle a separate Caddy container for supporting all features it provides, such as reverse proxy, SSL management and web server. All external (from outside docker) access will go through caddy.
- use docker network for connecting containers
- We will implement the Loopia domain management api for dynamic and automatic management of domain  records. This will later be expanded into a plugin system allowing for different domain providers.
- We will use blade templates for web views. No react, svelte or vue at this stage

## Status

See [STATUS.md](./STATUS.md)
