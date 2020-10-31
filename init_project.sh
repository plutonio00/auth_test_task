cp env.php.example env.php
mkdir logs
touch php_errors.log
sudo chmod 777 -R logs
sudo chmod 777 -R public/images
echo "127.0.0.1 auth_task.local" >> ~/hosts-copy
cd docker
cp .env.example .env
#docker-compose up -d