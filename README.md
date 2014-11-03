# README #

This is an example rest service for creating a custom integration with [www.zent.io](https://www.zent.io).

### Installation ###

* Clone this repo
* Install composer dependencies using below command.
```
#!bash

php composer.phar install
```

### Configuration ###
The data (i.e. basic auth info, customer data) used in this example service script can be configured by editing the yaml files inside the `data` folder.

### Run script ###

You can start this rest service script by using php's built-in webserver with the below command. But you'll probably want to use something like Apache2 or nginx in production.

```
#!bash

php -S localhost:8000
```

### Test drive ###

You can use the below `curl` commands to test drive this script.


```
#!bash

curl http://localhost:8000/customers  #=>  {"message":"Requires authentication"}
```

```
#!bash

curl -u zent-io-api-user http://localhost:8000/customers  # This command will prompt for a password.

```
