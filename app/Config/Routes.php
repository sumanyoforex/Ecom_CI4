<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public routes
$routes->get('/', 'HomeController::index');
$routes->get('landing', 'HomeController::index');
$routes->get('shop', 'ShopController::index');
$routes->get('shop/(:segment)', 'ShopController::detail/$1');
$routes->get('health', 'SystemController::health');
$routes->post('webhooks/payment', 'PaymentWebhookController::handle');

// Authentication routes
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('register', 'AuthController::registerForm');
$routes->post('register', 'AuthController::register');
$routes->get('forgot-password', 'AuthController::forgotPasswordForm');
$routes->post('forgot-password', 'AuthController::sendPasswordReset');
$routes->get('reset-password/(:segment)', 'AuthController::resetPasswordForm/$1');
$routes->post('reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->get('logout', 'AuthController::logout');

// Protected customer routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout/place', 'CheckoutController::place');
    $routes->post('checkout/apply-coupon', 'CheckoutController::applyCoupon');
    $routes->post('checkout/remove-coupon', 'CheckoutController::removeCoupon');

    $routes->get('account', 'UserController::account');
    $routes->get('order/(:num)', 'UserController::orderDetail/$1');
    $routes->post('order/cancel/(:num)', 'UserController::cancelOrder/$1');

    $routes->post('payment/mock-success/(:num)', 'PaymentController::mockSuccess/$1');

    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->post('cart/update', 'CartController::update');
    $routes->post('cart/remove', 'CartController::remove');
});

// Admin routes
$routes->get('admin/login', 'Admin\\AuthController::loginForm');
$routes->post('admin/login', 'Admin\\AuthController::login');
$routes->get('admin/logout', 'Admin\\AuthController::logout');

$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\\DashboardController::index');

    $routes->get('products', 'Admin\\ProductController::index');
    $routes->get('products/create', 'Admin\\ProductController::create');
    $routes->post('products/store', 'Admin\\ProductController::store');
    $routes->get('products/edit/(:num)', 'Admin\\ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\\ProductController::delete/$1');

    $routes->get('categories', 'Admin\\CategoryController::index');
    $routes->get('categories/create', 'Admin\\CategoryController::create');
    $routes->post('categories/store', 'Admin\\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\\CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\\CategoryController::delete/$1');

    $routes->get('orders', 'Admin\\OrderController::index');
    $routes->get('orders/(:num)', 'Admin\\OrderController::detail/$1');
    $routes->post('orders/status/(:num)', 'Admin\\OrderController::updateStatus/$1');

    $routes->get('users', 'Admin\\UserController::index');
    $routes->get('users/edit/(:num)', 'Admin\\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\\UserController::update/$1');
});
