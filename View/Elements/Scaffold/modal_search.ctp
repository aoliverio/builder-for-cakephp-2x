<?php if (TRUE): ?>
    <!-- MODAL SCAFFOLD -->
    <div class="modal fade" id="ScaffoldModal" tabindex="-1" role="dialog" aria-labelledby="scaffoldModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>    
    </div>
<?php endif; ?>
    
<?php if (TRUE): ?>
    <!-- MODAL IMPORT -->
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- form -->
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
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (TRUE): ?>
    <!-- MODAL EXPORT -->
    <div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <?php echo $this->Form->create('ScaffoldExport', array('type' => 'file')); ?>
            <?php echo $this->Form->input('export', array('type' => 'hidden')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $singularHumanName; ?> - Export data</h4>
                </div>
                <div class="modal-body">
                    <small>Export - Advanced setting</small>
                </div>
                <div class="modal-footer">
                    <input id="export-data" name="export-data" type="submit" class="btn btn-primary" value="Export Data" />            
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (TRUE): ?>
    <!-- MODAL SEARCH -->
    <div class="modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $singularHumanName; ?> - Search data</h4>
                </div>
                <div class="modal-body">
                    Search...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (TRUE): ?>
    <!-- MODAL FILTER -->
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $singularHumanName; ?> - Filter data</h4>
                </div>
                <div class="modal-body">
                    Filter...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>