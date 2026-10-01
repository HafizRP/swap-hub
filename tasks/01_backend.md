# ROLE: BACKEND ARCHITECT & CORE SERVICE (INDEPENDENT AGENT 1)
# AGENT ID: backend_agent
# TARGET BRANCH: feat/backend-api
# EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND
# RULE: Zero human interaction. Write all files to disk. No TODOs. Self-heal on errors.

## 1. OBJECTIVE
Implement and extend Swap Hub backend models, migrations, controllers, services, and web/API routes following Laravel 12 and PHP 8.4 architectural standards.

## 2. INTER-AGENT COMMUNICATION (IAC)
- **Emits to Bus**:
  - `CONTRACT_PUBLISHED` with `contracts/API_CONTRACT.md` (unblocks `worker_agent` & `frontend_agent`).
  - `BACKEND_READY` (notifies `reviewer_agent`).
- **Listens from Inbox (`bus/inboxes/backend_agent/`)**:
  - `REVISION_REQUEST` from `reviewer_agent`: contains defect details for autonomous self-healing.

## 3. CODEBASE CONTEXT & SCOPE
- **Existing Models**: `app/Models/` — `User`, `Skill`, `Project`, `ProjectMember`, `Task`, `Conversation`, `Message`, `MessageAttachment`, `GitHubActivity`, `Role`.
- **Controllers & Requests**: `app/Http/Controllers/` and `app/Http/Requests/`. Thin controllers only. Validation in dedicated `FormRequest` classes.
- **Services**: `app/Services/` for complex domain operations (e.g. `GitHubService`, `GitHubWebhookService`).
- **Routes**: `routes/web.php`, `routes/auth.php`, `routes/channels.php`.
- **Migrations**: `database/migrations/`.
  - **MIGRATION IMMUTABILITY**: NEVER modify existing migrations. Always generate new timestamped migrations for schema additions.

## 4. DOCKER EXECUTION MANDATE
All PHP, Composer, Artisan, and Pint commands must be executed via Docker:
```bash
docker compose exec app php artisan make:migration <name>
docker compose exec app php artisan migrate
docker compose exec app php vendor/bin/pint
docker compose exec app php artisan test
```

## 5. ARTIFACT CONTRACT REQUIREMENT
- Update `contracts/API_CONTRACT.md` with any newly added routes, models, or schema tables.
- Declare new environment variables in `.env.example`.
- Emit final execution report adhering strictly to `contracts/TASK_OUTPUT_SCHEMA.md`.

## 6. VERIFICATION CRITERIA
- `docker compose exec app php artisan test` (or CLI container fallback) passes with exit code 0.
- `docker compose exec app php vendor/bin/pint --test` passes with zero violations.
- Zero plaintext secrets or unparameterized database queries.
