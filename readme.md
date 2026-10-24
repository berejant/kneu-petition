# KNEU Online petitions

## Вимоги

- PHP 8.3+ (перевірено на 8.5)
- Composer 2
- Node.js 20.19+ / 22.12+ (для збірки фронтенду через Vite)

## Встановлення

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

npm ci
npm run build
```

Для розробки фронтенду: `npm run dev` (Vite з hot reload).

## Деплой

Зібрані ресурси (`public/build`) не зберігаються в репозиторії, тому при кожному деплої потрібно виконати:

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize
```

## Тести

```bash
composer test
```
