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
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('Controller', 'Controller');

/**
 * 
 */
class BuilderPluginController extends Controller {

    /**
     * Autoload Helpers
     *
     * @var array
     */
    public $helpers = array(
        'Js',
        'Html' => array(
            'className' => 'Builder.BootstrapHtml'
        ),
        'Form' => array(
            'className' => 'Builder.BootstrapForm'
        ),
        'Session' => array(
            'className' => 'Builder.BootstrapSession'
        ),
        'Paginator' => array(
            'className' => 'Builder.BootstrapPaginator'
        ),
    );

    /**
     * Autoload Components
     */
    public $components = array(
        'Scaffold' => array(
            'className' => 'Builder.Scaffold'
        )
    );

    /**
     * Check if they are logged in
     * Check if the session variable User exists, redirect to loginform if not
     */
    public function authenticate() {
        if (!$this->Session->check('Auth')) {
            $this->redirect(array('controller' => 'user', 'action' => 'login'));
            exit();
        }
    }

    /**
     * Authenticate on every action, except the login form
     */
    public function afterFilter() {
        
    }

    /**
     * Scaffold
     *
     * @var string
     */
    public $scaffold;

    /**
     * 
     */
    public function beforeRender() {


        /**
         * IMPORTANT: Resolve problem in FormHelper CakeLib
         * This code empties the settings of ClassRegistry in PluginName/Config/bootstrap.php
         */
        $maps = ClassRegistry::mapKeys();
        foreach ($maps as $k => $v) :
            ClassRegistry::removeObject($v);
        endforeach;
    }

    /**
     * Before Filter
     *
     * @return void
     */
    public function beforeFilter() {

        $this->Session = $this->Components->load('Session');

        /**
         * Default Authentication
         */
        $authentication = TRUE;

        /**
         * Service - autenthication is disabled
         */
        if ($this->request->params['controller'] == 'wrapper') {
            $authentication = FALSE;
        }

        /**
         * User Login
         */
        if ($this->request->params['controller'] == 'user' && $this->action == 'login') {
            $authentication = FALSE;
        }

        /**
         * Autenthication
         */
        if ($authentication) {
            $this->authenticate();
        }


        /**
         * 
         * 
          if ($this->action != 'login') {
          $this->authenticate();
          }
         * 
         * 
         */
        /**
         * IMPORTANT: If the request is ajax
         */
        if ($this->request->is('ajax')) :
            $this->layout = 'ajax';
        endif;

        /**
         * Get Model variables
         */
        $modelClass = $this->modelClass;
        $Model = ClassRegistry::init($modelClass);

        $ignoreModelList = array();
        if (isset($Model->ignoreModelList)) {
            $ignoreModelList = $Model->ignoreModelList;
        }
        $displayFieldTypes[$modelClass] = array();
        if (isset($Model->displayFieldTypes)) {
            $displayFieldTypes[$modelClass] = $Model->displayFieldTypes;
        }

        /**
         * Get association model displayfield types
         */
        foreach ($Model->hasMany as $key => $value) {
            $subModel = ClassRegistry::init($value['className']);
            $displayFieldTypes[$key] = $subModel->displayFieldTypes;
        }
        $ignoreFieldList = array();
        if (isset($Model->ignoreFieldList)) {
            $ignoreFieldList = $Model->ignoreFieldList;
        }
        $BuilderSettings = array();
        if (isset($Model->BuilderSettings)) {
            $BuilderSettings = $Model->BuilderSettings;
        }

        /**
         * Create DynamicController
         */
        /**
         * 
         */
        parent::beforeFilter();

        /**
         * Process Request Data
         */
        $data = $this->request->data;

        if ($data) {

            $Model = ClassRegistry::init($modelClass);
            $imgDir = $Model->upLoads['imgDir'];

            if (array_key_exists($modelClass, $data)) {

                foreach ($data[$modelClass] as $key => $value) {

                    /**
                     * Type Image
                     */
                    if (isset($displayFieldTypes[$modelClass][$key]) && $displayFieldTypes[$modelClass][$key] == 'image') {

                        if (empty($value['name'])) {
                            unset($this->request->data[$modelClass][$key]);
                        } else {
                            $itemDir = '';
                            if (isset($Model->upLoads['itemDir']) && !empty($Model->upLoads['itemDir'])) {
                                $itemDir = $Model->upLoads['itemDir'];
                                if (is_array($itemDir) && isset($itemDir['field'])) {
                                    $itemField = $itemDir['field'];
                                    if (isset($data[$modelClass][$itemField])) {
                                        $itemDir = $data[$modelClass][$itemField];
                                    }
                                } else {
                                    $itemDir = $Model->upLoads['itemDir'];
                                }
                            }
                            $fileOK = $this->_fileUpload($imgDir, $value, $itemDir);
                            if (array_key_exists('urls', $fileOK)) {
                                $this->request->data[$modelClass][$key] = $fileOK['urls'][0];
                            }
                        }
                    }

                    /**
                     * Field Type
                     */
                    if (isset($displayFieldTypes[$modelClass][$key])) {

                        $fieldType = $displayFieldTypes[$modelClass][$key];

                        switch ($fieldType) {
                            case 'file':
                                if (!$this->_fileUpload($modelClass, $key))
                                    break;
                            case 'image':
                                // echo 'image';
                                break;
                            default:
                                // default
                                break;
                        }
                    }
                }
            }
        }

        /**
         * Set default params if is empty value
         */
        if (empty($ignoreFieldList))
            $ignoreFieldList = array();
        if (empty($displayFieldTypes))
            $displayFieldTypes = array();
        if (empty($ignoreModelList))
            $ignoreModelList = array();
        if (empty($BuilderSettings))
            $BuilderSettings = array();

        /**
         * Set variables
         */
        $this->set(compact('navbar', 'displayFieldTypes', 'ignoreFieldList', 'BuilderSettings'));
    }

