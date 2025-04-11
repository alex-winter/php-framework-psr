docker-compose down -v
docker network prune -f
docker-compose up -d

docker-compose run --rm dependencies

docker-compose exec app ./vendor/bin/doctrine-migrations migrate --no-interaction