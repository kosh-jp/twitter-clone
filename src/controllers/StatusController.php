<?php

class StatusController extends Controller
{
    /** @var StatusRepository */
    protected $statusRepository;

    /**
     * {@inheritDoc}
     *
     * With loading StatusRepository class
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->statusRepository = $this->db_manager->get('Status');
    }

    /**
     * @return string
     */
    public function indexAction(): string
    {
        $user = $this->session->get('user');
        $statuses = $this->statusRepository->fetchAllPersonalArchivesByUserId($user['id']);

        $body = '';
        $_token = $this->generateCsrfToken('status/post');
        return $this->render(compact('statuses', 'body', '_token'));
    }
    /**
     * Insert tweet body action
     *
     * @throws HttpNotFoundException
     * @return string|null
     */
    public function postAction(): ?string
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');

        $errors = [];
        if (empty($body)) {
            $errors[] = 'ひとことを入力してください';
        } elseif (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->session->get('user');
            $this->statusRepository->insert($user['id'], $body);
        }

        $user = $this->session->get('user');
        $statuses = $this->statusRepository->fetchAllPersonalArchivesByUserId($user['id']);
        $_token = $this->generateCsrfToken('status/post');
        return $this->render(compact('errors', 'statuses', 'body', '_token'), 'index');
    }
}
