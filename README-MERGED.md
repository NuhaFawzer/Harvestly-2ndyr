# Harvestly — Integrated Interim Build

This folder is the merged Harvestly project prepared from the team's `main` project and Nuha's Admin/Auth/Landing project.

## What is authoritative in this merge

- **Main landing page:** Nuha version (`index.php`)
- **Login / role selection / registrations / forgot-reset password:** Nuha common Auth module (`auth/`)
- **Admin:** Nuha Admin module (`admin/`)
- **Buyer:** Team Buyer UI under `View/Buyer/`, connected through the integrated MySQLi controllers in `Controller/Buyer/`
- **Farmer:** Team Farmer UI under `View/Farmer/`, connected to the common session and Harvestly MySQLi database
- **Courier Partner:** Team Courier UI under `View/Delivey/` (the original `Delivey` spelling is intentionally retained for compatibility)

The role flow is:

`Landing -> Common Login -> role check -> Admin / Buyer / Farmer / Courier Partner`

## Requirements

- XAMPP Apache + MySQL
- PHP 8.x
- MySQL / MariaDB
- Core PHP, MySQLi, HTML, CSS and Vanilla JavaScript

The active integrated code does not require a PHP framework. The Courier dashboard chart/icon dependency was converted to local Vanilla JS/CSS so the merged UI does not require those external libraries.

## Setup on Windows / XAMPP

1. Extract/copy this folder as:
   `C:\xampp\htdocs\Harvestly`
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin.
4. Import:
   - `database/harvestly_database_with_distances.sql`
   - then `database/seed_harvestly_data.sql`
5. Open:
   `http://localhost/Harvestly/`

When running this workspace directly from `C:\xampp\htdocs\Harvestly-merged\Harvestly`, use:
`http://localhost/Harvestly-merged/Harvestly/`
Do not use a separate `C:\xampp\htdocs\Harvestly` copy, because that folder contains the retired PDO buyer application and an incompatible database migration.

For a fresh database, the two files above are the main required SQL files. The `database/migrations/` folder is retained for existing/older databases and later updates.

## Demo accounts from the seed file

The seeded demo users use the password:

`admin123`

Examples:

- Admin: `admin@harvestly.lk`
- Buyer: `buyer@gmail.com`
- Farmer: `farmer@harvestly.lk`
- Courier Partner: `courier@lankaagro.lk`

The Farmer and Courier accounts above are active demo accounts. Pending accounts are also included in the seed data for Admin approval demonstrations.

## Important integration notes

### Common authentication
`auth/controllers/AuthController.php` redirects successful logins to:

- Admin -> `index.php?page=admin_overview`
- Buyer -> `Controller/Buyer/DashboardController.php`
- Farmer -> `index.php?page=farmer_dashboard`
- Courier Partner -> `View/Delivey/index.php?page=dashboard`

### Database

`config/database.php` is the authoritative connection configuration. It uses **MySQLi**.

Default local settings:

- host: `127.0.0.1`
- user: `root`
- password: empty
- database: `harvestly`
- port: `3306`

Change this file only if your local MySQL settings are different.

### Buyer integration

The original Buyer frontend was preserved. Its active controllers were adapted to the Harvestly schema and common login/session using MySQLi. The original Buyer model source files remain in `Model/Buyer/` for team-history/reference, but the merged active Buyer flow uses `Controller/Buyer/_bridge.php` for the interim integration.

### Farmer integration

Missing shared Farmer helper files were supplied under `View/Farmer/includes/` so the team's Farmer pages use the common session and Harvestly database without moving the original frontend.

### Courier Partner integration

The original team folder is spelled `Delivey`. It was not renamed because existing relative links depend on that location. Rename/refactor it only after the interim demo, when all references can be updated together.

### Payment

The Checkout page is kept and shows **PayHere Sandbox** as the intended payment method. The merged interim build does **not** complete the PayHere server-side payment transaction yet; the checkout controller returns a clear message instead of creating a fake real payment.

## Scope preserved during merge

- All 25 districts
- Farmer -> Buyer marketplace
- Courier Partner organization model
- District-based delivery model
- No GPS/live map requirement
- No OTP delivery confirmation
- Buyer `Confirm Received` flow is preserved in Buyer order tracking
- General complaint workflow
- No driver/vehicle/fleet management module
- MySQLi active database access

## Validation performed on this package

- PHP syntax lint completed for all PHP files
- JavaScript syntax check completed for all JavaScript files
- Literal PHP `require/include` paths checked for missing files
- Root/local Buyer asset references checked after merge

A live MySQL/XAMPP browser test still needs to be done on the target computer because this packaging environment does not run your XAMPP database.

## Before merging to GitHub `main`

Test this package locally first. Keep your existing branches (`nuha-admin`, `backup-nuha-admin`, and team `main`) unchanged until the integrated build has been demonstrated successfully. Then commit the integrated project on an integration branch and merge through a Pull Request.
