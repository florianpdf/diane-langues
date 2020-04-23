Diane Langues

Simple administrable onepage website

# Initialisation process

1. Clone project

2. Run `composer install`  
*Read more [here](https://getcomposer.org/)*   

3. Create a *.env.local* file and passed your own parameters, bellow the minimum information
```yaml
DATABASE_URL="mysql://your_id:your_password@127.0.0.1:3306/database_name"
APP_ENV=dev
```
*Read more [here](https://symfony.com/doc/current/configuration.html#configuration-based-on-environment-variables)*  

4. Create your and update your database
```shell
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migration:migrate
```
*Read more [here](https://symfony.com/doc/current/doctrine.html)*  

5. Manage assets by running yarn `yarn install` & `yarn encore dev` for dev or `yarn encore prod` for prod  
*Read more [here](https://symfony.com/doc/current/frontend.html)*  

6. Run following command to create a admin user and add the first configuration for the webstite `php bin/console app:initialize-config`

6. Run Symfony server with `symfony server:start`  
*Read more [here](https://symfony.com/doc/current/setup/symfony_server.html)* 

