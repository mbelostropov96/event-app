/**
 * Общие данные для компонентов, связанных с мероприятиями
 */

// Тематические картинки для разных типов мероприятий
export const eventTypeImages = {
  cultural: [
    'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=800&q=80', // Театр
    'https://images.unsplash.com/photo-1533929736458-ca588d08c8be?auto=format&fit=crop&w=800&q=80', // Музей
    'https://images.unsplash.com/photo-1545178803-4056771d60a3?auto=format&fit=crop&w=800&q=80', // Выставка
    'https://images.unsplash.com/photo-1594122230689-45899d9e6f69?auto=format&fit=crop&w=800&q=80', // Опера
    'https://images.unsplash.com/photo-1525909002-1b05e0c869d8?auto=format&fit=crop&w=800&q=80', // Балет
  ],
  sports: [
    'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80', // Футбол
    'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=800&q=80', // Баскетбол
    'https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=800&q=80', // Стадион
    'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80', // Теннис
    'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=800&q=80', // Бег
  ],
  educational: [
    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80', // Библиотека
    'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=800&q=80', // Лекция
    'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80', // Мастер-класс
    'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80', // Учебный класс
    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80', // Конференция
  ],
  entertainment: [
    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80', // Концерт
    'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=800&q=80', // DJ
    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80', // Фестиваль
    'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80', // Вечеринка
    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80', // Караоке
  ],
  other: [
    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', // Общее
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80', // Воркшоп
    'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=80', // Пикник
    'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?auto=format&fit=crop&w=800&q=80', // Встреча
    'https://images.unsplash.com/photo-1517457373958-b7f3bade9f55?auto=format&fit=crop&w=800&q=80', // Networking
  ],
};

// Цвета для разных типов мероприятий
export const eventTypeColors = {
  cultural: 'purple',
  sports: 'blue',
  educational: 'green',
  entertainment: 'pink',
  other: 'grey'
};

// Функция для получения цвета типа мероприятия
export const getEventTypeColor = (type) => {
  return eventTypeColors[type] || 'grey';
};

// Функция для получения изображения мероприятия
export const getEventImage = (event) => {
  if (event.image) return event.image;
  
  const typeImages = eventTypeImages[event.type] || eventTypeImages.other;
  const randomIndex = Math.floor(Math.random() * typeImages.length);
  return typeImages[randomIndex];
};

// Функция для форматирования даты
export const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Функция для форматирования времени
export const formatTime = (time) => {
  if (!time) return '';
  
  // Проверяем, является ли время ISO строкой (содержит T)
  if (time.includes('T')) {
    const date = new Date(time);
    return `${date.getHours()}:00`;
  }
  
  // Обработка обычного времени в формате HH:MM
  const [hours, minutes] = time.split(':');
  return `${hours}:00`;
}; 