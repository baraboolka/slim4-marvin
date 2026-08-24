-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 24 2026 г., 03:26
-- Версия сервера: 5.6.43
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `slim4-marvin-db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `galleries`
--

CREATE TABLE `galleries` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_editor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_keywords` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `text`, `text_editor`, `alias`, `seo_title`, `seo_keywords`, `seo_description`, `parent`) VALUES
(3, 'gallery test 1', 'gallery test 1 text text', '<p>gallery test 1 text editor editorr<br></p>', 'new-gallery-test-1', 'seo gallery test 1 title', 'seo gallery test 1 keywords', 'gallery test 1 description', 0),
(6, 'new-gallery-2026-08-21-03-09-00', '', '', 'new-gallery-2026-08-21-03-09-00', '', '', '', 3),
(7, 'new-gallery-2026-08-21-03-09-27', '', '', 'new-gallery-2026-08-21-03-09-27', '', '', '', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `galleries_images`
--

CREATE TABLE `galleries_images` (
  `id` int(11) NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `main` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `galleries_images`
--

INSERT INTO `galleries_images` (`id`, `file`, `title`, `main`, `num`, `parent`) VALUES
(7, 'image-3-Ts96b-08-21-26.jpg', '', 1, 0, 3),
(10, 'image-7-yHm00-08-21-26.png', '111', 1, 0, 7),
(11, 'image-7-SKPZi-08-21-26.png', '222', 0, 0, 7),
(12, 'image-6-M4Au5-08-21-26.jpg', '', 1, 0, 6),
(13, 'image-6-fnNIZ-08-21-26.png', '', 0, 0, 6),
(14, 'image-3-pPY7s-08-21-26.png', '', 0, 0, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_editor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_keywords` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `title`, `text`, `text_editor`, `alias`, `seo_title`, `seo_keywords`, `seo_description`, `parent`) VALUES
(1, 'Slim 4 installation', '<p>\"Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.\"</p>\r\n\r\n<h2>Official site</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://www.slimframework.com/docs/v4/\r\n</code></pre>\r\n\r\n<h2>Source, github</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://github.com/slimphp/Slim\r\n</code></pre>\r\n\r\n<h2>Console command for composer</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer require slim/slim:\"4.*\"\r\n</code></pre>\r\n\r\n<h2>Add | chose PSR-7 implementation via console</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer require slim/psr7\r\n</code></pre>\r\n\r\n<h2>Create public folder and index.php file</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n/public_html/index.php\r\n</code></pre>\r\n\r\n<h2>Create .htaccess file for this public folder</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n\r\n# /public_html/.htaccess\r\n\r\nRewriteEngine On\r\nRewriteCond %{REQUEST_FILENAME} !-f\r\nRewriteCond %{REQUEST_FILENAME} !-d\r\nRewriteRule ^ index.php [QSA,L]\r\n</code></pre>\r\n\r\n<h2>A simple example of the index.php file content</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.phg */\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface as Response;\r\nuse Psr\\Http\\Message\\ServerRequestInterface as Request;\r\nuse Slim\\Factory\\AppFactory;\r\n\r\nrequire __DIR__ . \'/../vendor/autoload.php\';\r\n\r\n$app = AppFactory::create();\r\n\r\n$app->get(\'/\', function (Request $request, Response $response, $args) {\r\n    $response->getBody()->write(\"Hello world!\");\r\n    return $response;\r\n});\r\n\r\n$app->run();\r\n</code></pre>\r\n\r\n<h2>Add the \"autoload\" section for custom classes in the composer.json file</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n{\r\n    \"require\": {\r\n        \"slim/slim\": \"4.*\",\r\n        \"slim/psr7\": \"^0.6.0\",\r\n        \"php-di/php-di\": \"^6.0\",\r\n        \"slim/twig-view\": \"^3.0\",\r\n        \"slim/csrf\": \"^1.1\",\r\n        \"phpmailer/phpmailer\": \"^7.1\"\r\n    },\r\n	\"autoload\": {\r\n		\"psr-4\": {\r\n			\"\": \"app/\"\r\n		}\r\n	}\r\n}\r\n</code></pre>\r\n\r\n<h2>Update the composer.json file after \"autoload\" section changing</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer dump-autoload\r\n</code></pre>', '', 'slim-4', '', '', '', 0),
(2, 'PHP-DI', '<p>\"PHP-DI is a dependency injection container meant to be practical, powerful, and framework-agnostic.\"</p>\r\n\r\n<h2>Official site</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://php-di.org/\r\n</code></pre>\r\n\r\n<h2>Source, github</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://github.com/PHP-DI/PHP-DI\r\n</code></pre>\r\n\r\n<h2>Console command for composer</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer require php-di/php-di\r\n</code></pre>\r\n\r\n<h2>A simple usage in index.php file</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\n//...\r\n\r\nuse DI\\Container;\r\nuse Psr\\Container\\ContainerInterface;\r\n\r\n//...\r\n\r\n/* create container using PHP-DI */\r\n$container = new Container();\r\n\r\n/* set container to create App with on AppFactory */\r\nAppFactory::setContainer($container);\r\n\r\n/* start the App */\r\n$app = AppFactory::create();\r\n\r\n//...\r\n\r\n/* add custom container */\r\n$container->set(\'myService\', function () {\r\n    $settings = [...];\r\n    return new MyService($settings);\r\n});\r\n\r\n//...\r\n\r\n/* get that custom container */\r\n\r\n$app->get(\'/foo\', function (Request $request, Response $response, $args) {\r\n    $myService = $this->get(\'myService\');\r\n\r\n    // ...do something with $myService...\r\n\r\n    return $response;\r\n});\r\n\r\n//...\r\n</code></pre>\r\n\r\n<h2>Getting the container in custom class</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n\r\n&lt;?php\r\n\r\n/* /app/Controllers/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Controllers;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface;\r\nuse Psr\\Http\\Message\\ServerRequestInterface;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\nuse Slim\\Views\\Twig;\r\n\r\nclass Page {\r\n\r\n    private $container;\r\n\r\n    //constructor receives container instance\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {\r\n\r\n        /* csrf protection */\r\n        $csrfProtection = $this->container->get(\'csrf\');\r\n        $csrf = array();\r\n        $csrf[\"nameKey\"] = $csrfProtection->getTokenNameKey();\r\n        $csrf[\"valueKey\"] = $csrfProtection->getTokenValueKey();\r\n        $csrf[\"name\"] = $request->getAttribute($csrf[\"nameKey\"]);\r\n        $csrf[\"value\"] = $request->getAttribute($csrf[\"valueKey\"]);         \r\n\r\n        //...\r\n\r\n    }\r\n  \r\n    //...  \r\n\r\n}\r\n</code></pre>', '', 'php-di', '', '', '', 0),
(3, 'Twig', '<p>\"This is a Slim Framework view helper built on top of the Twig templating component. You can use this component to create and render templates in your Slim Framework application.\"</p>\r\n\r\n<h2>Official site</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://twig.symfony.com/\r\n</code></pre>\r\n\r\n<h2>Source, github</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://github.com/slimphp/Twig-View\r\n</code></pre>\r\n\r\n<h2>Console command for composer</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer require slim/twig-view\r\n</code></pre>\r\n\r\n<h2>A simple usage in index.php file</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n\r\n&lt;?php\r\n\r\n/* /public_html/index.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface as Response;\r\nuse Psr\\Http\\Message\\ServerRequestInterface as Request;\r\nuse Psr\\Http\\Server\\RequestHandlerInterface as RequestHandler;\r\nuse Slim\\Csrf\\Guard;\r\nuse Slim\\Factory\\AppFactory;\r\n\r\nuse DI\\Container;\r\nuse Psr\\Container\\ContainerInterface;\r\n\r\nuse Slim\\Views\\Twig;\r\nuse Slim\\Views\\TwigMiddleware;\r\n\r\n//...\r\n\r\n/* create container using PHP-DI */\r\n$container = new Container();\r\n\r\n/* set container to create App with on AppFactory */\r\nAppFactory::setContainer($container);\r\n\r\n/* start the App */\r\n$app = AppFactory::create();\r\n\r\n/* create Twig */\r\n$twig = Twig::create(__DIR__ . \'/templates\', [\'cache\' => false]);\r\n\r\n/* add Twig-View Middleware */\r\n$app->add(TwigMiddleware::create($app, $twig));\r\n\r\n//...\r\n\r\n/* main page */\r\n$app->get(\'/\', function (Request $request, Response $response, $args) {\r\n    \r\n    //...\r\n\r\n    /* view */\r\n    $view = Twig::fromRequest($request);\r\n    \r\n    return $view->render($response, \'/main.html.twig\', [\r\n        \'name\' => \'John\',\r\n        \'userTempHash\' => $_SESSION[\"user_temp_hash\"],\r\n\r\n        \'csrf\' => $csrf,\r\n        \'pageTree\' => $pageTree,\r\n        \'galleryTree\' => $galleryTree,\r\n    ]);\r\n\r\n});\r\n\r\n//...\r\n\r\n</code></pre>\r\n\r\n<h2>Using Twig in custom class</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n\r\n&lt;?php\r\n\r\n/* /app/Controllers/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Controllers;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface;\r\nuse Psr\\Http\\Message\\ServerRequestInterface;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\nuse Slim\\Views\\Twig;\r\n\r\nclass Page {\r\n\r\n    private $container;\r\n\r\n    //constructor receives container instance\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {\r\n    \r\n        //...\r\n\r\n        /* view */\r\n        $view = Twig::fromRequest($request);\r\n        \r\n        return $view->render($response, \'/page.html.twig\', [\r\n            \'csrf\' => $csrf,\r\n            \'page\' => $pageInfo,\r\n            \'pageTree\' => $pageTree,\r\n            \'children\' => $pageChildren,\r\n        ]);\r\n\r\n    }\r\n\r\n</code></pre>\r\n\r\n<h2>Some syntax features with twig: ...including</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n&lt;!-- index.twig -->\r\n{% include \'header.twig\' %}\r\n\r\n&lt;div id=\"main\">\r\n\r\n    {% include \'menu.twig\' %}\r\n\r\n    &lt;div id=\"content\">\r\n        {% block content %}\r\n            &lt;!-- content -->\r\n        {% endblock %}\r\n    &lt;/div>\r\n&lt;/div>\r\n\r\n{% include \'footer.twig\' %}\r\n\r\n</code></pre>\r\n\r\n<h2>...nesting</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n&lt;!-- main.twig -->\r\n{% extends \"index.twig\" %}\r\n\r\n{% block content %}\r\n	&lt;h2>Main page&lt;/h2>\r\n	&lt;p>Hello. This is a simple CRUD-system&lt;/p>\r\n{% endblock %}\r\n\r\n</code></pre>\r\n\r\n<h2>...cyrcle \"for\" and variables</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n&lt;ul>\r\n	{% for item in menu %}\r\n		&lt;li>\r\n			&lt;a href=\"/page/{{ item.alias }}\"> {{ item.title }} &lt;/a>\r\n		&lt;/li>\r\n	{% endfor %}\r\n&lt;/ul>\r\n\r\n</code></pre>\r\n\r\n<h2>...operator \"if-else\"</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n&lt;div id=\"header-user\">\r\n	{% if userName %}\r\n		&lt;a href=\"/admin/page\">{{ userName }}&lt;/a> | &lt;a href=\"/logout\">out&lt;/a>\r\n	{% else %}\r\n		guest | &lt;a href=\"/login\">login&lt;/a>\r\n	{% endif %}     \r\n&lt;/div>	\r\n\r\n</code></pre>\r\n\r\n<h2>...macrosing</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n&lt;!-- /admin/page-edit.html.twig -->\r\n\r\n&lt;div class=\"form-block\">\r\n	&lt;label for=\"parent\">parent&lt;/label>\r\n	&lt;select name=\"parent\">\r\n		&lt;option value=\"0\">root&lt;/option>\r\n\r\n		{% import \"admin/page-edit-parent.html.twig\" as macros %}\r\n		{{ macros.parentTree(tree) }}\r\n\r\n	&lt;/select>\r\n&lt;/div>\r\n\r\n&lt;!-- ... -->\r\n\r\n&lt;!-- /admin/page-edit-parent.html.twig -->\r\n\r\n{% macro parentTree(tree) %}\r\n\r\n	{% if tree %}\r\n	\r\n		{% for branch in tree %}\r\n\r\n            &lt;div class=\"list-row\">\r\n                &lt;div class=\"list-col list-title\">\r\n                    &lt;h3>{{ branch.title }}&lt;/h3>\r\n                    &lt;h4>/{{ branch.alias }}&lt;/h4>\r\n                &lt;/div>\r\n\r\n                {% if branch.sub %}\r\n                            \r\n                    {{ _self.parentTree(branch.sub) }}	\r\n                \r\n                {% endif %}\r\n\r\n			&lt;/div>\r\n            \r\n		{% endfor %}\r\n\r\n	{% else %}\r\n\r\n        &lt;div class=\"list-row\">\r\n			The list is empty\r\n		&lt;/div>\r\n        \r\n	{% endif %}\r\n	\r\n{% endmacro %}\r\n\r\n</code></pre>', '', 'twig', '', '', '', 0),
(4, 'Twig, strange things', '<h2>Strange thing 1:</h2>\r\n\r\n<p>If we want to output prism.js constructions (like &lt;pre>&lt;code...>) with a twig variable, we need to use the \"raw\" parameter</p>\r\n\r\n<pre><code class=\"language-php\">\r\n{{ page.text | raw }}\r\n\r\n</code></pre>\r\n\r\n<h3>Strange thing 2:</h3>\r\n\r\n<p>If we want to show html-tags in prism.js constructions, we need to change every \"<\" symbol with \"& lt;\"</p>\r\n\r\n<pre><code class=\"language-html\">\r\n\"<\" <=> \"& lt;\"\r\n\r\n</code></pre>\r\n', '', 'twig-strange-things', '', '', '', 3),
(5, 'Database', '<h2>Create configuration file</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /config/database.php */\r\n\r\n$database = array();\r\n\r\n$database[\"host\"]       = \"localhost\";\r\n$database[\"user\"]       = \"root\";\r\n$database[\"password\"]   = \"\";\r\n$database[\"database\"]   = \"slim4-marvin-db\";\r\n</code></pre>\r\n\r\n<h2>Add PDO container for the database in index.php</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface as Response;\r\nuse Psr\\Http\\Message\\ServerRequestInterface as Request;\r\nuse Psr\\Http\\Server\\RequestHandlerInterface as RequestHandler;\r\nuse Slim\\Csrf\\Guard;\r\nuse Slim\\Factory\\AppFactory;\r\n\r\nuse DI\\Container;\r\nuse Psr\\Container\\ContainerInterface;\r\n\r\nuse Slim\\Views\\Twig;\r\nuse Slim\\Views\\TwigMiddleware;\r\n\r\nuse Slim\\Routing\\RouteCollectorProxy;\r\n\r\nuse Factories\\Mailer;\r\nuse PHPMailer\\PHPMailer\\PHPMailer;\r\n\r\n/* vendor autoload */\r\nrequire __DIR__ . \'/../vendor/autoload.php\';\r\n\r\n/* start session */\r\nsession_start();\r\n\r\n/* create container using PHP-DI */\r\n$container = new Container();\r\n\r\n/* set container to create App with on AppFactory */\r\nAppFactory::setContainer($container);\r\n\r\n/* start the App */\r\n$app = AppFactory::create();\r\n\r\n//...\r\n\r\n/* create PDO container for the database */\r\n$container->set(\'pdo\', function(){\r\n\r\n    require __DIR__ . \'/../config/database.php\';\r\n    \r\n    $pdo = new PDO(\"mysql:host=\" . $database[\"host\"] . \";dbname=\" . $database[\"database\"], $database[\"user\"], $database[\"password\"]);\r\n\r\n    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\r\n    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\r\n	\r\n    return $pdo;\r\n\r\n});\r\n\r\n//...\r\n\r\n/* main page */\r\n$app->get(\'/\', function (Request $request, Response $response, $args) {\r\n    \r\n    //...\r\n\r\n    /* pass the PDO connection to the class */\r\n    $page = new \\Models\\Page($this->get(\"pdo\"));\r\n    $pageTree =  $page->getFamilyTree();\r\n\r\n    //...\r\n}\r\n\r\n//...\r\n\r\n</code></pre>\r\n\r\n<h2>Pass PDO connection in the custom class</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n\r\n&lt;?php\r\n\r\n/* /app/Controllers/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Controllers;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface;\r\nuse Psr\\Http\\Message\\ServerRequestInterface;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\nuse Slim\\Views\\Twig;\r\n\r\nclass Page {\r\n\r\n    private $container;\r\n\r\n    //constructor receives container instance\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {\r\n    \r\n        //...\r\n\r\n        /* page */\r\n        $page = new \\Models\\Page($this->container->get(\"pdo\"));\r\n        $pageTree = $page->getFamilyTree();\r\n\r\n        //...\r\n\r\n   }\r\n\r\n   //...\r\n\r\n}\r\n</code></pre>\r\n\r\n<h2>Use PDO in the custom model</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n\r\n&lt;?php\r\n\r\n/* /app/Models/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Models;\r\n\r\nuse PDO;\r\n\r\nclass Page {\r\n    \r\n    public $pdo;\r\n    public $tableName = \'pages\';\r\n\r\n    public function __construct(PDO $pdo) {\r\n\r\n        $this->pdo = $pdo;\r\n    \r\n    }\r\n\r\n    //...\r\n\r\n    public function getFamilyTree(int $parent = 0, int $selected = -1, int $exclude = -1, int $level = 0): ?array {\r\n\r\n	$sql = \"SELECT id, title, alias, parent FROM \" . $this->tableName . \" WHERE parent = :parent AND id != :exclude ORDER BY id ASC\";\r\n\r\n	$query = $this->pdo->prepare($sql);	\r\n	$query->bindParam(\':parent\', $parent);\r\n	$query->bindParam(\':exclude\', $exclude);\r\n	$query->execute();\r\n		\r\n	$result = $query->fetchAll();\r\n\r\n	$level++;\r\n\r\n	foreach($result as $key => $value){\r\n\r\n	    $result[$key][\"sub\"] = $this->getFamilyTree((int)$value[\"id\"], (int)$selected, (int)$exclude, (int)$level);\r\n	    $result[$key][\"level\"] = $level;\r\n\r\n	    if( $value[\"id\"] == $selected ){\r\n\r\n	        $result[$key][\"selected\"] = \"selected\";\r\n\r\n	    }else{\r\n\r\n	        $result[$key][\"selected\"] = \"\";\r\n\r\n	    }\r\n\r\n        }\r\n\r\n        return $result;\r\n		\r\n    }\r\n\r\n    //...\r\n\r\n}\r\n\r\n</code></pre>', '', 'database', '', '', '', 0),
(6, 'Database query examples', '<pre><code class=\"language-php\">\r\n\r\n//update\r\npublic function edit(array $value): int {\r\n\r\n    $sql = \"UPDATE \" . $this->tableName . \" SET \r\n    `title` = :title, \r\n    `text` = :text, \r\n    `text_editor` = :text_editor, \r\n    `alias` = :alias, \r\n    `seo_title` = :seo_title, \r\n    `seo_keywords` = :seo_keywords, \r\n    `seo_description` = :seo_description, \r\n    `parent` = :parent \r\n    WHERE `id` = :id\";\r\n\r\n    $query = $this->pdo->prepare($sql);\r\n    $query->bindParam(\':id\', $value[\"id\"]);	\r\n    $query->bindParam(\':title\', $value[\"title\"]);	\r\n    $query->bindParam(\':text\', $value[\"text\"]);\r\n    $query->bindParam(\':text_editor\', $value[\"text_editor\"]);\r\n    $query->bindParam(\':alias\', $value[\"alias\"]); \r\n    $query->bindParam(\':seo_title\', $value[\"seo_title\"]);   \r\n    $query->bindParam(\':seo_keywords\', $value[\"seo_keywords\"]);  \r\n    $query->bindParam(\':seo_description\', $value[\"seo_description\"]);     \r\n    $query->bindParam(\':parent\', $value[\"parent\"]);                                          \r\n    $query->execute();\r\n\r\n    return (int)$value[\"id\"];\r\n\r\n}\r\n\r\n//insert\r\npublic function add(array $value): int {\r\n\r\n    $sql = \"INSERT INTO \" . $this->tableName . \" \r\n    (`title`, `text`, `text_editor`, `alias`, `seo_title`, `seo_keywords`, `seo_description`, `parent`) \r\n    VALUES \r\n    (:title, :text, :text_editor, :alias, :seo_title, :seo_keywords, :seo_description, :parent)\";\r\n\r\n    $query = $this->pdo->prepare($sql);\r\n    $query->bindParam(\':title\', $value[\"title\"]);	\r\n    $query->bindParam(\':text\', $value[\"text\"]);\r\n    $query->bindParam(\':text_editor\', $value[\"text_editor\"]);\r\n    $query->bindParam(\':alias\', $value[\"alias\"]); \r\n    $query->bindParam(\':seo_title\', $value[\"seo_title\"]);   \r\n    $query->bindParam(\':seo_keywords\', $value[\"seo_keywords\"]);  \r\n    $query->bindParam(\':seo_description\', $value[\"seo_description\"]);     \r\n    $query->bindParam(\':parent\', $value[\"parent\"]);  \r\n    $query->execute();\r\n\r\n    $userId = $this->pdo->lastInsertId();\r\n\r\n    return (int)$userId;\r\n	\r\n}\r\n    \r\n//delete\r\npublic function delete(int $value): int {\r\n\r\n    $sql = \"DELETE FROM \" . $this->tableName . \" WHERE `id` = ?\";\r\n    \r\n    $query = $this->pdo->prepare($sql);\r\n    $query->execute(array($value)); \r\n\r\n    return $value;\r\n\r\n}  \r\n\r\n//select all\r\npublic function getAll(): ?array {\r\n\r\n    $sql = \"SELECT * FROM \" . $this->tableName . \" ORDER BY id ASC\";\r\n\r\n    $query = $this->pdo->prepare($sql);\r\n    $query->execute();\r\n		\r\n    $result = $query->fetchAll();\r\n\r\n    return is_array($result) ? $result : null;\r\n\r\n}\r\n\r\n//select one\r\npublic function getById(int $value): ?array {\r\n\r\n    $sql = \"SELECT * FROM \" . $this->tableName . \" WHERE id = ? \";\r\n\r\n    $query = $this->pdo->prepare($sql);\r\n    $query->execute(array($value));\r\n		\r\n    $result = $query->fetch();\r\n\r\n    return is_array($result) ? $result : null;\r\n\r\n}\r\n\r\n//search\r\npublic function search(string $value): ?array {\r\n\r\n    $word = \"%\" . $value . \"%\";\r\n\r\n    $sql = \"SELECT * FROM \" . $this->tableName . \" WHERE title like :word_title OR text like :word_text OR text_editor like :word_editor\";\r\n\r\n    $query = $this->pdo->prepare($sql);	\r\n    $query->bindParam(\':word_title\', $word);\r\n    $query->bindParam(\':word_text\', $word);\r\n    $query->bindParam(\':word_editor\', $word);\r\n    $query->execute();\r\n		\r\n    $result = $query->fetchAll();\r\n\r\n    return is_array($result) ? $result : null;\r\n\r\n}\r\n \r\n</code></pre>', '', 'database-query-examples', '', '', '', 5),
(10, 'Slim CSRF', '<p>\"Slim Framework CSRF protection PSR-15 middleware. CSRF protection applies to all unsafe HTTP requests (POST, PUT, DELETE, PATCH).\"</p>\r\n\r\n<h2>Source, github</h2>\r\n\r\n<pre><code class=\"language-html\">\r\nhttps://github.com/slimphp/Slim-Csrf\r\n</code></pre>\r\n\r\n<h2>Console command for composer</h2>\r\n\r\n<pre><code class=\"language-html\">\r\ncomposer require slim/csrf\r\n</code></pre>\r\n\r\n\r\n<h2>A simple usage in index.php file</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface as Response;\r\nuse Psr\\Http\\Message\\ServerRequestInterface as Request;\r\nuse Psr\\Http\\Server\\RequestHandlerInterface as RequestHandler;\r\nuse Slim\\Csrf\\Guard;\r\nuse Slim\\Factory\\AppFactory;\r\n\r\nuse DI\\Container;\r\nuse Psr\\Container\\ContainerInterface;\r\n\r\n//...\r\n\r\n/* vendor autoload */\r\nrequire __DIR__ . \'/../vendor/autoload.php\';\r\n\r\n/* start session */\r\nsession_start();\r\n\r\n/* create container using PHP-DI */\r\n$container = new Container();\r\n\r\n/* set container to create App with on AppFactory */\r\nAppFactory::setContainer($container);\r\n\r\n/* start the App */\r\n$app = AppFactory::create();\r\n\r\n//...\r\n\r\n/* register middleware for CSRF protection */\r\n$responseFactory = $app->getResponseFactory();\r\n\r\n$container->set(\'csrf\', function () use ($responseFactory) {\r\n\r\n    return new Guard($responseFactory);\r\n\r\n});\r\n\r\n/* register middleware for CSRF protectin to be executed on all routes */\r\n$app->add(\'csrf\');\r\n\r\n//...\r\n\r\n/* search */\r\n$app->get(\'/somepage\', function (Request $request, Response $response) {\r\n    \r\n    /* csrf protection */\r\n    $csrfProtection = $this->get(\'csrf\');\r\n    $csrf = array();\r\n    $csrf[\"nameKey\"] = $csrfProtection->getTokenNameKey();\r\n    $csrf[\"valueKey\"] = $csrfProtection->getTokenValueKey();\r\n    $csrf[\"name\"] = $request->getAttribute($csrf[\"nameKey\"]);\r\n    $csrf[\"value\"] = $request->getAttribute($csrf[\"valueKey\"]); \r\n\r\n    //...\r\n    \r\n    /* view */\r\n    $view = Twig::fromRequest($request);\r\n    \r\n    return $view->render($response, \'/somepage.html.twig\', [\r\n        \'csrf\' => $csrf,\r\n        \'message\' => $message,\r\n        \'pages\' => $pageSearch,\r\n        \'galleries\' => $gallerySearch,\r\n        \'word\' => $word,\r\n    ]);\r\n\r\n});\r\n\r\n</code></pre>\r\n\r\n<h2>Usage CSFR in custom class</h2>\r\n\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /app/Controllers/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Controllers;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface;\r\nuse Psr\\Http\\Message\\ServerRequestInterface;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\nuse Slim\\Views\\Twig;\r\n\r\nclass Page {\r\n\r\n    private $container;\r\n\r\n    //constructor receives container instance\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {\r\n   \r\n        //...\r\n\r\n        /* csrf protection */\r\n        $csrfProtection = $this->container->get(\'csrf\');\r\n        $csrf = array();\r\n        $csrf[\"nameKey\"] = $csrfProtection->getTokenNameKey();\r\n        $csrf[\"valueKey\"] = $csrfProtection->getTokenValueKey();\r\n        $csrf[\"name\"] = $request->getAttribute($csrf[\"nameKey\"]);\r\n        $csrf[\"value\"] = $request->getAttribute($csrf[\"valueKey\"]);         \r\n\r\n        //...\r\n\r\n        /* view */\r\n        $view = Twig::fromRequest($request);\r\n        \r\n        return $view->render($response, \'/page.html.twig\', [\r\n            \'csrf\' => $csrf,\r\n            \'page\' => $pageInfo,\r\n            \'pageTree\' => $pageTree,\r\n            \'children\' => $pageChildren,\r\n        ]);\r\n\r\n    }\r\n	\r\n    //...\r\n	\r\n}\r\n</code></pre>\r\n\r\n\r\n<h2>Including CSFR tokens in twig template</h2>\r\n\r\n<pre><code class=\"language-html\">\r\n{% extends \"/admin/index.html.twig\" %}\r\n\r\n{% block content %}\r\n\r\n&lt;form action=\"/admin/page/save\" method=\"post\" class=\"form\">\r\n    &lt;input type=\"hidden\" class=\"csrf-static-name\" name=\"{{ csrf.nameKey }}\" value=\"{{ csrf.name }}\">\r\n    &lt;input type=\"hidden\" class=\"csrf-static-value\" name=\"{{ csrf.valueKey }}\" value=\"{{ csrf.value }}\">\r\n\r\n    /* ... */\r\n	\r\n    &lt;div class=\"form-block\">\r\n        &lt;input type=\"submit\" class=\"form-save\" value=\"save\">\r\n    &lt;/div> \r\n&lt;/form>\r\n{% endblock %}\r\n</code></pre>', '', 'slim-csrf', '', '', '', 0),
(11, 'Routing', '<h2>Simple routs in the index.php file</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n/* /public_html/index.phg */\r\n\r\n//...\r\n\r\n/* controllers & routing */\r\n$app->get(\'/\', function (Request $request, Response $response, $args) {\r\n    \r\n    //...\r\n\r\n    /* view */\r\n    $view = Twig::fromRequest($request);\r\n    \r\n    return $view->render($response, \'/main.html.twig\', [\r\n        \'name\' => \'John\',\r\n        \'userTempHash\' => $_SESSION[\"user_temp_hash\"],\r\n\r\n        //\'csrf\' => $csrf,\r\n        \'pageTree\' => $pageTree,\r\n        \'galleryTree\' => $galleryTree,\r\n    ]);\r\n\r\n});\r\n\r\n//...\r\n</code></pre>\r\n\r\n<h2>Routs for the custom class</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n/* /public_html/index.phg */\r\n\r\n//...\r\n\r\n/* pages */\r\n$app->get(\'/page[/{alias}]\', Controllers\\Page::class . \':page\');\r\n\r\n/* galleries */\r\n$app->get(\'/gallery[/{alias}]\', Controllers\\Gallery::class . \':gallery\');\r\n\r\n/* authorization */\r\n$app->get(\'/login\', Controllers\\User::class . \':login\');\r\n\r\n$app->post(\'/login\', Controllers\\User::class . \':login\');\r\n\r\n$app->get(\'/logout\', Controllers\\User::class . \':logout\');\r\n\r\n//...\r\n\r\n/* group routing */\r\n$group->group(\'/user\', function (RouteCollectorProxy $group) {\r\n\r\n	$group->get(\'\', Controllers\\User::class . \':list\');  \r\n\r\n	$group->get(\'/add\', Controllers\\User::class . \':add\');\r\n\r\n	$group->get(\'/edit[/{id}]\', Controllers\\User::class . \':edit\');\r\n	\r\n	$group->post(\'/save\', Controllers\\User::class . \':save\');\r\n\r\n	$group->get(\'/delete[/{id}]\', Controllers\\User::class . \':delete\');              \r\n\r\n});\r\n\r\n//...\r\n\r\n/* route for the not found 404 page */\r\n$app->any(\'/404\', function(Request $request, Response $response) {\r\n\r\n    $view = Twig::fromRequest($request);\r\n\r\n    return $view->render($response, \'404.html.twig\');\r\n\r\n});\r\n\r\n/* catch all other urls and redirect them to /404 */\r\n$app->any(\'{route:.*}\', function(Request $request, Response $response) {\r\n\r\n    //$view = Twig::fromRequest($request);\r\n\r\n    //return $view->render($response, \'404.html.twig\');\r\n\r\n    return $response\r\n        ->withHeader(\'Location\', \'/404\')\r\n        ->withStatus(404);    \r\n\r\n});\r\n\r\n$app->run();\r\n\r\n</code></pre>\r\n\r\n<h2>Custom class with the methods</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /app/Controllers/Page.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Controllers;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface;\r\nuse Psr\\Http\\Message\\ServerRequestInterface;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\nuse Slim\\Views\\Twig;\r\n\r\nclass Page {\r\n\r\n    private $container;\r\n\r\n    //constructor receives container instance\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {\r\n    \r\n        //...\r\n\r\n        /* view */\r\n        $view = Twig::fromRequest($request);\r\n        \r\n        return $view->render($response, \'/page.html.twig\', [\r\n            \'csrf\' => $csrf,\r\n            \'page\' => $pageInfo,\r\n            \'pageTree\' => $pageTree,\r\n            \'children\' => $pageChildren,\r\n        ]);\r\n\r\n    }\r\n	\r\n    //...\r\n	\r\n}	\r\n\r\n</code></pre>', '', 'routing', '', '', '', 0),
(12, 'Middleware', '<p>A simple example of middleware</p>\r\n\r\n<h2>The middleware class</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /app/Middleware/CheckAccess.php */\r\n\r\ndeclare(strict_types = 1);\r\n\r\nnamespace Middleware;\r\n\r\nuse Psr\\Http\\Message\\ResponseInterface as Response;\r\nuse Psr\\Http\\Message\\ServerRequestInterface as Request;\r\n\r\nuse Psr\\Http\\Server\\MiddlewareInterface;\r\nuse Psr\\Http\\Server\\RequestHandlerInterface as RequestHandler;\r\n\r\nuse Psr\\Container\\ContainerInterface;\r\n\r\nclass CheckAccess implements MiddlewareInterface {\r\n    \r\n    private $container;\r\n\r\n    public function __construct(ContainerInterface $container)\r\n    {\r\n\r\n        $this->container = $container;\r\n\r\n    }\r\n\r\n    public function process(Request $request, RequestHandler $handler): Response\r\n    {\r\n\r\n        $response = $handler->handle($request);\r\n\r\n        if( isset($_SESSION[\'user_temp_hash\']) ){\r\n\r\n            $user = new \\Models\\User($this->container->get(\"pdo\"));\r\n            $checkAccess = $user->checkAccess($_SESSION[\'user_temp_hash\']);\r\n\r\n            if( $checkAccess ){\r\n\r\n                /* user has access for the admin panel */\r\n                return $response;\r\n\r\n            }\r\n            \r\n        }\r\n\r\n        /* user has no access for the admin panel */\r\n        return $response->withHeader(\'Location\', \'/login\')->withStatus(302);;\r\n\r\n    }\r\n}\r\n\r\n</code></pre>\r\n\r\n<h2>Registering middleware</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.phg */\r\n\r\n/* admin panel */\r\n$app->group(\'/admin\', function (RouteCollectorProxy $group) {\r\n\r\n	//...\r\n	\r\n    /* admin/page */\r\n    $group->group(\'/page\', function (RouteCollectorProxy $group) {\r\n\r\n        $group->get(\'\', Controllers\\Page::class . \':list\');  \r\n\r\n        $group->get(\'/add\', Controllers\\Page::class . \':add\');\r\n \r\n        $group->get(\'/edit[/{id}]\', Controllers\\Page::class . \':edit\');\r\n        \r\n        $group->post(\'/save\', Controllers\\Page::class . \':save\');\r\n  \r\n        $group->get(\'/delete[/{id}]\', Controllers\\Page::class . \':delete\');              \r\n\r\n    });    \r\n\r\n	//...\r\n\r\n\r\n})->add(new \\Middleware\\CheckAccess($container));\r\n\r\n//...\r\n\r\n</code></pre>\r\n\r\n<h2>or...</h2>\r\n\r\n<pre><code class=\"language-php\">\r\n&lt;?php\r\n\r\n/* /public_html/index.phg */\r\n\r\n//...\r\n\r\n//add middleware to the App\r\n$app->add(new ExampleMiddleware());\r\n\r\n//add middleware to the App using dependency injection\r\n$app->add(ExampleMiddleware::class);\r\n\r\n//add middleware to a route\r\n$app->get(\'/\', function () { ... })->add(new ExampleMiddleware());\r\n\r\n//add middleware to a route group\r\n$app->group(\'/\', function () { ... })->add(new ExampleMiddleware());\r\n\r\n//...\r\n\r\n</code></pre>\r\n\r\n', '', 'middleware', '', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `temp_hash` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `temp_hash`) VALUES
(4, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'uSRcduOGwl'),
(9, 'new-user1', '7cd7a29bd79adbc644ab5ff9add6a4a9', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `galleries_images`
--
ALTER TABLE `galleries_images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `galleries_images`
--
ALTER TABLE `galleries_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
