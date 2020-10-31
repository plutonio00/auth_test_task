cp env.php.example env.php

mkdir logs
touch logs/php_errors.log

echo "127.0.0.1 auth_task.local" | sudo tee --append /etc/hosts > /dev/null

cd docker
cp .env.example .env
docker-compose up -d
cd ..

sudo chmod 777 -R logs
sudo chmod 777 -R public/images