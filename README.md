# WeatherApp
## How to use the application
#### Step 1. Install the [Docker](https://www.docker.com/). If you already have it installed then skip this step
#### Step 2. Go to the root directory of the project and run the command
```
    docker-compose build
```
next
```
   docker-compose up -d
```
#### Step 3. The application is ready to work at
```
    http://localhost:80
```
#### Step 4. If you need to perform PHPStan - PHP Static Analysis Tool. Go to the root directory of the project and run the command
```
    php vendor/phpstan/phpstan/phpstan analyse -l max src Tests
```
#### Step 5. If you need to perform PHPUnit tests. Go to the root directory of the project and run the command
```
    docker ps
```
Copy NAMES_CONTAINER
next
```
   docker exec NAMES_CONTAINER vendor/phpunit/phpunit/phpunit Tests sh

```
