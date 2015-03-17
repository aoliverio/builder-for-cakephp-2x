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
$brand = isset($navbar[0]) && is_array($navbar[0]) ? $navbar[0] : array();
$navbar_left = isset($navbar[1]) && is_array($navbar[1]) ? $navbar[1] : array();
$navbar_right = isset($navbar[2]) && is_array($navbar[2]) ? $navbar[2] : array();
?>

<!-- Elements navbar -->
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
            $label = isset($brand['label']) ? trim($brand['label']) : '';
            $url = is_array($brand['url']) ? $brand['url'] : array();
            if (trim($label) != '')
                echo $this->Html->link(__($label), $url, array('class' => 'navbar-brand'));
            ?>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php if (isset($navbar_left)): ?>
                <ul class="nav navbar-nav">
                    <?php foreach ($navbar_left as $nav): ?>
                        <?php
                        $icon = isset($nav['glyphicon']) ? '<span class="glyphicon glyphicon-' . $nav['glyphicon'] . '"></span> ' : '';
                        $text = Inflector::humanize(Inflector::underscore($nav['label']));
                        $link = $icon . $text;
                        ?>                          
                        <li <?php echo $nav['url']['controller'] == $this->request['controller'] ? ' class="active"' : ''; ?>><?php echo $this->Html->link($link, $nav['url'], array('escape' => false)); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (isset($navbar_left)): ?>
                <ul class="nav navbar-nav navbar-right">
                    <?php foreach ($navbar_right as $nav): ?>
                        <?php
                        $icon = isset($nav['glyphicon']) ? '<span class="glyphicon glyphicon-' . $nav['glyphicon'] . '"></span> ' : '';
                        $text = Inflector::humanize(Inflector::underscore($nav['label']));
                        $link = $icon . $text;
                        ?>         
                        <li <?php echo $nav['url']['controller'] == $this->request['controller'] ? ' class="active"' : ''; ?>><?php echo $this->Html->link($link, $nav['url'], array('escape' => false)); ?></li>
                    <?php endforeach; ?>                
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>