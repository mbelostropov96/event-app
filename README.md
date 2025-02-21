# Event Management System

Система управления событиями для города Балаково. Позволяет просматривать, создавать и регистрироваться на городские мероприятия.

## Требования

- Docker
- Docker Compose

## Установка и запуск

1. Клонируйте репозиторий:
```bash
git clone <repository-url>
cd event-app
```

2. Скопируйте файл окружения:
```bash
cp .env.example .env
```

3. Запустите скрипт установки и запуска:
```bash
./start.sh
```

После этого приложение будет доступно по адресу: http://localhost:8000

## Режим разработки

Для работы с фронтендом в режиме разработки выполните:
```bash
docker-compose exec app npm run dev
```

Это включит:
- Hot Module Replacement (HMR) - автоматическое обновление при изменении кода
- Source Maps для удобной отладки
- Не минифицированный код
- Автоматическую пересборку при изменении файлов

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
