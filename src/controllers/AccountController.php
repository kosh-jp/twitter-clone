<?php

class AccountController extends Controller
{
    /** @var FollowingRepository $following_repository */
    protected $following_repository;
    /** @var UserRepository $user_repository */
    protected $user_repository;
    /** @var array<string>|bool */
    protected $auth_actions = ['index', 'signout', 'follow'];

    /**
     * {@inheritDoc}
     *
     * With loading DbRepositories
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->following_repository = $this->db_manager->get('Following');
        $this->user_repository = $this->db_manager->get('User');
    }


    /**
     * @return string
     */
    public function indexAction(): string
    {
        $user = $this->session->get('user');
        $followings = $this->user_repository->fetchAllFollowingsByUserId($user['id']);

        return $this->render(compact('user', 'followings'));
    }

    /**
     * @return string|null
     */
    public function signinAction(): ?string
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render([
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signin'),
        ]);
    }

    /**
     * @return string|null
     */
    public function authenticateAction(): ?string
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {
            return $this->redirect('account/signin');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = [];

        if (empty($user_name)) {
            $errors[] = 'ユーザーIDを入力してください';
        }

        if (empty($password)) {
            $errors[] = 'パスワードを入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->user_repository->fetchByUserName($user_name);

            if (empty($user) || !password_verify($password, $user['password'])) {
                $errors[] = 'ユーザーIDかパスワードが不正です';
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);

                return $this->redirect('/');
            }
        }

        $_token = $this->generateCsrfToken('account/signin');
        return $this->render(compact('user_name', 'password', 'errors', '_token'), 'signin');
    }

    /**
     * @return null
     */
    public function signoutAction(): ?string
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/account/signin');
    }

    /**
     * @return string|null
     */
    public function signupAction(): ?string
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render([
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signup'),
        ]);
    }

    /**
     * @throws HttpNotFoundException
     * @return string|null
     */
    public function registerAction(): ?string
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signup', $token)) {
            return $this->redirect('/account/signup');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = [];

        if (empty($user_name)) {
            $errors[] = 'ユーザーIDを入力してください';
        } elseif (!preg_match('/\w{3,20}$/', $user_name)) {
            $errors[] = 'ユーザーIDは半角英数字およびアンダースコアを3〜30文字以内で入力してください';
        } elseif (!$this->user_repository->isUniqueUserName($user_name)) {
            $errors[] = 'ユーザーIDは既に使用されています';
        }

        if (empty($password)) {
            $errors[] = 'パスワードを入力してください';
        } elseif (4 > strlen($password) || strlen($password) > 30) {
            $errors[] = 'パスワードは4〜30文字以内で入力してください';
        }

        if (count($errors) !== 0) {
            $_token = $this->generateCsrfToken('account/signup');
            return $this->render(compact('user_name', 'password', '_token', 'errors'), 'signup');
        }

        if ($this->user_repository->insert($user_name, $password)) {
            $this->session->setAuthenticated(true);

            $user = $this->user_repository->fetchByUserName($user_name);
            $this->session->set('user', $user);

            return $this->redirect('/');
        }

        $errors[] = 'エラーが発生しました。時間をおいて再度試してください';
        $_token = $this->generateCsrfToken('account/signup');
        return $this->render(compact('user_name', 'password', '_token', 'errors'), 'signup');
    }

    /**
     * @throws HttpNotFoundException
     * @return string|null
     */
    public function followAction(): ?string
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $following_name = $this->request->getPost('following_name');
        if (!$following_name) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/follow', $token)) {
            return $this->redirect('/user/' . $following_name);
        }

        $follow_user = $this->user_repository->fetchByUserName($following_name);
        if (empty($follow_user)) {
            $this->forward404();
        }

        $user = $this->session->get('user');

        if (
            $user['id'] !== $follow_user['id']
            && !$this->following_repository->isFollowing($user['id'], $follow_user['id'])
        ) {
            $this->following_repository->insert($user['id'], $follow_user['id']);
        }

        return $this->redirect('/account');
    }
}
