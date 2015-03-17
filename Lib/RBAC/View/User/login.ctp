<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h4><span class="glyphicon glyphicon-log-in"></span> <?php echo Configure::read('Builder.app_name'); ?></h4>
            </div>
            <div class="panel-body">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('User', array('controller' => 'Users', 'action' => 'login')); ?>
                <p><?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'insert username ...')); ?></p>
                <p><?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'insert password ...',)); ?></p>
                <div class="text-right"><?php echo $this->Form->button('Login', array('type' => 'submit', 'class' => 'btn btn-success')); ?></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>