cp env.php.example env.php
mkdir logs
touch php_errors.log
cd docker
cp .env.example .env
# add hosts
docker-compose up -d