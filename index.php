<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

/*
 |------------------------
 | Bootstrap
 |-------------------------
*/

function run_basic_authentication()
{
    if ( ! isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] == 'b')
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');

        echo json_encode(['message' => 'Access denied']);
        exit;
    }
}


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



/*
 |------------------------------------
 | Define routes using a Silex app
 |------------------------------------
*/

$app = new Silex\Application();
$app['debug'] = true;

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
    ];


    return $app->json($data, 200);
});



/*
 |------------------------
 | And away we go !
 |-------------------------
*/

run_basic_authentication();

$app->run();