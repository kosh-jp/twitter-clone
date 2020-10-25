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
