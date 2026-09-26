<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$active_group = 'default';
$query_builder = TRUE;
$active_record = TRUE;

$db['default'] = array(
    'dsn' => '',
    'hostname' => getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost',
    'port' => getenv('DB_PORT') ? getenv('DB_PORT') : 3306,
    'username' => getenv('DB_USER') ? getenv('DB_USER') : 'tameasy_user',
    'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'tameasy_user@123',
    'database' => getenv('DB_NAME') ? getenv('DB_NAME') : 'tameasy_billing_crm',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
