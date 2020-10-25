<?php

abstract class Application
{
    /** @var bool */
    protected $debug = false;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var Session */
    protected $session;
    /** @var DbManager */
    protected $db_manager;
    /** @var Router */
    protected $router;

    /**
     * Constructor method
     * Call setDebugMode initialize configure
     *
     * @param boolean $debug
     */
    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /**
     * Set debug mode
     *
     * @param bool $debug
     * @return void
     */
    public function setDebugMode(bool $debug): void
    {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', '1');
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', '0');
        }
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function initialize(): void
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db_manager = new DbManager();
        $this->router = new Router($this->registerRoutes());
    }

    /**
     * Configure instance based on the user arguments and options
     *
     * @return void
     */
    protected function configure(): void
    {
    }

    abstract public function getRootDir(): string;

    /**
     * Return web routes definition
     *
     * @return array<string,array>
     */
    abstract protected function registerRoutes(): array;

    /**
     * Run controller action and send HTTP response
     *
     * @throws Exception
     * @return void
     */
    public function run(): void
    {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller  = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $this->render404page($e);
        }

        $this->response->send();
    }

    /**
     * Run controller action and set the content on the response
     *
     * @param string $controller_name
     * @param string $action
     * @param array<string,string> $params
     * @throws HttpNotFoundException
     * @return void
     */
    public function runAction(string $controller_name, string $action, array $params = []): void
    {
        $controller_class = ucfirst($controller_name) . 'Controller';

        $controller = $this->findController($controller_class);
        if ($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        $this->response->setContent($content);
    }

    /**
     * Load a controller class instance
     *
     * @param string $controller_class
     * @throws HttpNotFoundException
     * @return Controller|false
     */
    public function findController(string $controller_class)
    {
        if (class_exists($controller_class)) {
            return new $controller_class($this);
        }

        $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
        if (!is_readable($controller_file)) {
            return false;
        }

        require_once $controller_file;
        if (!class_exists($controller_class)) {
            return false;
        }

        return new $controller_class($this);
    }

    /**
     * Set 404 not found html as content
     *
     * @param HttpNotFoundException $e
     * @return void
     */
    protected function render404page(HttpNotFoundException $e): void
    {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found';
        $message = htmlspecialchars($message, ENT_QUOTES);

        $this->response->setContent(<<<EOF
            <!DOCTYPE html>
            <html lang="ja">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404|Not Found</title>
            </head>
            <body>
                {$message}
            </body>
            </html>
        EOF);
    }

    public function isDebugMode(): bool
    {
        return $this->debug;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getDbManager(): DbManager
    {
        return $this->db_manager;
    }

    public function getControllerDir(): string
    {
        return $this->getRootDir() . '/controllers';
    }

    public function getViewDir(): string
    {
        return $this->getRootDir() . '/views';
    }

    public function getModelDir(): string
    {
        return $this->getRootDir() . '/models';
    }

    public function getWebDir(): string
    {
        return $this->getRootDir() . '/web';
    }
}
