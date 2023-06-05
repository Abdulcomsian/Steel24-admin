  SETUP Project:
  
  1.Run git clone 'link project github'
  2.Run sudo chmod -R 777 storage/ bootstrap/cache/   or sudo chmod 775 -R storage
  3.Run composer install
  4.Run cp .env.example .env
  5.Run php artisan key:generate
  6.Run php artisan migrate
  
  7. php artisan config:clear
  8. php artisan config:cache
  9. php artisan route:clear
  10.php artisan route:cache
  
  Instead of 7,8,9,10 run 11.
  
  11.Run php artisan optimize:clear
  12.Run php artisan storage:link
  13.Run php artisan serve
