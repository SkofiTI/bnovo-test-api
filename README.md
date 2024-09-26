# Микросервис управления гостями

## Описание проекта

Микросервис на PHP, предоставляющий API для управления записями гостей. Сервис поддерживает операции CRUD (создание, чтение, обновление, удаление) для сущности "Гость". Данные хранятся в базе данных MySQL. Проект использует фреймворк Laravel и упакован в контейнер Docker для удобства развертывания.

### Основные возможности:
- **CRUD-операции** для гостей
- **Валидация данных** (имя, фамилия, телефон, email, страна)
- **Определение страны по номеру телефона** (например, `+7` для России)
- **Отладочные заголовки** (`X-Debug-Time` и `X-Debug-Memory`) в каждом ответе API
- **Docker-окружение** для быстрого запуска

---

## Системные требования

- Docker
- Docker Compose
- PHP 8.2+
- MySQL 8.0+

---

## Инструкция по запуску

Для запуска микросервиса выполните следующие шаги:

### 1. Склонируйте репозиторий

```bash
git clone https://github.com/SkofiTI/bnovo-test-api.git
cd bnovo-test-api
```

### 2. Создайте группу Docker

```bash
sudo groupadd docker
```

### 3. Добавьте текущего пользователя в группу Docker

```bash
sudo usermod -aG docker $USER
```

### 4. Запустите контейнеры

```bash
docker-compose up --build -d
```

### 5. Установите зависимости Composer

```bash
docker-compose exec app composer install
```

### 6. Настройте файл окружения `.env`

```bash
docker-compose exec app cp .env.example .env
```

Не забудьте проверить и при необходимости настроить параметры базы данных в файле `.env`. По умолчанию, пароль к БД установлен как `root`, а сервер запускается на порту `8000`.

### 7. Выполните миграции и начальное заполнение базы данных

```bash
docker-compose exec app php artisan migrate --seed
```

### 8. Сгенерируйте ключ приложения

```bash
docker-compose exec app php artisan key:generate
```

После выполнения этих шагов приложение должно быть доступно по адресу `http://localhost`.

---

## API Эндпоинты

### Базовый URL:
```
http://localhost:8000/api
```

### Формат запросов и ответов:
- Content-Type: `application/json`
- Все запросы и ответы в формате JSON.

---

### Эндпоинты для управления гостями

#### 1. **GET /guests**
Получить список всех гостей.

**Пример ответа:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "second_name": "Doe",
      "email": "john@example.com",
      "phone": "+123456789",
      "country": "USA"
    }
  ],
  "message": null
}
```

#### 2. **POST /guests**
Создать нового гостя.

**Пример тела запроса:**
```json
{
  "first_name": "John",
  "second_name": "Doe",
  "email": "john@example.com",
  "phone": "+123456789",
  "country": "USA"
}
```

**Пример ответа:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "second_name": "Doe",
    "email": "john@example.com",
    "phone": "+123456789",
    "country": "USA"
  },
  "message": "Guest Create Successful"
}
```

#### 3. **GET /guests/{id}**
Получить данные конкретного гостя по его идентификатору.

**Пример ответа:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "second_name": "Doe",
    "email": "john@example.com",
    "phone": "+123456789",
    "country": "USA"
  }
}
```

#### 4. **PUT /guests/{id}**
Обновить информацию о госте.

**Пример тела запроса:**
```json
{
  "first_name": "Jane",
  "second_name": "Doe",
  "email": "jane@example.com",
  "phone": "+987654321",
  "country": "Canada"
}
```

**Пример ответа:**
```json
{
  "success": true,
}
```

#### 5. **DELETE /guests/{id}**
Удалить гостя по его идентификатору.

**Пример ответа:**
```json
{
  "success": true,
}
```

---

## Правила валидации

- **first_name**: Обязательное поле, строка, максимум 255 символов
- **second_name**: Обязательное поле, строка, максимум 255 символов
- **email**: Обязательное поле, уникальное, формат email, максимум 255 символов
- **phone**: Обязательное поле, уникальное, должно быть валидным номером телефона, максимум 15 символов
- **country**: Необязательно, если не указана — определяется по коду телефона

---

## Отладочные заголовки

- `X-Debug-Time`: Время выполнения запроса в миллисекундах
- `X-Debug-Memory`: Память, использованная в процессе запроса (в КБ)

Эти заголовки автоматически добавляются в каждый ответ сервера.

---

## Примеры запросов cURL

### Получение всех гостей:
```bash
curl -X GET http://localhost:8000/api/guests
```

### Получение конкретного гостя:
```bash
curl -X GET http://localhost:8000/api/guests/1
```

### Создание нового гостя:
```bash
curl -X POST http://localhost:8000/api/guests \
-H "Content-Type: application/json" \
-d '{
  "first_name": "John",
  "second_name": "Doe",
  "email": "john@example.com",
  "phone": "+123456789",
  "country": "USA"
}'
```

### Обновление гостя:
```bash
curl -X PUT http://localhost:8000/api/guests/1 \
-H "Content-Type: application/json" \
-d '{
  "first_name": "Jane",
  "second_name": "Doe",
  "email": "jane@example.com",
  "phone": "+987654321",
  "country": "Canada"
}'
```

### Удаление гостя:
```bash
curl -X DELETE http://localhost:8000/api/guests/1
```

---

## Docker-контейнеры

### `app`

Основной контейнер, содержащий Laravel-приложение.

### `webserver`

Контейнер Nginx, в котором хранятся данные веб-сервера.

### `db`

Контейнер MySQL, в котором хранятся данные гостей.

---

## Технические детали

- **Фреймворк**: Laravel 11.x
- **База данных**: MySQL 8.0
- **ORM**: Eloquent
- **Контейнеризация**: Docker
- **Документация API**: В этом файле README

---