export default {
    app: {
        name: 'Культурный Балаково',
        footer: {
            copyright: '© {year} Культурный Балаково'
        }
    },
    navigation: {
        home: 'Главная',
        events: 'События',
        myRegistrations: 'Мои регистрации',
        search: 'Поиск',
        account: 'Аккаунт',
        calendar: 'Календарь',
        eventTypes: 'Категории',
        reviews: 'Отзывы',
        contacts: 'Контакты',
        about: 'О нас',
        backToHome: 'Вернуться на главную',
        admin: 'Администрирование',
        adminDashboard: 'Панель управления',
        adminEvents: 'Управление событиями',
        adminReviews: 'Модерация отзывов',
        adminUsers: 'Пользователи'
    },
    home: {
        welcome: {
            title: 'Добро пожаловать в Культурный Балаково',
            description: 'Найдите интересное мероприятие и зарегистрируйтесь на него',
            action: 'Найти мероприятия'
        },
        upcomingEvents: {
            title: 'Ближайшие события',
            viewDetails: 'Подробнее',
            noEvents: 'Нет предстоящих событий'
        },
        categories: {
            title: 'Категории мероприятий'
        },
        actions: {
            browseEvents: 'Смотреть мероприятия',
            viewAll: 'Смотреть все'
        },
        features: {
            upcoming: {
                title: 'Календарь событий',
                description: 'Удобный поиск мероприятий по дате, месту проведения и категории. Будьте в курсе всех культурных событий города.'
            },
            community: {
                title: 'Отзывы и рейтинги',
                description: 'Делитесь своими впечатлениями о посещенных мероприятиях, оставляйте отзывы и помогайте другим в выборе.'
            },
            featured: {
                title: 'Доступно везде',
                description: 'Просматривайте события и покупайте билеты с любого устройства - компьютера, планшета или смартфона.'
            }
        },
        currentEvents: {
            title: 'Текущие мероприятия'
        },
        recommendedEvents: {
            title: 'Рекомендуемые мероприятия'
        }
    },
    categories: {
        cultural: 'Культурные',
        culturalDesc: 'Театры, музеи, выставки, концерты классической музыки',
        culturalIcon: 'mdi-theater',
        
        sports: 'Спортивные',
        sportsDesc: 'Матчи, соревнования, турниры, тренировки',
        sportsIcon: 'mdi-soccer',
        
        educational: 'Образовательные',
        educationalDesc: 'Лекции, семинары, мастер-классы, конференции',
        educationalIcon: 'mdi-school',
        
        entertainment: 'Развлекательные',
        entertainmentDesc: 'Концерты, фестивали, вечеринки, шоу',
        entertainmentIcon: 'mdi-party-popper',
        
        other: 'Другие',
        otherDesc: 'Встречи, нетворкинг, воркшопы и другие мероприятия',
        otherIcon: 'mdi-calendar-blank'
    },
    calendar: {
        title: 'Календарь мероприятий',
        viewDetails: 'Подробнее',
        eventsForDate: 'Мероприятия на выбранную дату',
        noEvents: 'Нет мероприятий на выбранную дату'
    },
    eventTypes: {
        title: 'Категории мероприятий',
        events: 'мероприятий'
    },
    reviews: {
        title: 'Отзывы и оценки',
        viewEvent: 'Перейти к мероприятию',
        delete: 'Удалить',
        count: '{n} отзывов',
        empty: {
            title: 'Пока нет отзывов',
            description: 'Будьте первым, кто оставит отзыв',
            action: 'Написать отзыв'
        },
        deleteConfirm: {
            title: 'Удаление отзыва',
            message: 'Вы уверены, что хотите удалить этот отзыв?'
        },
        status: {
            published: 'Опубликован',
            pending: 'На модерации',
            rejected: 'Отклонен',
            unknown: 'Неизвестно',
            approved: 'Опубликован'
        },
        actions: {
            approve: 'Одобрить',
            reject: 'Отклонить',
            delete: 'Удалить',
            cancel: 'Отмена'
        },
        rating: 'Рейтинг',
        yourRating: 'Ваша оценка',
        writeReview: 'Написать отзыв',
        form: {
            name: 'Ваше имя',
            email: 'Ваш email',
            subject: 'Тема',
            message: 'Сообщение',
            send: 'Отправить'
        }
    },
    contacts: {
        title: 'Контакты',
        getInTouch: 'Связаться с нами',
        phone: 'Телефон',
        email: 'Электронная почта',
        address: 'Адрес',
        sendMessage: 'Отправить сообщение',
        form: {
            name: 'Ваше имя',
            email: 'Ваш email',
            subject: 'Тема',
            message: 'Сообщение',
            send: 'Отправить'
        }
    },
    about: {
        title: 'О нас',
        mission: {
            title: 'Миссия',
            description: 'Мы стремимся сделать культурную жизнь Балаково более доступной и насыщенной, помогая жителям и гостям города находить интересные мероприятия и делиться впечатлениями.'
        },
        features: {
            title: 'Наши преимущества',
            items: {
                events: 'Актуальные события',
                eventsDesc: 'Всегда актуальная информация о культурных мероприятиях города',
                community: 'Активное сообщество',
                communityDesc: 'Отзывы и рекомендации от реальных посетителей',
                booking: 'Удобная регистрация',
                bookingDesc: 'Простая и быстрая регистрация на мероприятия',
                reviews: 'Честные отзывы',
                reviewsDesc: 'Система рейтингов и отзывов помогает сделать правильный выбор'
            }
        },
        team: {
            title: 'Наша команда',
            description: 'Мы небольшая, но увлеченная команда, работающая над улучшением культурной жизни города',
            joinUs: 'Присоединяйтесь к нам'
        },
        stats: {
            title: 'Статистика',
            events: 'Мероприятий',
            users: 'Пользователей',
            organizers: 'Организаторов'
        }
    },
    events: {
        title: 'События в Балаково',
        details: 'Подробнее',
        free: 'Бесплатно',
        filters: {
            type: 'Категория',
            price: 'Стоимость',
            fromDate: 'Дата с',
            toDate: 'Дата по',
            dateHint: 'Выберите дату',
            apply: 'Применить фильтры',
            clear: 'Сбросить',
            showPastEvents: 'Показать прошедшие события',
            priceRanges: {
                free: 'Бесплатно',
            }
        },
        sorting: {
            field: 'Сортировать по',
            asc: 'По возрастанию',
            desc: 'По убыванию',
            fields: {
              title: 'По названию',
              date: 'Дате',
              price: 'Стоимости',
              type: 'По типу'
            }
        },
        description: 'Описание',
        organizer: 'Организатор',
        organizerUnknown: 'Организатор не указан',
        pricePerPerson: 'Стоимость за человека',
        showOnMap: 'Показать на карте',
        free: 'Бесплатно',
        register: 'Зарегистрироваться',
        registered: 'Вы зарегистрированы',
        already_registered: 'Вы уже зарегистрированы',
        login_required: 'Для регистрации необходимо войти в систему',
        registration_error: 'Произошла ошибка при регистрации на мероприятие',
        unregister: 'Отменить регистрацию',
        unregister_confirm: 'Вы уверены, что хотите отменить регистрацию на это мероприятие?',
        unregistration_error: 'Произошла ошибка при отмене регистрации',
        pastEvent: 'Прошедшее событие',
        actions: {
            addToCalendar: 'Добавить в календарь',
            share: 'Поделиться',
            copy: 'Копировать ссылку',
            back: 'Назад к списку',
            details: 'Подробнее'
        },
        empty: {
            title: 'Мероприятий не найдено',
            description: 'Попробуйте изменить параметры поиска или вернитесь позже'
        },
        detail: {
            location: 'Место проведения',
            locationNotSpecified: 'Место не указано',
            error: 'Не удалось загрузить информацию о мероприятии'
        },
        reviews: {
            title: 'Отзывы',
            empty: 'Пока нет отзывов. Будьте первым!',
            auth_required: 'Войдите в систему, чтобы оставить отзыв',
            future_event: 'Отзывы можно оставлять только после посещения мероприятия',
            cannot_review: 'Вы уже оставили отзыв на это мероприятие',
            not_registered: 'Для возможности оставить отзыв необходимо зарегистрироваться на мероприятие',
            form: {
                text: 'Ваш отзыв',
                submit: 'Отправить отзыв'
            }
        }
    },
    registrations: {
        empty: {
            title: 'У вас пока нет регистраций',
            description: 'Найдите интересное мероприятие и зарегистрируйтесь на него',
            action: 'Найти мероприятия',
            active: 'У вас нет активных регистраций',
            past: 'У вас нет прошедших мероприятий'
        },
        actions: {
            cancel: 'Отменить регистрацию'
        },
        registered_at: 'Зарегистрирован',
        tabs: {
            active: 'Активные',
            past: 'Прошедшие'
        }
    },
    common: {
        cancel: 'Отмена',
        save: 'Сохранить',
        delete: 'Удалить',
        edit: 'Редактировать'
    },
    auth: {
        login: 'Вход',
        register: 'Регистрация',
        logout: 'Выйти',
        email: 'Email',
        password: 'Пароль',
        name: 'Имя',
        firstName: 'Имя',
        lastName: 'Фамилия',
        middleName: 'Отчество',
        forgotPassword: 'Забыли пароль?',
        noAccount: 'Нет аккаунта?',
        hasAccount: 'Уже есть аккаунт?',
        signUp: 'Зарегистрироваться',
        signIn: 'Войти',
        passwordConfirmation: 'Подтверждение пароля',
        loginSuccess: 'Вход выполнен успешно',
        registerSuccess: 'Регистрация выполнена успешно',
        logoutSuccess: 'Выход выполнен успешно',
        loginRequired: 'Для доступа к этой странице необходимо войти в систему',
        adminRequired: 'Для доступа к этой странице необходимы права администратора',
        invalidCredentials: 'Неверный email или пароль',
        rememberMe: 'Запомнить меня'
    },
    admin: {
        dashboard: {
            title: 'Панель управления',
            welcome: 'Добро пожаловать в панель управления',
            stats: {
                events: 'Мероприятия',
                users: 'Пользователи',
                registrations: 'Регистрации',
                reviews: 'Отзывы'
            },
            upcomingEvents: 'Ближайшие мероприятия',
            pendingReviews: 'Отзывы на модерации',
            viewAll: 'Смотреть все',
            events: 'Мероприятия',
            users: 'Пользователи',
            reviews: 'Отзывы'
        },
        events: {
            title: 'Управление мероприятиями',
            add: 'Добавить мероприятие',
            edit: 'Редактировать мероприятие',
            deleteTitle: 'Удалить мероприятие?',
            deleteConfirm: 'Вы уверены, что хотите удалить мероприятие "{title}"? Это действие нельзя отменить.',
            table: {
                id: 'ID',
                title: 'Название',
                type: 'Тип',
                date: 'Дата',
                location: 'Место',
                price: 'Стоимость',
                actions: 'Действия'
            },
            form: {
                title: 'Название',
                description: 'Описание',
                location: 'Место проведения',
                type: 'Тип мероприятия',
                price: 'Стоимость (₽)',
                priceHint: 'Оставьте пустым для бесплатного мероприятия',
                capacity: 'Вместимость',
                date: 'Дата проведения',
                time: 'Время начала',
                image: 'URL изображения',
                imageHint: 'Оставьте пустым для автоматического выбора изображения'
            },
            validation: {
                titleRequired: 'Название обязательно',
                descriptionRequired: 'Описание обязательно',
                locationRequired: 'Место проведения обязательно',
                typeRequired: 'Тип мероприятия обязателен',
                capacityRequired: 'Вместимость обязательна',
                dateRequired: 'Дата проведения обязательна',
                timeRequired: 'Время начала обязательно'
            }
        },
        reviews: {
            title: 'Модерация отзывов',
            headers: {
                id: 'ID',
                user: 'Пользователь',
                event: 'Мероприятие',
                rating: 'Оценка',
                status: 'Статус',
                date: 'Дата',
                actions: 'Действия'
            },
            close: 'Закрыть',
            delete: {
                title: 'Удаление отзыва',
                message: 'Вы уверены, что хотите удалить этот отзыв? Это действие нельзя отменить.'
            },
            approve: 'Одобрить',
            reject: 'Отклонить',
            delete: 'Удалить',
            deleteConfirm: {
                title: 'Удаление отзыва',
                message: 'Вы уверены, что хотите удалить этот отзыв? Это действие нельзя отменить.'
            },
            status: {
                pending: 'На модерации',
                approved: 'Одобрен',
                rejected: 'Отклонен'
            },
            table: {
                id: 'ID',
                event: 'Мероприятие',
                user: 'Пользователь',
                rating: 'Оценка',
                text: 'Текст',
                status: 'Статус',
                date: 'Дата',
                actions: 'Действия'
            },
            close: 'Закрыть'
        },
        users: {
            title: 'Управление пользователями',
            table: {
                id: 'ID',
                name: 'Имя',
                email: 'Email',
                role: 'Роль',
                registrations: 'Регистрации',
                reviews: 'Отзывы',
                joined: 'Дата регистрации',
                actions: 'Действия'
            }
        }
    },
    common: {
        save: 'Сохранить',
        cancel: 'Отмена',
        delete: 'Удалить',
        edit: 'Редактировать',
        view: 'Просмотр',
        search: 'Поиск',
        filter: 'Фильтр',
        reset: 'Сбросить',
        apply: 'Применить',
        yes: 'Да',
        no: 'Нет',
        back: 'Назад',
        next: 'Далее',
        loading: 'Загрузка...',
        noData: 'Нет данных',
        all: 'Все'
    },
    errors: {
        notFound: {
            title: 'Страница не найдена',
            description: 'Запрашиваемая страница не существует или была перемещена',
            action: 'Вернуться на главную'
        },
        pageNotFound: 'Страница не найдена',
        serverError: {
            title: 'Ошибка сервера',
            description: 'Произошла ошибка при обработке запроса',
            action: 'Попробовать снова'
        },
        validation: {
            required: 'Поле обязательно для заполнения',
            email: 'Введите корректный email',
            minLength: 'Минимальная длина: {min} символов',
            maxLength: 'Максимальная длина: {max} символов',
            passwordMatch: 'Пароли не совпадают'
        }
    }
}
