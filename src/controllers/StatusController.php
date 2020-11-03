<?php

class StatusController extends Controller
{
    /** @var FollowingRepository */
    protected $followingRepository;
    /** @var StatusRepository */
    protected $statusRepository;
    /** @var UserRepository */
    protected $userRepository;
    /** @var array<string>|bool */
    protected $auth_actions = ['index', 'post'];

    /**
     * {@inheritDoc}
     *
     * With loading DbRepositories
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->followingRepository = $this->db_manager->get('Following');
        $this->statusRepository = $this->db_manager->get('Status');
        $this->userRepository = $this->db_manager->get('User');
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

    /**
     * @param array<string,string> $params
     * @throws HttpNotFoundException
     * @return string
     */
    public function userAction(array $params): string
    {
        $user = $this->userRepository->fetchByUserName($params['user_name']);
        if (empty($user)) {
            $this->forward404();
        }

        $following = null;
        if ($this->session->isAuthenticated()) {
            $my = $this->session->get('user');
            if ($my['id'] !== $user['id']) {
                $following = $this->followingRepository->isFollowing($my['id'], $user['id']);
            }
        }

        $statuses = $this->statusRepository->fetchAllByUserId($user['id']);
        $_token = $this->generateCsrfToken('account/follow');
        return $this->render(compact('user', 'statuses', 'following', '_token'));
    }

    /**
     * @param array<string,string> $params
     * @throws HttpNotFoundException
     * @return string
     */
    public function showAction(array $params): string
    {
        $status = $this->statusRepository->fetchByIdAndUserName($params['id'], $params['user_name']);
        if (empty($status)) {
            $this->forward404();
        }

        return $this->render(compact('status'));
    }
}
