<?php

abstract class Controller
{
    /** @var string */
    protected $controller_name;
    /** @var string */
    protected $action_name;
    /** @var Application */
    protected $application;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var Session */
    protected $session;
    /** @var DbManager */
    protected $db_manager;

    /**
     * Constructor method
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->controller_name = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request = $application->getRequest();
        $this->response = $application->getResponse();
        $this->session = $application->getSession();
        $this->db_manager = $application->getDbManager();
    }

    /**
     * Retrieve the response content
     *
     * @param string $action
     * @param array<string,string> $params
     * @return string
     */
    public function run(string $action, array $params = []): string
    {
        // TODO
        return '';
    }
}
