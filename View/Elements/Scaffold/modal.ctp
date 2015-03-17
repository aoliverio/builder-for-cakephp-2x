<script>
    $(document).ready(function () {
        $('#modalScaffold').on('hidden.bs.modal', function (e) {
            $('#modalScaffoldContent').empty();
        });
    });
</script>    
<div class="modal fade" id="modalScaffold" tabindex="-1" role="dialog" aria-labelledby="modalScaffold" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modalScaffoldContent" class="modal-content"></div>
    </div>    
</div>