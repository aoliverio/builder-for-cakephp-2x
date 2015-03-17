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
 * Related data
 */
if (!empty($associations['hasOne'])) :
    foreach ($associations['hasOne'] as $_alias => $_details):
        ?>
        <div class="panel panel-default ">
            <div class="panel-heading">
                <h4><?php echo Inflector::humanize($_details['controller']) ?>
                    <small><?php echo __d('cake', "Related"); ?></small>

                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                            Actions <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><?php
                                echo $this->Html->link(
                                        __d('cake', 'Edit %s', Inflector::humanize(Inflector::underscore($_alias))), array('controller' => $_details['controller'], 'action' => 'edit', ${$singularVar}[$_alias][$_details['primaryKey']])
                                );
                                echo "</li>\n";
                                ?>
                        </ul>                
                    </div>
                </h4>
            </div>    
            <div class="panel-body">
                <?php if (!empty(${$singularVar}[$_alias])): ?>
                    <dl class="dl-horizontal">
                        <?php
                        $otherFields = array_keys(${$singularVar}[$_alias]);
                        foreach ($otherFields as $_field) {
                            echo "\t\t<dt>" . Inflector::humanize($_field) . "</dt>\n";
                            echo "\t\t<dd>\n\t" . ${$singularVar}[$_alias][$_field] . "\n&nbsp;</dd>\n";
                        }
                        ?>
                    </dl>
                <?php endif; ?>
            </div>
            <div class="panel-footer">&nbsp;</div>
        </div>
        <?php
    endforeach;
endif;

/**
 * Related multiple - HasMany
 */
if (empty($associations['hasMany'])) {
    $associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
    $associations['hasAndBelongsToMany'] = array();
}

$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);

foreach ($relations as $_alias => $_details):

    $otherSingularVar = Inflector::variable($_alias);
    ?>
    <div class="panel panel-default ">
        <div class="panel-heading">
            <h4>
                <?php echo Inflector::humanize($_details['controller']) ?>
                <small><?php echo __d('cake', "Related"); ?></small>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php
                            echo $this->Html->link(
                                    __d('cake', "New %s", Inflector::humanize(Inflector::underscore($_alias))), array('controller' => $_details['controller'], 'action' => 'add', '?' => $_details['foreignKey'] . '=' . $this->model->id)
                            );
                            ?> </li>
                    </ul>
                </div>
            </h4>
        </div>
        <?php if (!empty(${$singularVar}[$_alias])): ?>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-condensed table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width:30px"><?php echo $this->Form->checkbox('check_all', array('hiddenField' => false)); ?></th>
                                <?php
                                $otherFields = array_keys(${$singularVar}[$_alias][0]);
                                if (isset($_details['with'])) {
                                    $index = array_search($_details['with'], $otherFields);
                                    unset($otherFields[$index]);
                                }
                                foreach ($otherFields as $_field) {
                                    if (!$simple_view || ($simple_view && !in_array($_field, array('id', 'created', 'updated', 'create_user_id', 'update_user_id'))))
                                        echo "\t\t<th>" . Inflector::humanize($_field) . "</th>\n";
                                }
                                ?>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach (${$singularVar}[$_alias] as ${$otherSingularVar}):
                                ?>

                                <tr>
                                    <td class=""><?php echo $this->Form->checkbox('check_all', array('hiddenField' => false)); ?></td>

                                    <?php
                                    foreach ($otherFields as $_field) {
                                        if (!$simple_view || ($simple_view && !in_array($_field, array('id', 'created', 'updated', 'create_user_id', 'update_user_id'))))
                                            echo "\t\t\t<td>" . ${$otherSingularVar}[$_field] . "</td>\n";
                                    }
                                    ?>
                                    <td class="text-right" style="min-width: 90px">
                                        <?php echo $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-search"></span>'), array('plugin' => 'admin', 'action' => 'view', ${$otherSingularVar}['id']), array('class' => 'btn btn-xs btn-info', 'escape' => FALSE)); ?>
                                        <?php echo $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-pencil"></span>'), array('plugin' => 'admin', 'action' => 'edit', ${$otherSingularVar}['id']), array('class' => 'btn btn-xs btn-warning', 'escape' => FALSE)); ?>
                                        <?php echo $this->Form->postLink(__d('cake', '<span class="glyphicon glyphicon-trash"></span>'), array('plugin' => 'admin', 'action' => 'delete', ${$otherSingularVar}['id']), array('class' => 'btn btn-xs btn-danger', 'escape' => FALSE), __d('cake', 'Are you sure you want to delete %s %s?', $modelClass, ${$singularVar}[$modelClass][$primaryKey])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <div class="panel-footer">
            <div class="row">
                <div class="col-lg-6">        
                    <div class="">
                        <p><small>Total records <?php echo count(${$singularVar}[$_alias]); ?></small></p>
                    </div>
                </div>    
                <div class="col-lg-6">&nbsp;</div>
            </div>
        </div>       
    </div>

    <?php
    $modelClass = Inflector::humanize($_details['controller']);
    $fields = $otherFields;
    $data = array();
    foreach (${$singularVar}[$_alias] as $row) :
        $data[][$modelClass] = $row;
    endforeach;

    /**
     * Set Datagrid parameter
     */
    $datagrid = array(
        'modelClass' => $modelClass,
        'fields' => $fields,
        'data' => $data
    );
    echo $this->element('Datagrid/index', array('datagrid' => $datagrid));
    ?>

<?php endforeach; ?>
