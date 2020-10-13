@extends('layouts.app')

@section('content')

    @section('styles')
        <link href="{{ asset('css/notebook.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-extend.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-markdown.min.css') }}" rel="stylesheet">
    @stop

    @push('scripts')
        <script difer src="{{ asset('js/marked.js') }}" defer></script>
        <script difer src="{{ asset('js/bootstrap-markdown.js') }}" defer></script>
    @endpush

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Note') }}</h3>
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

        <div class="row">
            <div class="col-md-12">
                <div class="app-notebook">
                    <div class="page-aside" style="overflow-x: hidden;">
                        <div class="page-aside-inner">
                            @if(!empty($records) && !$records->isEmpty())
                                <div class="input-search">
                                    <form action="{{ route('notes.index') }}" method="GET">
                                        <button class="input-search-btn" type="submit">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>

                                        <input class="form-control" type="text" placeholder="{{ __('Search Keyword') }}" name="s" value="{{ $request->get('s', '') }}">
                                    </form>
                                </div>
                            @endif

                            <div class="app-notebook-list page-aside-scroll scrollable is-enabled scrollable-vertical" style="position: relative;">
                                <div data-role="container" class="scrollable-container" style="height: 180.953px; width: 276px;">
                                    <div data-role="content" class="scrollable-content" style="width: 259px;">
                                        <ul class="list-group">
                                            @if(!empty($records) && !$records->isEmpty())
                                                @foreach($records as $index => $note)
                                                    <li class="list-group-item {{ ($selectedId == $note->id) ? 'active' : '' }}" data-toggle="context" data-target="#contextMenu" data-id="{{ $note->id }}">
                                                        <h4 class="list-group-item-heading mt-0">{{ $note->name }}</h4>
                                                        <p class="list-group-item-text">{{ $note->content }}</p>
                                                        <div class="info">
                                                            <i class="icon wb-folder "></i>
                                                            @if(strtotime($note->created_at) > 0)
                                                                <span class="time">{{ date('M dS\, h:i:s A', strtotime($note->created_at)) }}</span>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item active">
                                                    <h4 class="list-group-item-heading mt-0">{{ __('No Notes.') }}</h4>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-main" style="margin-left: 260px;display: none;">
                        <form action="{{ route('notes.store') }}" method="POST">
                            @csrf

                            @php
                                $selectedIndex = 0;
                            @endphp

                            @if(!empty($records) && !$records->isEmpty())
                                @foreach($records as $index => $note)
                                    <textarea name="contents[{{ $note->id }}]" data-provide="markdown" rows="160" data-iconlibrary="fa" data-exit-fullscreen="false" style="resize: none;" data-id="{{ $note->id }}" id="contentBook-{{ $note->id }}">{{ $note->content }}</textarea>

                                    <input type="hidden" name="names[{{ $note->id }}]" value="{{ $note->name }}">

                                    @php
                                        if ($selectedId == $note->id) {
                                            $selectedIndex = $index;
                                        }
                                    @endphp
                                @endforeach
                            @else
                                <div class="noContent">
                                    <button class="saveNotes btn btn-primary" title="Create New" data-title="Create New" type="button"><i class="fa fa-plus"></i> {{ __('Create New') }}</button>
                                </div>
                            @endif

                            <input type="hidden" name="currentId" id="currentId" value="{{ $firstId }}">
                            <input type="hidden" name="newName" id="newNotes" value="">
                            <input type="hidden" name="deletedId" id="deletedId" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style type="text/css">
        .app-notebook .md-editor:nth-of-type({{ $selectedIndex + 1 }}) {
            display: block !important;
        }
    </style>

    <script type="text/javascript">
        var store = "{{ route('notes.store') }}";
    </script>
@endsection
