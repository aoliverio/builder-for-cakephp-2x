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
App::uses('HtmlHelper', 'View/Helper');

/**
 * 
 */
class BootstrapHtmlHelper extends HtmlHelper {

    /**
     * Return tabs form Elements dir
     */
    public function tabs($options = array()) {

        /**
         * 
         */
        return $this->_View->element('Bootstrap/tabs', array('options' => $options));
    }

}
