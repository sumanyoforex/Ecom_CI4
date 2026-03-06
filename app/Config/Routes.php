<?php

// ============================================================
// Routes.php — All URL routes for the application
// ============================================================
// How routing works:
//   $routes->get('/path', 'Controller::method');
//   $routes->post('/path', 'Controller::method');
// ============================================================

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ------------------------------------------------------------------
// PUBLIC ROUTES (anyone can visit)
// ------------------------------------------------------------------
$routes->get('/',              'HomeController::index');
$routes->get('shop',           'ShopController::index');
$routes->get('shop/(:segment)','ShopController::detail/$1');  // product detail

// Auth pages
$routes->get('login',    'AuthController::loginForm');
$routes->post('login',   'AuthController::login');
$routes->get('register', 'AuthController::registerForm');
$routes->post('register','AuthController::register');
$routes->get('logout',   'AuthController::logout');

// Cart (works for guests AND logged-in users via session)
$routes->get('cart',           'CartController::index');
$routes->post('cart/add',      'CartController::add');
$routes->post('cart/update',   'CartController::update');
$routes->post('cart/remove',   'CartController::remove');

// ------------------------------------------------------------------
// PROTECTED ROUTES — requires JWT/session login
// ------------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('checkout',       'CheckoutController::index');
    $routes->post('checkout/place','CheckoutController::place');
    $routes->get('account',        'UserController::account');
    $routes->get('order/(:num)',   'UserController::orderDetail/$1');
});

// ------------------------------------------------------------------
// ADMIN ROUTES — requires admin session
// ------------------------------------------------------------------
$routes->get('admin/login',  'Admin\AuthController::loginForm');
$routes->post('admin/login', 'Admin\AuthController::login');
$routes->get('admin/logout', 'Admin\AuthController::logout');

$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/',                       'Admin\DashboardController::index');

    // Products
    $routes->get('products',               'Admin\ProductController::index');
    $routes->get('products/create',        'Admin\ProductController::create');
    $routes->post('products/store',        'Admin\ProductController::store');
    $routes->get('products/edit/(:num)',   'Admin\ProductController::edit/$1');
    $routes->post('products/update/(:num)','Admin\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\ProductController::delete/$1');

    // Categories
    $routes->get('categories',               'Admin\CategoryController::index');
    $routes->get('categories/create',        'Admin\CategoryController::create');
    $routes->post('categories/store',        'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)',   'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)','Admin\CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // Orders
    $routes->get('orders',                       'Admin\OrderController::index');
    $routes->get('orders/(:num)',                 'Admin\OrderController::detail/$1');
    $routes->post('orders/status/(:num)',         'Admin\OrderController::updateStatus/$1');

    // Users
    $routes->get('users', 'Admin\UserController::index');
});
