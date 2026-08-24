<?php

declare(strict_types = 1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class Page {

    private $container;

    //constructor receives container instance
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    
        /* empty page alias */
        if( empty($args['alias']) ){

            return $response
                ->withHeader('Location', '/404')
                ->withStatus(404);

        }

        /* csrf protection */
        $csrfProtection = $this->container->get('csrf');
        $csrf = array();
        $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
        $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
        $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
        $csrf["value"] = $request->getAttribute($csrf["valueKey"]);         

        /* page */
        $page = new \Models\Page($this->container->get("pdo"));
        $pageTree = $page->getFamilyTree();
        $pageAlias = $args['alias'];
        $pageInfo = $page->getByAlias($pageAlias);
        $pageChildren = $page->getByParent((int)$pageInfo["id"]);

        /* empty page content */
        if( !$pageInfo ){

            return $response
                ->withHeader('Location', '/404')
                ->withStatus(404);

        }

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/page.html.twig', [
            'csrf' => $csrf,
            'page' => $pageInfo,
            'pageTree' => $pageTree,
            'children' => $pageChildren,
        ]);

    }

    /*public function hello(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        // your code to access items in the container... $this->container->get('');

        print_r($this->container->get("testContainer"));
        print_r($this->container->get("testContainer2"));

        $page = new \Models\Page($this->container->get("pdo"));
        var_dump($page->getAll());
        var_dump($page->getById(12));
        var_dump($page->getByAlias('page 1 url'));
        var_dump($page->getByAlias('ddpage 1 url'));

        $response->getBody()->write("Hello from Controllers/Test->hello");

	    return $response;
   }*/

    public function list(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* page */
        $page = new \Models\Page($this->container->get("pdo"));
        $pageTree =  $page->getFamilyTree();
        
        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/page-list.html.twig', [
            'tree' => $pageTree,
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

        /* page */
        $page = new \Models\Page($this->container->get("pdo"));
        $pageTree =  $page->getFamilyTree();

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/page-edit.html.twig', [
            'csrf' => $csrf,
            'tree' => $pageTree,
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

        /* page */
        $page = new \Models\Page($this->container->get("pdo"));
        $pageId = (int)$args['id'];
        $pageInfo = $page->getById($pageId);
        $pageTree =  $page->getFamilyTree(0, (int)$pageInfo["parent"], $pageId, 0);

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/page-edit.html.twig', [
            'csrf' => $csrf,
            'page' => $pageInfo,
            'tree' => $pageTree,
        ]);
            
    }   

    public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

    /* page */
        $page = new \Models\Page($this->container->get("pdo"));

        $params = (array)$request->getParsedBody();

        $params["title"] = trim($params["title"]);

        if( empty($params["title"]) || $params["title"] == "" ){

            $params["title"] = "new-page-" . date('Y-m-d-H-i-s');

        }
        
        if( empty($params["alias"]) || $params["alias"] == "" ){

            $params["alias"] = $params["title"];

        }  

        /* unify the alias */
        if ( !preg_match('/^[a-zA-Z0-9_-]+$/', $params["alias"]) ) {

            \Controllers\Utility::putMessageIntoStash("incorrect URL, please use only Latin characters and numbers.");
            return $response
                ->withHeader('Location', '/admin/page')
                ->withStatus(302);; 

        }
        //$params["alias"] = \Controllers\Utility::clearSpecialSymbols($params["alias"]);
        //$params["alias"] = \Controllers\Utility::changeCyrillicSymbols($params["alias"]);

        //$params["parent] - устанавливаем родительскую категорию

        if( empty($params["id"]) ){
            
            /* add new page */
            if( $page->getByAlias($params["alias"]) ){

                /* check for uniqueness|double aliases */
                $params["alias"] = $params["alias"] . "-" . \Controllers\Utility::generateRandomString(5);

            }

            $add = $page->add($params);
            \Controllers\Utility::putMessageIntoStash("page wad added");

        }else{

            /* edit page */
            $params["id"] = (int)$params["id"];

            if( !$page->checkForDoubleAliases($params["alias"], $params["id"]) ){

                /* check for uniqueness|double aliases */
                $params["alias"] = $params["alias"] . "-" . \Controllers\Utility::generateRandomString(5);

            }            
            
            $edit = $page->edit($params);
            \Controllers\Utility::putMessageIntoStash("page was edited");

        }    

        return $response->withHeader('Location', '/admin/page')->withStatus(302);;  
            
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $page = new \Models\Page($this->container->get("pdo"));
        $pageId = (int)$args['id'];
        $pageInfo = $page->getById($pageId);

        $pageHasChildren = $page->getByParent((int)$pageInfo["id"]);

        if( !empty($pageHasChildren) ){
            //page has children
            \Controllers\Utility::putMessageIntoStash("page has sub-pages. Please, delete them first");

            return $response->withHeader('Location', '/admin/page')->withStatus(302);;             

        }

        $page->delete($pageId); 
        \Controllers\Utility::putMessageIntoStash("Page was deleted");

        return $response->withHeader('Location', '/admin/page')->withStatus(302);; 
            
    }

}