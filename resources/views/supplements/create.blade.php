@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Supplements Create') }}</h3>
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
                {!! session('error') !!}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" method="POST" action="{{ route('supplements.store') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Select Client') }} : </label>

                                <select name="user_id" class="form-control{{ $errors->has('*.user_id') ? ' is-invalid' : '' }}">
                                    <option value="">{{ __('Select') }}</option>

                                    @if(!empty($users) && !$users->isEmpty())
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected="true"' : '' }}>{{ $user->name . " " . $user->surname }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('*.user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('*.user_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('Select Date') }} : </label>

                                <input type="text" name="date" class="form-control datepicker{{ $errors->has('*.date') ? ' is-invalid' : '' }}" placeholder="yyyy-mm-dd" value="{{ (!empty(old('date')) && strtotime(old('date')) > 0) ? date('Y-m-d', strtotime(old('date'))) : '' }}">

                                @if ($errors->has('*.date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('*.date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <div class="table-responsive">
                                    <table class="table table-bordered full-inputs">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ __('SUPPLEMENT') }}</th>
                                                <th class="text-center">{{ __('UPON WAKING') }}</th>
                                                <th class="text-center">{{ __('AT BREAKFAST') }}</th>
                                                <th class="text-center">{{ __('AT LUNCH') }}</th>
                                                <th class="text-center">{{ __('AT DINNER') }}</th>
                                                <th class="text-center">{{ __('BEFORE BED') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <textarea name="supplement[]" class="form-control" rows="5">{{ old('supplement.0') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="upon_waking[]" class="form-control" rows="5">{{ old('upon_waking.0') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_breakfast[]" class="form-control" rows="5">{{ old('at_breakfast.0') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_lunch[]" class="form-control" rows="5">{{ old('at_lunch.0') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_dinner[]" class="form-control" rows="5">{{ old('at_dinner.0') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="before_bed[]" class="form-control" rows="5">{{ old('before_bed.0') }}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <textarea name="supplement[]" class="form-control" rows="5">{{ old('supplement.1') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="upon_waking[]" class="form-control" rows="5">{{ old('upon_waking.1') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_breakfast[]" class="form-control" rows="5">{{ old('at_breakfast.1') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_lunch[]" class="form-control" rows="5">{{ old('at_lunch.1') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_dinner[]" class="form-control" rows="5">{{ old('at_dinner.1') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="before_bed[]" class="form-control" rows="5">{{ old('before_bed.1') }}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <textarea name="supplement[]" class="form-control" rows="5">{{ old('supplement.2') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="upon_waking[]" class="form-control" rows="5">{{ old('upon_waking.2') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_breakfast[]" class="form-control" rows="5">{{ old('at_breakfast.2') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_lunch[]" class="form-control" rows="5">{{ old('at_lunch.2') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="at_dinner[]" class="form-control" rows="5">{{ old('at_dinner.2') }}</textarea>
                                                </td>
                                                <td>
                                                    <textarea name="before_bed[]" class="form-control" rows="5">{{ old('before_bed.2') }}</textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @can('supplements_create')
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    <a class="btn btn-default" href="{{ route('supplements.index') }}"><i class="fa fa-arrow-left"></i></a>
                                </div>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
