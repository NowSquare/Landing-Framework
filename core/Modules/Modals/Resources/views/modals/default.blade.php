@extends('modals::modals.layout.master', ['id' => $id])

@section('head_end')
<style type="text/css">
  body {
    margin: 2rem;
  }
</style>
@stop

@section('content')

<h1><i class="fas fa-exclamation-triangle mr-1"></i> {{ trans('modals::global.modal_error_title') }}</h1>
<p class="lead my-4">{{ trans('modals::global.modal_error_text') }}</p>
<ul class="lead">
  <li>{{ trans('modals::global.modal_error_item1') }}</li>
  <li>{{ trans('modals::global.modal_error_item2') }}</li>
  <li>{{ trans('modals::global.modal_error_item3') }}</li>
  <li>{{ trans('modals::global.modal_error_item4') }}</li>
</ul>

@stop