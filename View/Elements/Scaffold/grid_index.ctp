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
$datagrid_footer = TRUE;

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
$scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> Add %s', $singularHumanName), array('action' => 'add'), array('escape' => false)) . '</li>';
$scaffoldActions .= '<li><a href="' . $this->Html->url(array('action' => 'export')) . '" data-toggle="modal" data-target="#modalScaffold"><span class="glyphicon glyphicon-export"></span> Export ' . $singularHumanName . '</a><li>';
$scaffoldActions .= '<li><a href="' . $this->Html->url(array('action' => 'import')) . '" data-toggle="modal" data-target="#modalScaffold"><span class="glyphicon glyphicon-import"></span> Import ' . $singularHumanName . '</a><li>';
$scaffoldActions .= '<li><a href="' . $this->Html->url(array('action' => 'filter')) . '" data-toggle="modal" data-target="#modalScaffold"><span class="glyphicon glyphicon-filter"></span> Filter ' . $singularHumanName . '</a><li>';
$done = array();
foreach ($associations as $_type => $_data) {
    foreach ($_data as $_alias => $_details) {
        if ($_details['controller'] != $this->name && !in_array($_details['controller'], $done)) {
            $scaffoldActions .= '<li class="divider"></li>';
            $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-plus-sign"></span> Add %s', Inflector::humanize(Inflector::underscore($_alias))), array('controller' => $_details['controller'], 'action' => 'add'), array('escape' => false)) . '</li>';
            $scaffoldActions .= '<li>' . $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-list-alt"></span> List %s', Inflector::humanize($_details['controller'])), array('controller' => $_details['controller'], 'action' => 'index'), array('escape' => false)) . '</li>';
            $done[] = $_details['controller'];
        }
    }
}

/**
 * Paginator setting
 */
$paginate_count = intval($this->Paginator->counter(array('format' => __d('cake', '{:count}'))));
$paginate_total_pages = intval($this->Paginator->counter(array('format' => __d('cake', '{:pages}'))));
$paginate_current_page = intval($this->Paginator->counter(array('format' => __d('cake', '{:page}'))));
$paginate_first = $this->Html->url('/' . $this->request->params['controller'] . '/index/page:1');
$paginate_prev = '';
if ($paginate_current_page > 1)
    $paginate_prev = $this->Html->url('/' . $this->request->params['controller'] . '/index/page:' . intval($paginate_current_page - 1));
$paginate_next = '';
if ($paginate_current_page < $paginate_total_pages)
    $paginate_next = $this->Html->url('/' . $this->request->params['controller'] . '/index/page:' . intval($paginate_current_page + 1));
$paginate_last = $this->Html->url('/' . $this->request->params['controller'] . '/index/page:' . $paginate_total_pages);

/**
 * 
 */
$options_page_number = array();
for ($i = 1; $i <= $paginate_total_pages; $i++) :
    $options_page_number[$i] = $i;
endfor;

$options_page_size = array();
$options_page_size[10] = '10';
$options_page_size[20] = '20';
$options_page_size[50] = '50';
$options_page_size[100] = '100';
$options_page_size[200] = '200';
$options_page_size[500] = '500';
$options_page_size[1000] = '1000';
?>

<!-- JS Script -->
<script>
    $(document).ready(function () {

        /**
         * Hide block #selected_items_action
         */
        $("#selected_items_action").hide();

        /**
         * Hide block #selected_items_action
         */
        $('#selected_hide_block').click(function () {
            $("#selected_items_action").hide();
        });

        /**
         * CheckAll
         */
        $('#checkall').click(function () {
            $('input:checkbox[class=check]').prop('checked', this.checked);
            $("#selected_items_action").show();
            checkSave();
        });

        /**
         * CheckSingleInput
         */
        $('.check').change(function () {
            $("#selected_items_action").show();
            checkSave();
        });

        /**
         * Function checkSave
         * Use admin/scaffoldCheckSave      
         */
        function checkSave() {
            var checkValues = $('input[class=check]:checked').map(function () {
                return $(this).val();
            }).get();
            // alert(checkValues);
            var url = '<?php echo $this->Html->url(array('action' => 'check')); ?>';
            $.ajax({
                url: url,
                type: 'post',
                data: {check: checkValues},
                success: function (data) {
                }
            });
        }

        /**
         * Hide Modal for data Export
         */
        $('#export-data').click(function () {
            $('#modalExport').modal('hide')
        });

        /**
         * Paginate: number records for page
         */
        $('#PaginateLimit').change(function () {
            $('#PaginateLimitIndexForm').submit();
        });

        /**
         * Paginate: direct access to the page number
         */
        $('#PaginatePage').change(function () {
            var base_url = '<?php echo $this->Html->url(array('controller' => trim($this->request->params['controller']))); ?>';
            var page_url = '/index/page:' + $('#PaginatePage').val();
            $(location).attr('href', base_url + page_url);
        });
    });
