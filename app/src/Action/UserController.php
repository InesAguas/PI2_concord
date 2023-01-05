<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

use \PDO;

use App\Classes\GereUsers;

final class UserController
{
    private $logger;
    private $link;

    public function __construct( LoggerInterface $logger, PDO $link)
    {
        $this->logger = $logger;
        $this->link = $link;
    }

    public function addUser(Request $request, Response $response, $args)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if(isset($data['name']) && isset($data['username']) && isset($data['pass']) && isset($data['avatar'])) {
            $users = new GereUsers($this->link);
            $user = $users->selecionarUser($data['username']);
            if(count($user) > 0) {
                $res['err'] = 1;
                $res['err_txt'] = "Username already exists";
            } else {
                $users->inserirUser($data['name'], $data['username'], $data['pass'], $data['avatar'], bin2hex(random_bytes(8)));
                $res['err'] = 0;
                $res['err_txt'] = "";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Data missing";
        }

        $this->logger->info("Endpoint para adicionar utilizador processado");
        return $response->withJson($res);
    }

    public function login(Request $request, Response $response)
    {
        
        $json = $request->getBody();
        $data = json_decode($json, true);
        $res = array();
        
        //utilizar apenas o username
        if(isset($data['username']) && isset($data['pass'])) {
            $username = $data['username'];
            $pass = $data['pass'];

            $users = new GereUsers($this->link);
            $user = $users->selecionarUser($username);

            if(count($user) > 0) {
                if(password_verify($pass, $user[0]->getPass())) {

                    $res['err'] = 0;
                    $res['err_txt'] = '';
                    $res['user']['u_id'] = $user[0]->getId();
                    $res['user']['u_username'] = $user[0]->getUsername();
                    $res['user']['u_name'] = $user[0]->getName();
                    $res['user']['u_avatar'] = $user[0]->getAvatar();
                    $res['user']['u_token'] = bin2hex(random_bytes(8));

                    $users->updateToken($res['user']['u_token'], $res['user']['u_id']);
                } else {
                    $res['err'] = 1;
                    $res['err_txt'] = "Wrong username or password";
                }
            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Wrong username or password";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Username or password missing";
        }
        
        $this->logger->info("Endpoint de login processado");
        return $response->withJson($res);
    }

    public function editUser(Request $request, Response $response)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if($request->hasHeader('Authorization')) {
            $headers = $request->getHeader('Authorization');    
            $token = $headers[0];

            $users = new GereUsers($this->link);
            $user = $users->selecionaUserToken($token);

            if(count($user) > 0) {
                if(isset($data['name']) && isset($data['username']) && isset($data['pass']) && isset($data['avatar'])) {
                    if($users->editarUser($data['name'], $data['username'], $data['pass'], $data['avatar'], $user[0]->getId())) {
                        $res['err'] = 0;
                        $res['err_txt'] = "";
                    } else {
                        $res['err'] = 1;
                        $res['err_txt'] = "Database error";
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

        $this->logger->info("Endpoint para editar utilizador processado");
        return $response->withJson($res);
    }

    public function logout(Request $request, Response $response)
    {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $res = array();

        if($request->hasHeader('Authorization')) {
            $headers = $request->getHeader('Authorization');    
            $token = $headers[0];

            $users = new GereUsers($this->link);
            $user = $users->selecionaUserToken($token);

            if(count($user) > 0) {
                //update do token
                $users->updateToken(bin2hex(random_bytes(8)), $user[0]->getId());

                $res['err'] = 0;
                $res['err_txt'] = "";
            } else {
                $res['err'] = 1;
                $res['err_txt'] = "Invalid token";
            }
        } else {
            $res['err'] = 1;
            $res['err_txt'] = "Token not sent";
        }

        $this->logger->info("Endpoint de logout processado");
        return $response->withJson($res);
    }
}