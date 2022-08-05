How To Set up Pet Shop API Project

1. copy the .env.example to .env file 

2. Now generate the public,private key pair for jwt using
      `bash key_generate.sh`

3. Run the following in root of the project directory
      `docker-compose up -d --build`

4. Now check the containers are up and ready by running
      `docker ps`
5. Now login to the database container of the project using following commands
    
    `docker exec -ti database sh` 

    `mysql -uroot -ppassword -hdatabase`

    and create the db `CREATE DATABASE pet_shop;`
   
    Now run `exit` two times.

6. Now you'll be in root directory again, use the following to login to the app container
    
    `docker exec -ti petshopapi_app_1 sh` 
    
    While you're inside the container run the following commands

    `chmod -R 777 /var/www/storage` <!-- run this command, if there's a permission error -->

    `composer install`

    `php artisan key:generate`

    `php artisan migrate`
    
    `php artisan db:seed`

8. To generate the api documentation run the following inside the app conatiner
    
    `php artisan l5-swagger:generate`

    Now you can access the Swagger UI in
   
    `http://localhost:8080/api/v1/documentation`

User details:

There will be an admin user:
Login credentials
email: admin@petshop.net
password: password

Normal users' email can be fetched form list all users end point and password for all users is `password`
