# BeyondChats Articles API

Laravel-based REST API for managing articles scraped from BeyondChats blog.

## Setup

```bash
cd laravel-api
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
php artisan serve
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/articles | List all articles |
| GET | /api/articles/latest | Get latest original article |
| GET | /api/articles/{id} | Get article by ID or slug |
| POST | /api/articles | Create new article |
| PUT | /api/articles/{id} | Update article |
| DELETE | /api/articles/{id} | Delete article |

## Query Parameters

- `type=original` - Only original articles
- `type=updated` - Only AI-updated versions
- `with_versions=true` - Include related articles

## Example Requests

```bash
# get all articles
curl http://localhost:8000/api/articles

# get latest article
curl http://localhost:8000/api/articles/latest

# create article
curl -X POST http://localhost:8000/api/articles \
  -H "Content-Type: application/json" \
  -d '{"title":"Test","content":"Content here"}'
```
