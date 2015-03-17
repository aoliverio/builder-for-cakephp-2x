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
App::uses('Scaffold', 'View');
App::uses('Component', 'Controller');

/**
 * Scaffolding is a set of automatic actions for starting web development work faster.
 *
 * Scaffold inspects your database tables, and making educated guesses, sets up a
 * number of pages for each of your Models. These pages have data forms that work,
 * and afford the web developer an early look at the data, and the possibility to over-ride
 * scaffolded actions with custom-made ones.
 *
 * @package Cake.Controller
 * @deprecated Dynamic scaffolding will be removed and replaced in 3.0
 */
class ScaffoldComponent extends Component {

    /**
     * Scaffold object
     *
     * @var type 
     */
    public $scaffold = array();

    /**
     * Controller object
     *
     * @var Controller
     */
    public $controller = null;

    /**
     * Name of the controller to scaffold
     *
     * @var string
     */
    public $name = null;

    /**
     * Name of current model this view context is attached to
     *
     * @var string
     */
    public $model = null;

    /**
     *
     * @var type 
     */
    public $modelClass = null;

    /**
     *
     * @var type 
     */
    public $modelFields = array();

    /**
     *
     * @var type 
     */
    public $modelKey = 'id';

    /**
     * Path to View.
     *
     * @var string
     */
    public $viewPath;

    /**
     * Name of layout to use with this View.
     *
     * @var string
     */
    public $layout = 'default';

    /**
     * Request object
     *
     * @var CakeRequest
     */
    public $request;

    /**
     * Response object
     *
     * @var CakeResponse 
     */
    public $response;

    /**
     * Valid session.
     *
     * @var boolean
     */
    protected $_validSession = null;

    /**
     * List of variables to collect from the associated controller
     *
     * @var array
     */
    protected $_passedVars = array(
        'layout', 'name', 'viewPath', 'request'
    );

    /**
     * Title HTML element for current scaffolded view
     *
     * @var string
     */
    public $scaffoldTitle = null;

