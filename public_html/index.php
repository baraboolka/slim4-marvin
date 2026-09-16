<?php

declare(strict_types = 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;

use DI\Container;
use Psr\Container\ContainerInterface;

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use Slim\Routing\RouteCollectorProxy;

use Factories\Mailer;
use PHPMailer\PHPMailer\PHPMailer;

/* vendor autoload */
require __DIR__ . '/../vendor/autoload.php';

/* start session */
session_start();

/* create container using PHP-DI */
$container = new Container();

/* set container to create App with on AppFactory */
AppFactory::setContainer($container);

/* start the App */
$app = AppFactory::create();

/* create Twig */
$twig = Twig::create(__DIR__ . '/templates', ['cache' => false]);

/* add Twig-View Middleware */
$app->add(TwigMiddleware::create($app, $twig));

/* create PDO container for the database */
$container->set('pdo', function(){

    require __DIR__ . '/../config/database.php';
    
    $pdo = new PDO("mysql:host=" . $database["host"] . ";dbname=" . $database["database"], $database["user"], $database["password"]);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
    return $pdo;

});

/* register middleware for CSRF protection */
$responseFactory = $app->getResponseFactory();

$container->set('csrf', function () use ($responseFactory) {

    return new Guard($responseFactory);

});

/* register middleware for CSRF protectin to be executed on all routes */
$app->add('csrf');

/* create PHPmailer container */
$container->set(Mailer::class, function () {

    require __DIR__ . '/../config/mailer.php';

    return new Mailer($mailing);

});

/* add custom containers */
/*
$container->set('testContainer', function(){

    $settings = [1,2,3];
    return $settings;

});

$container->set('testContainer2', function(){

    $settings = [4,5,6];
    return $settings;

});

$container->set('testContainer3', function(){

    $settings = "mane name";
    return $settings;

});
*/

/* controllers & routing */

/* main page */
$app->get('/', function (Request $request, Response $response, $args) {

    /* page menu */
    $page = new \Models\Page($this->get("pdo"));
    $pageTree =  $page->getFamilyTree();

    /* gallery menu */
    $gallery = new \Models\Gallery($this->get("pdo"));
    $galleryTree =  $gallery->getFamilyTree();   
    
    /* seo */
    $seo = array();
    $seo["seo_title"] = 'Free Marwin based on slim 4';
    $seo["seo_keywords"] = 'Free Marwin based on slim 4';
    $seo["seo_description"] = 'Free Marwin based on slim 4';

    /* view */
    $view = Twig::fromRequest($request);
    
    return $view->render($response, '/main.html.twig', [
        'pageTree' => $pageTree,
        'galleryTree' => $galleryTree,
        'seo' => $seo,
    ]);

});

/* search */
$app->get('/search', function (Request $request, Response $response) {
    
    /* page menu */
    $page = new \Models\Page($this->get("pdo"));
    $pageTree =  $page->getFamilyTree();

    /* gallery menu */
    $gallery = new \Models\Gallery($this->get("pdo"));
    $galleryTree =  $gallery->getFamilyTree(); 

    /* searching */
    $params = $request->getQueryParams();

    if ( empty($params["word"]) ){

        $message = "the search word is empty";

    }else{

        $word = trim($params["word"]);
        $message = "searching by word: " . $word;

        /* page list */
        $pageSearch =  $page->search($word);

        /* gallery list */
        $gallerySearch =  $gallery->search($word); 

    }

    /* seo */
    $seo = array();
    $seo["seo_title"] = 'Searching...';
    $seo["seo_keywords"] = 'Searching...';
    $seo["seo_description"] = 'Searching...';
    
    /* view */
    $view = Twig::fromRequest($request);
    
    return $view->render($response, '/search.html.twig', [
        'pageTree' => $pageTree,
        'galleryTree' => $galleryTree,
        'message' => $message,
        'pages' => $pageSearch,
        'galleries' => $gallerySearch,
        'word' => $word,
        'seo' => $seo,
    ]);

});

/* load mail form */
$app->get('/load-mail-form', function (Request $request, Response $response) {

    /* csrf protection */
    $csrfProtection = $this->get('csrf');
    $csrf = array();
    $csrf["nameKey"] = $csrfProtection->getTokenNameKey();
    $csrf["valueKey"] = $csrfProtection->getTokenValueKey();
    $csrf["name"] = $request->getAttribute($csrf["nameKey"]);
    $csrf["value"] = $request->getAttribute($csrf["valueKey"]); 

    /* view */
    $view = Twig::fromRequest($request);

    return $view->render($response, '/mail-form.html.twig', [
        'csrf' => $csrf,
    ]);

});

/* send mail form */
$app->post('/send-mail-form', function (Request $request, Response $response) {

    $params = (array)$request->getParsedBody();

    $to = "some_email@gmail.com";/* !!! */
    $subject = "New message";


    /* simple field validation */
    if( !empty($params["author"]) ){

        $author = htmlspecialchars($params["author"], ENT_QUOTES, 'UTF-8');

    }
    
    if( !empty($params["text"]) ){

        $text = htmlspecialchars($params["text"], ENT_QUOTES, 'UTF-8');

    }

    $mailerFactory = $this->get(Mailer::class);

    try{

        /* call the factory as a function thanks to __invoke */
        $mailer = $mailerFactory([
            "to"  => $to,
            "subject" => $subject,
            "text" => "Author: " . $author . "; message: " . $text,            
        ]);

        $mailer->send();

    }catch(\Exception $e){
        
        echo $e;
        
        return $response
        ->withHeader('Content-Type', 'application/json;charset=utf-8')
        ->withStatus(400);

    }

    return $response
    ->withHeader('Content-Type', 'application/json;charset=utf-8')
    ->withStatus(200);
   
});

/* pages */
$app->get('/page[/{alias}]', Controllers\Page::class . ':page');

/* galleries */
$app->get('/gallery[/{alias}]', Controllers\Gallery::class . ':gallery');

/* authorization */
$app->get('/login', Controllers\User::class . ':login');

$app->post('/login', Controllers\User::class . ':login');

$app->get('/logout', Controllers\User::class . ':logout');

/* admin panel */
$app->group('/admin', function (RouteCollectorProxy $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        
        /* show admin/user module by default */
        return $response->withHeader('Location', '/admin/user')->withStatus(302);;

    });    

    /* admin/user */
    $group->group('/user', function (RouteCollectorProxy $group) {

        $group->get('', Controllers\User::class . ':list');  

        $group->get('/add', Controllers\User::class . ':add');
 
        $group->get('/edit[/{id}]', Controllers\User::class . ':edit');
        
        $group->post('/save', Controllers\User::class . ':save');
  
        $group->get('/delete[/{id}]', Controllers\User::class . ':delete');              

    });

    /* admin/page */
    $group->group('/page', function (RouteCollectorProxy $group) {

        $group->get('', Controllers\Page::class . ':list');  

        $group->get('/add', Controllers\Page::class . ':add');
 
        $group->get('/edit[/{id}]', Controllers\Page::class . ':edit');
        
        $group->post('/save', Controllers\Page::class . ':save');
  
        $group->get('/delete[/{id}]', Controllers\Page::class . ':delete');              

    });    

    /* admin/file */
    $group->group('/file', function (RouteCollectorProxy $group) {
        $group->get('/browse[/{directory:.*}]', Controllers\File::class . ':browse');

        $group->post('/create-folder', Controllers\File::class . ':createFolder');

        $group->post('/delete-folder', Controllers\File::class . ':deleteFolder');

        $group->post('/load-file', Controllers\File::class . ':loadFile');

        $group->post('/delete-file', Controllers\File::class . ':deleteFile');        
    });

    /* admin/galery */
    $group->group('/gallery', function (RouteCollectorProxy $group) {

        $group->get('', Controllers\Gallery::class . ':list');

        $group->get('/add', Controllers\Gallery::class . ':add');
 
        $group->get('/edit[/{id}]', Controllers\Gallery::class . ':edit');
        
        $group->post('/save', Controllers\Gallery::class . ':save');
  
        $group->get('/delete[/{id}]', Controllers\Gallery::class . ':delete');          

    });

})->add(new \Middleware\CheckAccess($container));

/* route for the not found 404 page */
$app->any('/404', function(Request $request, Response $response) {

    $view = Twig::fromRequest($request);

    return $view->render($response, '404.html.twig');

});

/* catch all other urls and redirect them to /404 */
$app->any('{route:.*}', function(Request $request, Response $response) {

    /*
    $view = Twig::fromRequest($request);

    return $view->render($response, '404.html.twig');
    */

    return $response
        ->withHeader('Location', '/404')
        ->withStatus(404);    

});

$app->run();