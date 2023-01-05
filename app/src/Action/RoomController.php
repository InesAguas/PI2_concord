<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

use \PDO;

use App\Classes\GereRooms;
use App\Classes\GereUsers;

final class RoomController
{
    private $logger;
    private $link;

    public function __construct( LoggerInterface $logger, PDO $link)
    {
        $this->logger = $logger;
        $this->link = $link;
    }

    public function getRooms(Request $request, Response $response)
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
                $res['err'] = 0;
                $res['err_txt'] = "";
                $res['rooms'] = $rooms->listarRooms();
                foreach($res['rooms'] as $r) {
                    $r_user = $users->selecionarUserID($r->u_id);
                    $r->u_username = $r_user[0]->getUsername();
                }

            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Invalid token";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Token not sent";
        }

        $this->logger->info("Endpoint para listar salas processado");
        return $response->withJson($res);
    }

    public function addRoom(Request $request, Response $response)
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
                if(isset($data['name'])) {
                    $rooms = new GereRooms($this->link);
                    $rooms->inserirRoom($data['name'], $user[0]->getId());
                    $res['err'] = 0;
                    $res['err_txt'] = "";
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

        $this->logger->info("Endpoint para adicionar sala processado");
        return $response->withJson($res);
    }
}