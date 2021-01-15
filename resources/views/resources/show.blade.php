@extends('layouts.app')

@section('content')

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
                @if (!empty($records) && !$records->isEmpty())
                    @foreach ($records as $record)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="custom-box">
                                <div class="servicetitle">
                                    <h4>Standard</h4>
                                    <hr>
                                </div>

                                <p style="height: 500px;">
                                    <embed
                                        src="{{ $record->url }}"
                                        type="{{ $record->mime_type }}"
                                        frameBorder="0"
                                        scrolling="auto"
                                        height="100%"
                                        width="100%"
                                    ></embed>
                                </p>

                                <a href="{{ route('resources.download', $record->id) }}" class="btn btn-save btn-primary" target="__blank">{{ __('Download') }}</a>
                            </div>
                        </div>
                        <!-- end custombox -->
                    @endforeach
                  @else
                    <mark>{{ __('No record found!') }}</mark>
                  @endif
            </div>
        </div>
    </section>
@endsection
