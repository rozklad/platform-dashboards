@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
    {{{ trans('platform/dashboard::common.title') }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('index', 'platform/dashboard::css/index.scss', 'style') }}

{{-- Inline scripts --}}
@section('scripts')
    @parent
@stop

{{-- Inline styles --}}
@section('styles')
    @parent
@stop

{{-- Page content --}}
@section('page')

    @dashboard('main')

@stop
