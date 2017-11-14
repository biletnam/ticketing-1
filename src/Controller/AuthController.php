<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;

    class AuthController extends AppController
    {
        public function beforeFilter(Event $event)
        {
            $this->Auth->allow();
        }

        public function login()
        {
            $this->viewBuilder()
                ->setTemplate('/Pages/login');
        }

        public function callback()
        {

        }

        public function logout()
        {

        }
    }
