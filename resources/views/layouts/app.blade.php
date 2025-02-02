<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #app-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #F3E5F5;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #app-loading.fade-out {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #E1BEE7;
            border-top-color: #9C27B0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        [v-cloak] {
            display: none;
        }
    </style>
</head>
<body>
    <div id="app-loading">
        <div class="spinner"></div>
    </div>

    <div id="app" v-cloak>
        @verbatim
        <v-app>
            <!-- App Bar -->
            <v-app-bar color="primary" prominent>
                <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
                
                <v-toolbar-title>{{ $t('app.name') }}</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-account</v-icon>
                </v-btn>
            </v-app-bar>

            <!-- Navigation Drawer -->
            <v-navigation-drawer v-model="drawer" temporary>
                <v-list>
                    <v-list-item
                        prepend-icon="mdi-view-dashboard"
                        :title="$t('navigation.events')"
                        value="events"
                        href="/events"
                    ></v-list-item>
                    
                    <v-list-item
                        prepend-icon="mdi-account-multiple"
                        :title="$t('navigation.myRegistrations')"
                        value="registrations"
                        href="/event-registrations"
                    ></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <!-- Main Content -->
            <v-main>
                <v-container fluid>
                    @yield('content')
                </v-container>
            </v-main>

        @verbatim
            <!-- Footer -->
            <v-footer app color="primary" class="text-center d-flex justify-center">
                <span class="white--text">{{ $t('app.footer.copyright', { year: new Date().getFullYear() }) }}</span>
            </v-footer>
        </v-app>
        @endverbatim
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const app = document.getElementById('app');
            const loading = document.getElementById('app-loading');

            // Слушаем событие монтирования Vue приложения
            app.addEventListener('vue:mounted', function() {
                loading.classList.add('fade-out');
                setTimeout(() => {
                    loading.style.display = 'none';
                }, 300);
            });
        });
    </script>
</body>
</html>
