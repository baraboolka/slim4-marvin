<?php

declare(strict_types = 1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class Gallery {

    private $container;
    const GALLERY_DIRECTORY = "public_html" . DIRECTORY_SEPARATOR . "galleries"; 

    //constructor receives container instance
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function gallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* empty gallery alias */
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

        /* gallery */
        $gallery = new \Models\Gallery($this->container->get("pdo"));
        $galleryTree = $gallery->getFamilyTree();
        $galleryAlias = $args['alias'];
        $galleryInfo = $gallery->getByAlias($galleryAlias);
        $galleryChildren = $gallery->getByParent((int)$galleryInfo["id"]);
        $galleryImages = $gallery->getImages((int)$galleryInfo["id"]);

        /* empty gallery content */
        if( !$galleryInfo ){

            return $response
                ->withHeader('Location', '/404')
                ->withStatus(404);

        }

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/gallery.html.twig', [
            'csrf' => $csrf,
            'gallery' => $galleryInfo,
            'galleryTree' => $galleryTree,
            'images' => $galleryImages,
            'children' => $galleryChildren,
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

        /* gallery */
        $gallery = new \Models\Gallery($this->container->get("pdo"));
        $galleryTree = $gallery->getFamilyTree();
        
        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/gallery-list.html.twig', [
            'tree' => $galleryTree,
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

        /* gallery */
        $gallery = new \Models\Gallery($this->container->get("pdo"));
        $galleryTree = $gallery->getFamilyTree();

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/gallery-edit.html.twig', [
            'csrf' => $csrf,
            'tree' => $galleryTree,
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

        /* gallery */
        $gallery = new \Models\Gallery($this->container->get("pdo"));
        $galleryId = (int)$args['id'];
        $galleryInfo = $gallery->getById($galleryId);
        $galleryTree = $gallery->getFamilyTree(0, (int)$galleryInfo["parent"], $galleryId, 0);
        $galleryImages = $gallery->getImages($galleryId);

        if( !empty($galleryImages) ){

            foreach($galleryImages as $key => $value){

                if( $value["main"] > 0 ){

                    $galleryImages[$key]["checked"] = "checked";

                }else{

                    $galleryImages[$key]["checked"] = "";

                }

            }

        }

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/gallery-edit.html.twig', [
            'csrf' => $csrf,
            'gallery' => $galleryInfo,
            'tree' => $galleryTree,
            'images' => $galleryImages,
        ]);
            
    }   

    public function save(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* gallery */
        $gallery = new \Models\Gallery($this->container->get("pdo"));

        $params = (array)$request->getParsedBody();

        $params["title"] = trim($params["title"]);

        if( empty($params["title"]) || $params["title"] == "" ){

            $params["title"] = "new-gallery-" . date('Y-m-d-H-i-s');

        }
        
        if( empty($params["alias"]) || $params["alias"] == "" ){

            $params["alias"] = $params["title"];

        }  

        /* unify the alias */
        if ( !preg_match('/^[a-zA-Z0-9_-]+$/', $params["alias"]) ) {

            \Controllers\Utility::putMessageIntoStash("incorrect URL, please use only Latin characters and numbers.");
            return $response
                ->withHeader('Location', '/admin/gallery')
                ->withStatus(302);; 

        }

        if( empty($params["id"]) ){
            
            /* add new gallery */
            if( $gallery->getByAlias($params["alias"]) ){
                /* check for uniqueness|double aliases */
                $params["alias"] = $params["alias"] . "-" . \Controllers\Utility::generateRandomString(5);

            }

            $add = $gallery->add($params);
            $lastId = $add;
            \Controllers\Utility::putMessageIntoStash("gallery wad added");

        }else{

            /* edit gallery */
            $params["id"] = (int)$params["id"];

            if( !$gallery->checkForDoubleAliases($params["alias"], $params["id"]) ){
                /* check for uniqueness|double aliases */
                $params["alias"] = $params["alias"] . "-" . \Controllers\Utility::generateRandomString(5);

            }            
            
            $edit = $gallery->edit($params);
            $lastId = $edit;
            \Controllers\Utility::putMessageIntoStash("gallery was edited");

        }    

        /* edit images */
        $editImages = array();

        if( !empty($params["image_id"]) ){
            
            $i = 0;

            foreach( $params["image_id"] as $key => $value ){

                $editImages[$i]["id"] = $value;
                $i++;

            }
        }

        if( !empty($params["image_title"]) ){
            
            $i = 0;

            foreach( $params["image_title"] as $key => $value ){

                $editImages[$i]["title"] = $value;
                $i++;

            }
        }     
 
        if( !empty($params["image_main"]) ){
            
            for( $i = 0; $i < count($editImages); $i++ ){

                if( $editImages[$i]["id"] == $params["image_main"] ){

                    $editImages[$i]["main"] = 1;

                }else{

                    $editImages[$i]["main"] = 0;

                }

            }
        }    
        
        foreach( $editImages as $key => $value ){

            $gallery->editImage($value);

        }
        
        /* delete images */
        if( !empty($params["image_delete"]) ){
            
            foreach( $params["image_delete"] as $key => $value ){

                $imageId = (int)$value;

                $deleteImage = $gallery->deleteImage($imageId);
                //echo $deleteImage;

                $imageDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::GALLERY_DIRECTORY);
                
                if( file_exists($imageDirectory . DIRECTORY_SEPARATOR . $deleteImage) && is_file($imageDirectory . DIRECTORY_SEPARATOR . $deleteImage) ) {
                
                    unlink($imageDirectory . DIRECTORY_SEPARATOR . $deleteImage);

                }

            }
        }  

        /* add new images */
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['file'] ?? null;

        if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            /* new file name */
            $allowedExtensions = ["jpeg", "jpg", "png"]; 

            $originalName = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));

            if( in_array($extension, $allowedExtensions) ){

                $newName = "image-" . $lastId . "-" . \Controllers\Utility::generateRandomString(5) . "-" . date("m-d-y")  . "." . $extension;

                /* pathway */
                $imageDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::GALLERY_DIRECTORY);
                $uploadedFile->moveTo($imageDirectory . DIRECTORY_SEPARATOR . $newName);

                $newImage = array();
                $newImage["file"] = $newName;
                $newImage["parent"] = $lastId;

                $gallery->addImage($newImage);

            }          
        }

        return $response->withHeader('Location', '/admin/gallery/edit/' . $lastId)->withStatus(302);;  
            
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $gallery = new \Models\Gallery($this->container->get("pdo"));
        $galleryId = (int)$args['id'];
        $galleryInfo = $gallery->getById($galleryId);

        $galleryHasChildren = $gallery->getByParent((int)$galleryInfo["id"]);

        if( !empty($galleryHasChildren) ){
            //gallery has children
            \Controllers\Utility::putMessageIntoStash("gallery has sub-galleries. Please, delete them first");

            return $response->withHeader('Location', '/admin/gallery')->withStatus(302);;             

        }

        $galleryHasImages = $gallery->getImages($galleryId);

        if( !empty($galleryHasImages) ){
            //gallery has images
            \Controllers\Utility::putMessageIntoStash("gallery is not empty. Please, delete images first");

            return $response->withHeader('Location', '/admin/gallery')->withStatus(302);; 

        }

        $gallery->delete($galleryId); 
        \Controllers\Utility::putMessageIntoStash("gallery was deleted");

        return $response->withHeader('Location', '/admin/gallery')->withStatus(302);; 
            
    }

}