</script>

<!-- Print Scaffold Status -->
<?php pr($this->Session->read('Scaffold')); ?>

<!-- Session Flash -->
<?php echo $this->Session->flash(); ?>

<!-- Render datagrid header -->
<?php if (isset($datagrid_header)) : ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-inline">
                <div class="form-group">
                    <?php echo $this->Form->create('ScaffoldSearch', array('url' => array('controller' => $this->request->controller, 'action' => 'search'))); ?>
                    <?php
                    $search_text = '';
                    if ($this->Session->check('Scaffold.Search.' . $modelClass)) {
                        if (is_array($this->Session->read('Scaffold.Search.' . $modelClass))) {
                            foreach ($this->Session->read('Scaffold.Search.' . $modelClass) as $k => $v):
                                $search_text = str_replace('%', '', $v);
                                break;
                            endforeach;
                        }
                    }
                    ?>
                    <div class="input-group"> 
                        <input type="text" name="data[ScaffoldSearch][search]" class="form-control input-sm" value="<?php echo $search_text; ?>" placeholder="Quick search text...">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modalScaffold" title="advanced filter data"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter</button>                    
                </div>
                <?php if ($this->Session->check('Scaffold.Search.' . $modelClass)) : ?>
                    <div class="form-group">
                        <a href="<?php echo $this->Html->url(array('action' => 'reset')); ?>" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-refresh" aria-hidden="true" title="reset all active filters"></span> Reset</a>                    
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="text-right">
                <div class="form-inline">
                    <div class="form-group">
                        <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true" title="add new record"></span> Add</a>
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
    <?php if (TRUE) : ?>
        <div id="selected_items_action" class="alert alert-info alert-dismissible" role="alert">
            <div class="row">
                <div class="col-xs-12">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" title="close this block"><span aria-hidden="true">&times;</span></button>
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="btn-xs"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> <strong>Only for selected items</strong></div>
                        </div>
                        <div class="form-group">
                            <a href="" class="btn btn-default btn-sm" title="view all checked items"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span> View</a>
                        </div>
                        <div class="form-group">
                            <a href="" class="btn btn-default btn-sm" title="edit all checked items"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>
                        </div>
                        <div class="form-group">
                            <a href="" class="btn btn-danger btn-sm" title="delete all checked items"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>
                        </div>
                        <div class="form-group">
                            <a href="" class="btn btn-default btn-sm" title="export all checked items"><span class="glyphicon glyphicon-export" aria-hidden="true"></span> Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<!-- Render datagrid content -->
