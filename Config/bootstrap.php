<?php

/**
 * The bootstrap file
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @author     Antonio Oliverio <antonio.oliverio@gmail.com>
 * @copyright  Copyright 2014, Antonio Oliverio (http://www.aoliverio.com)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @since      CakePHP(tm) v 2.1.1
 */
App::uses('Inflector', 'Utility');
App::uses('ClassRegistry', 'Utility');
App::uses('CakeRequest', 'Network');

/**
 * Use singular form - uniflected all words 
 * Es. UserController, User for model and table, User for the Viev directory
 */
Inflector::rules('plural', array('uninflected' => array('.*')));
Inflector::rules('singular', array('uninflected' => array('.*')));

/**
 * Autoload MVC file for plugin 'Builder'
 */
$PLUGIN_PATH = App::pluginPath('Builder');
App::build(array('Model' => array($PLUGIN_PATH . DS . 'Model' . DS)), App::APPEND);
App::build(array('View' => array($PLUGIN_PATH . DS . 'View' . DS)), App::APPEND);
App::build(array('Controller' => array($PLUGIN_PATH . DS . 'Controller' . DS)), App::APPEND);

/**
 * Autoload MVC file for RBAC - Role Based Access Control
 */
App::build(array('Model' => array($PLUGIN_PATH . DS . 'Lib' . DS . 'RBAC' . DS . 'Model' . DS)), App::APPEND);
App::build(array('View' => array($PLUGIN_PATH . DS . 'Lib' . DS . 'RBAC' . DS . 'View' . DS)), App::APPEND);
App::build(array('Controller' => array($PLUGIN_PATH . DS . 'Lib' . DS . 'RBAC' . DS . 'Controller' . DS)), App::APPEND);

/**
 * Authorization
 */
$adminIsAuthorized = (function_exists('adminIsAuthorized')) ? adminIsAuthorized() : true;
if ($adminIsAuthorized === false) {
    throw new MethodNotAllowedException();
}

/**
 * CakeRequest
 */
$Request = new CakeRequest();
if (isset($Request->url)) {
    $parts = explode('/', $Request->url);

    /**
     * Set $createController
     */
    if (isset($parts[0]) && (strtolower($parts[0]) == '' || strtolower($parts[0]) == 'builder'))
        $createController = FALSE;
    else
        $createController = TRUE;

    /**
     * 
     */
    if ($createController) {

        /**
         * Set $controllerName
         */
        $controllerName = Inflector::camelize($parts[0]);

        /**
         * Set Model
         */
        $modelClassName = Inflector::camelize(Inflector::singularize($controllerName));
        if (App::import('Model', $modelClassName)) {
            $Model = ClassRegistry::init($modelClassName);
            $DataSource = $Model->getDataSource();
        }

        /**
         * Set Controller
         */
        $controllerClassName = Inflector::camelize($controllerName) . 'Controller';

        /**
         * Load controller class from App Controller / define default controller dynamically
         */
        if (!App::import('Controller', $controllerName)) {
            $controllerClass = '/** Define default controllor dynamically **/';
            $controllerClass .= 'App::uses(\'BuilderAppController\',\'Builder.Controller\');';
            $controllerClass .= 'class ' . $controllerClassName . ' extends BuilderAppController{';
            $controllerClass .= 'public $uses = \'' . $modelClassName . '\';';
            $controllerClass .= '}';
            eval($controllerClass);
        }
    }
}