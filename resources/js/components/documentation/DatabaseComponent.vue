<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">Структура базы данных</h1>
      </v-col>
    </v-row>

    <!-- Общее описание -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-database" class="mr-2"></v-icon>
            Обзор базы данных
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              База данных приложения построена на MySQL и содержит несколько основных сущностей, связанных между собой отношениями. 
              Основные таблицы включают пользователей, события, регистрации на события и отзывы.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Схема связей (код для dbdiagram.io)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>

Table users {
  id bigint [pk, increment]
  name varchar
  email varchar [unique]
  email_verified_at timestamp [null]
  password varchar
  role enum('user', 'admin') [default: 'user']
  remember_token varchar [null]
  created_at timestamp
  updated_at timestamp
}

Table events {
  id bigint [pk, increment]
  title varchar
  description text
  date datetime
  location varchar
  max_participants integer [null]
  image varchar [null]
  created_at timestamp
  updated_at timestamp
}

Table event_registrations {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id, not null]
  event_id bigint [ref: > events.id, not null]
  registered_at timestamp
  created_at timestamp
  updated_at timestamp

  indexes {
    (user_id, event_id) [unique]
  }
}

Table event_reviews {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id, not null]
  event_id bigint [ref: > events.id, not null]
  rating integer
  comment text
  status enum('pending', 'approved', 'rejected') [default: 'pending']
  moderated_at timestamp [null]
  created_at timestamp
  updated_at timestamp

  indexes {
    (user_id, event_id) [unique]
  }
}
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Таблица Users -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-account-group" class="mr-2"></v-icon>
            Таблица Users (Пользователи)
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Хранит информацию о пользователях системы, включая администраторов и обычных пользователей.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Структура таблицы</h3>
            <v-table class="mb-4">
              <thead>
                <tr>
                  <th>Поле</th>
                  <th>Тип</th>
                  <th>Описание</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><code>id</code></td>
                  <td>bigint unsigned</td>
                  <td>Первичный ключ</td>
                </tr>
                <tr>
                  <td><code>name</code></td>
                  <td>varchar(255)</td>
                  <td>Имя пользователя</td>
                </tr>
                <tr>
                  <td><code>email</code></td>
                  <td>varchar(255)</td>
                  <td>Email пользователя (уникальный)</td>
                </tr>
                <tr>
                  <td><code>password</code></td>
                  <td>varchar(255)</td>
                  <td>Хешированный пароль</td>
                </tr>
                <tr>
                  <td><code>role</code></td>
                  <td>enum('user', 'admin')</td>
                  <td>Роль пользователя</td>
                </tr>
                <tr>
                  <td><code>remember_token</code></td>
                  <td>varchar(100)</td>
                  <td>Токен для функции "запомнить меня"</td>
                </tr>
                <tr>
                  <td><code>created_at</code></td>
                  <td>timestamp</td>
                  <td>Дата создания записи</td>
                </tr>
                <tr>
                  <td><code>updated_at</code></td>
                  <td>timestamp</td>
                  <td>Дата последнего обновления</td>
                </tr>
              </tbody>
            </v-table>

            <h3 class="text-h6 mt-4 mb-2">Связи</h3>
            <ul class="mb-4">
              <li>Один пользователь может иметь много регистраций на события (one-to-many с <code>event_registrations</code>)</li>
              <li>Один пользователь может оставить много отзывов (one-to-many с <code>event_reviews</code>)</li>
            </ul>

            <h3 class="text-h6 mt-4 mb-2">Миграция</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['user', 'admin'])->default('user');
    $table->rememberToken();
    $table->timestamps();
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Таблица Events -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-calendar-multiple" class="mr-2"></v-icon>
            Таблица Events (События)
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Содержит информацию о всех событиях, доступных в системе.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Структура таблицы</h3>
            <v-table class="mb-4">
              <thead>
                <tr>
                  <th>Поле</th>
                  <th>Тип</th>
                  <th>Описание</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><code>id</code></td>
                  <td>bigint unsigned</td>
                  <td>Первичный ключ</td>
                </tr>
                <tr>
                  <td><code>title</code></td>
                  <td>varchar(255)</td>
                  <td>Название события</td>
                </tr>
                <tr>
                  <td><code>description</code></td>
                  <td>text</td>
                  <td>Описание события</td>
                </tr>
                <tr>
                  <td><code>date</code></td>
                  <td>datetime</td>
                  <td>Дата и время проведения</td>
                </tr>
                <tr>
                  <td><code>location</code></td>
                  <td>varchar(255)</td>
                  <td>Место проведения</td>
                </tr>
                <tr>
                  <td><code>max_participants</code></td>
                  <td>integer</td>
                  <td>Максимальное количество участников</td>
                </tr>
                <tr>
                  <td><code>image</code></td>
                  <td>varchar(255)</td>
                  <td>Путь к изображению события</td>
                </tr>
                <tr>
                  <td><code>created_at</code></td>
                  <td>timestamp</td>
                  <td>Дата создания записи</td>
                </tr>
                <tr>
                  <td><code>updated_at</code></td>
                  <td>timestamp</td>
                  <td>Дата последнего обновления</td>
                </tr>
              </tbody>
            </v-table>

            <h3 class="text-h6 mt-4 mb-2">Связи</h3>
            <ul class="mb-4">
              <li>Одно событие может иметь много регистраций (one-to-many с <code>event_registrations</code>)</li>
              <li>Одно событие может иметь много отзывов (one-to-many с <code>event_reviews</code>)</li>
            </ul>

            <h3 class="text-h6 mt-4 mb-2">Миграция</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->dateTime('date');
    $table->string('location');
    $table->integer('max_participants')->nullable();
    $table->string('image')->nullable();
    $table->timestamps();
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Таблица Event Registrations -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-clipboard-check" class="mr-2"></v-icon>
            Таблица Event Registrations (Регистрации на события)
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Отслеживает регистрации пользователей на события.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Структура таблицы</h3>
            <v-table class="mb-4">
              <thead>
                <tr>
                  <th>Поле</th>
                  <th>Тип</th>
                  <th>Описание</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><code>id</code></td>
                  <td>bigint unsigned</td>
                  <td>Первичный ключ</td>
                </tr>
                <tr>
                  <td><code>user_id</code></td>
                  <td>bigint unsigned</td>
                  <td>Внешний ключ к таблице users</td>
                </tr>
                <tr>
                  <td><code>event_id</code></td>
                  <td>bigint unsigned</td>
                  <td>Внешний ключ к таблице events</td>
                </tr>
                <tr>
                  <td><code>registered_at</code></td>
                  <td>timestamp</td>
                  <td>Дата и время регистрации</td>
                </tr>
                <tr>
                  <td><code>created_at</code></td>
                  <td>timestamp</td>
                  <td>Дата создания записи</td>
                </tr>
                <tr>
                  <td><code>updated_at</code></td>
                  <td>timestamp</td>
                  <td>Дата последнего обновления</td>
                </tr>
              </tbody>
            </v-table>

            <h3 class="text-h6 mt-4 mb-2">Связи</h3>
            <ul class="mb-4">
              <li>Принадлежит одному пользователю (belongs-to с <code>users</code>)</li>
              <li>Принадлежит одному событию (belongs-to с <code>events</code>)</li>
            </ul>

            <h3 class="text-h6 mt-4 mb-2">Миграция</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
