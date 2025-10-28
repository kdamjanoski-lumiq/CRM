<?php

/**
 * Automotive Plugin Routes
 * 
 * Define custom routes for the automotive plugin
 */

// Main automotive routes
$routes->get('automotive', 'Automotive::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive/(:any)', 'Automotive::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive/(:any)', 'Automotive::$1', ['namespace' => 'Plugins\Automotive\Controllers']);

// Trade-ins routes
$routes->get('automotive_trade_ins', 'Automotive_trade_ins::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive_trade_ins/(:any)', 'Automotive_trade_ins::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive_trade_ins/(:any)', 'Automotive_trade_ins::$1', ['namespace' => 'Plugins\Automotive\Controllers']);

// Deposits routes
$routes->get('automotive_deposits', 'Automotive_deposits::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive_deposits/(:any)', 'Automotive_deposits::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive_deposits/(:any)', 'Automotive_deposits::$1', ['namespace' => 'Plugins\Automotive\Controllers']);

// Floor stock routes
$routes->get('automotive_floor_stock', 'Automotive_floor_stock::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive_floor_stock/(:any)', 'Automotive_floor_stock::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive_floor_stock/(:any)', 'Automotive_floor_stock::$1', ['namespace' => 'Plugins\Automotive\Controllers']);

// Service routes
$routes->get('automotive_service', 'Automotive_service::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive_service/(:any)', 'Automotive_service::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive_service/(:any)', 'Automotive_service::$1', ['namespace' => 'Plugins\Automotive\Controllers']);

// Parts routes
$routes->get('automotive_parts', 'Automotive_parts::index', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->get('automotive_parts/(:any)', 'Automotive_parts::$1', ['namespace' => 'Plugins\Automotive\Controllers']);
$routes->post('automotive_parts/(:any)', 'Automotive_parts::$1', ['namespace' => 'Plugins\Automotive\Controllers']);