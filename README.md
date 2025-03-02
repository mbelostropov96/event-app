# Event Management System

Система управления событиями для города Балаково. Позволяет просматривать, создавать и регистрироваться на городские мероприятия.

## Установка и запуск на Windows

1. Установить Docker Desktop и WSL

2. Скопировать файл окружения
```bash
cp .env.example .env
```
3. Запустить контейнеры в докере
```bash
docker-compose up -d
```

4. Установить зависимости PHP через Composer
```bash
docker-compose exec -T app composer install
```

5. Запустить миграции для БД
```bash
docker-compose exec -T app php artisan migrate:fresh --seed
docker-compose exec -T app php artisan key:generate
```

6. Установить зависимости npm
```bash
docker-compose exec -T app npm install
```

7. Собрать фронтенд
```bash
 docker-compose exec -T app npm run build
 ```

После этого приложение будет доступно по адресу: http://localhost:8000

## Режим разработки

Для работы с фронтендом в режиме разработки выполнить:
```bash
docker-compose exec app npm run dev
```

## Структура проекта

- `app/` - Backend код (PHP/Laravel)
- `resources/js/` - Frontend код (Vue.js)
- `resources/views/` - Blade шаблоны
- `database/` - Миграции и сиды базы данных
- `docker/` - Docker конфигурация

## Технологии

- Backend: Laravel 10
- Frontend: Vue 3 + Vuetify 3
- База данных: MySQL 8.0
- Сборка фронтенда: Vite
- Контейнеризация: Docker
