<div class="page-header">
    <h2>SECURITY <small>save the settings to basic safety</small></h2>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Description</th>
            <th>Monitor</th>
            <th>Actions</th>
        </tr>   

    </thead>
    <tbody>
        <tr>
            <td>Table Users</td>
            <td></td>
            <td class="text-right">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array('controller' => 'user')); ?>"><span class="glyphicon glyphicon-list"></span> List</a>
            </td>
        </tr>   
        <tr>
            <td>Table Roles</td>
            <td></td>
            <td class="text-right">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array('controller' => 'role')); ?>"><span class="glyphicon glyphicon-list"></span> List</a>
            </td>
        </tr>
        <tr>
            <td>Table Tasks</td>
            <td></td>
            <td class="text-right">
                <a class="btn btn-default" href="<?php echo $this->Html->url(array('controller' => 'task')); ?>"><span class="glyphicon glyphicon-list"></span> List</a>
            </td>
        </tr>        
    </tbody>
</table>