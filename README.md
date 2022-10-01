Tec_see
=============
In this repo, I have created an API for Tec-see as an assessment
The system has two types of users (user, admin), each one has its own functions, like the following

* Admin(Supervisor)
    * Create/Update/Remove/Get a project
    * Create/Update/Remove/Get a task
    * Assign an employee for a task
    * Remove an employee from a task

* User(Employee)
    * Submit a task
    * View his (submitted/pending/all) tasks
    * View his projects
    * Add a new stadium.
    * View match details
    * View vacant/reserved seats for each match.

## Table of contents
- [API Documentation](#api-documentation)
- [Database scheme](#database-scheme)
- [Stack](#stack)
- [Development and support](#development-and-support)
- [Authors](#authors)

## API Documentation
You can see the API documentation by just clicking [here](https://sofyanmahmoud0000.github.io/tec_see/public/docs/)

And you can get the postman collection for the API from [here](https://github.com/SofyanMahmoud0000/tec_see/blob/master/public/docs/collection.json)

## Database scheme

![Database schema](https://github.com/sofyanmahmoud0000/tec_see/blob/master/public/ReadmeImages/scheme.png)

## Unit testing
This app is provided with unit testing covers some test cases in login and signup units, and in the feature hope to add solid test cases cover all the units.
You can run the test cases by the command
```bash
php artisan test --testsuite=Unit
```

The output will show the passed/failed test cases like that
![Unit testing output](https://github.com/sofyanmahmoud0000/tec_see/blob/master/public/ReadmeImages/unit_testing.png)

## Feature work
 - Add more and solid test cases in unit testing
 - Dockerize the app

## Stack 
* [PHP](https://www.php.net/) - Language used
* [Laravel](https://laravel.com/) - Framework used
* [MySQL](https://dev.mysql.com/doc/refman/8.0/en/what-is-mysql.html) - database management system

## Development and support 
If you have any questions on how to use this APIs, or have any idea for future development, 
don't hesitate to send me an e-mail to sofyan1020@gmail.com.


## Authors
* [Sofyan Mahmoud](https://github.com/sofyanmahmoud0000) - Software development engineer