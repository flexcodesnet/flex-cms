@extends('panel.layout')

@section('main-content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <!-- form start -->
                <form action="{{$action}}" method="POST" id="main-from" enctype="multipart/form-data">
                    @method($method)
                    @csrf
                    <div class="card-body">
                        <div class="col-12 m-auto">
                            {{--                                @if($translatableFields and is_countable($translatableFields))--}}
                            {{--                                    <div class="mb-3 card card-primary card-outline card-outline-tabs">--}}
                            {{--                                        <div class="card-header p-0 border-bottom-0">--}}
                            {{--                                            <ul class="nav nav-tabs" id="local-tabs" role="tablist">--}}
                            {{--                                                @foreach(get_locales() as $key => $local)--}}
                            {{--                                                    <li class="nav-item">--}}
                            {{--                                                        <a class="nav-link {{$key == 0 ? 'active' : ''}}"--}}
                            {{--                                                           id="local-tabs-{{$local}}-tab"--}}
                            {{--                                                           data-toggle="pill" href="#local-tabs-{{$local}}" role="tab"--}}
                            {{--                                                           aria-controls="local-tabs-{{$local}}"--}}
                            {{--                                                           aria-selected="{{$key == 0 ? 'true' : 'false'}}">{{__('messages.languages.'.$local.'.title')}}</a>--}}
                            {{--                                                    </li>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            </ul>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="card-body">--}}
                            {{--                                            <div class="tab-content"--}}
                            {{--                                                 id="local-tabs-tabContent">--}}
                            {{--                                                @foreach(get_locales() as $key => $local)--}}
                            {{--                                                    <div class="tab-pane fade {{$key == 0 ? 'show active' : ''}}"--}}
                            {{--                                                         id="local-tabs-{{$local}}" role="tabpanel"--}}
                            {{--                                                         aria-labelledby="local-tabs-{{$local}}-tab">--}}
                            {{--                                                        @foreach($translatableFields as $field)--}}
                            {{--                                                            @include('panel.include.forms.inputs', ['parentFieldLocal'=>$local])--}}
                            {{--                                                        @endforeach--}}
                            {{--                                                    </div>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <!-- /.card -->--}}
                            {{--                                    </div>--}}
                            {{--                                @endif--}}

                            @if($sections and is_countable($sections))
                                @foreach($sections as $section=>$sectionFields)
                                    @if($section==\FXC\Base\Supports\BaseFields::TRANSLATION_SECTION)
                                        @if($sectionFields and is_countable($sectionFields))
                                            <div class="mb-3 card card-primary card-outline card-outline-tabs">
                                                <div class="card-header p-0 border-bottom-0">
                                                    <ul class="nav nav-tabs" id="local-tabs" role="tablist">
                                                        @foreach(get_locales() as $key => $local)
                                                            <li class="nav-item">
                                                                <a class="nav-link {{$key == 0 ? 'active' : ''}}"
                                                                   id="local-tabs-{{$local}}-tab"
                                                                   data-toggle="pill" href="#local-tabs-{{$local}}"
                                                                   role="tab"
                                                                   aria-controls="local-tabs-{{$local}}"
                                                                   aria-selected="{{$key == 0 ? 'true' : 'false'}}">{{__('messages.languages.'.$local.'.title')}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content"
                                                         id="local-tabs-tabContent">
                                                        @foreach(get_locales() as $key => $local)
                                                            <div class="tab-pane fade {{$key == 0 ? 'show active' : ''}}"
                                                                 id="local-tabs-{{$local}}" role="tabpanel"
                                                                 aria-labelledby="local-tabs-{{$local}}-tab">
                                                                @foreach($sectionFields as $field)
                                                                    @include('panel.include.forms.inputs', ['parentFieldLocal'=>$local])
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <!-- /.card -->
                                            </div>
                                        @endif
                                    @else
                                        @if($sectionFields and is_countable($sectionFields))
                                            <div class="card card-light">
                                                <h3 class="card-header">
                                                        <span class="text-capitalize">
                                                            {{__("messages.$section")}}
                                                        </span>
                                                </h3>
                                                <div class="card-body">
                                                    @foreach($sectionFields as $field)
                                                        @include('panel.include.forms.inputs')
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>
                    <!-- /.card-body -->
                    @if($method != 'GET')
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <button type="reset"
                                        class="btn btn-default mr-2 ml-2">@lang('messages.buttons.reset')</button>
                                <button type="submit"
                                        class="btn btn-primary">@lang(sprintf('messages.buttons.%s',$submit_button))</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@stop
@push('head')
@endpush
@push('foot')
    <!-- Page specific script -->
    <script type="text/javascript">
        main({});

        $("#main-from").submit(function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            if ($debug) {
                console.log(Object.fromEntries(formData.entries()));
            }

            $.ajax({
                url: "{{$action}}",
                method: formData._method,
                headers: {
                    'X-CSRF-TOKEN': $token //pass the CSRF_TOKEN()
                },
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (result, status, xhr) {
                    console.log(result);
                    if (status === "success") {
                        toastr.options.onHidden = function () {
                            if ($debug) {
                                console.log(result.redirect);
                            }
                            if (result.redirect === undefined) {
                                window.location.reload(true);
                            } else {
                                window.location.replace(result.redirect);
                            }
                        };
                        toastr.success(result.message);
                    }
                }, error: function (xhr, status, error) {
                    if ($debug) {
                        console.error(xhr);
                        console.error(error);
                    }
                    toastr.error(xhr.responseJSON.message);
                }
            });
        });
    </script>
@endpush
