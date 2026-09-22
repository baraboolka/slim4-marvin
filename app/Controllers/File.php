<?php

declare(strict_types = 1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class File {

    private $container;
    const FILE_DIRECTORY = "public_html" . DIRECTORY_SEPARATOR . "files"; 

    /* constructor receives container instance */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function browse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        /* csrf protection */
        $csrfProtection = $this->container->get('csrf');
        $csrf = array();
        $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
        $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
        $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
        $csrf["value"] = $request->getAttribute($csrf["valueKey"]); 

        /* GET params */
        $params = $request->getQueryParams();
     
        if( !empty($params["directory"]) ){
            //$currentDirectory = "/" . $params["directory"];
            $currentDirectory = $params["directory"];
        }else{
            $currentDirectory = "";
        }

        /* pathway */
        $fileDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::FILE_DIRECTORY);
        
        $i = 0;

        foreach( explode("/", $currentDirectory) as $item ){
            if( $item != "" ){
                $summaryAlias .= "/" . $item;
                $pathway[$i]["title"] = $item;
                $pathway[$i]["alias"] = $summaryAlias;
                $i++;
            }
        }

        /* files|folders */
        $files = array(); 
        $folders = array();
        
        $items = array_diff(scandir(realpath($fileDirectory . $currentDirectory)), array('.', '..'));
        
        $num = 0;
        foreach ( $items as $item ){    

            if( is_dir($fileDirectory . $currentDirectory . "/" . $item) ){
                /* folders */
                if( preg_match('/^[a-zA-Z0-9_-]+$/', $item) ){
                    /* show folders only with latin names for safety */
                    $folders[$num]["title"] = $item;
                    $folders[$num]["alias"] = $currentDirectory . "/" . $item; 

                }

            }else{
                /* files */
                $files[$num]["title"] = $item;
                $files[$num]["alias"] = $currentDirectory . "/" . $item;
            }

            $num++;

        } 

        //\Controllers\Utility::putMessageIntoStash("message!");

        /* view */
        $view = Twig::fromRequest($request);
        
        return $view->render($response, '/admin/file-browse.html.twig', [
            'csrf' => $csrf,
            'files' => $files,
            'folders' => $folders,
            'pathway' => $pathway,
            'currentDirectory' => $currentDirectory,
            'message' => \Controllers\Utility::getMessageFromStash(),
        ]);
            
    }

    public function createFolder(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $parsedBody = $request->getParsedBody();
        $newfolder = $parsedBody["file-manager-folder-name"];
        $currentDirectory = $parsedBody["current-directory"];
        
        /* pathway */
        $fileDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::FILE_DIRECTORY);

        if( empty($newfolder) || !preg_match('/^[a-zA-Z0-9_-]+$/', $newfolder) ) {
            /* check valid folder name */        
            \Controllers\Utility::putMessageIntoStash("incorrect folder name, please use only Latin characters and numbers.");

            return $response
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->withStatus(400);

        }
        
        $realPath = realpath($fileDirectory . DIRECTORY_SEPARATOR . $currentDirectory);

        if( $realPath && strpos($realPath, $fileDirectory) === 0 ){
            
            if( is_dir($realPath . DIRECTORY_SEPARATOR . $newfolder) ){
                /* folder with this name already exists */
                \Controllers\Utility::putMessageIntoStash("folder already exists.");

                return $response
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->withStatus(400);

            }else{
                /* folder does not exist yet */
                mkdir($realPath . DIRECTORY_SEPARATOR . $newfolder);
                \Controllers\Utility::putMessageIntoStash("new folder was created: " . $newfolder);

                return $response
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->withStatus(200);

            }

        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);        
  
    }

    public function deleteFolder(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $parsedBody = $request->getParsedBody();
        $deletedfolder = $parsedBody["deleted-folder"];
        $currentDirectory = $parsedBody["current-directory"];
        
        /* pathway */
        $fileDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::FILE_DIRECTORY);

        if( empty($deletedfolder) || !preg_match('/^[a-zA-Z0-9_-]+$/', $deletedfolder) ) {
            /* check valid folder name */        
            \Controllers\Utility::putMessageIntoStash("incorrect folder name, please use only Latin characters and numbers.");

            return $response
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->withStatus(400);

        }

        $deletedPath = realpath($fileDirectory . DIRECTORY_SEPARATOR . $currentDirectory . DIRECTORY_SEPARATOR . $deletedfolder);

        if( $deletedPath && strpos($deletedPath, $fileDirectory) === 0 ){
            
            $items = array_diff(scandir($deletedPath), array('.', '..'));

            if( !empty($items) ){
                /* there are sub-items here, we cant delete the folder */
                \Controllers\Utility::putMessageIntoStash("folder has subitems, please delete them first");
                
                return $response
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->withStatus(400);                

            }else{
                /* delete folder */
                rmdir($deletedPath);
                \Controllers\Utility::putMessageIntoStash("folder was deleted: " . $deletedfolder);

                return $response
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->withStatus(200);

            }

        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);        
  
    }

    public function loadFile(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        
        $parsedBody = $request->getParsedBody();
        $currentDirectory = $parsedBody["current-directory"];
 
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['file'] ?? null;

        if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            /* new file name */
            $originalName = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

            $newName = "file-" . \Controllers\Utility::generateRandomString(5) . "-" . date("m-d-y")  . "." . $extension;

            /* pathway */
            $fileDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::FILE_DIRECTORY);

            $realPath = realpath($fileDirectory . DIRECTORY_SEPARATOR . $currentDirectory);

            if( $realPath && strpos($realPath, $fileDirectory) === 0 ){ 
                /* load file */
                $uploadedFile->moveTo($realPath . DIRECTORY_SEPARATOR . $newName);
                \Controllers\Utility::putMessageIntoStash("new file was loaded: " . $newName);

                return $response
                    ->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->withStatus(200);

            }

        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
            
    }

    public function deleteFile(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        
        $parsedBody = $request->getParsedBody();
        $currentDirectory = $parsedBody["current-directory"];
        $deletedFile = $parsedBody["deleted-file"];
        //var_dump($parsedBody); 

        /* pathway */
        $fileDirectory = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . self::FILE_DIRECTORY);

        $deletedPath = realpath($fileDirectory . DIRECTORY_SEPARATOR . $currentDirectory . DIRECTORY_SEPARATOR . $deletedFile);

        if( $deletedPath && strpos($deletedPath, $fileDirectory) === 0 ){           

            /* delete file */
            unlink($deletedPath);
            \Controllers\Utility::putMessageIntoStash("file was deleted: " . $deletedFile);

            return $response
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->withStatus(200);

        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
            
    }
}