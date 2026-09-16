<?php

declare(strict_types = 1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class User {

    private $container;

    /* constructor receives container instance */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* csrf protection */
        $csrfProtection = $this->container->get('csrf');
        $csrf = array();
        $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
        $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
        $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
        $csrf["value"] = $request->getAttribute($csrf["valueKey"]); 

        /* method */
        $method = $request->getMethod();
        
        if($method == "GET"){

            /* page menu */
            $page = new \Models\Page($this->container->get("pdo"));
            $pageTree =  $page->getFamilyTree();

            /* gallery menu */
            $gallery = new \Models\Gallery($this->container->get("pdo"));
            $galleryTree =  $gallery->getFamilyTree(); 

            /* view */
            $view = Twig::fromRequest($request);
            
            return $view->render($response, 'login.html.twig', [
                'csrf' => $csrf,
                'pageTree' => $pageTree,
                'galleryTree' => $galleryTree,
            ]);

        }
        
        if($method == "POST"){

            $params = (array)$request->getParsedBody();

            $user = new \Models\User($this->container->get("pdo"));
            $authorization = $user->authorization($params);

            if( $authorization ){

                /* good authorization */
                $newHash = $user->updateHash($authorization);
                /* update session variable */
                $_SESSION['user_temp_hash'] = $newHash;

                return $response->withHeader('Location', '/admin')->withStatus(302);; 

            }else{

                /* bad authorization */
                return $response->withHeader('Location', '/login')->withStatus(302);; 
            }
        }

    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        if( isset($_SESSION['user_temp_hash']) ){
        
            unset($_SESSION['user_temp_hash']);

        }
    
        return $response->withHeader('Location', '/')->withStatus(302);;

    } 

    public function list(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $user = new \Models\User($this->container->get("pdo"));
        $list = $user->getAll();

        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/user-list.html.twig', [
            'users' => $list,
            'message' => \Controllers\Utility::getMessageFromStash(),
        ]);
            
    }  

    public function add(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* csrf protection */
        $csrfProtection = $this->container->get('csrf');
        $csrf = array();
        $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
        $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
        $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
        $csrf["value"] = $request->getAttribute($csrf["valueKey"]); 

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/user-edit.html.twig', [
            'csrf' => $csrf,
        ]);
            
    }
    
    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* csrf protection */
        $csrfProtection = $this->container->get('csrf');
        $csrf = array();
        $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
        $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
        $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
        $csrf["value"] = $request->getAttribute($csrf["valueKey"]); 

        /* user */
        $user = new \Models\User($this->container->get("pdo"));
        $userId = (int)$args['id'];
        $userInfo = $user->getById($userId);

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/user-edit.html.twig', [
            'csrf' => $csrf,
            'user' => $userInfo,
        ]);
            
    }  

    public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $user = new \Models\User($this->container->get("pdo"));

        $params = (array)$request->getParsedBody();

        $params["login"] = trim($params["login"]);

        if( empty($params["login"]) || $params["login"] == "" ){

            $params["login"] = "new-user";

        }

        if( empty($params["password"]) || $params["password"] == "" ){

            $params["password"] = \Controllers\Utility::generateRandomString(10);

        }        

        if( empty($params["id"]) ){
            
            /* add new user */
            $add = $user->add($params);
            \Controllers\Utility::putMessageIntoStash("added, " . $params["login"] . ", " . $params["password"]);

        }else{

            /* edit user */
            $params["id"] = (int)$params["id"];

            $edit = $user->edit($params);
            \Controllers\Utility::putMessageIntoStash("edited, " . $params["login"] . ", " . $params["password"]);

        }    

        return $response->withHeader('Location', '/admin/user')->withStatus(302);;  
            
    } 
    
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $user = new \Models\User($this->container->get("pdo"));
        $userId = (int)$args['id'];

        /* check amount of users */
        $amount = $user->getAll();

        if( count($amount) > 1 ){

           $user->delete($userId); 
           \Controllers\Utility::putMessageIntoStash("deleted");

        }else{

            \Controllers\Utility::putMessageIntoStash("We cant delete the last user");

        }

        return $response->withHeader('Location', '/admin/user')->withStatus(302);; 
            
    }      

}