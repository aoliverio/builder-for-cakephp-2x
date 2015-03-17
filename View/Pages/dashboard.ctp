<div class="page-header">
    <h2>Dashboard <small>Pannello di Controllo</small></h2>
</div>
<?php
/**
 * Load definded Admin.dashboard
 */
$dashboard = Configure::read('Admin.dashboard');
if (is_array($dashboard)) :
    foreach ($dashboard as $row) :
        echo $this->element($row['element']);
    endforeach;
endif;
?>