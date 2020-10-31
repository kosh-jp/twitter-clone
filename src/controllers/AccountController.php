<?php

class AccountController extends Controller
{
    /**
     * @return string
     */
    public function signupAction(): string
    {
        return $this->render([
            '_token' => $this->generateCsrfToken('account/signup')
        ]);
    }
}
