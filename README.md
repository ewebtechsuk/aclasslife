# Aclasslife Phase 1 platform

This monorepo contains the Phase 1 marketplace, portal and CRM stack to replace the public WordPress instance.

## Structure
- `apps/web` – Public marketplace site (Next.js + Tailwind)
- `apps/portal` – Vendor/Buyer portal (Next.js + Tailwind)
- `apps/admin` – Admin CRM (Next.js + Tailwind)
- `apps/api` – NestJS API
- `packages/types` – Shared types
- `packages/api-client` – Shared API client utilities
- `packages/ui` – Shared UI components
- `infra/docker` – Docker Compose + Nginx config
- `prisma` – Prisma schema + migrations
- `docs/ops-runbook.md` – Migration and rollback runbook

## Local development
```bash
pnpm install
pnpm --filter @aclasslife/api dev
pnpm --filter @aclasslife/web dev
pnpm --filter @aclasslife/portal dev
pnpm --filter @aclasslife/admin dev
```

## Docker Compose (blue/green on :8080)
```bash
cd infra/docker
docker compose -f compose.yml up -d --build
```

## Environment variables
- `DATABASE_URL` for the API (Postgres)
- `NEXT_PUBLIC_API_URL` for the Next.js apps

## Production cutover
1) Deploy the new stack on :8080 and verify all flows.
2) Switch Nginx :443 to proxy to the new stack.
3) Keep WordPress on an internal port or `wp-backup` subdomain for rapid rollback.

Refer to `docs/ops-runbook.md` for detailed steps.
