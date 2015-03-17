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
/**
 * 
 */
Configure::write('Routing.prefixes', array('builder'));

/**
 * 
 */
Router::parseExtensions('csv', 'json', 'xml', 'xlsx');

/**
 * Set custom route for plugin
 */
Router::connect('/builder', array('plugin' => 'builder', 'controller' => 'pages', 'action' => 'display', 'index'));
Router::connect('/builder/pages', array('plugin' => 'builder', 'controller' => 'pages', 'action' => 'index'));
Router::connect('/builder/pages/*', array('plugin' => 'builder', 'controller' => 'pages', 'action' => 'display'));
