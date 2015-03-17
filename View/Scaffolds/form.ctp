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
$scaffoldGrid = TRUE;
$scaffoldGridHeader = TRUE;
$scaffoldGridFooter = TRUE;
$scaffoldModal = TRUE;
$scaffoldType = 'default';

/**
 * Ajax Request
 */
if ($this->request->is('ajax')) {
    $scaffoldType = 'grid';
    $scaffoldGridHeader = FALSE;
    $scaffoldGridFooter = FALSE;
}

/**
 * Is set $scaffoldGrid
 */
if (isset($scaffoldTab))
    $scaffoldGrid = FALSE;

/**
 * RENDER SCAFFOLD MODAL - Load scaffoldModal
 */
if ($scaffoldModal) {
    echo $this->element('Scaffold/modal');
}

/**
 * RENDER SCAFFOLD GRID HEADER - Load scaffold head template before render scaffold content 
 */
if ($scaffoldGridHeader) {
    $view_filename = APP . 'View' . DS . $this->viewPath . DS . $scaffold['actionView'] . '_scaffold_header.ctp';
    if (file_exists($view_filename))
        require $view_filename;
}

/**
 * RENDER CUSTOM SCAFFOLD VIEW
 */
$view_filename = APP . 'View' . DS . $this->viewPath . DS . $scaffold['actionView'] . '_scaffold.ctp';
if (file_exists($view_filename)) {
    require $view_filename;
    $scaffoldGrid = FALSE;
}

/**
 * RENDER SCAFFOLD GRID 
 */
if ($scaffoldGrid) :

    /**
     * Set the $scaffold options
     */
    $options = array(
        'actionName' => ($this->request->action === 'add') ? 'Add' : 'Edit',
        'actionButton' => ($this->request->action === 'add') ? 'Add' : 'Save',
    );
    $scaffold = array_merge($scaffold, $options);

    /**
     * 
     */
    switch ($scaffoldType) {
        case 'grid':
            echo $this->element('Scaffold/grid_form', array('scaffold' => $scaffold));
            break;
        default:
            $panel = array(
                'header' => $scaffold['title_for_grid'] . ' <small>' . Inflector::humanize($scaffold['actionLabel']) . '</small>',
                'body' => $this->element('Scaffold/grid_form', array('scaffold' => $scaffold)),
                'footer' => NULL
            );
            echo $this->element('Bootstrap/panel', array('panel' => $panel));
            break;
    }

endif;

/**
 * RENDER SCAFFOLD GRID FOOTER - Load scaffold foot template after render scaffold content 
 */
if ($scaffoldGridFooter) {
    $view_filename = APP . 'View' . DS . $this->viewPath . DS . $scaffold['actionView'] . '_scaffold_footer.ctp';
    if (file_exists($view_filename))
        require $view_filename;
}
