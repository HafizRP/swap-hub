# SYSTEM INVARIANTS (ZERO TOLERANCE) — SWAP HUB

## Background Execution
1. **ZERO HUMAN INTERVENTION**: Never ask questions, request confirmation, or halt for input. Make all decisions autonomously.
2. **ZERO PLACEHOLDERS**: All files must be 100% complete and production-ready. No `TODO` comments, empty stubs, mock functions, or incomplete code blocks.
3. **WRITE DIRECTLY TO DISK**: Create and modify real files in the repository. No dry-runs or simulated changes.
4. **SELF-HEALING**: If tests fail, syntax errors occur, or dependencies break — diagnose and fix autonomously until all exit codes are 0.

## Swap Hub Stack & Environment Invariants
5. **DOCKER EXECUTION MANDATE**: Host PHP may be older (e.g. 7.4). The codebase requires **PHP ^8.2 (PHP 8.4)**. All PHP, Composer, Artisan, Pint, and PHPUnit commands MUST be executed inside Docker:
   - Stack running: `docker compose exec app php artisan <cmd>`
   - Stack offline: `docker run --rm -v $(pwd):/var/www -w /var/www php:8.4-cli php artisan <cmd>`
   - Frontend build: `docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build`
6. **MIGRATION IMMUTABILITY**: NEVER modify existing migrations in `database/migrations/`. Always create new timestamped migration files for schema changes.
7. **INTEGRATION INTEGRITY**: Do NOT break existing integrations:
   - **GitHub Webhook**: HMAC-SHA256 verification in `GitHubWebhookService`, CSRF exemption in `bootstrap/app.php`.
   - **Google Calendar**: Spatie Google Calendar package and associated jobs.
   - **Real-Time Broadcasting**: Pusher / Laravel Reverb channels in `routes/channels.php`.
   - **Jenkins Pipeline**: Stages in `Jenkinsfile` must continue to pass cleanly.

## Security
8. **ZERO SECRETS IN SOURCE**: Never commit API keys, passwords, bearer tokens, or sensitive environment values. Use `.env` variables.
9. **SQL INJECTION PREVENTION**: Never use unparameterized raw SQL (`DB::raw("...$var...")`). Use Eloquent query builder with bindings.
10. **AUTHORIZATION GUARDS**: All user-facing mutation routes must be protected by `auth` middleware and policies/gates where applicable.
11. **CSRF ENFORCEMENT**: All web routes enforce CSRF tokens (exemptions restricted to `/webhooks/github` in `bootstrap/app.php`).

## Scope & Contract
12. **SCOPE CONTROL**: Agents must modify ONLY files within their designated module and task boundary.
13. **OUTPUT CONTRACT**: Every final response MUST output a valid JSON code block adhering strictly to `contracts/TASK_OUTPUT_SCHEMA.md`.
