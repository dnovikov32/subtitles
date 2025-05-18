# Subtitles


## Configuration
- PHP 8.2

## Local development
Copy environment file:
```bash
cp .env.dist .env
```

Create `docker-compose.yml` symlink:
```bash
ln -s docker-compose.local.yml docker-compose.yml
```

Add `.env` variable `SITE_HOST` value to `/etc/hosts`
```text
127.0.1.1	subtitles.test
```

Build application:
```bash
make build
```

Start application:
```bash
make start
```

After start, application is available at http://subtitles.test/


## Laravel CLI
```bash 
docker exec -it subtitles-php bash
composer global require laravel/installer
php ~/.composer/vendor/laravel/installer/bin/laravel new app
```


