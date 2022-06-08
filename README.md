# Запуск
0. Создать .env (Скопировать .env.example) и вставить данные о БД

### Через artisan:
1. Запустить через команду serve
```bash
php artisan serve
```

### Без artisan:
1. Переименовать server.php => index.php
2. Переместить public/.htaccess => .htaccess
3. (Если Unix) настроить доступ к папкам (https://stackoverflow.com/questions/30639174/how-to-set-up-file-permissions-for-laravel)
4. Запустить через `php -S localhost:8000` или веб-сервер

# Прогресс
- [X] User
	- [X] Model
	- [X] Register
	- [X] Login
	- [X] Profile
	- [X] Bookings
- [X] Airport
	- [X] Model
	- [X] Search
- [X] Flight
	- [X] Model
	- [X] Search
- [X] Booking
	- [X] Model
	- [X] Create
	- [X] Get
- [X] Seat
	- [X] Model
	- [X] Change seat
	- [X] Get
