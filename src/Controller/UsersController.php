<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // allow users to register accounts when they are not logged in
        $this->Auth->allow('register');
    }

    // page for creating new accounts for the shop
    public function register()
    {
        // create new user
        $user = $this->Users->newEntity();
        // on submit
        if ($this->request->is('post')) {
            // create user from form data
            $user = $this->Users->patchEntity($user, $this->request->data);
            // try to save the user
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'login']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        // get the current user
        $this->set('authUser', $this->Auth->user());
    }

    // page for logging out
    public function logout()
    {
        // logout
        $this->Auth->logout();
        // get current user
        $this->set('authUser', $this->Auth->user());
    }
    public function login()
    {
        // get the current user
        $this->set('authUser', $this->Auth->user());
        // on form submit
        if ($this->request->is('post')) {
            //try to login
            $user = $this->Auth->identify();
            // if login ok, set user and redirect to products
            if ($user){
                $this->Auth->setUser($user);
                return $this->redirect("/products");
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }
    
    // page for the user's profile
    public function profile()
    {
        // get the current user
        $this->set('authUser', $this->Auth->user());
    }
}
