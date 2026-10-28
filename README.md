# PMITS – Project Management and Issue Tracker

Same app as the old single `index.php`, split into config, core, modules and views.

```
index.php                  front controller (boot -> POST action -> page)
config/
  app.php                  names, statuses/types/priorities/roles, tabs, security limits
  database.php             DB connection (XAMPP defaults)
  mail.php                 SMTP defaults, no secrets
  mail.local.php           your real SMTP login (git-ignored, overrides mail.php)
assets/css/app.css         all styles
views/layout/              head.php, auth.php (logged out), app.php (sidebar + page)
src/
  bootstrap.php            constants, session, loads core + every module
  context.php              per-request state the views read ($p, $t, $proj, $members ...)
  core/                    config, database, helpers, flash, auth, router, icons, migrations
  modules/
    auth/        login, register, forgot/reset password, reset-code email
    projects/    Your Projects page, new/join/leave, team join codes
    dashboard/   Dashboard tab
    board/       Kanban tab + move card
    items/       Backlog & Issues tab, edit screen, save/assign/delete
    sprints/     Sprints tab + create/start/complete
    releases/    Releases tab + create
    team/        Team tab, roles, add/remove members, new code
    activity/    logact() + Activity tab
```

## Config
Read anywhere with `config('mail.host')`, `config('app.security.join_max_fails', 5)`.
`config/<name>.local.php` overrides `config/<name>.php`. Environment variables
(`PMITS_DB_*`, `PMITS_SMTP_*`) work too when there is no local file value.
Set `debug` and `local_ssl_fix` to `false` in `config/mail.local.php` for production.

## Adding a module
Create `src/modules/<name>/module.php`. It is loaded automatically and registers itself:

```php
route_action('new_thing', 'things_create', 'project', true);   // POST a=new_thing, managers only
route_view('tab:things', __DIR__ . '/views/things.php');       // template for ?t=things
```
Action scopes: `public`, `user`, `project` (handler receives `$pid, $isManager`).
Handlers return an error string to show on the page, or redirect with `go()`.
For a new project tab also add it to `tabs` and `captions` in `config/app.php`.

## Needs
PHP 7.4+, MySQL with the existing `pmtracker` tables, PHPMailer (`vendor/` or `PHPMailer/src/`).
Optional `logo.png` next to `index.php`.
