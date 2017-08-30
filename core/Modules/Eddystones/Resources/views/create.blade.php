<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/eddystones">{{ trans('eddystones::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('eddystones::global.create_eddystone') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-7">

      <form class="ajax" id="frm" method="post" action="{{ url('eddystones/create') }}">
        {!! csrf_field() !!}

        <div class="panel panel-default">
          <fieldset class="panel-body">

           <p>{{ trans('eddystones::global.create_eddystone_help') }}</p>

            <div class="form-group m-t-20">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" maxlength="127" required autocomplete="off">
            </div>

            <div class="form-group">
              <label for="namespace_id">{{ trans('eddystones::global.namespace_id') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.namespace_id_help') }}">&#xE887;</i></label>
              <input type="text" class="form-control" name="namespace_id" id="namespace_id" maxlength="20" placeholder="" value="" required autocomplete="off">
            </div>

            <div class="form-group">
              <label for="instance_id">{{ trans('eddystones::global.instance_id') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('eddystones::global.instance_id_help') }}">&#xE887;</i></label>
              <input type="text" class="form-control" name="instance_id" id="instance_id" maxlength="12" placeholder="" value="" required autocomplete="off">
            </div>

            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
          </fieldset>
        </div>

        <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
          <div class="panel-body">
<?php if (! $first) { ?>
            <a href="#/eddystones" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
<?php } ?>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>

      </form>

    </div>
    <div class="col-sm-5">
<?php
$eddystone_visual = trans('eddystones::global.eddystone_visual');
$eddystone_explanation = trans('eddystones::global.eddystone_explanation');
$eddystone_explanation1 = trans('eddystones::global.eddystone_explanation1');
$eddystone_explanation2 = trans('eddystones::global.eddystone_explanation2');
$eddystone_explanation3 = trans('eddystones::global.eddystone_explanation3');

if ($eddystone_explanation != '') {
?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ $eddystone_explanation }}</h3>
        </div>
        <div class="panel-body">
<?php if ($eddystone_explanation1 != '') { ?>
          <p>{!! $eddystone_explanation1 !!}</p>
<?php } ?>
<?php if ($eddystone_explanation2 != '') { ?>
          <p>{!! $eddystone_explanation2 !!}</p>
<?php } ?>
<?php if ($eddystone_explanation3 != '') { ?>
          <p>{!! $eddystone_explanation3 !!}</p>
<?php } ?>
<?php if ($eddystone_visual != '') { ?>
          <img src="{{ $eddystone_visual }}" class="img-responsive m-t-20">
<?php } ?>
        </div>
      </div>
<?php } ?>
    </div>
  </div>

</div>
<script>
</script>