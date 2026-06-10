# NiaOS v2

Production-grade CRM and business management platform built with **Laravel 12**, **Filament 4**, and **PostgreSQL**.

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

# Storage link (for local file access)
php artisan storage:link

# Development server
php artisan serve
```

## Architecture

| Module | Resource | Description |
|--------|----------|-------------|
| Auth | Filament Auth | Login, password reset, workspace-scoped sessions |
| Workspaces | `WorkspaceResource` | Multi-tenant orgs with member management |
| Users & Roles | Spatie Permissions | 8 RBAC roles per workspace, team-based permissions |
| Customers | `CustomerResource` | Contact management with activities timeline |
| Leads | `LeadResource` | Pipeline stages: new → contacted → qualified → proposal → won/lost |
| Tasks | `TaskResource` | Assignable tasks with priority, status, due dates |
| Quotations | `QuotationResource` | Line-item quotes with auto-calculated totals |
| Files | `FileRecordResource` | Cloudflare R2 file attachments |
| Activity Logs | `ActivityLogResource` | Read-only audit trail for all entities |
| AI | Jose Assistant | Groq-powered AI assistant (placeholder) |

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

## License

Proprietary — NiaOS
