<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo Configure::read('Builder.app_name'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo $this->Html->meta('icon'); ?>
        <!-- CSS styles -->
        <?php echo $this->Html->css('/Builder/css/bootstrap.min'); ?>
        <?php echo $this->Html->css('/Builder/css/bootstrap-theme.min'); ?>
        <!-- JS scripts -->
        <?php echo $this->Html->script('/Builder/js/jquery-1.11.1.min'); ?>
        <?php echo $this->Html->script('/Builder/js/bootstrap.min'); ?>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- JQuery UI -->        
        <?php echo $this->Html->css('/Builder/css/jquery-ui'); ?>
        <?php echo $this->Html->script('/Builder/js/jquery-ui.min'); ?>
        <!-- Custom setting -->
        <?php echo $this->Html->css('/Builder/css/styles_login'); ?>
        <?php echo $this->Html->script('/Builder/js/scripts'); ?>
        <style>
            body {
                background-image: url('<?php echo Configure::read('Builder.login_image'); ?>');
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center center;
            }
            .well{
                opacity: 0.95;
                filter: alpha(opacity=95); /* For IE8 and earlier */
            }   
        </style>
    </head>
    <div id="container" class="container">
        <div id="header">
            <h1><?php echo Configure::read('Builder.app_name'); ?></h1>
        </div>
        <div id="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
        <div id="footer">
            <hr/>
            <div class="row" style="padding: 10px">
                <div class="col-sm-6 text-left"><small>&copy <?php echo date('Y'); ?> <?php echo Configure::read('Builder.copyright_text'); ?></small></div>
                <div class="col-sm-6 text-right"><small>powered by <strong>aoBuilder</strong> in CakePHP</small></div>
            </div>
        </div>
    </div>
</body>
</html>        

