<?php

class AccountController extends Controller
{
    /**
     * @return string
     */
    public function indexAction(): string
    {
        $user = $this->session->get('user');

        return $this->render(compact('user'));
    }

    /**
     * @return string
     */
    public function signupAction(): string
    {
        return $this->render([
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signup')
        ]);
    }

    /**
     * @throws HttpNotFoundException
     * @return string|null
     */
    public function registerAction()
    {
        /** @var UserRepository $user_repository */
        $user_repository = $this->db_manager->get('User');

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
        } elseif (!$user_repository->isUniqueUserName($user_name)) {
            $errors[] = 'ユーザーIDは既に使用されています';
        }

        if (empty($password)) {
            $errors[] = 'パスワードを入力してください';
        } elseif (4 > strlen($password) || strlen($password) > 30) {
            $errors[] = 'パスワードは4〜30文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $_token = $this->generateCsrfToken('account/signup');
            return $this->render(compact('user_name', 'password', '_token', 'errors'), 'signup');
        }

        if ($user_repository->insert($user_name, $password)) {
            $this->session->setAuthenticated(true);

            $user = $user_repository->fetchByUserName($user_name);
            $this->session->set('user', $user);

            return $this->redirect('/');
        }

        $errors[] = 'エラーが発生しました。時間をおいて再度試してください';
        $_token = $this->generateCsrfToken('account/signup');
        return $this->render(compact('user_name', 'password', '_token', 'errors'), 'signup');
    }
}
