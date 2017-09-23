@extends('scenarios::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <h1>{{ trans('scenarios::global.api') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4">

      <ul class="nav nav-tabs navtab-custom" style="width:100%">
        <li class="active" style="width:50%; text-align: center"><a href="#account" data-toggle="tab">{{ trans('global.account') }}</a></li>
        <li style="width:50%; text-align: center"><a href="#funnel" data-toggle="tab">{{ trans('global.funnel') }}</a></li>
      </ul>

    </div>

    <div class="tab-content">
      <div class="tab-pane active" id="account">
        <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4">
          <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('api/v1/scenarios/account') . '?token=' . $account_token, 'QRCODE', 10, 10, [0,0,0]) }}" alt="barcode" style="width:100%;">
        </div>

        <div class="col-xs-12 text-center">
          <a href="{{ url('api/v1/scenarios/account') . '?token=' . $account_token }}" class="btn btn-lg btn-primary m-t-20" target="_blank"><i class="mi link" style="top:4px"></i> {{ 'api/v1/scenarios/account' . '?token=' }}{{ substr($account_token, 0, 6) }}...</a>
        </div>
      </div>

      <div class="tab-pane" id="funnel">
        <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4">
          <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('api/v1/scenarios/funnel') . '?token=' . $funnel_token, 'QRCODE', 10, 10, [0,0,0]) }}" alt="barcode" style="width:100%;">
        </div>

        <div class="col-xs-12 text-center">
          <a href="{{ url('api/v1/scenarios/funnel') . '?token=' . $funnel_token }}" class="btn btn-lg btn-primary m-t-20" target="_blank"><i class="mi link" style="top:4px"></i> {{ 'api/v1/scenarios/funnel' . '?token=' }}{{ substr($funnel_token, 0, 6) }}...</a>
        </div>
      </div>
    </div>

    <div class="editor-modal-footer">
      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
    </div>

  </div>
</div>
@endsection