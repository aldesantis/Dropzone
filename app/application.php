<?php

/*
 * This file is part of the Dropzone package.
 * 
 * (c) Alessandro Desantis <desa.alessandro@ŋmail.com>
 * 
 * The full copyright and license information is contained in
 * the LICENSE file which is distributed with the source code.
 */

use Silex\Application;

require_once __DIR__ . '/autoload.php';

$app = new Application();
$app['debug'] = DEBUG;
$app['config'] = require_once __DIR__ . '/config.php';

$app->error(function () use ($app) {
    if ($app['debug']) {
        return;
    }
    
    return $app->json(array(
        'type'   => 'error',
        'status' => 'An internal error occurred.',
    ));
});

require_once __DIR__ . '/services.php';
require_once __DIR__ . '/controllers.php';

return $app;
