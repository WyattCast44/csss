# CSSS

CSSS: Common Squadron Software Suite

# Local Installation

### Clone Repo

```bash
git clone https://github.com/wyattcast44/csss csss
```

### Install PHP Deps

```bash
composer
```

### Install FE Deps

```bash
yarn
```

### Copy .env.example 

```bash
cp .env.example .env
```

### Generate Application Key

```bash
php artisan key:generate
```

### Migrate database

```bash
php artisan migrate:fresh --seed
```

### Build dev server

```bash
yarn dev
```

### Start dev processes

Start the queue worker in listen mode: 

```bash
php artisan queue:listen
```

Start the scheduler:

```bash
php artisan schedule:work
```

# Production Deployment Considerations

- Enable OP-Cache

```bash
# clear old optimizations
php artisan filament:optimize-clear
php artisan optimize:clear

# optimize
php artisan optimize
php artisan filament:optimize
```

# Packages

- https://filamentphp.com/plugins/eightynine-reports
- https://filamentphp.com/plugins/awcodes-recently
- https://www.reddit.com/r/laravel/comments/18x7wix/how_to_fill_out_existing_pdf/

# Notes

- new system?
- A user can: 
-- only be an active member of 1 organization at a time
-- only be an outbound member of 1 organization at a time
-- only be an inbound member of 1 organization at a time