# README #

This is an example service script for creating a custom integration with [www.zent.io](https://www.zent.io).

### Installation ###

* Clone this repo
* Install composer dependencies using below command.
```
#!bash

php composer.phar install
```

### Run script ###

You can run this service script by using php's built-in webserver with the below command. But you'll probably want to use something like Apache2 or nginx in production.

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

curl -u zent-io-api-user http://localhost:8000/customers  #  Enter pass123 in the password prompt

```