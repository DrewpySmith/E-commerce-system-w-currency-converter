<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ProductController::index');
// Auth
$routes->get('/login', 'AuthController::index');
$routes->post('/login/auth', 'AuthController::auth');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register/store', 'AuthController::store');

// Cart
$routes->get('/cart', 'CartController::index');
$routes->post('/cart/add', 'CartController::add');
$routes->get('/cart/remove/(:num)', 'CartController::remove/$1');


// Auth Routes
$routes->get('/login', 'AuthController::index');
$routes->post('/login/auth', 'AuthController::auth');
$routes->get('/logout', 'AuthController::logout');

// CRUD Routes (Protected ideally)
$routes->get('/product/create', 'ProductController::create');
$routes->post('/product/store', 'ProductController::store');



<<<<<<< HEAD
=======
// Admin Routes
$routes->group('admin', static function ($routes) {
    // Dashboard
    $routes->get('/', 'Admin\\AdminController::index');

    // Employees
    $routes->get('employees', 'Admin\\EmployeesController::index');
    $routes->get('employees/create', 'Admin\\EmployeesController::create');
    $routes->post('employees/store', 'Admin\\EmployeesController::store');
    $routes->get('employees/edit/(:num)', 'Admin\\EmployeesController::edit/$1');
    $routes->post('employees/update/(:num)', 'Admin\\EmployeesController::update/$1');
    $routes->get('employees/delete/(:num)', 'Admin\\EmployeesController::delete/$1');

    // Products (admin)
    $routes->get('products', 'Admin\\ProductsController::index');
    $routes->get('products/create', 'Admin\\ProductsController::create');
    $routes->post('products/store', 'Admin\\ProductsController::store');
    $routes->get('products/edit/(:num)', 'Admin\\ProductsController::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\\ProductsController::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\\ProductsController::delete/$1');
});
// Checkout Routes
$routes->get('checkout', 'CheckoutController::index');
$routes->post('checkout/place', 'CheckoutController::place');
$routes->get('checkout/success', 'CheckoutController::success');
>>>>>>> 12593c2 (my commit)
