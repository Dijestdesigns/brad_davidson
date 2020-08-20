@extends('layouts.app')

@section('content')
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> {{ __('Items') }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h4>{{ __('Search Form : ') }}</h4>
                <form class="form-inline search-form" method="__GET" action="{{ route('items.index') }}">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ __('Search by name') }}">
                            <input type="number" name="q" class="form-control" placeholder="{{ __('Search by qty') }}">
                            <select class="form-control" name="ml">
                                <option>{{ __('Min level') }}</option>
                            </select>
                            <select class="form-control" name="t">
                                <option>{{ __('Tags') }}</option>
                            </select>
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="pull-right add-new-button">
                        <a class="btn btn-primary" href="{{ route('items.create') }}"><i class="fa fa-plus"></i></a>
                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h3>11 {{ __('Items') }}</h3>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 mb">
                        <div class="content-panel sp">
                            <div id="blog-bg">
                                <img src="../img/blog-bg.jpg" />
                                <div class="pull-left">
                                    <div class="blog-title">Incredible Title</div>
                                </div>
                                <div class="pull-right">
                                    <div class="blog-title-right base-quantity">50qty | $520.667</div>
                                </div>
                            </div>
                            <div class="blog-text">
                                <p>Shivay test test</p>
                                <div>
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm" title="{{ __('Edit') }}"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-dark btn-sm" title="{{ __('Change Quantities') }}"><i class="fa fa-sort-amount-desc"></i></button>
                                            <button class="btn btn-warning btn-sm" title="{{ __('Move to folder') }}"><i class="fa fa-arrows"></i></button>
                                            <button class="btn btn-info btn-sm" title="{{ __('History') }}"><i class="fa fa-history"></i></button>
                                            <button class="btn btn-danger btn-sm" title="{{ __('Remove') }}"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
