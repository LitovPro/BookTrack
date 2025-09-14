# 📚 BookTrack

<div align="center">

![BookTrack Logo](https://img.shields.io/badge/BookTrack-Library%20Management-blue?style=for-the-badge&logo=book)

**Современная система управления библиотекой книг и авторов с SMS-уведомлениями**

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Yii2 Framework](https://img.shields.io/badge/Yii2-2.0.50+-0073E6?style=flat-square&logo=yii&logoColor=white)](https://yiiframework.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0+-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)

[![CI/CD](https://github.com/LitovPro/BookTrack/workflows/CI/CD%20Pipeline/badge.svg)](https://github.com/LitovPro/BookTrack/actions)
[![Code Quality](https://img.shields.io/badge/Code%20Quality-PSR--12%20%7C%20PHPStan%20Level%206-green?style=flat-square)](https://github.com/LitovPro/BookTrack)
[![Tests](https://img.shields.io/badge/Tests-Codeception-blue?style=flat-square)](https://github.com/LitovPro/BookTrack)

</div>

---

## 🚀 **Возможности**

### 📖 **Управление контентом**
- **CRUD операции** для книг и авторов
- **Много-ко-многим связи** между книгами и авторами
- **Загрузка обложек** книг с валидацией
- **Поиск и фильтрация** по различным критериям

### 👥 **Система пользователей**
- **Регистрация и авторизация** пользователей
- **Ролевая модель доступа** (гость/пользователь)
- **Безопасная аутентификация** с CSRF защитой

### 📱 **SMS-уведомления**
- **Подписка на авторов** для получения уведомлений
- **Автоматические SMS** при выходе новых книг
- **Интеграция с SMSPilot API** (с режимом эмуляции)
- **Логирование всех SMS** для отладки

### 📊 **Аналитика и отчеты**
- **TOP-10 авторов** по количеству книг за год
- **Кэширование отчетов** для повышения производительности
- **Интерактивные фильтры** по годам

### 🎨 **Современный интерфейс**
- **Bootstrap 5** для адаптивного дизайна
- **AJAX-создание авторов** без перезагрузки страницы
- **Интуитивно понятный UX** с поиском и фильтрацией

---

## 🛠 **Технический стек**

| Компонент | Версия | Описание |
|-----------|--------|----------|
| **PHP** | 8.2+ | Основной язык программирования |
| **Yii2** | 2.0.50+ | PHP фреймворк (basic template) |
| **MySQL** | 8.0+ | База данных |
| **Bootstrap** | 5.0+ | CSS фреймворк для UI |
| **Guzzle** | 7.0+ | HTTP клиент для API |
| **Codeception** | 5.0+ | Фреймворк для тестирования |

### 🔧 **Инструменты разработки**
- **PHPStan** (Level 6) - статический анализ кода
- **PHP_CodeSniffer** (PSR-12) - проверка стандартов кодирования
- **GitHub Actions** - CI/CD пайплайн
- **Composer** - управление зависимостями

---

## 📋 **Требования**

### Системные требования
- **PHP 8.2+** с расширениями: `mbstring`, `dom`, `fileinfo`, `mysql`, `zip`
- **MySQL 8.0+** или **MariaDB 10.4+**
- **Composer 2.0+**
- **Веб-сервер** (Apache/Nginx)

### Рекомендуемая среда разработки
- **XAMPP** (Windows) или **LAMP** (Linux)
- **PHPStorm** или **VS Code**
- **Git** для версионирования

---

## ⚡ **Быстрый старт**

### 1. Клонирование репозитория
```bash
git clone https://github.com/LitovPro/BookTrack.git
cd BookTrack
```

### 2. Установка зависимостей
```bash
composer install
```

### 3. Настройка окружения
```bash
cp .env.example .env
```

Отредактируйте `.env` файл:
```env
DB_DSN=mysql:host=localhost;dbname=booktrack
DB_USERNAME=your_username
DB_PASSWORD=your_password
SMS_PILOT_API_KEY=your_api_key_or_эмулятор
```

### 4. Настройка базы данных
```bash
# Создайте базу данных
mysql -u root -p -e "CREATE DATABASE booktrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Примените миграции
php yii migrate
```

### 5. Настройка веб-сервера
Настройте виртуальный хост, указывающий на папку `web/`:
```apache
<VirtualHost *:80>
    ServerName booktrack.local
    DocumentRoot /path/to/BookTrack/web
    
    <Directory /path/to/BookTrack/web>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Запуск приложения
Откройте браузер и перейдите по адресу: `http://booktrack.local`

**Тестовые данные:**
- **Логин:** `admin`
- **Пароль:** `admin123`

---

## 🧪 **Тестирование**

### Запуск всех тестов
```bash
composer test
```

### Проверка качества кода
```bash
# Проверка стандартов PSR-12
composer cs

# Статический анализ PHPStan
composer stan
```

### Покрытие тестами
```bash
# Запуск тестов с покрытием
vendor/bin/codecept run --coverage --coverage-html
```

---

## 📁 **Структура проекта**

```
BookTrack/
├── 📁 .github/workflows/     # GitHub Actions CI/CD
├── 📁 assets/                # Ресурсы приложения
├── 📁 components/            # Компоненты Yii2
├── 📁 config/                # Конфигурационные файлы
├── 📁 controllers/           # Контроллеры (MVC)
├── 📁 migrations/            # Миграции базы данных
├── 📁 models/                # Модели данных (ActiveRecord)
├── 📁 services/              # Бизнес-логика
├── 📁 tests/                 # Тесты (Codeception)
├── 📁 validators/            # Кастомные валидаторы
├── 📁 views/                 # Представления (шаблоны)
├── 📁 web/                   # Веб-ресурсы
│   ├── 📁 css/               # Стили
│   ├── 📁 uploads/           # Загруженные файлы
│   └── 📄 index.php          # Точка входа
├── 📄 composer.json          # Зависимости
├── 📄 README.md              # Документация
└── 📄 yii                    # Консольный скрипт
```

---

## 🔐 **Безопасность**

### Реализованные меры безопасности
- ✅ **CSRF защита** для всех форм
- ✅ **Валидация данных** на всех уровнях
- ✅ **Безопасная загрузка файлов** с проверкой типов
- ✅ **Ролевая модель доступа** (RBAC)
- ✅ **Защита от SQL-инъекций** (ActiveRecord)
- ✅ **XSS защита** (Html::encode)
- ✅ **API ключи** через переменные окружения

### Рекомендации для продакшена
- 🔒 Используйте **HTTPS** для всех соединений
- 🔒 Настройте **firewall** для базы данных
- 🔒 Регулярно **обновляйте зависимости**
- 🔒 Настройте **мониторинг** и логирование
- 🔒 Используйте **сильные пароли** для БД

---

## 📊 **API и интеграции**

### SMSPilot API
```php
// Отправка SMS через SMSPilot
$smsSender = new SmsPilotSender('your_api_key');
$result = $smsSender->sendSms('+7-999-123-45-67', 'Новая книга!');
```

### События системы
```php
// Триггер события при создании книги
Yii::$app->trigger('book.created', new BookCreatedEvent([
    'bookId' => $book->id,
    'authorIds' => $authorIds,
]));
```

---

## 🚀 **CI/CD Pipeline**

Проект использует **GitHub Actions** для автоматизации:

### Workflow включает:
- 🧪 **Автоматическое тестирование** при каждом push
- 🔍 **Проверка качества кода** (PSR-12, PHPStan)
- 🔒 **Проверка безопасности** зависимостей
- 📦 **Автоматический релиз** при создании тега
- 🚀 **Деплой** в продакшен (при push в main)

### Статус сборки:
[![CI/CD](https://github.com/LitovPro/BookTrack/workflows/CI/CD%20Pipeline/badge.svg)](https://github.com/LitovPro/BookTrack/actions)

---

## 🤝 **Вклад в проект**

Мы приветствуем вклад в развитие проекта! 

### Как внести вклад:
1. **Fork** репозитория
2. Создайте **feature branch**: `git checkout -b feature/amazing-feature`
3. **Commit** изменения: `git commit -m 'Add amazing feature'`
4. **Push** в branch: `git push origin feature/amazing-feature`
5. Создайте **Pull Request**

### Стандарты кодирования:
- Следуйте **PSR-12** стандарту
- Покрывайте код **тестами**
- Обновляйте **документацию**
- Используйте **осмысленные commit messages**

---

## 📝 **Лицензия**

Этот проект распространяется под лицензией **BSD-3-Clause**. 

См. файл [LICENSE](LICENSE) для подробной информации.

---

## 👨‍💻 **Автор**

**Alexey Nyzhnyk** - [@LitovPro](https://github.com/LitovPro)

---

## 🙏 **Благодарности**

- [Yii2 Framework](https://yiiframework.com) - за отличный фреймворк
- [Bootstrap](https://getbootstrap.com) - за красивые компоненты UI
- [SMSPilot](https://smspilot.ru) - за SMS API
- [Codeception](https://codeception.com) - за фреймворк тестирования

---

<div align="center">

**⭐ Если проект был полезен, поставьте звезду! ⭐**

[![GitHub stars](https://img.shields.io/github/stars/LitovPro/BookTrack?style=social)](https://github.com/LitovPro/BookTrack/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/LitovPro/BookTrack?style=social)](https://github.com/LitovPro/BookTrack/network/members)

</div>
