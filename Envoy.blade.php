@include('vendor/autoload.php')
@servers(['web' => 'deploy@127.0.0.1 -p 2222'])

@task('deploy', ['on' => 'web'])
    cd /var/www/
    git pull origin master
    composer install --no-interaction --quiet --prefer-dist --optimize-autoloader
    npm install
    npm run production
    php artisan migrate
@endtask
