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
                    <form class="form-group p-10" enctype="multipart/form-data" action="{{ route('clients.update', $record->id) }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Name') }} : </label>

                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $record->name) }}" autofocus="" required=""/>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('Surname') }} : </label>

                                <input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $record->surname) }}" />

                                @if ($errors->has('surname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Email') }} : </label>

                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $record->email) }}" required="" />

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>{{ __('Password') }}<span style="color: red;"> (* {{ __("Leave blank if don't want to update") }})</span></label>

                                        <div class="inner-addon right-addon">
                                            <i class="fa fa-eye togglePassword" id=""></i>
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}">

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>{{ __('Confirm Password') }}<span style="color: red;"> (* {{ __("Leave blank if don't want to update") }})</span></label>

                                        <div class="inner-addon right-addon">
                                            <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">

                                            @if ($errors->has('password_confirmation'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Tags') }} : </label>

                                <select name="tags[]" class="form-control{{ $errors->has('tags.0') ? ' is-invalid' : '' }}" multiple="" required="">
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

                            <div class="col-md-6">
                                <label>{{ __('Category') }} : </label>

                                <select name="category" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}">
                                    <!-- <option value="" {{ old('category') == '' ? 'selected=""' : '' }}>{{ __('Select') }}</option> -->

                                    @if (!empty($categories))
                                        @foreach ($categories as $index => $category)
                                            <option value="{{ $index }}" {{ old('category', $record->category) == $index ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('category'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(!empty(old('note_dates')))
                            @foreach (old('note_dates') as $index => $noteDate)
                                <div class="form-group row" id="{{ $index == 0 ? 'row-notes' : 'new-notes' }}">
                                    <div class="col-md-2">
                                        <label>{{ __('Date') }} : </label>

                                        <input type="text" name="note_dates[]" class='form-control{{ $errors->has("note_dates. $index") ? " is-invalid" : "" }} datepicker' value="{{ $noteDate }}">

                                        @if ($errors->has("note_dates.$index"))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first("note_dates.$index") }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <label>{{ __('Notes') }} : </label>

                                        <div class="form-inline">
                                            <textarea class='form-control{{ $errors->has("notes.$index") ? " is-invalid" : "" }}' style="width: 96%;" name="notes[]">{{ old("notes.$index") }}</textarea>

                                            <button id="{{ $index == 0 ? 'plus-notes' : 'minus-notes' }}" class="btn btn-primary pull-right" style="margin: 0 auto;" type="button">
                                                <i class="fa {{ $index == 0 ? 'fa-plus' : 'fa-trash' }}"></i>
                                            </button>
                                        </div>

                                        @if ($errors->has("notes.$index"))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first("notes.$index") }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @elseif(!empty($record->notes) && !$record->notes->isEmpty())
                            @foreach ($record->notes as $index => $noteData)
                                <div class="form-group row" id="{{ $index == 0 ? 'row-notes' : 'new-notes' }}">
                                    <div class="col-md-2">
                                        <label>{{ __('Date') }} : </label>

                                        <input type="text" name="note_dates[]" class='form-control{{ $errors->has("note_dates. $index") ? " is-invalid" : "" }} datepicker' value="{{ date('Y-m-d', $noteData->note_date) }}">

                                        @if ($errors->has("note_dates.$index"))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first("note_dates.$index") }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <label>{{ __('Notes') }} : </label>

                                        <div class="form-inline">
                                            <textarea class='form-control{{ $errors->has("notes.$index") ? " is-invalid" : "" }}' style="width: 96%;" name="notes[]">{{ $noteData->notes }}</textarea>

                                            <button id="{{ $index == 0 ? 'plus-notes' : 'minus-notes' }}" class="btn btn-primary pull-right" style="margin: 0 auto;" type="button">
                                                <i class="fa {{ $index == 0 ? 'fa-plus' : 'fa-trash' }}"></i>
                                            </button>
                                        </div>

                                        @if ($errors->has("notes.$index"))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first("notes.$index") }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="form-group row" id="row-notes">
                                <div class="col-md-2">
                                    <label>{{ __('Date') }} : </label>

                                    <input type="text" name="note_dates[]" class="form-control{{ $errors->has('note_dates.*') ? ' is-invalid' : '' }} datepicker" value="{{ old('note_dates.0') }}">

                                    @if ($errors->has('note_dates.0'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('note_dates.0') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <label>{{ __('Notes') }} : </label>

                                    <div class="form-inline">
                                        <textarea class="form-control{{ $errors->has('notes.0') ? ' is-invalid' : '' }}" style="width: 96%;" name="notes[]">{{ old('notes.0') }}</textarea>

                                        <button id="plus-notes" class="btn btn-primary pull-right" style="margin: 0 auto;" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                    @if ($errors->has('notes.0'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('notes.0') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div id="cloned-notes"></div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Role') }} : </label>

                                <select class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" required="true">
                                    <option value="">{{ __('Select Role') }}</option>

                                    @if ($record->isSuperAdmin())
                                        <span class="badge badge-lg badge-secondary text-white">
                                            {{ __('Superadmin') }}
                                        </span>
                                    @else
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{ (old('role_id') == $role->id || $record->hasRole($role) ? 'selected="true"' : '') }}>{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('role_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_id') }}</strong>
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
                                <a class="btn btn-default" href="{{ route('clients.index') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
