@extends('landingpages::layouts.modal')

@section('content') 
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
  </div>

  <div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4">
      <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($url . '?preview=1', 'QRCODE', 10, 10, [0,0,0]) }}" alt="barcode" style="width:100%;">
    </div>
    <div class="col-xs-12">
      <p class="lead text-center" style="margin: 3rem 0">{{ $url }}?preview=1</p>
    </div>

    <div class="col-xs-10 col-sm-6">
      <div class="editor-modal-footer">
        <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.close') }}</button>
      </div>
    </div>

  </div>
</div>
@endsection