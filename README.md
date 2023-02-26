## Laravel Backend API Support

## These are the list of api endpoints supported in this project

### User Resource

* Read the list of user
* Read a specific user
* Create a new user
* Update the existing user
* Delete a specific user

### Todo Resource

* Read the list of todo under a user
* Read a specific todo under a user
* Create a new todo under a user
* Update the existing todo under a user
* Delete a specific todo under a user

### Article Resource

* Read the list of article under a user
* Read a specific article under a user
* Create a new article under a user
* Update the existing article under a user
* Delete a specific article under a user

### Comment Resource

* Read the list of comment under a todo item
* Read a specific comment under a todo item
* Create a new comment under a todo item
* Update the existing comment under a todo item
* Delete a specific comment under a todo item

#### API documentation

* V1 version of API is intended to provide testing platform without auth which is connected to sqlite
* V2 version of API is intended to provide production platform with auth which is connected to mysql [Work is still going on this one]

For documentation [Click here](https://documenter.getpostman.com/view/23446250/2s93CHuEuV#8f2026d4-c1b9-437c-ab07-dcf806861b5b)
  
## Project Setup

#### _To create laravel project_ 

```
composer create-project laravel/laravel <project-name>
```

#### _To connect with database fille the follwing in (.env)_ 

```
DB_CONNECTION=<db-connection/driver>
DB_HOST=<db-host>
DB_PORT=<db-port>
DB_DATABASE=<database-name>
DB_USERNAME=<db-username>
DB_PASSWORD=<db-password>

```

#### _To migrate table to db_ 

```
php artisan migrate    [In the project root directory]
```

#### _To generate fake data_ 

```
php artisan tinker
App\Models\Todo::factory(30)->create();   [Creates 30 fake random records]
```

#### _Sqlite Connection_ 

* According to the migrations the test tables would be created in the following env params 

```
TEST_DB_DATABASE
```

#### Now for some fun

* Definitely checkout my [CHANGELOG.md](https://github.com/deepak0023/laravel_backend/blob/master/CHANGELOG.md) for some fun and development process feel
