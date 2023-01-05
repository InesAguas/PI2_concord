<?php
// to enable CORS - Cross Origin Resource Sharing
$mySimpleCORSMiddleware = function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, PATCH, POST, PUT, DELETE, OPTIONS');
};
$app->add($mySimpleCORSMiddleware);