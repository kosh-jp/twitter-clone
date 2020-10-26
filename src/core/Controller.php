<?php

abstract class Controller
{
    /** @var string */
    protected $controller_name;
    /** @var string */
    protected $action_name;
    /** @var array<string>|bool */
    protected $auth_actions;
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

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
            throw new UnauthorizedActionException();
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

    /**
     * Send a redirect response
     *
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->request->isSsl() ? 'https://' : 'http://';
            $host = $this->request->getHost();
            $base_url = $this->request->getBaseUrl();

            $url = $protocol . $host . $base_url;
        }

        $this->response->setStatusCode(302, 'Found');
        $this->response->setHttpHeader('Location', $url);
    }

    /**
     * @param string $form_name
     * @return string
     */
    protected function generateCsrfToken(string $form_name): string
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, []);

        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = hash('sha256', $form_name . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);

        return $token;
    }

    /**
     * @param string $form_name
     * @param string $token
     * @return bool
     */
    protected function checkCsrfToken(string $form_name, string $token): bool
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, []);

        $index = array_search($token, $tokens, true);
        if (false === $index) {
            return false;
        }

        unset($tokens[$index]);
        $this->session->set($key, $tokens);

        return true;
    }

    /**
     * Return true if action is to need authentication
     *
     * @param string $action
     * @return bool
     */
    protected function needsAuthentication(string $action)
    {
        if ($this->auth_actions === true) {
            return true;
        }
        if (is_array($this->auth_actions) && in_array($action, $this->auth_actions)) {
            return true;
        }

        return false;
    }
}
