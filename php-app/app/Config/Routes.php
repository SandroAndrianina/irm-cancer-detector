<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/',         'Home::index');
$routes->get('api/scans', 'Api\Scan::index');
$routes->post('api/scans','Api\Scan::create');
$routes->post('api/logs', 'Api\Logs::create');