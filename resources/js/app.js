import './bootstrap';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import router from './router';
import ru from './locales/ru';
import App from './App.vue';
import './axios-config'; // Import axios configuration

const i18n = createI18n({
    legacy: false, // Используем Composition API
    locale: 'ru',
    fallbackLocale: 'ru',
    messages: { ru }
});

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#9C27B0',     // Основной фиолетовый
                    secondary: '#E1BEE7',    // Светло-фиолетовый
                    accent: '#7B1FA2',       // Темно-фиолетовый
                    error: '#FF5252',
                    info: '#BA68C8',         // Средний фиолетовый
                    success: '#4CAF50',
                    warning: '#FB8C00',
                    background: '#F3E5F5'    // Очень светлый фиолетовый фон
                }
            }
        }
    }
});

const app = createApp(App);
app.use(router);
app.use(i18n);
app.use(vuetify);
app.mount('#app');

// Скрываем глобальный лоадер после монтирования Vue
const appLoading = document.getElementById('app-loading');
if (appLoading) {
    appLoading.classList.add('fade-out');
    setTimeout(() => {
        appLoading.style.display = 'none';
    }, 300);
}

// Отправляем событие после монтирования Vue
document.getElementById('app').dispatchEvent(new Event('vue:mounted'));
