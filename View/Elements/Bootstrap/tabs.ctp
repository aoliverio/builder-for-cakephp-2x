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
/**
 * Use Ajax mode
 */
$AJAX_MODE = FALSE;
if (array_key_exists('ajax', $options) && $options['ajax']) :
    unset($options['ajax']);
    $AJAX_MODE = TRUE;
endif;

/**
 * Define active Tab
 */
$ACTIVE_TAB = 0;

/**
 * Validate tab element
 */
foreach ($options as $key => $tab):

    $options[$key]['id'] = (array_key_exists('id', $tab)) ? $tab['id'] : $key;
    $options[$key]['label'] = (array_key_exists('label', $tab)) ? $tab['label'] : $key;
    $options[$key]['href'] = (array_key_exists('href', $tab)) ? $tab['href'] : '#' . $key;
    $options[$key]['active'] = (array_key_exists('active', $tab) && ($tab['active'] || $tab['active']) == 1) ? 'active' : '';

    if (is_array($options[$key]['href'])):
        $options[$key]['href'] = $this->Html->url($options[$key]['href'], true);
    endif;

    if (array_key_exists('active', $tab) && ($tab['active'] || $tab['active']) == 1) :
        $ACTIVE_TAB = $key;
        $options[$key]['active'] = 'active';
    endif;

    if (array_key_exists('badge', $tab))
        $options[$key]['out']['badge'] = ' <span class="badge">' . $tab['badge'] . '</span>';
    else
        $options[$key]['out']['badge'] = '';

endforeach;
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<?php foreach ($options as $tab): ?>
        <li class="<?php echo $tab['active'] ?>">
            <a href="<?php echo $tab['href'] ?>" role="tab" data-toggle="tab"><?php echo $tab['label'] . $tab['out']['badge'] ?></a>
        </li>  
<?php endforeach; ?>
</ul>

<?php if ($AJAX_MODE) { ?>

    <script>
        $(document).ready(function () {

            /**
             * 
             * @type String
             */
            var tabContent = '#tab-content';
            var tabSelector = 'a[data-toggle="tab"]';

            /**
             * 
             * @type String|String
             */
            loadTab("<?php echo $options[$ACTIVE_TAB]['href']; ?>", tabContent);

            /**
             * 
             * @type String
             */
            $(tabSelector).click(function (e) {
                var thisHref = $(this).attr('href');
                $('ul.nav-tabs li.active').removeClass('active')
                $(this).parent('li').addClass('active');
                loadTab(thisHref, tabContent);
            });
        });

        /**
         * 
         * @param {type} href
         * @param {type} divName
         * @returns {undefined}
         */
        function loadTab(href, divName) {
            $(divName).load(href, function () {
                // alert("Load was performed.");
            });
        }
    </script>

    <div id="tab-content"></div>

<?php } else { ?>

    <!-- Tab panes -->
    <div class="tab-content">
    <?php foreach ($options as $tab): ?>
            <div class="tab-pane <?php echo $tab['active'] ?>" id="<?php echo $tab['id'] ?>">
            <?php
            /**
             * Display element $tab['content']['text']
             */
            if (isset($tab['content']['text']))
                echo $tab['content']['text'];

            /**
             * Include $tab['content']['source']
             */
            if (isset($tab['content']['source']) && file_exists($tab['content']['source']))
                include($tab['content']['source']);

            /**
             * Render element $tab['content']['element']
             */
            if (isset($tab['content']['element']) && $this->element($tab['content']['element'])):

                $vars = (isset($tab['content']['vars'])) ? $tab['content']['vars'] : array();
                echo $this->element($tab['content']['element'], $vars);
            endif;

            /**
             * Render Scaffold component for $tab['content']['scaffold']
             */
            if (isset($tab['content']['scaffold'])) {

                $params = $tab['content']['scaffold'];
                $request = new CakeRequest();
                $request->addParams($params);
                pr($request);
            }
            ?>
            </div>
            <?php endforeach; ?>
    </div>
    <?php } ?>