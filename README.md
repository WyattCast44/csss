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