<?php if (isset($datagrid_content)) : ?>
    <div class="">
        <table class="table table-responsive table-condensed table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:25px">
                        <input id="checkall" class="" type="checkbox" name="" value="" />
                    </th>           
                    <?php foreach ($fields as $key => $val): ?>
                        <th><small><?php echo $this->Paginator->sort($key); ?></small></th>
                    <?php endforeach; ?>
                    <th style="min-width:100px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $item): ?>
                    <tr>
                        <td>
                            <input id="data[Check][<?php echo $modelClass ?>][<?php echo h($item[$modelClass][$primaryKey]) ?>]" class="check" type="checkbox" name="data[Check][<?php echo $modelClass ?>][<?php echo h($item[$modelClass][$primaryKey]) ?>]" value="<?php echo h($item[$modelClass][$primaryKey]) ?>" />
                        </td>
                        <?php
                        $html = null;

                        foreach ($fields as $fieldName => $fieldOptions):

                            /**
                             * 
                             */
                            if (is_numeric($fieldName) && !is_array($fieldOptions)) {
                                $fieldName = $fieldOptions;
                                $fieldOptions = array();
                            }

                            $entity = explode('.', $fieldName);

                            /**
                             * 
                             */
                            if (is_array($blacklist) && (in_array($fieldName, $blacklist) || in_array(end($entity), $blacklist)))
                                continue;

                            /**
                             * Not used in this view
                             */
                            $uid = $modelClass . '_' . $fieldName;
                            $caption = str_replace('_id', '', $fieldName);
                            $caption = Inflector::humanize($caption);

                            $fieldOptions['label'] = false;
                            $fieldOptions['div'] = false;
                            $fieldOptions['id'] = $uid;

                            /**
                             * Associations $fieldTypes
                             */
                            $isWorked = false;
                            if (!empty($associations['belongsTo'])) {
                                foreach ($associations['belongsTo'] as $_alias => $_details) {
                                    if ($fieldName === $_details['foreignKey']) {
                                        $isWorked = true;
                                        $fieldHtml = $this->Html->link(${$singularVar}[$_alias][$_details['displayField']], array('controller' => $_details['controller'], 'action' => 'view', ${$singularVar}[$_alias][$_details['primaryKey']]));
                                    }
                                }
                            }

                            /**
                             * Automatic $fieldTypes
                             */
                            if (!$isWorked) {
                                $switch = isset($fieldTypes[$fieldName]) ? $fieldTypes[$fieldName] : null;
                                switch ($switch) {
                                    case 'boolean':
                                        $checked = ($item[$modelClass][$fieldName] == 1) ? 'checked' : '';
                                        $fieldHtml = '<input type="checkbox" ' . $checked . ' disabled />';
                                        break;
                                    case 'file':
                                        $fieldHtml = $this->Html->link(h($val), h($val)) . h($val);
                                        break;
                                    case 'wysiwyg':
                                        $fieldHtml = h(String::truncate(strip_tags($fieldHtml), 110, array('ending' => '...', 'exact' => false, 'html' => true)));
                                        break;
                                    case 'wysihtml':
                                        $fieldHtml = h(String::truncate(strip_tags($fieldHtml), 110, array('ending' => '...', 'exact' => false, 'html' => true)));
                                        break;
                                    default:
                                        $fieldHtml = h(strip_tags($item[$modelClass][$fieldName]));
                                        break;
                                }
                            }

                            /**
                             * 
                             */
                            $html .= '<td>' . $fieldHtml . '</td>';

                        endforeach;
                        echo $html;
                        ?>
                        <td class="text-right">
                            <?php echo $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-zoom-in"></span>'), array('action' => 'view', $item[$modelClass][$primaryKey]), array('class' => 'btn btn-xs btn-default', 'title' => 'view item', 'escape' => FALSE)); ?>
                            <?php echo $this->Html->link(__d('cake', '<span class="glyphicon glyphicon-pencil"></span>'), array('action' => 'edit', $item[$modelClass][$primaryKey]), array('class' => 'btn btn-xs btn-default', 'title' => 'edit item', 'escape' => FALSE)); ?>
                            <a href="<?php echo $this->Html->url(array('action' => 'delete', $item[$modelClass][$primaryKey])); ?>" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalScaffold" title="delete item"><span class="glyphicon glyphicon-trash"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<!-- Render datagrid footer -->
<?php if (isset($datagrid_footer) && count($data > 0)) : ?>
    <hr/>
    <div class="row">
        <div class="col-xs-3 text-left">
            <div class="form-inline">
                <div class="form-group">
                    <span class="btn-sm"><?php echo $this->Paginator->counter(array('format' => __d('cake', 'Views: {:start} - {:end} of {:count}'))); ?></span>
                </div>        
                <div class="form-group"></div>
            </div>
        </div>
        <div class="col-xs-6 text-center">  
            <div class="form-inline">
                <div class="form-group">
                    <a href="<?php echo $paginate_first; ?>" class="btn btn-default btn-sm" title="first page"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a>
                </div>
                <div class="form-group">
                    <a href="<?php echo $paginate_prev; ?>" class="btn btn-default btn-sm" title="prev page"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span></a>
                </div>
                <div class="form-group">
                    <span class="btn-sm">Page</span>
                </div>
                <div class="form-group">
                    <?php
                    /**
                     * Page change select input
                     */
                    echo $this->Form->create('PaginatePage');
                    echo $this->Form->input('page', array(
                        'id' => 'PaginatePage',
                        'label' => FALSE,
                        'type' => 'select',
                        'class' => 'form-control input-sm',
                        'options' => $options_page_number,
                        'default' => $paginate_current_page
                            )
                    );
                    echo $this->Form->end();
                    ?>
                </div>
                <div class="form-group">
                    <span class="btn-sm">of <?php echo $paginate_total_pages; ?></span>    
                </div>
                <div class="form-group">
                    <small><a href="<?php echo $paginate_next; ?>" class="btn btn-default btn-sm" title="next page"><span class="glyphicon glyphicon-forward" aria-hidden="true"></span></a></small>
                </div>
                <div class="form-group">
                    <small><a href="<?php echo $paginate_last; ?>" class="btn btn-default btn-sm" title="last page"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></small>
                </div>
            </div>   
        </div>        
        <div class="col-xs-3 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <span class="btn-sm">Page size:</span>
                </div>
                <div class="form-group">
                    <?php
                    /**
                     * Page size select input
                     */
                    echo $this->Form->create('PaginateLimit');
                    echo $this->Form->input('number', array(
                        'id' => 'PaginateLimit',
                        'label' => FALSE,
                        'type' => 'select',
                        'class' => 'form-control input-sm',
                        'options' => $options_page_size,
                        'default' => $this->Session->check('Scaffold.Paginate.' . $modelClass . '.limit') ? intval($this->Session->read('Scaffold.Paginate.' . $modelClass . '.limit')) : '10'
                            )
                    );
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>