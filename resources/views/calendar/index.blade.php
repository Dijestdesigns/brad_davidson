@extends('layouts.app')

@section('content')

    @section('styles')
        <link href="{{ asset('css/calendar.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
    @stop

    @push('scripts')
        <script difer src="{{ asset('js/moment.min.js') }}" defer></script>
        <script difer src="{{ asset('js/fullcalendar.min.js') }}" defer></script>
    @endpush

    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Calendar') }}</h3>
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
                <div class="page-aside" style="overflow-x: hidden;display: none;">
                    <div class="page-aside-inner page-aside-scroll">
                        <div data-role="container">
                            <div data-role="content">
                                <section class="page-aside-section">
                                    <h5 class="page-aside-title">EVENTS</h5>
                                    <div class="list-group calendar-list">
                                        <a class="list-group-item calendar-event" data-title="{{ __('Meeting') }}" data-stick="true" data-color="red-600" href="javascript:void(0)"> <i class="wb-medium-point red-600 mr-10" aria-hidden="true"></i>{{ __('Meeting') }}</a>
                                        <a class="list-group-item calendar-event" data-title="{{ __('Birthday Party') }}" data-stick="true" data-color="green-600" href="javascript:void(0)"> <i class="wb-medium-point green-600 mr-10" aria-hidden="true"></i>{{ __('Birthday Party') }}</a>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-main">
                    <div class="calendar-container">
                        <div id="calendar"></div>
                        <!--AddEvent Dialog -->
                        <div class="modal fade" id="addNewEvent" aria-hidden="true" aria-labelledby="addNewEvent" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-simple">
                                <form class="modal-content form-horizontal" action="{{ route('calendar.store') }}" method="post" role="form">
                                    @csrf

                                    <div class="modal-header">
                                        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ __('New Event') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="ename">{{ __('Name') }} : </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="ename" name="name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="starts">{{ __('Starts') }} : </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datetimepicker" autocomplete="off" id="starts" name="start_date" data-container="#addNewEvent">
                                                    <span class="input-group-addon">
                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="ends">{{ __('Ends') }} : </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datetimepicker" autocomplete="off" id="ends" name="end_date" data-container="#addNewEvent">
                                                    <span class="input-group-addon">
                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="repeats">{{ __('Repeats') }}: </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="repeats" name="repeats" data-min="0" data-max="10" value="0">
                                            </div>
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="form-control-label col-md-2">{{ __('Color') }} : </label>
                                            <div class="col-md-10">
                                                <ul class="color-selector">
                                                    <li class="bg-blue-600">
                                                        <input type="radio" checked name="color" id="eventColorChosen2" value="blue">
                                                        <label for="eventColorChosen2"></label>
                                                    </li>
                                                    <li class="bg-green-600">
                                                        <input type="radio" name="color" id="eventColorChosen3" value="green">
                                                        <label for="eventColorChosen3"></label>
                                                    </li>
                                                    <li class="bg-cyan-600">
                                                        <input type="radio" name="color" id="eventColorChosen4" value="cyan">
                                                        <label for="eventColorChosen4"></label>
                                                    </li>
                                                    <li class="bg-orange-600">
                                                        <input type="radio" name="color" id="eventColorChosen5" value="orange">
                                                        <label for="eventColorChosen5"></label>
                                                    </li>
                                                    <li class="bg-blue-grey-600">
                                                        <input type="radio" name="color" id="eventColorChosen7" value="blue-grey">
                                                        <label for="eventColorChosen7"></label>
                                                    </li>
                                                    <li class="bg-purple-600">
                                                        <input type="radio" name="color" id="eventColorChosen8" value="purple">
                                                        <label for="eventColorChosen8"></label>
                                                    </li>
                                                    <li class="bg-red-600">
                                                        <input type="radio" name="color" id="eventColorChosen6" value="red">
                                                        <label for="eventColorChosen6"></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-actions">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                                            <a class="btn btn-sm btn-default" data-dismiss="modal" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End AddEvent Dialog -->
                        <!-- Edit Dialog -->
                        <div class="modal fade" id="editNewEvent" aria-hidden="true" aria-labelledby="editNewEvent" role="dialog" tabindex="-1" data-show="false">
                            <div class="modal-dialog modal-simple">
                                <form class="modal-content form-horizontal" action="{{ route('calendar.update') }}" method="post" role="form">
                                    @csrf

                                    <div class="modal-header">
                                        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ __('Edit Event') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="editEname">{{ __('Name') }} : </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="editEname" name="name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="editStarts">{{ __('Starts') }} : </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datetimepicker" autocomplete="off" id="editStarts" name="start_date" data-container="#editNewEvent">
                                                    <span class="input-group-addon">
                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="editEnds">{{ __('Ends') }} : </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datetimepicker" autocomplete="off" id="editEnds" name="end_date" data-container="#editNewEvent">
                                                    <span class="input-group-addon">
                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="editRepeats">{{ __('Repeats') }}: </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="editRepeats" name="repeats" data-plugin="TouchSpin" data-min="0" data-max="10" value="0">
                                            </div>
                                        </div> -->
                                        <div class="form-group row" id="editColor">
                                            <label class="form-control-label col-md-2">{{ __('Color') }}:</label>
                                            <div class="col-md-10">
                                                <ul class="color-selector">
                                                    <li class="bg-blue-600">
                                                        <input type="radio" data-color="blue|600" name="color" id="editColorChosen2" value="blue">
                                                        <label for="editColorChosen2"></label>
                                                    </li>
                                                    <li class="bg-green-600">
                                                        <input type="radio" data-color="green|600" name="color" id="editColorChosen3" value="green">
                                                        <label for="editColorChosen3"></label>
                                                    </li>
                                                    <li class="bg-cyan-600">
                                                        <input type="radio" data-color="cyan|600" name="color" id="editColorChosen4" value="cyan">
                                                        <label for="editColorChosen4"></label>
                                                    </li>
                                                    <li class="bg-orange-600">
                                                        <input type="radio" data-color="orange|600" name="color" id="editColorChosen5" value="orange">
                                                        <label for="editColorChosen4"></label>
                                                    </li>
                                                    <li class="bg-blue-grey-600">
                                                        <input type="radio" data-color="blue-grey|600" name="color" id="editColorChosen7" value="blue-grey">
                                                        <label for="editColorChosen7"></label>
                                                    </li>
                                                    <li class="bg-purple-600">
                                                        <input type="radio" data-color="purple|600" name="color" id="editColorChosen8" value="purple">
                                                        <label for="editColorChosen8"></label>
                                                    </li>
                                                    <li class="bg-red-600">
                                                        <input type="radio" data-color="red|600" name="color" id="editColorChosen6" value="red">
                                                        <label for="editColorChosen6"></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-actions">
                                            <input type="hidden" name="calendarId" id="calendarId" value="">
                                            <input type="hidden" name="isDelete" id="isDelete" value="">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                                            <button class="btn btn-danger deleteBtnCalendar" data-confirm-message="{{__("Are you sure you want to delete this?")}}" title="{{__('Delete')}}" type="button"><i class="fa fa-trash"></i></button>
                                            <a class="btn btn-sm btn-default" data-dismiss="modal" href=""><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End EditEvent Dialog -->
                        <!--AddCalendar Dialog -->
                        <div class="modal fade" id="addNewCalendar" aria-hidden="true" aria-labelledby="addNewCalendar" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-simple">
                                <form class="modal-content form-horizontal" action="#" method="post" role="form">
                                    <div class="modal-header">
                                        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ __('New Calendar') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="ename">{{ __('Name') }} : </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="ename" name="ename">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="people">{{ __('People') }}: </label>
                                            <div class="col-md-10">
                                                <select id="people" multiple class="plugin-selective"></select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-md-2">{{ __('Color') }} : </label>
                                            <div class="col-md-10">
                                                <ul class="color-selector">
                                                    <li class="bg-blue-600">
                                                        <input type="radio" checked name="colorChosen" id="colorChosen2" value="blue">
                                                        <label for="colorChosen2"></label>
                                                    </li>
                                                    <li class="bg-green-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen3" value="green">
                                                        <label for="colorChosen3"></label>
                                                    </li>
                                                    <li class="bg-cyan-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen4" value="cyan">
                                                        <label for="colorChosen4"></label>
                                                    </li>
                                                    <li class="bg-orange-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen5" value="orange">
                                                        <label for="colorChosen5"></label>
                                                    </li>
                                                    <li class="bg-blue-grey-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen7" value="blue-grey">
                                                        <label for="colorChosen7"></label>
                                                    </li>
                                                    <li class="bg-purple-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen8" value="purple">
                                                        <label for="colorChosen8"></label>
                                                    </li>
                                                    <li class="bg-red-600">
                                                        <input type="radio" name="colorChosen" id="colorChosen6" value="red">
                                                        <label for="colorChosen6"></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="form-actions">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                                            <a class="btn btn-sm btn-default" data-dismiss="modal" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End AddCalendar Dialog -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script type="text/javascript">
    @if (!empty($calendars))
        var calendarDatas = {!! $calendars->toJson() !!};
    @else
        var calendarDatas = [];
    @endif

    var selectedDate = '{{ $selectedDate }}';
</script>
