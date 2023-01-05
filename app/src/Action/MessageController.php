<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

use \PDO;

use App\Classes\GereRooms;
use App\Classes\GereUsers;
use App\Classes\GereMessages;

final class MessageController
{
    private $logger;
    private $link;

    public function __construct( LoggerInterface $logger, PDO $link)
    {
        $this->logger = $logger;
        $this->link = $link;
    }

    public function getMessages(Request $request, Response $response, $args)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if($request->hasHeader('Authorization')) {
            $headers = $request->getHeader('Authorization');    
            $token = $headers[0];

            $users = new GereUsers($this->link);
            $user = $users->selecionaUserToken($token);

            $res = array();

            if(count($user) > 0) {
                $rooms = new GereRooms($this->link);
                if($rooms->roomExiste($args['rid'])) {
                    $messages = new GereMessages($this->link);
                    $res['err'] = 0;
                    $res['err_txt'] = "";
                    $res['messages'] = $messages->getRoomMessages($args['rid']);
                    foreach($res['messages'] as $m) {
                        $m_user = $users->selecionarUserID($m->u_id);
                        $m->u_name = $m_user[0]->getName();
                        $m->u_username = $m_user[0]->getUsername();
                        $m->u_avatar = $m_user[0]->getAvatar();
                    }
                } else {
                    $res['err'] = 1;
                    $res['err_txt'] = "Invalid RID";
                }
            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Invalid token";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Token not sent";
        }

        $this->logger->info("Endpoint para listar mensagens processado");
        return $response->withJson($res);
    }

    public function sendMessage(Request $request, Response $response, $args)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if($request->hasHeader('Authorization')) {
            $headers = $request->getHeader('Authorization');    
            $token = $headers[0];

            $users = new GereUsers($this->link);
            $user = $users->selecionaUserToken($token);

            $res = array();

            if(count($user) > 0) {
                if(isset($data['message'])) {
                    $rooms = new GereRooms($this->link);
                    if($rooms->roomExiste($args['rid'])) {
                        $messages = new GereMessages($this->link);
                        $messages->inserirMessage($data['message'], $args['rid'], $user[0]->getId());

                        $res['err'] = 0;
                        $res['err_txt'] = "";
                    } else {
                        $res['err'] = 1;
                        $res['err_txt'] = "Invalid RID";
                    }
                } else {
                    $res['err'] = 1;
                    $res['err_txt'] = "Data missing";
                }
            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Invalid token";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Token not sent";
        }

        $this->logger->info("Endpoint para adicionar mensagem processado");
        return $response->withJson($res);
    }

    public function deleteMessage(Request $request, Response $response, $args)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if($request->hasHeader('Authorization')) {
            $headers = $request->getHeader('Authorization');    
            $token = $headers[0];

            $users = new GereUsers($this->link);
            $user = $users->selecionaUserToken($token);

            $res = array();

            if(count($user) > 0) {
                $messages = new GereMessages($this->link);
                if($messages->mensagemExiste($args['rid'], $args['mid'], $user[0]->getId())) {
                    $messages->deleteMessage($args['mid']);
                    $res['err'] = 0;
                    $res['err_txt'] = "";
                } else {
                    $res['err'] = 1;
                    $res['err_txt'] = "The user is not the author of that message";
                }
                
            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Invalid token";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Token not sent";
        }

        $this->logger->info("Endpoint para apagar mensagem processado");
        return $response->withJson($res);
    }
}