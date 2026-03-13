# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MedCardSymfony is a Symfony 8.0 REST API for managing medical records. It uses JWT authentication and follows a modular domain-driven design. The primary identifier for users is `phone` (E.164 format), not email.

## Common Commands

```bash
# Install dependencies
composer install

# Run all tests
bin/phpunit

# Run a single test file
bin/phpunit tests/User/UserServiceTest.php

# Run a single test method
bin/phpunit --filter testRegisterUser tests/User/UserServiceTest.php

# Database
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
bin/console cache:clear

# Docker (primary dev environment)
docker compose up --wait
docker compose exec php bin/phpunit
docker compose exec php bin/console doctrine:fixtures:load
```

## Architecture

### Module Structure

Each domain lives in its own directory under `src/` and follows this layout:

```
src/{Domain}/
├── {Domain}Entity.php       # Doctrine entity (attribute-based mapping)
├── {Domain}Service.php      # Business logic
├── {Domain}Controller.php   # HTTP handlers (JSON responses)
├── {Domain}Repository.php   # Data access (extends ServiceEntityRepository)
├── {Domain}EventListener.php # Exception → HTTP response conversion
├── DTO/                     # Readonly DTOs with Symfony Validator attributes
└── Exception/               # Domain-specific exceptions extending DomainException
```

Current domains: `User` (implemented), `Patient`, `Doctor`, `Appointment`, `Episode`, `Referral` (stubs).

### Shared Infrastructure

- `src/Shared/EventListener.php` — catches `ValidationException` → 422
- `src/Shared/Exception/DomainException.php` — base for all domain exceptions
- `src/Shared/Exception/ValidationException.php` — wraps Symfony constraint violations

### Exception Handling Pattern

Domain exceptions are caught by `#[AsEventListener]` listeners on `KernelEvents::EXCEPTION` and converted to JSON responses. Each domain module has its own listener for domain-specific errors. HTTP status mapping:
- 401 — wrong credentials
- 404 — not found
- 409 — conflict (e.g., duplicate phone)
- 422 — validation failure

### Authentication

- JWT via LexikJWTAuthenticationBundle (TTL: 3600s)
- Stateless API firewalls; no sessions
- `POST /api/user/login` returns `{user, token}`
- All `/api/*` routes except `/login` and `/register` require `Authorization: Bearer <token>`

### Validation

DTOs use Symfony Validator attributes. Validation is performed in the service layer. Example constraints on `RegisterUserDTO`:
- `phone`: E.164 format (`+[1-9]\d{9,14}`)
- `password`: 8–16 chars, must include uppercase, digit, and special character

### Testing

- PHPUnit 13 with DAMA Doctrine Test Bundle (wraps tests in transactions — no manual DB cleanup needed)
- Fixtures loaded via `UserFixture`: known user `+79991234567` / `secret123L@`, plus 5 random Faker users
- Test env uses `_test`-suffixed database (configured in `.env.test`)
- `phpunit.dist.xml` is the config file; fails on deprecations/notices/warnings

### Repository Pattern

Repositories extend `ServiceEntityRepository`. Manual persist/flush via `saveOne($entity, $flush = true)` and `deleteOne($entity, $flush = true)`. Batch operations can defer flush by passing `false`.
