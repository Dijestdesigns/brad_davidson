@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Clients Edit') }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" enctype="multipart/form-data" action="{{ route('folders.update', $record->id) }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Name') }} : </label>

                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $record->name) }}" autofocus="" />

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('Tags') }} : </label>

                                <select name="tags[]" class="form-control{{ $errors->has('tags.0') ? ' is-invalid' : '' }}" multiple="">
                                    @php
                                        $tagIds = [];

                                        if (!empty($record->clientTags) && !$record->clientTags->isEmpty()) {
                                            $tagIds = $record->clientTags->pluck('tag_id')->toArray();
                                        }
                                    @endphp

                                    <option value="" {{ (old('tags.0') == '' && empty($tagIds)) ? 'selected=""' : '' }}>{{ __('Select') }}</option>

                                    @if (!empty($tags))
                                        @foreach ($tags as $index => $tag)
                                            <option value="{{ $tag->id }}" {{ (old('tags.'.$index) == $tag->id || in_array($tag->id, $tagIds)) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('tags.0'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tags.0') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label>{{ __('Notes') }} : </label>

                                <textarea class="form-control{{ $errors->has('notes') ? ' is-invalid' : '' }}" name="notes">{{ old('notes', $record->notes) }}</textarea>

                                @if ($errors->has('notes'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="fileupload-buttonbar">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>{{ __('Photos') }}</span>
                                        <input type="file" name="photos[]" id="imgUpload" multiple="" accept="image/*">
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row" id="preview-image">
                            @if (!empty($record->photos) && !$record->photos->isEmpty())
                                @foreach ($record->photos as $photo)
                                    <div class="col-md-4">
                                        <img src="{{ $photo->photo }}" style="width:100%;height: 100%;object-fit: cover;" />
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                <a class="btn btn-default" href="{{ route('folders.index') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
