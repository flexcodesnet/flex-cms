@push('modal')
    <div id="image-select" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="loading" class="overlay d-flex justify-content-center align-items-center invisible">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Update Caption</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <div class="row">
                        <div class="col-3">
                            <input type="radio" name='thing' id="thing1"/><label for="thing1"></label>
                        </div>
                        <div class="col-3">
                            <input type="radio" name='thing' id="thing2"/><label for="thing2"></label>
                        </div>
                        <div class="col-3">
                            <input type="radio" name='thing' id="thing3"/><label for="thing3"></label>
                        </div>
                        <div class="col-3">
                            <input type="radio" name='thing' id="thing4"/><label for="thing4"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" data-dismiss="modal"
                            class="btn btn-default">@lang('messages.buttons.cancel')</button>
                    <button type="submit" class="btn btn-primary" id="add">
                        @lang('messages.buttons.update')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush
