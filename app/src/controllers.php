<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var $app Silex\Application */


$app->get('/', function (Request $request) use ($app) {

    $extraHeaders = [];
    $redisConfig = ['host' => 'redis'];
    $redis = new \Predis\Client($redisConfig);
    $featureService = new \Features\Service($redis);

    // Switch
    $feature = new \Features\SwitchFeature('featureFacebook');

    // Condition
    $conditionFn = function() use ($request) { return (bool) $request->get('featureFacebook'); };
//    $feature = new \Features\ConditionFeature('featureFacebook', $conditionFn);

    // Percentage
//    $feature = new \Features\PercentageFeature('featureFacebook');
//    $feature->initialize($app['session']);
//    $extraHeaders = ['X-Feature-UserQuota' => $feature->getUserQuota()];

    $facebookLanding = $featureService->isActive($feature);

    $sender = new \Liuggio\StatsdClient\Sender\SocketSender(/*'localhost', 8126, 'udp'*/);
    $client  = new \Liuggio\StatsdClient\StatsdClient($sender);
    $factory = new \Liuggio\StatsdClient\Factory\StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

    $statsd = new \Liuggio\StatsdClient\Service\StatsdService($client, $factory);

    $statsdKey = sprintf('stats.web.registration.%s.count', $facebookLanding ? 'social' : 'master');
    $statsd->increment($statsdKey);

    $app['featureFacebook'] = $facebookLanding;

    return new Response(
        $app['twig']->render('index.html.twig', []), 200,
        ['X-Feature' => $facebookLanding ? 'feature-facebook' : 'master'] + $extraHeaders
    );

})->bind('homepage');

$app->get('/registration/facebook', function () use ($app) {
    return $app['twig']->render('landing/facebook.html.twig', []);
})->bind('landing.facebook');

$app->get('/registration/credentials', function () use ($app) {
    return $app['twig']->render('landing/credentials.html.twig', []);
})->bind('landing.credentials');
