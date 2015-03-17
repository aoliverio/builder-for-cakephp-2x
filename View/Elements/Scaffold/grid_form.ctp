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
pr($scaffold);
extract($scaffold);

/**
 * Datagrid header
 */
$datagrid_header = TRUE;

/**
 * Datagrid content
 */
$datagrid_content = TRUE;

/**
 * Datagrid footer
 */
$datagrid_footer = FALSE;

/**
 * Ajax Request
 */
if ($this->request->is('ajax')) {
    $datagrid_header = FALSE;
    $datagrid_footer = FALSE;
}

/**
 * Set default $scaffoldActions
 */
$scaffoldActions = '';
$scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> New %s', $singularHumanName), array('action' => 'add'), array('escape' => false)) . '</li>';
$scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-list-alt"></span> List %s', $pluralHumanName), array('action' => 'index'), array('escape' => false)) . '</li>';
if (isset($data[$modelClass][$primaryKey])) :
    $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-zoom-in"></span> View %s', $singularHumanName), array('action' => 'view', $data[$modelClass][$primaryKey]), array('escape' => false)) . '</li>';
    $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-pencil"></span> Edit %s', $singularHumanName), array('action' => 'edit', $data[$modelClass][$primaryKey]), array('escape' => false)) . '</li>';
    $scaffoldActions .= '<li>' . $this->Form->postLink(__d('cake', '<span class="glyphicon glyphicon-trash"></span> Delete %s', $singularHumanName), array('action' => 'delete', $data[$modelClass][$primaryKey]), array('escape' => false), __d('cake', 'Are you sure you want to delete # %s?', $data[$modelClass][$primaryKey])) . '</li>';
endif;
$done = array();
foreach ($associations as $_type => $_data) {
    foreach ($_data as $_alias => $_details) {
        if ($_details['controller'] != $this->name && !in_array($_details['controller'], $done)) {
            $scaffoldActions .= '<li class="divider"></li>';
            $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> New %s', Inflector::humanize(Inflector::underscore($_alias))), array('controller' => $_details['controller'], 'action' => 'add'), array('escape' => false)) . '</li>';
            $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-list-alt"></span> List %s', Inflector::humanize($_details['controller'])), array('controller' => $_details['controller'], 'action' => 'index'), array('escape' => false)) . '</li>';
            $done[] = $_details['controller'];
        }
    }
}
?>

<!-- Print Scaffold Status -->
<?php pr($this->Session->read('Scaffold')); ?>

<!-- Session Flash -->
<?php echo $this->Session->flash(); ?>

<!-- Render datagrid header -->
<?php if ($datagrid_header) : ?>
    <div class="row">
        <div class="col-xs-4"></div>
        <div class="col-xs-4"></div>
        <div class="col-xs-4">
            <div class="text-right">
                <div class="form-inline">
                    <div class="form-group">
                        <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
                    </div>
                    <div class="form-group">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <?php echo $scaffoldActions; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
<?php endif; ?>

<!-- Render datagrid content -->
<?php if ($datagrid_content) : ?>
    <?php echo $this->Form->create($modelClass, array('type' => 'file')); ?>
    <?php
    $html = null;
    foreach ($scaffoldFields as $fieldName => $fieldOptions) :

        /**
         * 
         */
        if (is_numeric($fieldName) && !is_array($fieldOptions)) {
            $fieldName = $fieldOptions;
            $fieldOptions = array();
        }

        /**
         * Blacklist
         */
        $entity = explode('.', $fieldName);

        if (is_array($blacklist) && (in_array($fieldName, $blacklist) || in_array(end($entity), $blacklist)))
            continue;

        /**
         * Field settings
         */
        $uid = $modelClass . '_' . $fieldName;
        $caption = str_replace('_id', '', $fieldName);
        $caption = Inflector::humanize($caption);

        $fieldOptions['label'] = false;
        $fieldOptions['div'] = false;
        $fieldOptions['id'] = $uid;

        /**
         * Default template for scaffold row
         */
        $html .= '<div class="form-group">';
        $html .= '<label for="' . $uid . '" class="col-sm-2 control-label">' . $caption . '</label>';
        $html .= '<div class="col-sm-10">' . $this->Form->input($fieldName, $fieldOptions) . '</div>';
        $html .= '</div>';

    endforeach;
    echo $html;
    ?>
    <hr/>
    <?php echo $this->Form->end(__d('cake', $actionButton)); ?>
<?php endif; ?>

<!-- Render datagrid footer -->
<?php if ($datagrid_footer) : ?>
    <p>Footer not is implemented.</p>
<?php endif; ?>
