### Путь к папке scripts

В .env APP_SCRIPTS_PATH=/var/www/adm/storage/app/scripts 

Если не задано, то будет storage/app/scripts

### Colors

php artisan db:seed --class=ColorsTableSeeder

команда заполняет таблицу Colors, при этом учитывает, что некоторые цвета уже могут быть в таблице