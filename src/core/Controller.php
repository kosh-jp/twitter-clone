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
     * @throws HttpNotFoundException
     * @return string
     */
    public function run(string $action, array $params = []): string
    {
        $this->action_name = $action;

        $action_method = $action . 'Action';
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        $content = $this->$action_method($params);

        return $content;
    }

    /**
     * Get the string contents of the view
     *
     * @param array<string,string> $variables
     * @param string $template
     * @param string $layout
     * @return string
     */
    protected function render(array $variables = [], string $template = '', string $layout = 'layout'): string
    {
        $defaults = [
            'request' => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session' => $this->session
        ];

        $view = new View($this->application->getViewDir(), $defaults);

        if (!$template) {
            $template = $this->action_name;
        }

        $path = $this->controller_name . '/' . $template;

        return $view->render($path, $variables, $layout);
    }

    /**
     * Throw HttpNotFoundException
     *
     * @throws HttpNotFoundException
     */
    protected function forward404(): HttpNotFoundException
    {
        throw new HttpNotFoundException(
            'Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name
        );
    }
}
