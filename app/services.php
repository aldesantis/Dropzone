<?php

/*
 * This file is part of the Dropzone package.
 * 
 * (c) Alessandro Desantis <desa.alessandro@ŋmail.com>
 * 
 * The full copyright and license information is contained in
 * the LICENSE file which is distributed with the source code.
 */

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'    => __DIR__ . '/views',
    'twig.options' => array(
        'debug' => $app['debug'],
        'cache' => __DIR__ . '/cache',
    ),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addGlobal('web_root', rtrim($app['config']['web_root'], '/'));

    return $twig;
}));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/logs/' . ($app['debug'] ? 'dev' : 'prod') . '.log',
    'monolog.level'   => $app['debug'] ? Monolog\Logger::DEBUG : Monolog\Logger::INFO,
    'monolog.name'    => 'Dropzone',
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.class_path' => __DIR__ . '/../vendor/swiftmailer/swiftmailer/lib/classes',
    'swiftmailer.options'    => $app['config']['swiftmailer'],
));