    /**
     * Constructor for the base component class. All $settings that are also public 
     * properties will have their values changed to the matching value in $settings.
     * 
     * @param ComponentCollection $collection
     * @param type $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {

        /**
         * 
         */
        $this->response = new CakeResponse();
    }

    /**
     * CALLBACK inizilize
     * Is called before the controller’s beforeFilter method.
     * 
     * @param Controller $controller
     * @param type $settings
     */
    function initialize(Controller $controller, $settings = array()) {

        /**
         * 
         */
        $this->controller = $controller;
    }

    /**
     * CALLBACK startup
     * Is called after the controller’s beforeFilter method but before 
     * the controller executes the current action handler.
     * 
     * @param CakeRequest $request
     * @throws MissingModelException
     */
    public function startup(Controller $controller) {
        
    }

    /**
     * CALLBACK beforeRender
     * Is called after the controller executes the requested action’s logic, 
     * but before the controller’s renders views and layout.
     */
    public function beforeRender(Controller $controller) {
        
    }

    /**
     * CALLBACK shutdown
     * Is called before output is sent to the browser.
     */
    public function shutdown(Controller $controller) {
        
    }

    /**
     * Run scaffold system
     * 
     * @param Controller $controller
     * @throws MissingModelException
     */
    public function run(CakeRequest $request) {

        /**
         * 
         */
        $this->redirect = array('action' => 'index');
        $controller = $this->controller;

        $count = count($this->_passedVars);
        for ($j = 0; $j < $count; $j++) {
            $var = $this->_passedVars[$j];
            $this->{$var} = $controller->{$var};
        }

        /**
         * Load Model
         */
        $this->modelClass = $controller->modelClass;
        if (!is_object($this->controller->{$this->modelClass}))
            throw new MissingModelException($this->modelClass);
        $this->modelFields = $this->controller->{$this->modelClass}->schema();
        $this->modelKey = $this->controller->{$this->modelClass}->primaryKey;

        /**
         * 
         */
        $this->scaffoldModel = $this->controller->{$this->modelClass};
        $this->scaffoldTitle = Inflector::humanize(Inflector::underscore($this->viewPath));
        $this->scaffoldActions = $controller->scaffold;

        /**
         * 
         */
        $modelClass = $this->controller->modelClass;
        $primaryKey = $this->scaffoldModel->primaryKey;
        $displayField = $this->scaffoldModel->displayField;
        $singularVar = Inflector::variable($modelClass);
        $pluralVar = Inflector::variable($this->controller->name);
        $singularHumanName = Inflector::humanize(Inflector::underscore($modelClass));
        $pluralHumanName = Inflector::humanize(Inflector::underscore($this->controller->name));
        $schema = $this->scaffoldModel->schema();
        $scaffoldFields = array_keys($this->scaffoldModel->schema());
        $associations = $this->_associations();

        /**
         * Global scaffold setting
         */
        $this->scaffold = array(
            'title_for_layout' => Inflector::humanize($this->scaffoldTitle),
            'title_for_grid' => Inflector::humanize($this->scaffoldTitle),
            'blacklist' => array($this->modelKey, 'created', 'modified', 'updated'),
            'ignoreField' => isset($this->scaffoldModel->scaffold['ignoreField']) ? $this->scaffoldModel->scaffold['ignoreField'] : array(),
            'ignoreFieldList' => isset($this->scaffoldModel->scaffold['ignoreFieldList']) ? $this->scaffoldModel->scaffold['ignoreFieldList'] : array(),
            'fieldTypes' => isset($this->scaffoldModel->scaffold['fieldTypes']) ? $this->scaffoldModel->scaffold['fieldTypes'] : array(),
            'fieldSearch' => array(),
        );

        /**
         * Set vars for Scaffold layout
         */
        $this->controller->set(compact('modelClass', 'primaryKey', 'displayField', 'singularVar', 'pluralVar', 'singularHumanName', 'pluralHumanName', 'scaffoldFields', 'associations'));
        if ($this->controller->viewClass)
            $this->controller->viewClass = 'Scaffold';
        $this->_validSession = (isset($this->controller->Session) && $this->controller->Session->valid());

        /**
         * Call protected function _scaffold
         */
        $this->controller->set('title_for_layout', $this->scaffold['title_for_layout']);
        $this->_scaffold($request);
    }

    /**
     * When methods are now present in a controller
     * scaffoldView is used to call default Scaffold methods if:
     * `public $scaffold;` is placed in the controller's class definition.
     *
     * @param CakeRequest $request Request object for scaffolding
     * @return void
     * @throws MissingActionException When methods are not scaffolded.
     * @throws MissingDatabaseException When the database connection is undefined.
     */
    protected function _scaffold(CakeRequest $request) {

        $db = ConnectionManager::getDataSource($this->scaffoldModel->useDbConfig);
        $prefixes = Configure::read('Routing.prefixes');
        $scaffoldPrefix = $this->scaffoldActions;

        /**
         * 
         */
        if (isset($db)) {
            if (empty($this->scaffoldActions)) {
                $this->scaffoldActions = array('index', 'list', 'view', 'add', 'create', 'edit', 'update', 'delete', 'export', 'import', 'filter', 'search', 'check', 'reset');
            } else {
                if (!empty($prefixes) && in_array($scaffoldPrefix, $prefixes)) :
                    $this->scaffoldActions = array(
                        $scaffoldPrefix . '_index',
                        $scaffoldPrefix . '_list',
                        $scaffoldPrefix . '_view',
                        $scaffoldPrefix . '_add',
                        $scaffoldPrefix . '_create',
                        $scaffoldPrefix . '_edit',
                        $scaffoldPrefix . '_update',
                        $scaffoldPrefix . '_delete',
                        $scaffoldPrefix . '_export',
                        $scaffoldPrefix . '_import',
                        $scaffoldPrefix . '_filter',
                        $scaffoldPrefix . '_search',
                        $scaffoldPrefix . '_check',
                        $scaffoldPrefix . '_reset',
                    );
                endif;
            }

            /**
             * 
             */
            if (in_array($request->params['action'], $this->scaffoldActions)) {
                if (!empty($prefixes)) {
                    $request->params['action'] = str_replace($scaffoldPrefix . '_', '', $request->params['action']);
                }
                switch ($request->params['action']) {
                    case 'index':
                    case 'list':
                        $this->_scaffoldIndex($request);
                        break;
                    case 'view':
                        $this->_scaffoldView($request);
                        break;
                    case 'add':
                    case 'create':
                        $this->_scaffoldSave($request, 'add');
                        break;
                    case 'edit':
                    case 'update':
                        $this->_scaffoldSave($request, 'edit');
                        break;
                    case 'delete':
                        $this->_scaffoldDelete($request);
                        break;
                    case 'filter':
                        $this->_scaffoldFilter($request);
                        break;
                    case 'search':
                        $this->_scaffoldSearch($request);
                        break;
                    case 'export':
                        $this->_scaffoldExport($request);
                        break;
                    case 'import':
                        $this->_scaffoldImport($request);
                        break;
                    case 'check':
                        $this->_scaffoldCheck($request);
                        break;
                    case 'reset':
                        $this->_scaffoldReset($request);
                        break;
                }
            } else {
                throw new MissingActionException(array('controller' => $this->controller->name, 'action' => $request->action));
            }
        } else {
            throw new MissingDatabaseException(array('connection' => $this->scaffoldModel->useDbConfig));
        }
    }

    /**
     * 
     * @param CakeRequest $request
     */
    public function displayElement(CakeRequest $request) {

        /**
         *  
          $params = $request->params;
          $controllerName = 'ciao';
          pr($request);
          exit();
         */
    }

    /**
     * Renders index action of scaffolded model.
     *
     * @param array $params Parameters for scaffolding
     * @return mixed A rendered view listing rows from Models database table
     */
    protected function _scaffoldIndex($params) {

        /**
         * Set $scaffoldFields for action LIST / INDEX
         */
        $scaffoldFields = $this->getScaffoldFields();

        /**
         * Before scaffold
         */
        if ($this->controller->beforeScaffold('index')) {

            $modelClass = $this->controller->modelClass;

            // Set default $option
            $option = is_array($this->controller->paginate) ? $this->controller->paginate : array();

            // Scaffold Paginate
            $paginate = $this->controller->Session->check('Scaffold.Paginate.' . $modelClass) ? $this->controller->Session->read('Scaffold.Paginate.' . $modelClass) : array();
            $option = array_merge($paginate, $option);

            // Scaffold PaginateLimit
            $limit = $this->controller->Session->check('Scaffold.Paginate.' . $modelClass . '.limit') ? $this->controller->Session->read('Scaffold.Paginate.' . $modelClass . '.limit') : 10;
            if (isset($this->request->data['PaginateLimit']['number'])) {
                $limit = intval($this->request->data['PaginateLimit']['number']);
                unset($this->request->params['named']['page']);
            }
            $option = array_merge($option, array('limit' => $limit));

            // Scaffold PaginatePage
            $page = isset($this->request->params['named']['page']) ? intval($this->request->params['named']['page']) : 1;
            $option = array_merge($option, array('page' => $page));

            // Scaffold Ordering
            if (isset($this->request->params['named']['sort']) && ($this->request->params['named']['direction'])) {
                $order = $modelClass . '.' . trim($this->request->params['named']['sort']) . ' ' . trim($this->request->params['named']['direction']);
                $option = array_merge($option, array('order' => $order));
            }

            /**
             * Set $filter_opt from Scaffold Filter for subset data
             */
            $filter_opt = ($this->controller->Session->check('Scaffold.Filter.' . $modelClass)) ? ($this->controller->Session->read('Scaffold.Filter.' . $modelClass)) : array();

            /**
             * Set $search_opt from Scaffold Search in dataset
             */
            $search_opt = ($this->controller->Session->check('Scaffold.Search.' . $modelClass)) ? ($this->controller->Session->read('Scaffold.Search.' . $modelClass)) : array();

            /**
             * Set $option for data paginate
             */
            $conditions_AND = $filter_opt;
            $conditions_OR = (count($search_opt) > 0) ? array('OR' => $search_opt) : array();
            $conditions = array_merge($conditions_AND, $conditions_OR);
            $option = array_merge($option, array('conditions' => $conditions));

            /**
             * Scaffold Export ???????????????????
             */
            if (isset($this->request->data['ScaffoldExport']['export'])) {
                $this->controller->redirect(array('action' => 'export'));
                return;
            }

            /**
             * Scaffold Import ???????????????????
             */
            if (isset($this->request->data['ScaffoldImport']['file'])) {
                $this->_fileImport();
                $this->controller->redirect(array('action' => 'import'));
                return;
            }

            /**
             * Retrieves data
             */
            $this->scaffoldModel->recursive = -1;
            $this->controller->paginate = array_merge($this->controller->paginate, $option);

            /**
             * Set scaffold options for layout
             */
            $options = array(
                'action' => 'index',
                'actionLabel' => 'List',
                'actionView' => 'index',
                'data' => $this->controller->paginate(),
                'paginate' => $this->controller->paginate,
                'fields' => $scaffoldFields
            );
            $this->scaffold = array_merge($this->scaffold, $options);

            /**
             * Set variables before render layout
             */
            $this->controller->Session->write('Scaffold.Paginate.' . $modelClass, $this->controller->paginate);
            $this->controller->set('scaffold', $this->scaffold);
            $this->controller->render($this->request['action'], $this->layout);
        } else {

            /**
             * Error
             */
            if ($this->controller->scaffoldError('index') === false) {
                return $this->_scaffoldError();
            }
        }
    }

    /**
     * Renders an add or edit action for scaffolded model.
     *
     * @param string $action Action (add or edit)
     * @return void
     */
    protected function _scaffoldForm($action = 'edit') {

        /**
         * Action 'add'
         */
        if ($action == 'add') :

            /**
             * Set $scaffoldFields for action ADD
             */
            $scaffoldIgnoreField = array_merge($this->scaffold['ignoreField'], $this->scaffold['ignoreFieldList'], $this->scaffold['blacklist']);
            $scaffoldFields = array();
            foreach ($this->modelFields as $key => $val) :
                if (!in_array($key, $scaffoldIgnoreField)) :
                    $scaffoldFields[$key] = $val;
                endif;
            endforeach;

            /**
             * Set scaffold options for layout
             */
            $options = array(
                'action' => 'add',
                'actionButton' => 'Add',
                'actionLabel' => 'Add',
                'actionView' => 'add',
                'data' => array(), // IMPORTANT! Will use default values.
                'fields' => $scaffoldFields
            );
            $this->scaffold = array_merge($this->scaffold, $options);

            /**
             * 
             */
            $this->controller->set('scaffold', $this->scaffold);
            $this->controller->render($this->request['action'], $this->layout);
        endif;

        /**
         * Action 'edit'
         */
        if ($action == 'edit') :

            /**
             * 
             */
            if (!$this->scaffoldModel->exists())
                throw new NotFoundException(__d('cake', 'Invalid %s', Inflector::humanize($this->modelKey)));

            /**
             * 
             */
            if (isset($request->params['pass'][0]))
                $this->scaffoldModel->id = $request->params['pass'][0];

            /**
             * 
             */
            $this->scaffoldModel->recursive = -1;
            $this->controller->request->data = $this->scaffoldModel->read();

            /**
             * Set $scaffoldFields for action ADD
             */
            $scaffoldIgnoreField = array_merge($this->scaffold['ignoreField'], $this->scaffold['ignoreFieldList'], $this->scaffold['blacklist']);
            $scaffoldFields = array();
            foreach ($this->modelFields as $key => $val) :
                if (!in_array($key, $scaffoldIgnoreField)) :
                    $scaffoldFields[$key] = $val;
                endif;
            endforeach;

            /**
             * Set scaffold options for layout
             */
            $options = array(
                'action' => 'edit',
                'actionButton' => 'Save',
                'actionLabel' => 'Edit',
                'actionView' => 'edit',
                'data' => $this->request->data,
                'fields' => $scaffoldFields
            );
            $this->scaffold = array_merge($this->scaffold, $options);

            /**
             * 
             */
            $this->controller->set('scaffold', $this->scaffold);
            $this->controller->render($this->request['action'], $this->layout);
        endif;
    }

    /**
     * Renders a view action of scaffolded model.
     *
     * @param CakeRequest $request Request Object for scaffolding
     * @return mixed A rendered view of a row from Models database table
     * @throws NotFoundException
     */
    protected function _scaffoldView(CakeRequest $request) {

        /**
         * 
         */
        if ($this->controller->beforeScaffold('view')) {

            /**
             * 
             */
            if (isset($request->params['pass'][0])) {
                $this->scaffoldModel->id = $request->params['pass'][0];
            }

            /**
             * 
             */
            if (!$this->scaffoldModel->exists()) {
                throw new NotFoundException(__d('cake', 'Invalid %s', Inflector::humanize($this->modelKey)));
            }

            /**
             * Retrieves data
             */
            $this->scaffoldModel->recursive = -1;
            $this->controller->request->data = $this->scaffoldModel->read();

            /**
             * Set $scaffoldFields for action ADD
             */
            $scaffoldIgnoreField = array_merge($this->scaffold['ignoreField'], $this->scaffold['ignoreFieldList'], $this->scaffold['blacklist']);
            $scaffoldFields = array();
            foreach ($this->modelFields as $key => $val) :
                if (!in_array($key, $scaffoldIgnoreField)) :
                    $scaffoldFields[$key] = $val;
                endif;
            endforeach;

            /**
             * Set scaffold options for layout
             */
            $options = array(
                'action' => 'view',
                'actionLabel' => 'View',
                'actionView' => 'view',
                'data' => $this->request->data,
                'fields' => $scaffoldFields
            );
            $this->scaffold = array_merge($this->scaffold, $options);

            /**
             * Render
             */
            $this->controller->set('scaffold', $this->scaffold);
            $this->controller->render($this->request['action'], $this->layout);
        } else {

            /**
             * Error
             */
            if ($this->controller->scaffoldError('view') === false)
                return $this->_scaffoldError();
        }
    }

    /**
     * Saves or updates the scaffolded model.
     *
     * @param CakeRequest $request Request Object for scaffolding
     * @param string $action add or edit
     * @return mixed Success on save/update, add/edit form if data is empty or error if save or update fails
     * @throws NotFoundException
     */
    protected function _scaffoldSave(CakeRequest $request, $action = 'edit') {

        $formAction = 'edit';
        $success = __d('cake', 'updated');
        if ($action === 'add') {
            $formAction = 'add';
            $success = __d('cake', 'saved');
        }

        if ($this->controller->beforeScaffold($action)) {
            if ($action === 'edit') {
                if (isset($request->params['pass'][0])) {
                    $this->scaffoldModel->id = $request['pass'][0];
                }
                if (!$this->scaffoldModel->exists()) {
                    throw new NotFoundException(__d('cake', 'Invalid %s', Inflector::humanize($this->modelKey)));
                }
            }

            if (!empty($request->data)) {

                if ($action === 'create') {
                    $this->scaffoldModel->create();
                }

                if ($this->scaffoldModel->save($request->data)) {

                    /**
                     * 
                     */
                    if ($this->controller->afterScaffoldSave($action)) {
                        $message = __d('cake', 'The %1$s has been %2$s', Inflector::humanize($this->modelKey), $success
                        );
                        return $this->_sendMessage($message);
                    }

                    return $this->controller->afterScaffoldSaveError($action);


                    $this->controller->Session->setFlash(__d('cake', 'The %1$s has been %2$s', Inflector::humanize($this->modelKey), $success));
                }
                if ($this->_validSession) {
                    // $this->controller->Session->setFlash(__d('cake', 'Please correct errors below.'));
                }
            }

            if (empty($request->data)) {
                if ($this->scaffoldModel->id) {
                    $this->controller->data = $request->data = $this->scaffoldModel->read();
                } else {
                    $this->controller->data = $request->data = $this->scaffoldModel->create();
                }
            }

            foreach ($this->scaffoldModel->belongsTo as $assocName => $assocData) {
                $varName = Inflector::variable(Inflector::pluralize(
                                        preg_replace('/(?:_id)$/', '', $assocData['foreignKey'])
                ));
                $this->controller->set($varName, $this->scaffoldModel->{$assocName}->find('list'));
            }
            foreach ($this->scaffoldModel->hasAndBelongsToMany as $assocName => $assocData) {
                $varName = Inflector::variable(Inflector::pluralize($assocName));
                $this->controller->set($varName, $this->scaffoldModel->{$assocName}->find('list'));
            }

            return $this->_scaffoldForm($formAction);
        } elseif ($this->controller->scaffoldError($action) === false) {
            return $this->_scaffoldError();
        }
    }

    /**
     * Performs a delete on given scaffolded Model.
     *
     * @param CakeRequest $request Request for scaffolding
     * @return mixed Success on delete, error if delete fails
     * @throws MethodNotAllowedException When HTTP method is not a DELETE
     * @throws NotFoundException When id being deleted does not exist.
     */
    protected function _scaffoldDelete(CakeRequest $request) {

        if (!$request->is('post')) {
            $this->controller->set('message', 'Delete confirm?');
            $this->controller->render($request->action, $this->layout);
        } else {
            $id = false;
            if (isset($request->params['pass'][0])) {
                $id = $request->params['pass'][0];
            }
            $this->scaffoldModel->id = $id;
            if (!$this->scaffoldModel->exists()) {
                throw new NotFoundException(__d('cake', 'Invalid %s', Inflector::humanize($this->modelClass)));
            }
            if ($this->scaffoldModel->delete()) {
                $this->controller->Session->setFlash(__d('cake', 'The %1$s has been %2$s deleted', Inflector::humanize($this->modelKey), 'ok'));
            }
            // Redirect to index
            $this->controller->redirect(array('action' => 'index'));
            return;
        }
    }

    /**
     * Scaffold Filter
     * 
     * @param CakeRequest $request
     * @return type
     */
    protected function _scaffoldFilter(CakeRequest $request) {

        $modelClass = $this->controller->modelClass;

        /**
         * Set Scaffold.Paginate session
         */
        if (isset($this->request->data['ScaffoldFilter']['filter'])) {

            /**
             * Unset Scaffold.Paginate session
             */
            if ($this->controller->Session->check('Scaffold.Paginate'))
                $this->controller->Session->write('Scaffold.Paginate', array());

            /**
             * Set Scaffold.Paginate session
             */
            $this->controller->Session->write('Scaffold.Paginate', array());

            /**
             * Redirect to index
             */
            $this->controller->redirect(array('action' => 'index'));
            return;
        }

        /**
         * Render view for $request->action
         */
        $this->controller->render($request->action, $this->layout);
        return;
    }

    /**
     * Scaffold Search
     * 
     * @param CakeRequest $request
     * @return type
     */
    protected function _scaffoldSearch(CakeRequest $request) {

        $modelClass = $this->controller->modelClass;
        $scaffoldFields = $this->getScaffoldFields();
        $search_txt = '';
        $search_opt = array();

        /**
         * Set $scaffoldFieldSearch
         */
        $scaffoldFieldSearch = array();
        foreach ($scaffoldFields as $key => $field):
            if ($field['type'] == 'string' || $field['type'] == 'text')
                $scaffoldFieldSearch[] = $key;
        endforeach;

        /**
         * Set Scaffold.Search session
         */
        if (isset($this->request->data['ScaffoldSearch']['search'])) {
            $search_txt = trim($this->request->data['ScaffoldSearch']['search']);
            if (trim($search_txt) != '') {
                foreach ($scaffoldFieldSearch as $field) :
                    $search_opt = array_merge($search_opt, array($modelClass . '.' . $field . ' LIKE' => '%' . $search_txt . '%'));
                endforeach;
                $this->controller->Session->write('Scaffold.Search.' . $modelClass, $search_opt);
            } else {
                $this->controller->Session->delete('Scaffold.Search.' . $modelClass);
            }

            /**
             * Reset page number in Scaffold.Paginate session
             */
            if ($this->controller->Session->check('Scaffold.Paginate.' . $modelClass)) :
                $paginate = $this->controller->Session->read('Scaffold.Paginate.' . $modelClass);
                $paginate = array_merge($paginate, array('page' => 1));
                $this->controller->Session->write('Scaffold.Paginate.' . $modelClass, $paginate);
            endif;
        }

        /**
         * Redirect to index
         */
        $this->controller->redirect(array('action' => 'index'));
        return;
    }

    /**
     * Save $this->Session->write('Scaffold.Check')
     * mainly used in scaffold/index element
     * 
     * @param CakeRequest $request
     */
    protected function _scaffoldCheck(CakeRequest $request) {
        if (isset($this->request->data['check']))
            $this->controller->Session->write('Scaffold.Check.' . $this->modelClass, $this->request->data['check']);
        exit(-1);
    }

    /**
     * Scaffold Export
     * 
     * @param CakeRequest $request
     * @return type
     */
    protected function _scaffoldExport(CakeRequest $request) {

        if (!$request->is('post')) {
            $this->controller->render($request->action, $this->layout);
        } else {

            $modelClass = $this->controller->modelClass;

            /**
             * Set sheet for PHPExcel
             */
            $sheet = array();
            $data = $this->controller->paginate();
            $iter = 1;

            foreach ($data as $item) :
                foreach ($item[$modelClass] as $k => $v) :
                    if ($iter == 1) {
                        $sheet[$iter - 1][] = $k;
                        $sheet[$iter][] = $v;
                    } else {
                        $sheet[$iter][] = $v;
                    }
                endforeach;
                $iter++;
            endforeach;

            /**
             * csv
             * 
              if (false) {

              $this->controller->response->download("export.csv");

              if ($this->controller->beforeScaffold('export')) {
              $this->scaffoldModel->recursive = 0;
              $data = $this->controller->paginate();

              pr($data);
              exit;

              $this->controller->set(compact('data'));
              $this->controller->layout = 'ajax';
              return;
              } elseif ($this->controller->scaffoldError('export') === false) {
              return $this->_scaffoldError();
              }
              }
             * 
             */
            /**
             * Export File, use protected function
             */
            $title = Inflector::humanize(Inflector::underscore($modelClass));
            $filename = Inflector::humanize($modelClass);
            $this->_fileExport($sheet, $title, $filename);
        }
    }

    /**
     * Scaffold Import
     * 
     * @param string $filename
     * @return type
     */
    protected function _scaffoldImport(CakeRequest $request) {

        if (!$request->is('post')) {
            $this->controller->render($request->action, $this->layout);
        } else {

            if ($this->controller->Session->check('Scaffold.Import.' . $this->controller->modelClass . '.data')) {

                $data = $this->controller->Session->read('Scaffold.Import.' . $this->controller->modelClass . '.data');
                $schema = $this->scaffoldModel->schema();
                // pr($schema);
                // exit();
                // Create default array schema
                foreach ($this->scaffoldModel->schema() as $k => $v):
                    $fieldsName[] = $k;
                endforeach;

                $item = array();
                $iter = 0;
                foreach ($data as $row) :
                    if ($iter > 0) :

                        foreach ($fieldsName as $k => $v) :
                            if (array_key_exists($k, $row))
                                $item[$this->controller->modelClass][$v] = trim($row[$k]);
                        endforeach;

                        // set default $id
                        $id = $row[0];

                        // we have an id, so we update
                        if ($id > 0)
                            $this->scaffoldModel->id = $id;
                        else
                            $this->scaffoldModel->create();

                        // save the row
                        if (!$this->scaffoldModel->save($item))
                            $this->controller->flash($message, 'error');
                    endif;

                    $iter++;
                endforeach;

                /**
                 * Reset 'Scaffold.Import.' . $this->controller->modelClass
                 */
                $this->controller->Session->delete('Scaffold.Import.' . $this->controller->modelClass);
            }

            // Redirect to index
            $this->controller->redirect(array('action' => 'index'));
            return;
        }
    }

    /**
     * Unset session Scaffold and return INDEX action
     * 
     * @param CakeRequest $request
     */
    protected function _scaffoldReset(CakeRequest $request) {

        /**
         * Unset Scaffold session
         */
        if ($this->controller->Session->check('Scaffold'))
            $this->controller->Session->write('Scaffold', array());

        /**
         * Return to INDEX
         */
        return $this->controller->redirect(array('action' => 'index'));
    }

    /**
     * File Upload - Example
     */
    protected function _fileUpload() {
        if (!empty($this->request->data[$this->name]['field']['tmp_name']) && is_uploaded_file($this->request->data[$this->name]['field']['tmp_name'])) {
            $basename = basename($this->request->data[$this->name]['field']['name']);
            $fullpath = WWW_ROOT . 'documents' . DS . $basename;
            try {
                if (!move_uploaded_file($this->request->data[$this->name]['field']['tmp_name'], $fullpath))
                    echo 'errore upload';
            } catch (RuntimeException $e) {
                echo $e->getMessage();
            }
        } else {
            echo 'Error...';
            exit(-1);
        }
    }

    /**
     * File Download - Example
     */
    protected function _fileDownload() {
        
    }

    /**
     * File Export
     * Use Vendor class PHPExcel
     * 
     * @throws CacheException
     */
    protected function _fileExport($sheet = array(), $title = null, $filename = 'aoBuilderExport') {

        /**
         * Load Vendors - Excel 2007
         */
        App::import('Vendor', 'PHPExcel/Classes/PHPExcel');
        if (!class_exists('PHPExcel'))
            throw new CacheException('Vendor class PHPExcel not found!');

        /**
         * Set file properties
         */
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("aoBuilder");
        $objPHPExcel->getProperties()->setLastModifiedBy("aoBuilder");
        $objPHPExcel->getProperties()->setTitle("Office Document");
        $objPHPExcel->getProperties()->setSubject("Office Document");
        $objPHPExcel->getProperties()->setDescription("This document was generated using PHPExcel with aoBuilder.");

        /**
         * Set active sheet
         */
        $objPHPExcel->getActiveSheet()->setTitle($title);
        for ($i = 1; $i <= count($sheet); $i++) :
            $row = $sheet[$i - 1];
            $objPHPExcel->getActiveSheet()->fromArray($row, NULL, 'A' . $i);
        endfor;

        /**
         * Write new file EXCEL 2007
         */
        App::import('Vendor', 'PHPExcel/Classes/PHPExcel/Writer/Excel2007');
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:inline;filename=' . $filename . '_' . date('Ymd') . '.xlsx ');
        $objWriter->save('php://output');
        return;
    }

    /**
     * File Import
     * Use Vendor class PHPExcel
     * 
     * @throws CacheException
     */
    protected function _fileImport() {

        /**
         * Load Vendors
         */
        App::import('Vendor', 'PHPExcel/Classes/PHPExcel');
        if (!class_exists('PHPExcel'))
            throw new CacheException('Vendor class PHPExcel not found!');

        /**
         * Save session Scaffold.Import.$controller.file
         */
        $this->controller->Session->write('Scaffold.Import.' . $this->controller->modelClass . '.file', $this->request->data['ScaffoldImport']['file']);

        /**
         * Read uploaded file
         */
        $file = $this->request->data['ScaffoldImport']['file']['tmp_name'];
        $this->request->data = null;

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        /**
         * Create Session -> Scaffold.Import.$controller
         */
        $items = array();
        foreach ($objWorksheet->getRowIterator() as $row) {
            $item = array();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $item[] = $cell->getValue();
            }
            $itmes[] = $item;
        }

        /**
         * Save session Scaffold.Import.$controller.data
         */
        $this->controller->Session->write('Scaffold.Import.' . $this->controller->modelClass . '.data', $itmes);

        return;
    }

    /**
     * Sends a message to the user. Either uses Sessions or flash messages depending
     * on the availability of a session
     *
     * @param string $message Message to display
     * @return void
     */
    protected function _sendMessage($message) {
        if ($this->_validSession) {
            $this->controller->Session->setFlash($message);
            return $this->controller->redirect($this->redirect);
        }
        $this->controller->flash($message, $this->redirect);
    }

    /**
     * Show a scaffold error
     *
     * @return mixed A rendered view showing the error
     */
    protected function _scaffoldError() {
        return $this->controller->render('error', $this->layout);
    }

    /**
     * Returns associations for controllers models.
     *
     * @return array Associations for model
     */
    protected function _associations() {

        $keys = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
        $associations = array();

        foreach ($keys as $type) {
            foreach ($this->scaffoldModel->{$type} as $assocKey => $assocData) {

                $associations[$type][$assocKey]['primaryKey'] = $this->scaffoldModel->{$assocKey}->primaryKey;
                //$assocKeyModel->primaryKey;

                $associations[$type][$assocKey]['displayField'] = $this->scaffoldModel->{$assocKey}->displayField;
                // $assocKeyModel->displayField;

                $associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
                // list
                list($plugin, $model) = pluginSplit($assocData['className']);

                if ($plugin) {
                    $plugin = Inflector::underscore($plugin);
                }
                $associations[$type][$assocKey]['plugin'] = $plugin;

                $associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($model));

                if ($type === 'hasAndBelongsToMany') {
                    $associations[$type][$assocKey]['with'] = $assocData['with'];
                }
            }
        }

        return $associations;
    }

    /**
     * 
     * @return type
     */
    protected function getScaffoldFields() {
        $scaffoldIgnoreField = array_merge($this->scaffold['ignoreField'], $this->scaffold['ignoreFieldList'], $this->scaffold['blacklist']);
        $scaffoldFields = array();
        foreach ($this->modelFields as $key => $val) :
            if (!in_array($key, $scaffoldIgnoreField)) :
                $scaffoldFields[$key] = $val;
            endif;
        endforeach;

        return $scaffoldFields;
    }

    /**
     * 
     * @return type
     */
    protected function getScaffoldFieldSearch() {
        $scaffoldFields = $this->getScaffoldFields();
        $scaffoldFieldSearch = array();
        foreach ($scaffoldFields as $key => $field):
            if ($field['type'] == 'string' || $field['type'] == 'text')
                $scaffoldFieldSearch[] = $key;
        endforeach;

        return $scaffoldFieldSearch;
    }

}
