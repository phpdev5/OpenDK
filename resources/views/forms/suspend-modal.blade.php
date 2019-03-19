<div id="suspend-modal" class="modal fade modal-danger in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Suspend Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure want to suspend this data?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['id' => 'suspend', 'method' => 'DELETE']) !!}
                    <a id="suspend-modal-cancel" href="#" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Cancel</a>
                    {!! Form::submit('Suspend', [ 'class' => 'btn btn-warning waves-effect waves-light' ]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

         $(document).on('click', '#suspendModal', function(e) {
            var url = $(this).attr('data-href');
            $('#suspend').attr('action', url );
            $('#import').attr( 'method', 'post' );
            $('#suspend-modal').modal('show');
            e.preventDefault();
        });
    });
</script>
