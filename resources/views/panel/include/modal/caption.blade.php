@push('foot')
    <script type="text/javascript">
        function captionModal($path) {
            {{--captionImage({--}}
            {{--    path: $path,--}}
            {{--    putURL: '{{route('panel.image.caption.put', app()->getLocale())}}',--}}
            {{--    getURL: '{{route('panel.image.caption.get', app()->getLocale())}}',--}}
            {{--})--}}
        }
    </script>
@endpush
@push('modal')
    <div id="update-caption" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
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
                <form action="{{$action}}" method="POST">
                    <input type="hidden" name="path" id="path">
                    <div class="modal-body">
                        <!-- form start -->
                        <div class="form-group">
                            <label
                                for="InputCaption">@lang(sprintf('messages.fields.%s','caption'))</label>
                            <input type="text" class="form-control"
                                   id="InputCaption"
                                   name="caption"
                                   placeholder="@lang('messages.fields.enter') @lang(sprintf('messages.fields.%s','caption'))"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" data-dismiss="modal"
                                class="btn btn-default">@lang('messages.buttons.cancel')</button>
                        <button type="submit" class="btn btn-primary" id="add">
                            @lang('messages.buttons.update')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
