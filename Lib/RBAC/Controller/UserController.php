<?php

/**
 * Description ...
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @author     Antonio Oliverio <antonio.oliverio@gmail.com>
 * @copyright  Copyright 2014, Antonio Oliverio (http://www.aoliverio.com)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @since      CakePHP(tm) v 2.1.1
 */
App::uses('AppController', 'Controller');

/**
 * 
 */
class UserController extends AppController {

    /**
     * Login
     */
    function login() {

        // render default login form
        $this->layout = 'login';

        // user authentication
        if (empty($this->data['User']['username']) == false) {
            $user = $this->User->find('all', array('conditions' => array('User.username' => $this->data['User']['username'], 'User.password' => md5($this->data['User']['password']))));
            if ($user != false) {
                $this->Session->setFlash('Thank you for logging in!');
                $this->Session->write('Auth', $user);
                $this->Redirect(array('controller' => '', 'action' => 'index'));
                exit();
            } else {
                $this->Session->setFlash('Incorrect username/password!');
                $this->Redirect(array('action' => 'login'));
                exit();
            }
        }
    }

    /**
     * Logout
     */
    function logout() {

        // session destoy
        $this->Session->destroy();
        $this->Session->setFlash('You have been logged out!');
        $this->Redirect(array('action' => 'login'));
        exit();
    }

}
