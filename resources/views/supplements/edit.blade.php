@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Supplements') }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" method="POST" action="{{ route('supplements.update', [$userId, $date]) }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{ __('Select Client') }} : </label>
                                <select class="form-control{{ $errors->has('*.name') ? ' is-invalid' : '' }}" disabled="">
                                    <option value="">{{ __('Select') }}</option>

                                    @if(!empty($users) && !$users->isEmpty())
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $userId) == $user->id ? 'selected="true"' : '' }}>{{ $user->name . " " . $user->surname }}</option>
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
                                <input name="date" type="text" class="form-control datepicker{{ $errors->has('*.date') ? ' is-invalid' : '' }}" placeholder="yyyy-mm-dd" value="{{ (!empty(old('date')) && strtotime(old('date')) > 0) ? date('Y-m-d', strtotime(old('date'))) : ((!empty($date) && $date > 0) ? date('Y-m-d', $date) : '') }}" data-disabled-dates="{{ $disabledDates }}">

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
                                            @for($rowId = 1; $rowId <= $model::TOTAL_ROWS; $rowId++)
                                                <tr>
                                                    <td>
                                                        <textarea name="supplement[]" class="form-control" rows="5">{{ old('supplement.' . ($rowId - 1), (!empty($record[$rowId - 1]->supplement) ? $record[$rowId - 1]->supplement : '')) }}</textarea>
                                                    </td>
                                                    <td>
                                                        <textarea name="upon_waking[]" class="form-control" rows="5">{{ old('upon_waking.' . ($rowId - 1), (!empty($record[$rowId - 1]->upon_waking) ? $record[$rowId - 1]->upon_waking : '')) }}</textarea>
                                                    </td>
                                                    <td>
                                                        <textarea name="at_breakfast[]" class="form-control" rows="5">{{ old('at_breakfast.' . ($rowId - 1), (!empty($record[$rowId - 1]->at_breakfast) ? $record[$rowId - 1]->at_breakfast : '')) }}</textarea>
                                                    </td>
                                                    <td>
                                                        <textarea name="at_lunch[]" class="form-control" rows="5">{{ old('at_lunch.' . ($rowId - 1), (!empty($record[$rowId - 1]->at_lunch) ? $record[$rowId - 1]->at_lunch : '')) }}</textarea>
                                                    </td>
                                                    <td>
                                                        <textarea name="at_dinner[]" class="form-control" rows="5">{{ old('at_dinner.' . ($rowId - 1), (!empty($record[$rowId - 1]->at_dinner) ? $record[$rowId - 1]->at_dinner : '')) }}</textarea>
                                                    </td>
                                                    <td>
                                                        <textarea name="before_bed[]" class="form-control" rows="5">{{ old('before_bed.' . ($rowId - 1), (!empty($record[$rowId - 1]->before_bed) ? $record[$rowId - 1]->before_bed : '')) }}</textarea>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @can('supplements_edit')
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
