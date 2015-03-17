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
App::uses('AppModel', 'Model');

/**
 * 
 */
class User extends AppModel {

    /**
     * Define useTable
     * 
     * @var type 
     */
    public $useTable = 'aob_user';
    public $primaryKey = 'id';
    public $displayField = 'username';

    /**
     * Scaffold ignore field
     * 
     * @var type 
     */
    public $ignoreFieldList = array(
        'id',
        'password',
        'created',
        'modified'
    );

}

?>