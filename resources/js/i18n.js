import { createI18n } from 'vue-i18n';
import ru from './locales/ru';

export default createI18n({
    legacy: true, // Включаем legacy режим для поддержки глобальных методов
    locale: 'ru',
    fallbackLocale: 'ru',
    messages: {
        ru
    },
    globalInjection: true, // Включаем глобальную инъекцию для доступа к $t
    useScope: 'global' // Используем глобальный скоп для переводов
});
