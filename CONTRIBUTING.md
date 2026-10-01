# Contributing to Swap Hub

Thank you for contributing to Swap Hub! Please follow these guidelines to keep our codebase clean, reliable, and secure.

---

## Development Workflow

1. **Branching Strategy**
   - Branch from `main`.
   - Use descriptive branch prefixes:
     - `feat/...` for new features.
     - `fix/...` for bug fixes.
     - `ci/...` for CI/CD changes.
     - `docs/...` for documentation updates.

2. **Code Style & Quality Gates**
   - Run Laravel Pint before committing:
     ```bash
     composer lint
     composer format
     ```
   - All tests must pass locally and in GitHub Actions CI:
     ```bash
     php artisan test
     ```

3. **Pull Request Protocol**
   - Do NOT push directly to `main`.
   - Open a pull request targeting `main`.
   - Provide a clear summary of changes and verification steps.
   - Ensure all CI/CD pipeline checks are green before merging.
