# About

This project is intended for generating football matches and tournaments.  
All teams are divided into 2 division. Every team plays matches with each other. After that they go to playoff matches (
such as quarterfinals,semifinal etc).  
All football matches are generated randomly.  
Project is developed on Laravel framework version 6 using PHP 7.4 with MYSQL database

# Launching

First, you need to install necessary dependencies for this project. Run next command

```
composer install
npm install
```

After that you need specify environment variables. Create .env file in the root of project and copy all variables from
.env.example and specify variables for your database connection

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Then run next command in terminal to create migrations & seed your database

```
php artisan migrate
php artisan db:seed
```

After that when database is created and seeded you can run project using next commands

```
php artisan serve
npm run watch
```

# Routing

```
POST api/qualification/generate 

Allows generate random qualification  for random tournament. 
If qualification was generated api will return qualification generated earlier
```

```
GET api/qualification/tournament/{id} 

Will return all information about qualification tournament generated earlier.
Id parameter is id of tournament
```

```
POST api/playoff/tournament/{id}/generate

Allows generate playoffs for tournament
Id - Id of tournament. 
Will return information about quarterfinal, semifinal, final & third place games
```

```
GET api/playoff/tournament/{id}

Will return playoff information about tournament
```

```
GET api/team/division/{id}

Will return all teams of division
Id - id of division
```