Schema::create('event_registrations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('event_id')->constrained()->onDelete('cascade');
    $table->timestamp('registered_at');
    $table->timestamps();
    
    // Уникальный индекс для предотвращения дублирования регистраций
    $table->unique(['user_id', 'event_id']);
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Таблица Event Reviews -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-comment-text-multiple" class="mr-2"></v-icon>
            Таблица Event Reviews (Отзывы о событиях)
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Хранит отзывы пользователей о посещенных событиях.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Структура таблицы</h3>
            <v-table class="mb-4">
              <thead>
                <tr>
                  <th>Поле</th>
                  <th>Тип</th>
                  <th>Описание</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><code>id</code></td>
                  <td>bigint unsigned</td>
                  <td>Первичный ключ</td>
                </tr>
                <tr>
                  <td><code>user_id</code></td>
                  <td>bigint unsigned</td>
                  <td>Внешний ключ к таблице users</td>
                </tr>
                <tr>
                  <td><code>event_id</code></td>
                  <td>bigint unsigned</td>
                  <td>Внешний ключ к таблице events</td>
                </tr>
                <tr>
                  <td><code>rating</code></td>
                  <td>integer</td>
                  <td>Оценка события (1-5)</td>
                </tr>
                <tr>
                  <td><code>comment</code></td>
                  <td>text</td>
                  <td>Текст отзыва</td>
                </tr>
                <tr>
                  <td><code>status</code></td>
                  <td>enum('pending', 'approved', 'rejected')</td>
                  <td>Статус модерации отзыва</td>
                </tr>
                <tr>
                  <td><code>moderated_at</code></td>
                  <td>timestamp</td>
                  <td>Дата и время модерации</td>
                </tr>
                <tr>
                  <td><code>created_at</code></td>
                  <td>timestamp</td>
                  <td>Дата создания записи</td>
                </tr>
                <tr>
                  <td><code>updated_at</code></td>
                  <td>timestamp</td>
                  <td>Дата последнего обновления</td>
                </tr>
              </tbody>
            </v-table>

            <h3 class="text-h6 mt-4 mb-2">Связи</h3>
            <ul class="mb-4">
              <li>Принадлежит одному пользователю (belongs-to с <code>users</code>)</li>
              <li>Принадлежит одному событию (belongs-to с <code>events</code>)</li>
            </ul>

            <h3 class="text-h6 mt-4 mb-2">Миграция</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
Schema::create('event_reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('event_id')->constrained()->onDelete('cascade');
    $table->integer('rating');
    $table->text('comment');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamp('moderated_at')->nullable();
    $table->timestamps();
    
    // Уникальный индекс для предотвращения дублирования отзывов
    $table->unique(['user_id', 'event_id']);
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<style scoped>
.code-block {
  overflow-x: auto;
}

pre {
  margin: 0;
  white-space: pre-wrap;
  word-wrap: break-word;
}

code {
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.9rem;
  line-height: 1.5;
}
</style>
