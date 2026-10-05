# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Laravel 11 (PHP 8.3) backend for scheduling appointments between public servants (`Servidor`) and experts (`Perito`). Domain naming and comments are in Portuguese. The README is the stock Laravel one and has no project-specific info.

## Environment

The app runs in Docker (`docker-compose.yml`): `app` (php-fpm, built from `Dockerfile`), `web` (nginx on host port **8000**, config in `nginx/conf.d/app.conf`), and `redis`. PostgreSQL is **not** in compose — it runs on the host and the container reaches it via `DB_HOST=host.docker.internal`. Run artisan/composer inside the `app` container (`laravel_app`, user `laravel`, workdir `/var/www`):

```bash
docker compose up -d --build
docker compose exec app php artisan migrate
docker compose exec app composer install
```

`.env` uses `pgsql`; session, cache, and queue drivers are all `database` (Redis is provisioned but not yet used).

## Commands

```bash
php artisan test                                  # all tests (PHPUnit 11)
php artisan test --filter=ExampleTest             # single test class/method
php artisan test tests/Feature/ExampleTest.php    # single file
vendor/bin/pint                                   # code style (Laravel Pint)
php artisan migrate:fresh                         # rebuild schema
npm run dev / npm run build                       # Vite + Tailwind assets
composer dev                                      # serve + queue + pail + vite (non-Docker)
```

Note: the sqlite in-memory lines in `phpunit.xml` are commented out, so tests that hit the DB use the `.env` Postgres connection.

## Architecture

Layering for each resource: `Controller` → `FormRequest` (validation) → `Service` (`app/Services`, Eloquent writes/reads) → `JsonResource` (response shape). `ServidorController` / `StoreServidorRequest` / `ServidorService` / `ServidorResource` is the reference implementation to copy for other entities.

Data model (see `database/migrations/2026_09_23_*`):
- `servidors` — nome, cpf, email
- `peritos` — nome, especialidade, ativo
- `horario_disponivels` — a perito's available slot: `perito_id`, `data`, `hora_inicio`/`hora_fim` (4-char strings, e.g. `0900`), `status`
- `agendamentos` — booking linking `servidor_id`, `perito_id`, `disponibilidade_id` (→ `horario_disponivels`), with `status` and a unique `id_empotency_key` to prevent duplicate bookings

Table names follow Laravel's English pluralization (`servidors`, not `servidores`); `HorarioDisponivel` maps to `horario_disponivels`.

## Current state (work in progress)

- Only `routes/web.php` exists (welcome view); no API routes are registered yet, so `ServidorController` is not reachable. Adding API routes requires `php artisan install:api` (creates `routes/api.php` and registers it in `bootstrap/app.php`).
- Models other than `User` are empty — no `$fillable`, so `Servidor::create()` will throw `MassAssignmentException` until fillable fields are added; no relationships defined.
- Known bugs in the Servidor slice: `$req->valideted()` (should be `validated()`), `'requried'` rule in `StoreServidorRequest`, `store()` returns nothing, `servidors.email` column is `length:11`, and `ServidorService::update` shadows `$servidor`.
- FK columns are `unsignedInteger` while PKs from `id()` are bigint.
