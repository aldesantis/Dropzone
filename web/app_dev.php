<?php

/*
 * This file is part of the Dropzone package.
 * 
 * (c) Alessandro Desantis <desa.alessandro@ŋmail.com>
 * 
 * The full copyright and license information is contained in
 * the LICENSE file which is distributed with the source code.
 */

if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not authorized to access this file.');
}

define('DEBUG', true);

$app = require_once __DIR__ . '/../app/application.php';
$app->run();
