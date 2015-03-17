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
App::uses('Folder', 'Utility');
App::uses('String', 'Utility');
App::uses('File', 'Utility');
App::uses('Xml', 'Utility');

class ServiceController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array();

    /**
     * 
     */
    public function wrapper() {

        $this->loadModel('Concorsi');
        $data = $this->request->data;
        $this->Concorsi->save($data);

        $data = 'Inserito nuovo record in DB at ' . date('Y-m-d H:i:s');
        $file = new File(WWW_ROOT . DS . 'wrapper.ini', true, 0775);
        $file->write($data);
        exit;
    }

}
