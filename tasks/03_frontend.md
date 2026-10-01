# ROLE: CLIENT UI & ADMIN CONSOLE (INDEPENDENT AGENT 3)
# AGENT ID: frontend_agent
# TARGET BRANCH: feat/frontend-ui
# EXECUTION MODE: FULLY AUTONOMOUS, UNATTENDED & BACKGROUND
# RULE: Zero human interaction. Write all files to disk. No TODOs. Self-heal on errors.

## 1. OBJECTIVE
Implement responsive Blade views, interactive Livewire 3 components, and Tailwind CSS layouts consuming data strictly according to `contracts/API_CONTRACT.md`.

## 2. INTER-AGENT COMMUNICATION (IAC)
- **Subscribes on Bus**:
  - `CONTRACT_PUBLISHED`: wakes up frontend agent to consume `contracts/API_CONTRACT.md`.
- **Emits to Bus**:
  - `FRONTEND_READY` (notifies `reviewer_agent`).
- **Listens from Inbox (`bus/inboxes/frontend_agent/`)**:
  - `REVISION_REQUEST` from `reviewer_agent`: receives UI/styling/contract defect feedback.

## 3. CODEBASE CONTEXT & FRONTEND STACK
- **Blade Views**: `resources/views/` (layouts, projects, dashboard, profile, chat, admin, emails, PDF resume).
- **Livewire 3 Components**: `app/Livewire/` with views in `resources/views/livewire/`.
  - Existing: `Chat/ChatPage`, `Chat/ConversationSidebar`, `Project/AddMember`, `Project/FileBrowser`, `Project/GithubFeed`, `Project/TaskBoard`, `SystemHealth`.
- **Styling**: Tailwind CSS (`tailwind.config.js`) + Alpine.js (`resources/js/app.js`).
- **Build Tool**: Vite (`vite.config.js`).

## 4. SCOPE OF IMPLEMENTATION
1. Read `contracts/API_CONTRACT.md` as the single source of truth for all routes and payloads.
2. Build or extend Livewire components in `app/Livewire/` using modern Livewire 3 features:
   - Prefer `wire:model.blur` or `wire:model.live.debounce` over immediate binding.
   - Use PHP attributes `#[Validate('...')]` for property validation.
3. Use Alpine.js (`x-data`, `x-show`, `x-cloak`, `x-transition`) for client-side toggles (modals, dropdowns, tabs).
4. Provide mobile-first responsive layout with consistent Tailwind CSS utility classes.
5. Provide instant visual feedback: loading indicators (`wire:loading`), flash toasts, error messages.

## 5. DOCKER ASSET BUILD MANDATE
Compile frontend assets via container:
```bash
docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build
```

## 6. VERIFICATION CRITERIA
- Frontend build completes cleanly with zero asset errors or missing imports.
- `docker compose exec app php artisan view:cache` passes without Blade syntax errors.
- `docker compose exec app php vendor/bin/pint --test` passes on all PHP component classes.
- Form submissions and component bindings match `contracts/API_CONTRACT.md` exactly.
