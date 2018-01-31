<?php defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'hostname' => getenv('PRODUCTION_DB_HOST'),
    'username' => getenv('PRODUCTION_DB_USERNAME'),
    'password' => getenv('PRODUCTION_DB_PASSWORD'),
    'database' => getenv('PRODUCTION_DB_DATABASE'),
    'dbdriver' => getenv('PRODUCTION_DB_CONNECTION'),
);
