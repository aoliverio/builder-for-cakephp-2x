<?php echo $this->Form->create('ScaffoldImport', array('type' => 'file')); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><?php echo $singularHumanName; ?> - Import data</h4>
</div>
<div class="modal-body">
    <small>Import - Advanced setting</small>
    <hr/>
    <?php echo $this->Form->input('file', array('type' => 'file', 'class' => 'bs-file-input')); ?>
</div>
<div class="modal-footer">
    <input name="import-data" type="submit" class="btn btn-primary" value="Import Data" />
</div>
<?php echo $this->Form->end(); ?>