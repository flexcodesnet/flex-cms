@push('head')
@endpush
@push('foot')
    <script type="text/javascript">
        function {{\App\Support\Str::replace('.','',$slug)}}delModal($id) {
            delModal({
                id: $id,
                query: '#{{\App\Support\Str::replace('.','',$slug)}}delete-modal',
                url: "{{ (isset($gallery) || isset($inner_action)) ? $inner_action : route(sprintf('panel.%s.delete', $slug),[':id']) }}",
            });
        }
    </script>
@endpush
<div id="{{\App\Support\Str::replace('.','',$slug)}}delete-modal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="loading" class="overlay d-flex justify-content-center align-items-center invisible">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                Are you sure !
                <br>
                You want to delete <strong>:id</strong> ?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" data-dismiss="modal"
                        class="btn btn-default">@lang('messages.buttons.cancel')</button>
                <button type="button" class="btn btn-danger" id="delete">
                    <i class="fa fa-trash"></i>
                    @lang('messages.buttons.delete')
                </button>
            </div>
        </div>
    </div>
</div>