    /**
     * 
     */
    protected function _fileUpload($modelClass, $field, $baseDir = 'files') {


        $data = $this->request->data[$modelClass][$field];

        if (!empty($data['name'])) :

            /**
             * Destination PATH
             */
            $CONTENT_YEAR = date('Y');
            $CONTENT_MONTH = date('m');

            /**
             * Verify Path
             */
            $folder_dest = new Folder(WWW_ROOT);
            $folder_dest->cd($baseDir);

            if (!$folder_dest->inCakePath($folder_dest->pwd() . DS . $CONTENT_YEAR))
                $folder_dest->create($folder_dest->pwd() . DS . $CONTENT_YEAR);

            $folder_dest->cd($CONTENT_YEAR);

            if (!$folder_dest->inCakePath($folder_dest->pwd() . DS . $CONTENT_MONTH))
                $folder_dest->create($folder_dest->pwd() . DS . $CONTENT_MONTH);

            $folder_dest->cd($CONTENT_MONTH);

            /**
             * Upload File
             */
            if (!empty($data['tmp_name']) && is_uploaded_file($data['tmp_name'])) {
                $filename = basename($data['name']);
                move_uploaded_file($data['tmp_name'], $folder_dest->pwd() . DS . $filename);
            }

            $fullpath = DS . $baseDir . DS . $CONTENT_YEAR . DS . $CONTENT_MONTH . DS . $filename;
            $this->request->data[$modelClass][$field] = $fullpath;
            return TRUE;

        endif;

        /**
         * Remove element from $this->request->data
         */
        unset($this->request->data[$modelClass][$field]);
        return FALSE;
    }

    /**
     * @return: will return an array with the success of each file upload
     */
    protected function _imageUpload($folder = null, $formdata, $itemId = null) {

        // setup dir names absolute and relative
        if (isset($folder)) {
            $folder_url = WWW_ROOT . 'img' . DS . $folder;
            $rel_url = 'img' . DS . $folder;
        } else {
            $folder_url = WWW_ROOT . 'img';
            $rel_url = 'img';
        }

        // create the folder if it does not exist
        if (!is_dir($folder_url)) {
            mkdir($folder_url);
        }

        // if itemId is set create an item/sub folder
        if ($itemId) {
            // set new absolute folder
            $folder_url = $folder_url . DS . $itemId;
            // set new relative folder
            $rel_url = $rel_url . DS . $itemId;
            // create the folder if it does not exist
            if (!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }

        // list of permitted file types
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

        // replace spaces with underscores
        $filename = $formdata['name'];
        $filename = str_replace(' ', '_', $filename);

        // assume filetype is false
        $typeOK = false;

        // check filetype is ok
        foreach ($permitted as $type) {
            if ($type == $formdata['type']) {
                $typeOK = true;
                break;
            }
        }

        // if file type ok upload the file
        if ($typeOK) {
            // switch based on error code
            switch ($formdata['error']) {
                case 0:
                    // check filename already exists
                    if (!file_exists($folder_url . DS . $filename)) {
                        // create full filename
                        $full_url = $folder_url . DS . $filename;
                        $url = $rel_url . DS . $filename;
                        // upload the file
                        $success = move_uploaded_file($formdata['tmp_name'], $url);
                    } else {
                        // create unique filename and upload file
                        $now = date('Y-m-d-His');
                        $full_url = $folder_url . DS . $now . $filename;
                        $url = $rel_url . DS . $now . $filename;
                        $success = move_uploaded_file($formdata['tmp_name'], $url);
                    }
                    // if upload was successful
                    if ($success) {
                        // save the url of the file
                        $result['urls'][] = substr($url, 4);
                    } else {
                        $result['errors'][] = "Error uploaded $filename. Please try again.";
                    }
                    break;
                case 3:
                    // an error occured
                    $result['errors'][] = "Error uploading $filename. Please try again.";
                    break;
                default:
                    // an error occured
                    $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                    break;
            }
        } elseif ($formdata['error'] == 4) {
            // no file was selected for upload
            $result['nofiles'][] = "No file Selected";
        } else {
            // unacceptable file type
            $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
        }

        return $result;
    }

    /**
     * Load scaffold component from /Controller/Components/
     * override the Cake/Controller protected function
     * 
     * @param CakeRequest $request
     */
    protected function _getScaffold(CakeRequest $request) {

        /**
         * Attenzione la funzione startup è un callback
         * ma se non ridefinita non prende i settaggi di personalizzazione definiti
         * nel controller action...
         * 
         * Sistemare questo concetto!
         */
        $this->Scaffold->run($request);
    }

}
