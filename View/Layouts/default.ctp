<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>aoBuilder for CakePHP application</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
</head>
<div id="content" class="container">
    <div id="header">
        <br/>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo $this->Html->url('/builder'); ?>"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> aoBuilder</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo $this->Html->url('/builder/pages/objects'); ?>">Objects</a></li>
                        <li><a href="<?php echo $this->Html->url('/builder/pages/scaffolds'); ?>">Scaffolds</a></li>
                        <li><a href="<?php echo $this->Html->url('/builder/pages/settings'); ?>">Settings</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo $this->Html->url('/'); ?>"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Go to App</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="content">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
    <div id="footer">
        <hr/>
        <div class="row" style="padding: 10px">
            <div class="col-sm-6 text-left"><small>&copy <?php echo date('Y'); ?> <?php echo Configure::read('builder.copyright_text'); ?></small></div>
            <div class="col-sm-6 text-right"><small>powered by <strong>aoBuilder</strong> in CakePHP</small></div>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>   

