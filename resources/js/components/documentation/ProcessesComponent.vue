<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">Процессы в приложении</h1>

      </v-col>
    </v-row>

    <!-- Процесс 1: Подключение к базе данных -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-database" class="mr-2"></v-icon>
            Процесс подключения к базе данных
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Подключение к базе данных настраивается через конфигурационный файл и использует драйвер MySQL.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Конфигурация базы данных (.env)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
DB_CONNECTION=mysql
DB_HOST=event-app-db
DB_PORT=3306
DB_DATABASE=event_app
DB_USERNAME=event_app_user
DB_PASSWORD=password
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Настройка подключения (config/database.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Инициализация подключения (AppServiceProvider.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Bootstrap any application services.
 */
public function boot()
{
    // Проверка соединения с базой данных
    try {
        DB::connection()->getPdo();
        Log::info('База данных подключена успешно: ' . DB::connection()->getDatabaseName());
    } catch (\Exception $e) {
        Log::error('Ошибка подключения к базе данных: ' . $e->getMessage());
    }
}
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Процесс 2: Регистрация на событие -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-account-check" class="mr-2"></v-icon>
            Процесс регистрации на событие
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Пользователи могут зарегистрироваться на предстоящие события. Процесс включает проверку аутентификации, 
              отправку запроса на сервер и обновление статуса регистрации в интерфейсе.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Компонент интерфейса (EventDetailsComponent.vue)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
&lt;v-btn
  block
  color="primary"
  size="x-large"
  elevation="8"
  class="register-btn mb-4"
  :loading="loading"
  :disabled="!isAuthenticated"
  v-if="!event.registered"
  @click="register"
&gt;
  &lt;v-icon start icon="mdi-check-circle"&gt;&lt;/v-icon&gt;
  &lt;span class="text-subtitle-1"&gt;{{ t('events.register') }}&lt;/span&gt;
&lt;/v-btn&gt;
              </code></pre>
            </v-sheet>
            
            <h3 class="text-h6 mt-4 mb-2">Логика регистрации (JavaScript)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
const register = async () => {
  if (!isAuthenticated) {
    router.push({ name: 'login', query: { redirect: `/events/${event.value.id}` } });
    return;
  }
  
  loading.value = true;
  try {
    await axios.post(`/api/events/${event.value.id}/register`);
    event.value.registered = true;
    showSnackbar(t('events.messages.registered_success'));
  } catch (error) {
    console.error('Error registering for event:', error);
    showSnackbar(t('events.messages.registered_error'), 'error');
  } finally {
    loading.value = false;
  }
};
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Бэкенд контроллер (EventUserController.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Регистрация пользователя на событие
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function register($id)
{
    $user = auth()->user();
    $event = Event::findOrFail($id);
    
    // Проверяем, не зарегистрирован ли пользователь уже
    $existingRegistration = EventRegistration::where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->first();
        
    if ($existingRegistration) {
        return response()->json(['message' => 'Вы уже зарегистрированы на это событие'], 400);
    }
    
    // Создаем новую регистрацию
    $registration = new EventRegistration();
    $registration->user_id = $user->id;
    $registration->event_id = $event->id;
    $registration->registered_at = now();
    $registration->save();
    
    return response()->json(['message' => 'Регистрация успешна']);
}
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">API маршрут (routes/api.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
// Маршруты для регистрации на события (требуют аутентификации)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events/{id}/register', [EventUserController::class, 'register']);
    Route::post('/events/{id}/unregister', [EventUserController::class, 'unregister']);
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Процесс 3: Модерация отзывов -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-star-check" class="mr-2"></v-icon>
            Процесс модерации отзывов
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Администраторы могут модерировать отзывы пользователей, одобряя или отклоняя их. 
              Отзывы отображаются на странице события только после одобрения.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Компонент администратора (AdminReviews.vue)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
&lt;v-data-table
  :headers="headers"
  :items="reviews"
  :loading="loading"
  class="elevation-1"
&gt;
  &lt;template v-slot:item.status="{ item }"&gt;
    &lt;v-chip
      :color="getStatusColor(item.status)"
      :text="getStatusText(item.status)"
      size="small"
    &gt;&lt;/v-chip&gt;
  &lt;/template&gt;
  &lt;template v-slot:item.actions="{ item }"&gt;
    &lt;v-btn
      v-if="item.status === 'pending'"
      color="success"
      icon
      variant="text"
      @click="approveReview(item.id)"
    &gt;
      &lt;v-icon&gt;mdi-check&lt;/v-icon&gt;
    &lt;/v-btn&gt;
    &lt;v-btn
      v-if="item.status === 'pending'"
      color="error"
      icon
      variant="text"
      @click="rejectReview(item.id)"
    &gt;
      &lt;v-icon&gt;mdi-close&lt;/v-icon&gt;
    &lt;/v-btn&gt;
  &lt;/template&gt;
&lt;/v-data-table&gt;
              </code></pre>
            </v-sheet>
            
            <h3 class="text-h6 mt-4 mb-2">Логика модерации (JavaScript)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
const approveReview = async (reviewId) => {
  try {
    await axios.post(`/api/admin/reviews/${reviewId}/approve`);
    // Обновляем статус отзыва в списке
    const index = reviews.value.findIndex(review => review.id === reviewId);
    if (index !== -1) {
      reviews.value[index].status = 'approved';
    }
    showSnackbar('Отзыв успешно одобрен');
  } catch (error) {
    console.error('Error approving review:', error);
    showSnackbar('Ошибка при одобрении отзыва', 'error');
  }
};

const rejectReview = async (reviewId) => {
  try {
    await axios.post(`/api/admin/reviews/${reviewId}/reject`);
    // Обновляем статус отзыва в списке
    const index = reviews.value.findIndex(review => review.id === reviewId);
    if (index !== -1) {
      reviews.value[index].status = 'rejected';
    }
    showSnackbar('Отзыв отклонен');
  } catch (error) {
    console.error('Error rejecting review:', error);
    showSnackbar('Ошибка при отклонении отзыва', 'error');
  }
};
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Бэкенд контроллер (ReviewAdminController.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Одобрение отзыва
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function approve($id)
{
    $review = EventReview::findOrFail($id);
    $review->status = 'approved';
    $review->moderated_at = now();
    $review->save();
    
    return response()->json(['message' => 'Отзыв одобрен']);
}

/**
 * Отклонение отзыва
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function reject($id)
{
    $review = EventReview::findOrFail($id);
    $review->status = 'rejected';
    $review->moderated_at = now();
    $review->save();
    
    return response()->json(['message' => 'Отзыв отклонен']);
}
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">API маршруты для модерации (routes/api.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
// Маршруты для администраторов
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Маршруты для модерации отзывов
    Route::get('/reviews', [ReviewAdminController::class, 'index']);
    Route::post('/reviews/{id}/approve', [ReviewAdminController::class, 'approve']);
    Route::post('/reviews/{id}/reject', [ReviewAdminController::class, 'reject']);
});
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Процесс 4: Управление плейсхолдерами изображений -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-image" class="mr-2"></v-icon>
            Процесс управления плейсхолдерами изображений
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Для событий без загруженных изображений используются плейсхолдеры. Система кеширует плейсхолдеры по ID события, 
              чтобы обеспечить согласованное отображение на всех страницах.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Логика управления изображениями (eventData.js)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
// Кеш для плейсхолдеров изображений
const eventImageCache = {};

// Функция для получения изображения события
export const getEventImage = (event) => {
  if (!event) return '';
  
  // Если у события есть изображение, возвращаем его
  if (event.image) {
    return event.image;
  }
  
  // Проверяем, есть ли кешированный плейсхолдер для этого события
  if (eventImageCache[event.id]) {
    return eventImageCache[event.id];
  }
  
  // Генерируем случайный плейсхолдер и сохраняем в кеш
  const placeholderIndex = Math.floor(Math.random() * 10) + 1;
  const placeholderUrl = `/images/placeholders/event-${placeholderIndex}.jpg`;
  eventImageCache[event.id] = placeholderUrl;
  
  return placeholderUrl;
};
              </code></pre>
            </v-sheet>
            
            <h3 class="text-h6 mt-4 mb-2">Использование в компоненте (EventListComponent.vue)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
&lt;v-img
  :src="getEventImage(event)"
  height="200"
  cover
  class="rounded-t"
&gt;
  &lt;template v-slot:placeholder&gt;
    &lt;v-row class="fill-height ma-0" align="center" justify="center"&gt;
      &lt;v-progress-circular indeterminate color="primary"&gt;&lt;/v-progress-circular&gt;
    &lt;/v-row&gt;
  &lt;/template&gt;
&lt;/v-img&gt;
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Бэкенд обработка изображений (EventController.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Загрузка изображения для события
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function uploadImage(Request $request, $id)
{
    $event = Event::findOrFail($id);
    
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    if ($request->hasFile('image')) {
        // Удаляем старое изображение, если оно существует
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        
        // Сохраняем новое изображение
        $path = $request->file('image')->store('events', 'public');
        $event->image = $path;
        $event->save();
        
        return response()->json([
            'message' => 'Изображение успешно загружено',
            'image' => Storage::url($path)
        ]);
    }
    
    return response()->json(['message' => 'Ошибка при загрузке изображения'], 400);
}
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Процесс 4: Аутентификация и авторизация -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-shield-account" class="mr-2"></v-icon>
            Процесс аутентификации и авторизации
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Система поддерживает роли пользователей (admin/user) с соответствующим контролем доступа.
              Защищенные маршруты требуют аутентификации, а административные разделы доступны только администраторам.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Защита маршрутов (router.js)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
// Защита маршрутов
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const isAuthenticated = !!token;
  
  // Проверяем, требует ли маршрут аутентификации
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({ name: 'login', query: { redirect: to.fullPath } });
      return;
    }
    
    // Проверяем, требует ли маршрут роли администратора
    if (to.matched.some(record => record.meta.requiresAdmin)) {
      // Проверка роли администратора выполняется в компоненте
    }
  }
  
  // Если маршрут только для гостей и пользователь аутентифицирован
  if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
    next({ name: 'home' });
    return;
  }
  
  next();
});
              </code></pre>
            </v-sheet>
            
            <h3 class="text-h6 mt-4 mb-2">Компонент входа (LoginComponent.vue)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
const login = async () => {
  loading.value = true;
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    });
    
    // Сохраняем токен в localStorage
    localStorage.setItem('token', response.data.token);
    
    // Устанавливаем токен для всех будущих запросов
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
    
    // Перенаправляем пользователя
    const redirectPath = route.query.redirect || '/';
    router.push(redirectPath);
    
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.value = error.response?.data?.message || 'Ошибка входа';
  } finally {
    loading.value = false;
  }
};
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Бэкенд аутентификация (AuthController.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Аутентификация пользователя
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    return response()->json([
        'message' => 'Неверные учетные данные'
    ], 401);
}
              </code></pre>
            </v-sheet>

            <h3 class="text-h6 mt-4 mb-2">Middleware для проверки роли администратора (AdminMiddleware.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @return mixed
 */
public function handle($request, Closure $next)
{
    if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request);
    }
    
    return response()->json(['message' => 'Доступ запрещен'], 403);
}
              </code></pre>
            </v-sheet>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Процесс 6: Обработка опциональной аутентификации -->
    <v-row>
      <v-col cols="12">
        <v-card class="mb-8">
          <v-card-title class="text-h5 bg-primary text-white">
            <v-icon start icon="mdi-account-question" class="mr-2"></v-icon>
            Процесс обработки опциональной аутентификации
          </v-card-title>
          <v-card-text class="pt-4">
            <p class="text-body-1 mb-4">
              Некоторые страницы приложения, такие как детали события, доступны как для аутентифицированных, 
              так и для неаутентифицированных пользователей, но с разным функционалом. Система обрабатывает 
              опциональную аутентификацию для таких случаев.
            </p>
            
            <h3 class="text-h6 mt-4 mb-2">Бэкенд контроллер с опциональной аутентификацией (EventUserController.php)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
/**
 * Получение информации о событии с проверкой регистрации пользователя
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function getEvent($id)
{
    $event = Event::with(['reviews' => function ($query) {
        $query->where('status', 'approved');
    }])->findOrFail($id);
    
    // Проверяем, зарегистрирован ли текущий пользователь на событие
    $user = optional(auth()->user());
    $event->registered = false;
    
    if ($user) {
        $registration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
            
        $event->registered = !!$registration;
    }
    
    return response()->json($event);
}
              </code></pre>
            </v-sheet>
            
            <h3 class="text-h6 mt-4 mb-2">Фронтенд компонент с учетом состояния аутентификации (EventDetailsComponent.vue)</h3>
            <v-sheet color="grey-lighten-4" class="pa-4 mb-4 rounded code-block">
              <pre><code>
// Проверка состояния аутентификации
const isAuthenticated = computed(() => {
  return !!localStorage.getItem('token');
});

// Отображение кнопки регистрации с учетом состояния аутентификации
&lt;v-btn
  block
  color="primary"
  size="x-large"
  elevation="8"
  class="register-btn mb-4"
  :loading="loading"
  :disabled="!isAuthenticated"
  v-if="!event.registered"
  @click="register"
&gt;
  &lt;v-icon start icon="mdi-check-circle"&gt;&lt;/v-icon&gt;
  &lt;span class="text-subtitle-1"&gt;{{ t('events.register') }}&lt;/span&gt;
&lt;/v-btn&gt;
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
