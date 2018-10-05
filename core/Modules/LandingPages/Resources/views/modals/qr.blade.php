@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
  </div>

  <div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4">

      <ul class="nav nav-tabs navtab-custom" style="width:100%">
        <li class="active" style="width:50%; text-align: center"><a href="#published" data-toggle="tab" aria-expanded="false">{{ trans('global.published') }}</a></li>
        <li style="width:50%; text-align: center"><a href="#preview" data-toggle="tab" aria-expanded="false">{{ trans('global.preview') }}</a></li>
      </ul>

    </div>

    <div class="tab-content">
      <div class="tab-pane active" id="published">
        <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4">
          <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($url, 'QRCODE', 10, 10, [0,0,0]) }}" alt="barcode" style="width:100%;">
        </div>

        <div class="col-xs-12 text-center">
          <a href="{{ $url }}" class="btn btn-lg btn-primary m-t-20" target="_blank"><i class="mi link" style="top:4px"></i> {{ $url }}</a>
          <?php /*<textarea class="form-control text-center m-t-20" rows="1" disabled>{{ $url }}</textarea>*/ ?>
        </div>
      </div>

      <div class="tab-pane" id="preview">
        <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4">
          <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($url . '?preview=1&token=' . $jwt_token, 'QRCODE', 10, 10, [0,0,0]) }}" alt="barcode" style="width:100%;">
        </div>

        <div class="col-xs-12 text-center">
          <a href="{{ $url }}?preview=1&token={{ $jwt_token }}" class="btn btn-lg btn-primary m-t-20" target="_blank"><i class="mi link" style="top:4px"></i> {{ $url }}?preview=1&token={{ substr($jwt_token, 0, 6) }}...</a>
<?php /*          <textarea class="form-control text-center m-t-20" rows="4" disabled>{{ $url }}?preview=1&token={{ $jwt_token }}</textarea>*/ ?>
        </div>
      </div>
    </div>

    <div class="editor-modal-footer">
      <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
    </div>

  </div>
</div>
@endsection