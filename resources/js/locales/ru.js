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
        backToHome: 'Вернуться на главную'
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
        viewEvent: 'Перейти к мероприятию'
    },
    contacts: {
        title: 'Контакты',
        getInTouch: 'Свяжитесь с нами',
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
            title: 'Наша миссия',
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
        actions: {
            addToCalendar: 'Добавить в календарь',
            share: 'Поделиться',
            copy: 'Копировать ссылку'
        },
        actions: {
            back: 'Назад к списку',
            details: 'Подробнее'
        },
        empty: {
            title: 'Мероприятий не найдено',
            description: 'Попробуйте изменить параметры поиска или вернитесь позже'
        },
        detail: {
            location: 'Место проведения',
            locationNotSpecified: 'Место не указано'
        },
        reviews: {
            title: 'Отзывы',
            empty: 'Пока нет отзывов. Будьте первым!',
            auth_required: 'Войдите в систему, чтобы оставить отзыв',
            form: {
                text: 'Ваш отзыв',
                submit: 'Отправить отзыв'
            }
        },
        registration: {
            title: 'Регистрация',
            free: 'Бесплатно',
            spots_left: 'Осталось мест: {count}',
            button: 'Зарегистрироваться',
            closed: 'Регистрация закрыта',
            auth_required: 'Войдите в систему, чтобы зарегистрироваться'
        },
        details: {
            capacity: 'Вместимость',
            registered: 'Зарегистрировано',
            people: 'человек'
        },
        errors: {
            notFound: 'Событие не найдено'
        },
        share: {
            title: 'Поделиться',
            copy: 'Копировать ссылку'
        },
        register: 'Зарегистрироваться',
        registered: 'Вы зарегистрированы',
        noEvents: 'Нет событий по заданным критериям'
    },
    registrations: {
        status: {
            confirmed: 'Подтверждено',
            pending: 'В обработке',
            cancelled: 'Отменено'
        },
        actions: {
            cancel: 'Отменить регистрацию'
        },
        empty: {
            title: 'У вас пока нет билетов',
            description: 'Найдите интересное мероприятие и зарегистрируйтесь на него',
            action: 'Найти мероприятия'
        }
    },
    errors: {
        pageNotFound: 'Страница не найдена'
    }
};
