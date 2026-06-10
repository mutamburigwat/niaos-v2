# NiaOS v2

Standalone SaaS CRM and business management platform built with **Laravel 12**, **Filament 4**, and **PostgreSQL**.

**Akudzwe Digital Partners** is a workspace/tenant inside the NiaOS platform, operating under a free internal workspace. All other clients pay for their own workspaces.

## Requirements

- PHP 8.3+
- Composer 2.x
- PostgreSQL 16+
- Node.js 20+ & npm (for frontend assets)

## Quick Start

```bash
# Install PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Configure .env — set DB_* to your PostgreSQL credentials
# Set GROQ_API_KEY for AI features
# Set R2_* for Cloudflare R2 file storage

# Database
php artisan migrate

# Seed roles & permissions
php artisan db:seed --class=RolesAndPermissionsSeeder

# Bootstrap platform admin (use if login fails after migrations)
php artisan niaos:ensure-platform-admin

# Storage link (for local file access)
php artisan storage:link

# Development server
php artisan serve
```

## Architecture

| Module | Resource | Description |
|--------|----------|-------------|
| Auth | Filament Auth | Login, password reset, workspace-scoped sessions |
| Workspaces | `WorkspaceResource` | Multi-tenant client workspaces with member management |
| Users & Roles | Spatie Permissions | 8 RBAC roles per workspace, team-based permissions |
| Customers | `CustomerResource` | Contact management with activities timeline |
| Leads | `LeadResource` | Pipeline stages: new → contacted → qualified → proposal → won/lost |
| Tasks | `TaskResource` | Assignable tasks with priority, status, due dates |
| Quotations | `QuotationResource` | Line-item quotes with auto-calculated totals |
| Files | `FileRecordResource` | Cloudflare R2 file attachments |
| Activity Logs | `ActivityLogResource` | Read-only audit trail for all entities |
| AI | Jose Assistant | Groq-powered AI assistant (placeholder) |

## SaaS Tenancy

NiaOS is a standalone SaaS platform. Each client operates in their own workspace.

- **Workspace types**: `internal` (free workspace for the platform owner's own business), `paid_client` (paying clients), `demo` (evaluations), `partner` (implementation partners).
- **Billing statuses**: `free` (no cost), `trial`, `active` (paid), `overdue`, `suspended`, `cancelled`.
- **Plans**: `free_internal` (internal workspace plan), `starter`, `growth`, `business`, `custom`.
- Internal workspaces (`workspace_type: internal`, `plan: free_internal`) can never be suspended for billing.
- Platform admins set workspace type, plan, and billing status via the Workspace form.

Akudzwe Digital Partners is a workspace/tenant inside NiaOS with workspace type `internal`, billing `free`, and plan `free_internal`. Platform admin privileges (like `is_platform_admin`) are attached to individual users, not to any specific workspace.

## Key Design Decisions

- **UUID primary keys** via `HasUuids` trait for distributed/offline compatibility
- **Global workspace scoping** via `BelongsToWorkspace` trait — all business models automatically scoped to active workspace
- **Spatie Permissions with teams** — `workspace_id` as `team_foreign_key` for per-workspace role assignments
- **Custom activity log table** — not the Spatie default — to keep `workspace_id` as a first-class foreign key
- **Manual Filament navigation** via `NavigationBuilder` for precise menu control

## File Storage

Storage driver auto-selects based on `FILESYSTEM_DISK` env:
- `local` — `storage/app/public` (development)
- `r2` — Cloudflare R2 (production)

## Roles

| Role | Scope |
|------|-------|
| Owner | Full access, billing, workspace deletion |
| Admin | Full access except billing/deletion |
| Manager | CRM + tasks + reports |
| Sales | Customers, leads, tasks, quotations |
| Support | Customers, tasks |
| Accounts | Quotations, tasks |
| Procurement | Tasks |
| Viewer | Read-only on all entities |

## Deployment Safety

- **Never** run `migrate:fresh` or `migrate:refresh` on the main (`niaos`) database — it destroys all tenant data.
- **Never** run `php artisan test` against the main (`niaos`) database — use `.env.testing` (database `niaos_test`) instead.
- **Never** overwrite `.env` — copy `.env.example` to `.env` only during initial setup.
- **Always** run `php artisan filament:assets` after Composer install/update, dependency changes, or when Filament CSS/assets go missing.

### Safe deploy

```bash
chmod +x deploy-refresh.sh
./deploy-refresh.sh
```

## License

Proprietary — NiaOS
