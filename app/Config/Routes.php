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



