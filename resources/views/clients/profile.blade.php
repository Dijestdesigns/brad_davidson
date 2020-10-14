@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('My Profile') }}</h3>
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
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" enctype="multipart/form-data" action="{{ route('clients.myprofile.update') }}#edit" method="POST">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-4 profile-text mt mb centered hidden-sm hidden-xs">
                                <div class="right-divider">
                                    <h4>{{ $totalNotes }}</h4>
                                    <h6>&nbsp;&nbsp;{{ __('Notes') }}</h6>
                                    <h4>{{ $tagNames }}</h4>
                                    <h6>&nbsp;&nbsp;{{ __('Tags') }}</h6>
                                    <h4>{{ $category }}</h4>
                                    <h6>&nbsp;&nbsp;{{ __('Category') }}</h6>
                                </div>
                            </div>

                            <div class="col-md-4 profile-text text-md-left text-center">
                                <h3>{{ $record->name }} {{ $record->surname }}</h3>
                                <h6>{{ $roleNames }}</h6>
                                <p>
                                    <a href="tel:{{ $record->contact }}">
                                        {{ $record->contact }}
                                    </a>
                                </p>
                                <p>
                                    <a href="mailto:{{ $record->email }}">
                                        {{ $record->email }}
                                    </a>
                                </p>
                            </div>

                            <div class="col-md-4 centered">
                                <div class="profile-pic">
                                    <p>&nbsp;</p>
                                    <p id="preview-profile-image">
                                        <img src="{{ $record->profile_photo }}" class="img-circle">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom"></div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="content-panel">
                                    <div class="panel-heading">
                                        <ul class="nav nav-tabs nav-justified">
                                            <li class="tab-panel active" style="width: 50%;">
                                                <a data-toggle="tab" href="#overview">{{ __('Overview') }}</a>
                                            </li>
                                            <li class="tab-panel" style="width: 50%;">
                                                <a data-toggle="tab" href="#edit">{{ __('Edit Profile') }}</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div id="overview" class="tab-pane active">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Name') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->name) ? $record->name : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Surname') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->surname) ? $record->surname : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Contact') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->contact) ? $record->contact : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Age') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->age) ? $record->age : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Weight') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $record->weight }} {{ ($record->weight_unit != 'n' && !empty(App\User::$weightUnits[$record->weight_unit])) ? App\User::$weightUnits[$record->weight_unit] : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Gender') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ ($record->gender != 'n' && !empty(App\User::$genders[$record->gender])) ? App\User::$genders[$record->gender] : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Email') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->email) ? $record->email : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Shipping Address') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ !empty($record->shipping_address) ? $record->shipping_address : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Tags') }}</div>
                                                                <div class="col-md-8">
                                                                    @php
                                                                        $tagNames = [];

                                                                        if (!empty($record->clientTags) && !$record->clientTags->isEmpty()) {
                                                                            foreach ($record->clientTags as $clientTag) {
                                                                                $tagNames[] = $clientTag->tag->name;
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    {{ !empty($tagNames) ? implode(", ", $tagNames) : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Category') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $category }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Notes') }}</div>
                                                                <div class="col-md-10">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>{{ __('Note Date') }}</th>
                                                                                    <th>{{ __('Notes') }}</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @if(!empty($record->notes))
                                                                                    @foreach ($record->notes as $note)
                                                                                        <tr>
                                                                                            <td>{{ date('Y-m-d', $note->note_date) }}</td>
                                                                                            <td>{{ $note->notes }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Created at') }}</div>
                                                                <div class="col-md-3">
                                                                    {{$record->created_at}}
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-2">{{ __('Role') }}</div>
                                                                <div class="col-md-3">
                                                                    <span class="badge badge-lg badge-secondary text-white">{{@$record->getRoleNames()[0]}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /OVERVIEW -->
                                            </div>
                                            <!-- /tab-pane -->
                                            <div id="edit" class="tab-pane">
                                                <div class="row">
                                                    <div class="col-lg-8 col-lg-offset-2 detailed">
                                                        <h4 class="mb">{{ __('Basic Informations') }}</h4>
                                                    </div>

                                                    <div class="col-lg-8 col-lg-offset-3 detailed">
                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Name') }}</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $record->name) }}" autofocus="" required="" />

                                                                @if ($errors->has('name'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Surname') }}</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $record->surname) }}" />

                                                                @if ($errors->has('surname'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('surname') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Contact') }}</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" class="form-control{{ $errors->has('contact') ? ' is-invalid' : '' }}" name="contact" value="{{ old('contact', $record->contact) }}" />

                                                                @if ($errors->has('contact'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('contact') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Age') }}</label>
                                                            <div class="col-lg-6">
                                                                <input type="number" class="form-control{{ $errors->has('age') ? ' is-invalid' : '' }}" name="age" value="{{ old('age', $record->age) }}" />

                                                                @if ($errors->has('age'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('age') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Weight') }}</label>
                                                            <div class="col-lg-3">
                                                                <input type="number" class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" value="{{ old('weight', $record->weight) }}" />

                                                                @if ($errors->has('weight'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('weight') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <label class="col-lg-1 control-label" style="padding: 0;">{{ __('Weight Unit') }} : </label>
                                                            <div class="col-lg-2">
                                                                <select name="weight_unit" class="form-control{{ $errors->has('weight_unit') ? ' is-invalid' : '' }}">
                                                                    @if(!empty(App\User::$weightUnits))
                                                                        @foreach(App\User::$weightUnits as $value => $weightUnit)
                                                                            <option value="{{ $value }}" {{ (old('weight_unit', $record->weight_unit) == $value) ? 'selected' : '' }}>{{ $weightUnit }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>

                                                                @if ($errors->has('weight_unit'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('weight_unit') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Gender') }}</label>
                                                            <div class="col-lg-6">
                                                                <select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                                                    @if(!empty(App\User::$genders))
                                                                        @foreach(App\User::$genders as $value => $gender)
                                                                            <option value="{{ $value }}" {{ (old('gender', $record->gender) == $value) ? 'selected' : '' }}>{{ $gender }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>

                                                                @if ($errors->has('gender'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Email') }}</label>
                                                            <div class="col-lg-6">
                                                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $record->email) }}" readonly="" />

                                                                @if ($errors->has('email'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('email') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Password') }}</label>
                                                            <div class="col-lg-3">
                                                                <input id="password" type="password" placeholder="{{ __("Leave blank if don't want to update") }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}">

                                                                @if ($errors->has('password'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="password" placeholder="{{ __('Confirm Password') }}" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">

                                                                @if ($errors->has('password_confirmation'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">{{ __('Shipping Address') }}</label>
                                                            <div class="col-lg-6">
                                                                <textarea class="form-control{{ $errors->has('shipping_address') ? ' is-invalid' : '' }}" name="shipping_address">{{ old('shipping_address', $record->shipping_address) }}</textarea>

                                                                @if ($errors->has('shipping_address'))
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $errors->first('shipping_address') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">&nbsp;</label>

                                                            <div class="col-lg-6">
                                                                <div class="fileupload-buttonbar">
                                                                    <span class="btn btn-success fileinput-button">
                                                                        <i class="glyphicon glyphicon-plus"></i>
                                                                        <span>{{ __('Profile Photo') }}</span>
                                                                        <input type="file" name="profile_photo" id="imgProfileUpload" accept="image/*">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-2 control-label">&nbsp;</label>

                                                            <div class="col-md-6">
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                            </div>
                                            <!-- /tab-pane -->
                                        </div>
                                        <!-- /tab-content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
