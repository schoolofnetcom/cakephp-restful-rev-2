<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Utility\Security;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login']);
    }
    public function login()
    {
        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
            $data = [
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' => time() + 3600 * 24 * 365
                ], Security::salt())
            ];

            $this->set([
                'data' => $data,
                '_serialize' => ['data']
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'data' => 'Usuário ou senha inválidos',
                '_serialize' => ['data']
            ]);
        }
    }

    public function index()
    {
        $users = $this->Users->find('all');
        $this->set([
            'data' => $users,
            '_serialize' => ['data']
        ]);
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set([
            'data' => $user,
            '_serialize' => ['data']
        ]);
    }

    public function add()
    {
        $user = $this->Users->newEntity($this->request->getData());
        if ($this->Users->save($user)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }

        $this->set([
            'data' => $user,
            'message' => $message,
            '_serialize' => ['data', 'message']
        ]);
    }

    public function edit($id)
    {
        $user = $this->Users->get($id);
        $user = $this->Users->patchEntity($user, $this->request->getData());
        if ($this->Users->save($user)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }

        $this->set([
            'data' => $user,
            'message' => $message,
            '_serialize' => ['data', 'message']
        ]);
    }

    public function delete($id)
    {
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $message = 'Deleted';
        } else {
            $message = 'Error';
        }

        $this->set([
            'data' => $user,
            'message' => $message,
            '_serialize' => ['data', 'message']
        ]);
    }
}
