<?php

// Routes

$app->post('/concord/user/add', 'App\Action\UserController:addUser')
    ->setName('useradd');  

$app->patch('/concord/user/login', 'App\Action\UserController:login')
    ->setName('userlogin');

$app->put('/concord/user/edit', 'App\Action\UserController:editUser')
    ->setName('useredit');

$app->patch('/concord/user/logout', 'App\Action\UserController:logout')
    ->setName('userlogout');

$app->get('/concord/rooms', 'App\Action\RoomController:getRooms')
    ->setName('rooms');

$app->post('/concord/rooms/add','App\Action\RoomController:addRoom')
    ->setName('roomadd');

$app->post('/concord/messages/{rid}', 'App\Action\MessageController:sendMessage')
    ->setName('sendmessage');

$app->get('/concord/messages/{rid}', 'App\Action\MessageController:getMessages')
    ->setName('getmessages');

$app->delete('/concord/messages/{rid}/{mid}', 'App\Action\MessageController:deleteMessage')
    ->setName('deletemessages');
    
// Não tirar esta rota...
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($request, $response, $args) {
   return $response->withStatus(404);
});