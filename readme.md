### Версия админки
 ver 1.8
### Инициализация админки

Создаем ключ:

php artisan key:generate

Создаем базу данных, указываем доступы в .env

Запускаем миграции и сидеры:

php artisan migrate --seed

(Можно отдельно миграции: php artisan migrate
а затем отдельно сидеры php artisan db:seed)

В результате созданы таблицы в базе 
и заполнены таблицы Colors, Devtypes, Menu, Settings

Далее создаем пользователя:

php artisan create:user

Указываем логин, пароль. Эти данные можно изменить в личном кабинете администратора в разделе Профиль

Если ошибка вида Сlass...Seeder не найден, то выполнить команду

composer dumpautoload

### Заполнение базы тестовыми данными

php artisan fake

Создается админ: логи admin, пароль 111111 и заполняются несколько таблиц (termostats, rooms, objects и др.)

Не заполняет таблицы Colors, Devtypes, Menu, Settings. Они должны быть заполнены до вызова этой команды. 
Например, с помощью команды php artisan db:seed

Чтобы сбросить текущее состояние для тестирования, можно выполнить две команды:

php artisan migrate:refresh --seed

php artisan fake

### Путь к папке scripts

В .env APP_SCRIPTS_PATH=/var/www/adm/storage/app/scripts 

Если не задано, то будет storage/app/scripts

### Colors

php artisan db:seed --class=ColorsTableSeeder

команда заполняет таблицу Colors, при этом учитывает, что некоторые цвета уже могут быть в таблице

### Devtypes

php artisan db:seed --class=DevtypesTableSeeder

команда заполняет таблицу Devtypes

### Menu

php artisan db:seed --class=MenuTableSeeder

команда заполняет таблицу Menu

### Settings

php artisan db:seed --class=SettingsTableSeeder

команда заполняет таблицу Settings
