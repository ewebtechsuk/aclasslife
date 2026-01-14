# Aclasslife Phase 1 migration runbook

## Safety prerequisites
- **Do not delete WordPress assets until backups are verified.**
- Keep a rollback path that restores WordPress within 15 minutes.
- Use blue/green deployment: new stack on :8080 before switching :443.

## A) Audit & Backup
1) **Detect web server and WordPress root**
   - `systemctl status nginx` or `systemctl status apache2`
   - Confirm the WordPress root (expected `/var/www/aclasslife`).
2) **Back up WordPress files**
   - `mkdir -p /root/backups/aclasslife/$(date +%Y%m%d)`
   - `tar -czf /root/backups/aclasslife/$(date +%Y%m%d)/wp-files.tar.gz /var/www/aclasslife`
   - `sha256sum /root/backups/aclasslife/$(date +%Y%m%d)/wp-files.tar.gz > /root/backups/aclasslife/$(date +%Y%m%d)/wp-files.sha256`
3) **Back up database**
   - Extract DB credentials from `/var/www/aclasslife/wp-config.php`.
   - `mysqldump -u <db_user> -p <db_name> > /root/backups/aclasslife/$(date +%Y%m%d)/wp-db.sql`
   - `sha256sum /root/backups/aclasslife/$(date +%Y%m%d)/wp-db.sql > /root/backups/aclasslife/$(date +%Y%m%d)/wp-db.sha256`
4) **Export web server configs**
   - `cp -a /etc/nginx /root/backups/aclasslife/$(date +%Y%m%d)/nginx`
   - or `cp -a /etc/apache2 /root/backups/aclasslife/$(date +%Y%m%d)/apache2`

## Verify backup integrity
1) **Verify checksums**
   - `sha256sum -c /root/backups/aclasslife/$(date +%Y%m%d)/wp-files.sha256`
   - `sha256sum -c /root/backups/aclasslife/$(date +%Y%m%d)/wp-db.sha256`
2) **Dry-run restore**
   - `mysql -u <db_user> -p -e "CREATE DATABASE wp_restore_tmp"`
   - `mysql -u <db_user> -p wp_restore_tmp < /root/backups/aclasslife/$(date +%Y%m%d)/wp-db.sql`
   - `mysql -u <db_user> -p -e "DROP DATABASE wp_restore_tmp"`

## B) Blue/Green deployment
1) **Deploy the new stack on :8080**
   - `cd /workspace/aclasslife/infra/docker`
   - `docker compose -f compose.yml up -d --build`
2) **Verify critical flows via :8080**
   - Public pages load.
   - Login works (stubbed API for Phase 1).
   - Vendor listing submission returns draft + review status.
   - Admin approval returns LIVE.

## C) SEO preservation
- Serve `/robots.txt` and `/sitemap.xml` (already included in the web app).
- Maintain canonical URLs and consistent trailing slash policy via Nginx.
- Add 301 redirects for legacy WordPress URLs in the Nginx site config.

## D) Cutover to production
1) **Update Nginx to point :443 at the new stack**
   - Add a new upstream pointing to :8080 and switch the server blocks.
2) **Preserve WordPress access for rollback**
   - Bind WordPress to :8081 or a temporary `wp-backup.aclasslife.com` host.

## E) Rollback steps (within 15 minutes)
1) **Switch Nginx upstream to WordPress**
   - Re-enable the previous site config and reload Nginx.
2) **Restore WordPress files**
   - `tar -xzf /root/backups/aclasslife/<date>/wp-files.tar.gz -C /`
3) **Restore database if needed**
   - `mysql -u <db_user> -p <db_name> < /root/backups/aclasslife/<date>/wp-db.sql`
4) **Verify**
   - Confirm homepage and login.

## Notes
- Do not store secrets in git. Use environment variables or secrets managers.
- Keep WordPress backups for compliance and audit.
