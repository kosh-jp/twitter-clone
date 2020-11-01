<?php

class StatusController extends Controller
{
    /**
     * @return string
     */
    public function indexAction(): string
    {
        /** @var StatusRepository $statusRepository */
        $statusRepository = $this->db_manager->get('Status');
        $user = $this->session->get('user');
        $statuses = $statusRepository->fetchAllPersonalArchivesByUserId($user['id']);

        $body = '';
        $_token = $this->generateCsrfToken('status/post');
        return $this->render(compact('statuses', 'body', '_token'));
    }
}
