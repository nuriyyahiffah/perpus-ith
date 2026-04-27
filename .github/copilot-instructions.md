# Copilot / Agent Instructions — perpus-ith

Short, actionable guidance for AI coding agents working in this repository.

- Purpose: This is a Laravel-based library management app (MVC). Focus code changes around `routes/web.php`, `app/Http/Controllers/*`, `app/Models/*`, and Blade views under `resources/views`.

- Big picture:
  - The app is organized by user roles. Route groups (see `routes/web.php`) separate: public, `auth` protected, and role prefixes: `kelola` (shared admin+pustakawan), `admin`, `pustakawan`, `mahasiswa`, `dosen`.
  - Role-based access is enforced by `app/Http/Middleware/CheckRole.php` (uses role strings: `admin`, `pustakawan`, `dosen`, `mahasiswa`).
  - Controllers are namespaced by feature: `App\\Http\\Controllers\\Admin`, `Mahasiswa`, `Dosen`, etc. Views follow similar folders (e.g., `resources/views/shared`, `resources/views/admin`).

- Data & models:
  - Eloquent models live in `app/Models`. Look at `User` for fields used across the app (notably `role`, `nomor_identitas`, `kategori_anggota_id`, `prodi`).
  - DB schema is in `database/migrations`. Migrations show fields like `file_pdf` for `buku` and other domain-specific columns.

- Project-specific conventions to follow when editing code:
  - Use Indonesian short messages for user-facing flash text (e.g., `->with('success', '...')`).
  - Validation: controllers commonly call `$request->validate([...], [...])` with custom message arrays.
  - For update uniqueness, use `Rule::unique(...)->ignore($id)` (see `Admin\\DosenController::update`).
  - Default password behavior: when creating users, password is often `Hash::make($request->nomor_identitas)`.
  - Controllers return Blade views with `view('shared.dosen.index', compact(...))` — prefer `compact` over passing large associative arrays.

- Integrations to be aware of:
  - WhatsApp notifications: `App\\Traits\\WhatsappTrait` which posts to `https://api.fonnte.com/send`. Uses env `FONNTE_TOKEN`.
  - Excel import/export: classes in `app/Imports` and `app/Exports` (Maatwebsite/Excel present in composer). Look at `MahasiswaExport.php` and `UsersImport.php` for patterns.
  - File storage: PDFs and uploaded files use `storage` / `public` (run `php artisan storage:link` if serving assets).

- Developer workflows (commands most devs will run):
  - Install deps: `composer install` and `npm install`.
  - Setup env: copy `.env.example` → `.env`, set DB credentials, then `php artisan key:generate`.
  - Migrate & seed: `php artisan migrate --seed`.
  - Build assets: `npm run dev` (or `npm run build`).
  - Serve locally: on Windows/Laragon use Laragon virtualhost or `php artisan serve`.
  - Link storage: `php artisan storage:link`.
  - Run tests: `php artisan test` or `vendor/bin/phpunit` (project has `phpunit.xml`).

- Debugging & discovery tips:
  - Inspect active routes: `php artisan route:list` to find controllers & middleware.
  - Use `php artisan tinker` to inspect models, or `storage/logs/laravel.log` for runtime issues.
  - Search for role checks and permission redirects in `app/Http/Middleware/CheckRole.php`.

- Examples to reference when editing or adding features:
  - Role-based routes: see `routes/web.php` (many route groups and prefixes).
  - Middleware implementation: `app/Http/Middleware/CheckRole.php`.
  - WhatsApp sending: `app/Traits/WhatsappTrait.php`.
  - Controller pattern (store/update/destroy): `app/Http/Controllers/Admin/DosenController.php`.

- What *not* to change lightly:
  - Role strings used in middleware and database (`role` column) — keep exact names.
  - Route names & prefixes — many views and redirects depend on them.

If anything in this brief is unclear or you'd like more examples (specific controllers, migrations, or Blade paths), tell me which area to expand. I'll iterate quickly.
