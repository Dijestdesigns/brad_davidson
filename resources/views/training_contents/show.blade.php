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
        @include('ultimateLogo')

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
