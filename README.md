# BookTrack - Система управления книгами и авторами

Веб-приложение для управления каталогом книг и авторов с системой подписок и SMS-уведомлений.

## Описание

BookTrack - это система управления книгами и авторами, которая позволяет:

- Просматривать каталог книг и авторов
- Подписываться на уведомления о новых книгах любимых авторов
- Получать SMS-уведомления при выходе новых книг
- Просматривать отчёты по популярности авторов
- Управлять контентом (для авторизованных пользователей)

## Технический стек

- **PHP 8.2+**
- **Yii2 Framework** (basic template)
- **MySQL 8.0+**
- **Bootstrap 5** (для UI)
- **Guzzle HTTP** (для SMS API)
- **Codeception** (для тестирования)
- **PHPStan** (статический анализ)
- **PHPCS** (проверка стиля кода)

## Требования

- PHP 8.2 или выше
- MySQL 8.0 или выше
- Composer
- Веб-сервер (Apache/Nginx) или встроенный сервер PHP

## Установка

### 1. Клонирование проекта

```bash
git clone <repository-url> booktrack
cd booktrack
```

### 2. Установка зависимостей

```bash
composer install
```

### 3. Настройка окружения

Скопируйте файл конфигурации окружения:

```bash
cp env.example .env
```

Отредактируйте файл `.env`:

```env
# Database Configuration
DB_DSN=mysql:host=localhost;dbname=booktrack
DB_USER=root
DB_PASS=your_password

# SMS Configuration
SMSPILOT_API_KEY=эмулятор

# Application Configuration
YII_DEBUG=true
YII_ENV=dev
```

### 4. Настройка базы данных

Создайте базу данных MySQL:

```sql
CREATE DATABASE booktrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Применение миграций

```bash
php yii migrate
```

### 6. Настройка веб-сервера

#### Встроенный сервер PHP (для разработки)

```bash
php -S localhost:8080 -t web
```

#### Apache

Создайте виртуальный хост, указывающий на папку `web/`:

```apache
<VirtualHost *:80>
    ServerName booktrack.local
    DocumentRoot /path/to/booktrack/web
    
    <Directory /path/to/booktrack/web>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name booktrack.local;
    root /path/to/booktrack/web;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Тестовые данные

После применения миграций в системе будет создан тестовый пользователь:

- **Логин:** `admin`
- **Пароль:** `admin123`

⚠️ **Важно:** Обязательно смените пароль в продакшене!

## Команды

### Миграции

```bash
# Применить миграции
php yii migrate

# Откатить последнюю миграцию
php yii migrate/down

# Создать новую миграцию
php yii migrate/create migration_name
```

### Тестирование

```bash
# Запуск всех тестов
composer test

# Запуск unit тестов
vendor/bin/codecept run unit

# Запуск тестов с покрытием
vendor/bin/codecept run unit --coverage
```

### Качество кода

```bash
# Проверка стиля кода (PHPCS)
composer cs

# Статический анализ (PHPStan)
composer stan

# Исправление стиля кода
vendor/bin/phpcbf --standard=PSR12 .
```

## Роуты

### Публичные страницы (доступны всем)

- `/` - Главная страница
- `/books` - Список книг
- `/books/{id}` - Просмотр книги
- `/authors` - Список авторов
- `/authors/{id}` - Просмотр автора
- `/report` - Отчёт ТОП-10 авторов
- `/subscription` - Подписка на автора

### Административные страницы (требуют авторизации)

- `/books/create` - Создание книги
- `/books/{id}/update` - Редактирование книги
- `/books/{id}/delete` - Удаление книги
- `/authors/create` - Создание автора
- `/authors/{id}/update` - Редактирование автора
- `/authors/{id}/delete` - Удаление автора

## Проверка сценариев

### 1. Подписка на автора

1. Перейдите на страницу любого автора
2. В форме подписки введите номер телефона (например: `+7-999-123-45-67`)
3. Нажмите "Подписаться"
4. Должно появиться сообщение об успешной подписке

### 2. Создание книги и SMS-уведомления

1. Войдите в систему как `admin` / `admin123`
2. Перейдите в "Книги" → "Добавить книгу"
3. Заполните форму и выберите автора
4. Сохраните книгу
5. Проверьте таблицу `sms_log` в базе данных - должны появиться записи с `status = 'EMULATED'`

### 3. Отчёт ТОП-10 авторов

1. Перейдите в "Отчёт"
2. Выберите год из выпадающего списка
3. Просмотрите таблицу с ТОП-10 авторами по количеству книг

### 4. Проверка SMS-лога

Выполните SQL-запрос для просмотра логов SMS:

```sql
SELECT * FROM sms_log ORDER BY created_at DESC LIMIT 10;
```

## Структура проекта

```text
booktrack/
├── config/                 # Конфигурационные файлы
├── controllers/            # Контроллеры
├── models/                 # Модели данных
├── services/               # Бизнес-логика
├── views/                  # Представления
├── migrations/             # Миграции БД
├── tests/                  # Тесты
├── web/                    # Веб-корень
│   ├── assets/            # Ресурсы
│   ├── css/               # Стили
│   └── uploads/           # Загруженные файлы
└── runtime/               # Временные файлы
```

## API SMS

Система использует SMSPilot API для отправки SMS. В режиме эмуляции (по умолчанию) SMS не отправляются, но логируются в базу данных.

Для настройки реальной отправки SMS:

1. Получите API ключ от SMSPilot
2. Установите его в `.env`: `SMSPILOT_API_KEY=your_real_api_key`
3. Перезапустите приложение

## Расширения

### Подключение очередей (необязательно)

Для асинхронной отправки SMS можно использовать Yii2 Queue:

```bash
composer require yiisoft/yii2-queue
```

### Подключение Redis (необязательно)

Для кеширования можно использовать Redis:

```bash
composer require yiisoft/yii2-redis
```

### Elasticsearch для поиска (планируется)

Для улучшения поиска книг планируется интеграция с Elasticsearch:

```bash
composer require yiisoft/yii2-elasticsearch
```

## Чек-лист приёмки

- [x] Миграции применяются на чистой БД MySQL 8
- [x] CRUD авторов и книг работает, обложки грузятся
- [x] Подписка гостя на автора по телефону работает; повторная подписка не создаёт дубль
- [x] При создании книги всем подписчикам соответствующих авторов пишется запись в `sms_log` с `status=EMULATED`
- [x] Отчёт ТОП-10 за год показывает корректные данные и кешируется
- [x] Доступы: guest видит публичные страницы, user может редактировать
- [x] README покрывает установку и проверку сценариев

## Поддержка

При возникновении проблем:

1. Проверьте логи в `runtime/logs/`
2. Убедитесь, что все миграции применены
3. Проверьте права доступа к папкам `runtime/` и `web/uploads/`
4. Убедитесь, что PHP и MySQL соответствуют требованиям

## Лицензия

BSD-3-Clause
