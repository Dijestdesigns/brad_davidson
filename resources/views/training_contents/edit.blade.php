@extends('layouts.app')

@section('content')

    @section('styles')
        <link href="{{ asset('css/richtext.min.css') }}" rel="stylesheet">
    @stop

    @push('scripts')
        <script difer type="text/javascript" src="{{ asset('js/jquery.richtext.min.js') }}" defer></script>
        <script difer type="text/javascript" src="{{ asset('js/jquery.richtext.js') }}" defer></script>
    @endpush

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Training Contents') }}</h3>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @can('training_content_create')
            <div class="pull-right add-new-button">
                <form action="{{ route('trainingContents.store') }}" method="POST" style="display: inline-block;" enctype="multipart/form-data">
                    @csrf

                    <button class="btn btn-primary createTrainingContents" title="{{ __('Add New') }}" data-html="create-training-contents"><i class="fa fa-plus"></i></button>
                </form>

                <div class="create-training-contents d-none">
                    <form action="{{ route('trainingContents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="border-head h3">
                                            {{ __('Create Training Content') }}
                                        </div>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ __('Day') }}&nbsp;:&nbsp;</label>

                                        <select name="day" class="form-control" required="true">
                                            <option value="">{{ __('Select Day') }}</option>
                                            @for ($day = 1; $day <= 31; $day++)
                                                <option value="{{ $day }}">{{ $day }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ __('Title') }}&nbsp;:&nbsp;</label>

                                        <input type="text" name="title" required="true" class="form-control" placeholder="{{ __('Enter title here...') }}" />
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ __('Description') }}&nbsp;:&nbsp;</label>

                                        <textarea id="messageArea" name="description" rows="7" class="form-control richtext" placeholder="Enter description here..."></textarea>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            {{ __('Upload File') }}&nbsp;:&nbsp;
                                            <input type="radio" name="upload" value="file" class="upload-file">
                                        </label>

                                        &nbsp;:&nbsp;

                                        <label>
                                            {{ __('Video URL') }}&nbsp;:&nbsp;
                                            <input type="radio" name="upload" value="url" class="upload-url">
                                        </label>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="file" name="file" id="file" class="form-control d-none" />

                                        <input type="text" name="url" id="url" class="form-control d-none" />
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="role_id" value="{{ $roleId }}" />
                                        <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                        <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    @if (!empty($records) && !$records->isEmpty())
                        @foreach ($records as $record)
                            <div class="row">
                                <div class="col-md-12 mb">
                                    <div class="message-p">
                                        <div class="message-header form-inline">
                                            <div class="col-md-6 text-right">
                                                <h5>Day {{ $record->day }}</h5>
                                            </div>

                                            @can('resource_delete')
                                                <div class="col-md-6 text-right">
                                                    <form action="{{ route('trainingContents.destroy', $record->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf

                                                        <input type="hidden" name="role_id" value="{{ $record->role_id }}" />
                                                        <a href="#" class="deleteBtn pull-right" data-confirm-message="{{__("Are you sure you want to delete this training content?")}}" data-toggle="tooltip" data-placement="top" title="{{__('Delete Training Content')}}"><i class="fa fa-trash fa-2x"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                @if (empty($record->mime_type))
                                                    <iframe width="400" height="400" src="{{ $record->url }}"></iframe>
                                                @else
                                                    <embed
                                                        src="{{ $record->url }}"
                                                        type="{{ $record->mime_type }}"
                                                        frameBorder="0"
                                                        scrolling="auto"
                                                        height="400px"
                                                        width="400px"
                                                    ></embed>
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <p>
                                                    <name>{{ $record->title }}</name>
                                                </p>
                                                <p class="message">{!! $record->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Message Panel-->
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label><mark>{{ __('No records found!') }}<mark></label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
