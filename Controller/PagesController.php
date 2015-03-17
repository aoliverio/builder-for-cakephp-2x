<?php

/**
 * PagesController is static content controller.
 * Load view from /Builder/View/Pages/
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
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    /**
     * Uses
     *
     * @var mixed
     */
    public $uses = array();

    /**
     * Displays a view
     * 
     * @param mixed What page to display
     * @return void
     * @throws NotFoundException When the view file could not be found or MissingViewException in debug mode.
     */
    public function display() {

        /**
         * 
         */
        $path = func_get_args();

        /**
         * 
         */
        $count = count($path);

        /**
         * 
         */
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        /**
         * 
         */
        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title_for_layout'));

        /**
         * 
         */
        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

}
