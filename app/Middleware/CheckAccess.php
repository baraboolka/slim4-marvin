<?php

declare(strict_types = 1);

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Container\ContainerInterface;

class CheckAccess implements MiddlewareInterface {
    
    private $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function process(Request $request, RequestHandler $handler): Response
    {

        $response = $handler->handle($request);

        if( isset($_SESSION['user_temp_hash']) ){

            $user = new \Models\User($this->container->get("pdo"));
            $checkAccess = $user->checkAccess($_SESSION['user_temp_hash']);

            if( $checkAccess ){
                /* user has access for the admin panel */
                return $response;

            }
            
        }

        /* user has no access for the admin panel */
        return $response->withHeader('Location', '/login')->withStatus(302);;

    }
}

