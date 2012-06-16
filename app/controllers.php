<?php

/*
 * This file is part of the Dropzone package.
 * 
 * (c) Alessandro Desantis <desa.alessandro@ŋmail.com>
 * 
 * The full copyright and license information is contained in
 * the LICENSE file which is distributed with the source code.
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Upload form
$app->get('/', function () use ($app) {
    return $app['twig']->render('home.html.twig');
})->bind('homepage');

// AJAX upload handler
$app->post('/upload', function (Request $request) use ($app) {
    $file = $request->files->get('file');
    $size = $file->getSize();

    if ($size > ($app['config']['files']['max_size'] * 1024 * 1024)) {
        return $app->json(array(
            'type'   => 'error',
            'status' => "Maximum file size is {$app['config']['max_file_size']} MB.",
        ));
    }

    if (count($parts = explode('.', $file->getClientOriginalName())) > 1) {
        $ext = '.' . $parts[count($parts) - 1];
    } else {
        $ext = '';
    }
    
    $name    = basename($file->getClientOriginalName(), $ext);
    $newName = "{$name}-" . time() . $ext;

    try {
        $file->move($app['config']['files']['directory'], $newName);
        $path = "{$app['config']['files']['directory']}/{$newName}";

        if (!is_file($path)) {
            throw new Exception();
        }

        chmod($path, 0777);
    } catch (Exception $e) {
        return $app->json(array(
            'type'   => 'error',
            'status' => 'Could not upload the file.',
        ));
    }

    $ip = $request->getClientIp();
    $name = $file->getClientOriginalName();
    $url = $app['url_generator']->generate('download', array(
        'hash' => base64_encode($newName),
    ), true);

    $app['monolog']->addInfo("User {$ip} uploaded file {$name} ({$size} bytes) ({$url}).");

    $body = <<<EOF
A new file has been uploaded by the following IP: {$ip}.
The file's name is {$name}, and its size is {$size} bytes.
You can download it at the following URL: {$url}.
EOF;

    $message = \Swift_Message::newInstance()
        ->setSubject('New file uploaded')
        ->setFrom(array($app['config']['emails']['from_address']))
        ->setTo(array($app['config']['emails']['to_address']))
        ->setBody($body)
    ;

    if (!$app['mailer']->send($message)) {
        return $app->json(array(
            'type'   => 'error',
            'status' => 'Could not upload the file.',
        ));
    }

    return $app->json(array(
        'type'   => 'success',
        'status' => 'The file has been uploaded successfully!',
    ));
})->bind('upload');

// File download
$app->get('/download/{hash}', function ($hash, Request $request) use ($app) {
    if (!$request->server->has('PHP_AUTH_USER')) {
        return new Response('', 403, array(
            'WWW-Authenticate' => 'Basic realm="Dropzone"',
        ));
    }

    $username = $request->server->get('PHP_AUTH_USER');
    $password = sha1($request->server->get('PHP_AUTH_PW'));

    if (!isset($app['config']['users'][$username]) || $password !== $app['config']['users'][$username]) {
        $app->abort(401);
    }

    $fname = base64_decode($hash);

    if (!$fname || !is_file($path = "{$app['config']['files']['directory']}/{$fname}")) {
        $app->abort(404);
    }

    return new Response(file_get_contents($path), 200, array(
        'Content-Type'        => 'application/octet-stream',
        'Content-Length'      => filesize($path),
        'Content-Disposition' => 'attachment; filename='. urlencode($fname),
    ));
})->bind('download');
