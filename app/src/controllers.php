<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var $app Silex\Application */


$app->get('/', function (Request $request) use ($app) {

    $facebookLanding = true;
    $app['featureFacebook'] = $facebookLanding;

    return new Response(
        $app['twig']->render('index.html.twig', []), 200,
        ['X-Feature' => $facebookLanding ? 'feature-facebook' : 'master']
    );

})->bind('homepage');

$app->get('/registration/facebook', function () use ($app) {
    return $app['twig']->render('landing/facebook.html.twig', []);
})->bind('landing.facebook');

$app->get('/registration/credentials', function () use ($app) {
    return $app['twig']->render('landing/credentials.html.twig', []);
})->bind('landing.credentials');
