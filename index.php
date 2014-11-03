<?php

// require composer autoloader
require_once __DIR__.'/vendor/autoload.php';

// import namespaces
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

/*
 |-----------------------------------
 | Some example utility functions
 |-----------------------------------
*/

/**
 * @return array
 */
function find_customer($email, $phone)
{
    $result = array_filter(get_customers(), function($customer) use ($email, $phone)
    {
        if ($customer['email'] === $email && $customer['phone'] === $phone) {
            return $customer;
        }
    });

    if ( ! empty(array_values($result))) {
        return array_values($result)[0];
    }
}

/**
 * @return array
 */
function get_customers()
{
    return Yaml::parse(file_get_contents(__DIR__.'/data/customers.yml'));
}

/**
 * @return array
 */
function get_auth_data()
{
    return Yaml::parse(file_get_contents(__DIR__.'/data/auth.yml'));
}


/*
 |------------------------------------
 | Create Silex app
 |------------------------------------
*/

$app = new Silex\Application();
$app['debug'] = true;

/*
 |------------------------------------
 | Setup HTTP basic authentication
 |------------------------------------
*/

$app->before(function() use ($app)
{
    $data = get_auth_data();

    if ( ! isset($_SERVER['PHP_AUTH_USER']))
    {
        header('WWW-Authenticate: Basic realm="'.$data['basic_auth_realm'].'"');

        return $app->json(['message' => 'Requires authentication'], 401);
    }


    if ($data['basic_auth_username'] !== $_SERVER['PHP_AUTH_USER'] || $data['basic_auth_password'] !== $_SERVER['PHP_AUTH_PW'])
    {
        return $app->json(array('message' => 'Forbidden'), 403); // send forbidden if credentials are incorrect
    }
});

/*
 |------------------------------------
 | Define routes
 |------------------------------------
*/

/**
 * GET /
 */
$app->get('/', function() use ($app) {
    return $app->json(['message' => 'Not Found'], 404);
});


/**
 * GET /customers
 */
$app->get('/customers', function(Request $request) use ($app) {

    $email = $request->query->get('email');
    $phone = $request->query->get('phone');

    $customer = find_customer($email, $phone);

    if ( ! $customer) {
        return $app->json(['message' => 'Not Found'], 404);
    }

    $data = [
        'name' => $customer['name'],
        'email' => $customer['email'],
        'phone' => $customer['phone'],
        'custom_attributes' => $customer['custom_attributes']
    ];


    return $app->json($data, 200);
});



/*
 |------------------------
 | And away we go !
 |-------------------------
*/
$app->run();
