<?php
    namespace App\Controller;

    use Google_Client;
    use Google_Service_Oauth2;
    use Cake\Routing\Router;
    use Cake\Core\Configure;
    use Cake\Event\Event;
    use App\Controller\AppController;

    class AuthController extends AppController
    {
        public $client;

        public function initialize()
        {
            parent::initialize();

            $this->client = new Google_Client();
            $this->client->setClientId(Configure::read('Google.credentials.clientID'));
            $this->client->setClientSecret(Configure::read('Google.credentials.secretKey'));
            $this->client->setRedirectUri(Router::url('/auth/callback', true));

            $this->client->setScopes(array(
                "https://www.googleapis.com/auth/userinfo.profile",
                'https://www.googleapis.com/auth/userinfo.email'
            ));
        }

        public function beforeFilter(Event $event)
        {
            $this->Auth->allow();
        }

        public function login()
        {
            $url = $this->client->createAuthUrl();
            $this->redirect($url);
        }

        public function callback()
        {
            $this->client->setApprovalPrompt('auto');

            // Succed
            if (isset($this->request->query['code']))
                $this->client->authenticate($_GET['code']);
                $this->request->Session()->write('access_token', $this->client->getAccessToken());

            if ($this->request->Session()->check('access_token') && ($this->request->Session()->read('access_token')))
                $this->client->setAccessToken($this->request->Session()->read('access_token'));

            // Getting user data
            if ($this->client->getAccessToken()) {
                $oauth2 = new Google_Service_Oauth2($this->client);

                $user = $oauth2->userinfo->get();

                if($user){
                    // Find localUser to Sync
                    $localUser = $this->Users->find()
                        ->where([
                            'social_id' => $user['id']
                        ])
                        ->first();

                    // Fist Access
                    if(!$localUser){
                        $localUser = $this->Users->newEntity([
                            'social_id' => $user['id'],
                            'first_name' => $user['givenName'],
                            'last_name' => $user['familyName'],
                            'email' => $user['email'],
                            'full_name' => "{$user['givenName']} {$user['familyName']}" // Just for quick search
                        ]);

                        $this->Users->save($localUser);
                    }

                    // Defines system User
                    $this->Auth->setUser($localUser->toArray());
                    $this->redirect('/');
                }
            }

            $this->redirect($this->Auth->logout());
        }

        public function logout()
        {
            $this->request->session()
                  ->destroy('access_token');

            $this->Auth->logout();

            return $this->redirect($this->Auth->logout());
        }
    }
