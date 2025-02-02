<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    private array $eventDescriptions = [
        'Конференция' => [
            'IT и технологии' => [
                'title' => 'IT-конференция "Цифровое будущее"',
                'description' => 'Ежегодная конференция для IT-специалистов, где ведущие эксперты обсудят последние тренды в разработке, DevOps и искусственном интеллекте. В программе: доклады, воркшопы и нетворкинг.',
            ],
            'Бизнес и предпринимательство' => [
                'title' => 'Форум "Бизнес в регионах"',
                'description' => 'Масштабный бизнес-форум для предпринимателей Поволжья. Успешные бизнесмены поделятся опытом развития компаний в современных условиях. Включает секции по маркетингу, финансам и управлению.',
            ],
        ],
        'Фестиваль' => [
            'Музыкальный' => [
                'title' => 'Фестиваль "Волжские берега"',
                'description' => 'Большой музыкальный фестиваль под открытым небом. В программе выступления local-групп и хедлайнеров российской сцены. Фуд-корты, развлекательные зоны и детские площадки.',
            ],
            'Гастрономический' => [
                'title' => 'Фестиваль "Вкусы Поволжья"',
                'description' => 'Гастрономический праздник с участием лучших ресторанов города. Мастер-классы от шеф-поваров, дегустации, конкурсы и развлекательная программа для всей семьи.',
            ],
        ],
        'Выставка' => [
            'Современное искусство' => [
                'title' => 'Выставка "Искусство здесь и сейчас"',
                'description' => 'Экспозиция работ современных художников Поволжья. Живопись, скульптура, инсталляции и медиа-арт. Включает встречи с авторами и кураторские экскурсии.',
            ],
            'Фотография' => [
                'title' => 'Фотовыставка "Город у воды"',
                'description' => 'Выставка работ местных фотографов, посвященная жизни города. Городские пейзажи, портреты горожан и индустриальная эстетика ГЭС.',
            ],
        ],
        'Мастер-класс' => [
            'Фотография' => [
                'title' => 'Мастер-класс "Основы фотографии"',
                'description' => 'Интенсивный курс для начинающих фотографов. Теория и практика фотосъёмки, работа со светом, основы композиции. Практические занятия с профессиональным оборудованием.',
            ],
            'Программирование' => [
                'title' => 'Воркшоп "Web-разработка с нуля"',
                'description' => 'Практический воркшоп по созданию современных веб-приложений. Изучение HTML, CSS и JavaScript. Участники создадут свой первый сайт под руководством опытного разработчика.',
            ],
        ],
    ];

    public function definition(): array
    {
        $locations = [
            'Балаково, ул. 30 лет Победы, 5 (ГЭС)' => [3000, 5000],
            'Балаково, ул. Ленина, 126 (Дворец Культуры)' => [2000, 4000],
            'Балаково, ул. Титова, 25А (Молодёжный)' => [1000, 3000],
            'Балаково, ул. Набережная Леонова, 1А (Спортивный комплекс "Форум")' => [2500, 4500],
            'Балаково, ул. Факел Социализма, 27 (Центр искусств)' => [1500, 3500],
            'Балаково, ул. Чапаева, 107 (Городской парк)' => [0, 1000],
            'Балаково, ул. Трнавская, 6 (ТЦ "Оранж")' => [1000, 2000],
            'Балаково, ул. Саратовское шоссе, 2 (Усадьба Мальцева)' => [2000, 3500],
        ];

        // Выбираем случайный тип события и подтип
        $eventType = $this->faker->randomElement(array_keys($this->eventDescriptions));
        $eventSubtype = $this->faker->randomElement(array_keys($this->eventDescriptions[$eventType]));
        $eventInfo = $this->eventDescriptions[$eventType][$eventSubtype];

        // Выбираем случайную локацию и диапазон цен
        $location = $this->faker->randomElement(array_keys($locations));
        $priceRange = $locations[$location];

        // Генерируем дату в следующие 6 месяцев
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $hour = $this->faker->randomElement([10, 12, 14, 16, 18, 19, 20]);
        $minute = '00';
        $startTime = Carbon::createFromFormat('H:i', sprintf('%02d:%s', $hour, $minute));

        // Округляем цену до сотен рублей
        $minPrice = ceil($priceRange[0] / 100) * 100;
        $maxPrice = floor($priceRange[1] / 100) * 100;
        $price = $this->faker->randomElement(range($minPrice, $maxPrice, 100));

        return [
            'title' => $eventInfo['title'],
            'description' => $eventInfo['description'],
            'location' => $location,
            'start_date' => $startDate,
            'start_time' => $startTime,
            'price' => $price,
        ];
    }

    public function free(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => 0,
            ];
        });
    }

    public function premium(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => $this->faker->numberBetween(5000, 15000),
            ];
        });
    }
}
