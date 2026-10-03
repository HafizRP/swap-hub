# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- Academic Skill Matching Engine with Jaccard Similarity Coefficient and requirement coverage scoring (`App\Services\SkillMatchingService`).
- Project Required Skills relation and interactive visual matching widgets in project list, detail, and skill swap barter cards.
- System Usability Scale (SUS) 10-item Likert evaluation survey (`/usability-survey`) with automated Brooke (1996) score calculation.
- Administrative SUS Analytics dashboard (`/admin/usability`) with statistical distributions (mean, std-dev, grade, adjective).
- System benchmark console command (`php artisan app:benchmark`) generating academic performance tables.
- Comprehensive thesis manuscripts: `docs/skripsi/BAB_3_METODOLOGI_DAN_ALGORITMA.md` and `docs/skripsi/BAB_4_HASIL_DAN_PENGUJIAN.md`.
- Full Progressive Web App (PWA) support with web app manifest, offline service worker, cache strategies, and install prompts.
- Offline fallback view and static HTML page (`/offline` and `offline.html`).
- Multi-resolution PWA and Apple touch icons (72x72 to 512x512 maskable).
- Feature test suite for PWA validation (`tests/Feature/PwaTest.php`).
- GitHub Actions CI/CD pipeline covering Pint linting, frontend build, PHPUnit test suites, and Docker multi-stage builds.
- Self-hosted Laravel Reverb real-time WebSocket server integration with Nginx reverse proxy.
- AI & developer documentation standards (`llms.txt`, `llms.md`, `CONTRIBUTING.md`, `SECURITY.md`).
- Project review, health check, and skill swap end-to-end test cases.

### Changed
- Migrated real-time broadcaster from external Pusher to internal Laravel Reverb.
- Dynamic host and port detection for Laravel Echo client.
- Testing environment isolated with in-memory SQLite and null broadcaster.

### Fixed
- Livewire `@livewire` directive key parameter syntax in chat views.
- Permission issues on Docker container `storage/` and `bootstrap/cache/` volumes.
- Google API client dependency download timeouts during Docker builds.
