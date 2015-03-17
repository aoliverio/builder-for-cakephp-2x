<script type="text/javascript">
    /**
     * Call loadBlock function
     */
    $(document).ready(function() {
        loadBlock("<?php echo $this->Html->url(array('controller' => $controller_name, 'action' => $action_name)); ?>", "#ajax-response");
    });
    /**
     * Loads in a URL into a specified divName, and applies the function to
     * all the links inside the pagination div of that page (to preserve the ajax-request)
     * @param string href The URL of the page to load
     * @param string divName The name of the DOM-element to load the data into
     * @return boolean False To prevent the links from doing anything on their own.
     */
    function loadBlock(href, divName) {
        $(divName).load(href, {}, function() {
            // Pagination Link
            var divPaginationLinks = ".pagination a";
            $(divPaginationLinks).click(function() {
                var thisHref = $(this).attr("href");
                loadBlock(thisHref, divName);
                return false;
            });
            // Sort Link
            var divSortLinks = "th a";
            $(divSortLinks).click(function() {
                var thisHref = $(this).attr("href");
                loadBlock(thisHref, divName);
                return false;
            });
        });
    }
</script>

<!-- -->
<div id="ajax-response"></div>