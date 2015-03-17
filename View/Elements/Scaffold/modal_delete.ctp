<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $singularHumanName; ?> - Delete data</h4>
    </div>
    <div class="modal-body">
        <?php echo $message; ?>
    </div>
    <div class="modal-footer">
        <?php echo $this->Form->create('ScaffoldDelete'); ?>
        <input id="export-data" name="export-data" type="submit" class="btn btn-danger" value="Delete Data" />
        <?php echo $this->Form->end(); ?>
    </div>
</div>
