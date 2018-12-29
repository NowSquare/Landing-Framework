<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/modals">{{ trans('modals::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('modals::global.create_modal') }}</a>
          </div>
<?php if (! $first) { ?>
          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/modals" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
<?php } ?>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">

    <form class="ajax" id="frm" method="post" action="{{ url('modals/modal') }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('modals::global.modal') }}</h3>
          </div>
          <fieldset class="panel-body">
           
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>
          
            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active">{{ trans('global.active') }}</label>
              </div>
            </div>

          </fieldset>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('modals::global.content') }}</h3>
          </div>

          <fieldset class="panel-body">
            <div class="form-group">
              <label for="url">{{ trans('modals::global.url') }} <sup>*</sup></label>
              <select name="url_helper" id="url_helper" class="select2-required form-control" data-placeholder="{{ trans('modals::global.get_url_from') }}">
                <option value="">&nbsp;</option>
<?php
// Forms
if (count($forms) > 0) {
  echo '<optgroup label="' . trans('forms::global.module_name_plan') . '">';
  foreach ($forms as $form) {
    echo '<option value="' . $form->url() . '">' . $form->name . '</option>';
  }
  echo '</optgroup>';
}

// Landing pages
if (count($sites) > 0) {
  echo '<optgroup label="' . trans('landingpages::global.module_name_plural') . '">';
  foreach ($sites as $site) {
    echo '<option value="' . $site->pages->first()->url() . '">' . $site->name . '</option>';
  }
  echo '</optgroup>';
}
?>            </select>
            </div>

            <div class="form-group" style="margin-bottom: 0">
              <input type="text" class="form-control" name="url" id="url" value="" required placeholder="{{ trans('modals::global.url_placeholder') }}" autocomplete="off">
            </div>

          </fieldset>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('modals::global.conditions') }}</h3>
          </div>

          <fieldset class="panel-body">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
<?php
echo Former::select('trigger')
  ->class('select2-required form-control')
  ->name('trigger')
  ->options([
    'onload' => trans('modals::global.onload'),
    'onleave' => trans('modals::global.onleave'),
    'onscroll' => trans('modals::global.onscroll') 
  ])
  ->label(trans('modals::global.trigger') . ' <sup>*</sup>');
?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" id="trigger_onscroll" style="display: none">
                  <label for="scrollTop">{{ trans('modals::global.scroll_down') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('modals::global.scroll_info') }}">&#xE887;</i></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="scrollTop" id="scrollTop" required value="40">
                    <div class="input-group-addon">%</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="delay">{{ trans('modals::global.delay') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('modals::global.delay_info') }}">&#xE887;</i></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="delay" id="delay" required value="0">
                    <div class="input-group-addon">{{ trans('modals::global.milliseconds') }}</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="ignoreAfterCloses">{{ trans('modals::global.show') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('modals::global.show_info') }}">&#xE887;</i></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="ignoreAfterCloses" id="ignoreAfterCloses" required value="1">
                    <div class="input-group-addon">{{ trans('modals::global.times') }}</div>
                  </div>
                </div>
              </div>
            </div>

            <br>

<?php
echo '<div class="form-group">';
echo Former::select('allowedHosts[]')
  ->multiple('multiple')
  ->class('select2-tags')
  ->name('allowedHosts[]')
  ->id('allowedHosts')
  ->dataPlaceholder(trans('modals::global.allowed_hosts_placeholder'))
  ->label(trans('modals::global.allowed_hosts') . ' <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . trans('modals::global.allowed_hosts_info') . '">&#xE887;</i>');
echo '</div>';

echo '<div class="form-group">';
echo Former::select('allowedPaths[]')
  ->multiple('multiple')
  ->class('select2-tags')
  ->name('allowedPaths[]')
  ->id('allowedPaths')
  ->dataPlaceholder(trans('modals::global.allowed_paths_placeholder'))
  ->label(trans('modals::global.allowed_paths') . ' <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . trans('modals::global.allowed_paths_info') . '">&#xE887;</i>');
echo '</div>';
?>
          </fieldset>
        </div>

      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('modals::global.test_title') }}</h3>
          </div>
          <fieldset class="panel-body">
            <button type="button" class="btn-success btn-lg" id="showModal">{{ trans('modals::global.show_modal') }}</button>
          </fieldset>
        </div>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('modals::global.styling') }}</h3>
          </div>

          <fieldset class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
<?php
echo Former::select('position')
  ->class('select2-required form-control')
  ->name('position')
  ->options([
    'center' => trans('modals::global.center'),
    'right-bottom' => trans('modals::global.right_bottom') 
  ])
  ->label(trans('modals::global.position') . ' <sup>*</sup>');
?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" style="margin-top:34px">
                  <div class="checkbox checkbox-primary">
                    <input name="shadow" id="shadow" type="checkbox" value="1">
                    <label for="shadow">{{ trans('modals::global.shadow') }}</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                  <label for="width">{{ trans('modals::global.width') }} <sup>*</sup></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="width" id="width" required value="800">
                    <div class="input-group-addon">px</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                  <label for="height">{{ trans('modals::global.height') }} <sup>*</sup></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="height" id="height" required value="450">
                    <div class="input-group-addon">px</div>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group" style="margin-top:20px">
                  <div class="checkbox checkbox-primary">
                    <input name="backdropVisible" id="backdropVisible" type="checkbox" value="1" checked>
                    <label for="backdropVisible">{{ trans('modals::global.show_backdrop') }}</label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="backdrop_color">{{ trans('modals::global.backdrop_color') }} <sup>*</sup></label>
                  <div class="colorpicker-backdrop input-group colorpicker-element colorpicker-component">
                    <input type="text" id="backdrop_color" name="backdrop_color" value="rgba(0,0,0,0.85)" class="form-control">
                    <span class="input-group-btn add-on">
                      <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                        <i style="background-color: rgb(255, 255, 255);height:31px;width:31px"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" style="margin-top:20px">
                  <div class="checkbox checkbox-primary">
                    <input name="showLoader" id="showLoader" type="checkbox" value="1" checked>
                    <label for="showLoader">{{ trans('modals::global.show_loader') }}</label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="loader_color">{{ trans('modals::global.loader_color') }} <sup>*</sup></label>
                  <div class="colorpicker-loader input-group colorpicker-element colorpicker-component">
                    <input type="text" id="loader_color" name="loader_color" value="#ffffff" class="form-control">
                    <span class="input-group-btn add-on">
                      <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                        <i style="background-color: rgb(255, 255, 255);height:31px;width:31px"></i>
                      </button>
                    </span>
                  </div>
                </div>

              </div>
            </div>

            <br>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="close_color">{{ trans('modals::global.close_button_color') }} <sup>*</sup></label>
                  <div class="colorpicker-close input-group colorpicker-element colorpicker-component">
                    <input type="text" id="close_color" name="close_color" value="#000000" class="form-control">
                    <span class="input-group-btn add-on">
                      <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                        <i style="background-color: rgb(255, 255, 255);height:31px;width:31px"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                  <label for="closeBtnMargin">{{ trans('modals::global.close_button_margin') }} <sup>*</sup></label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="closeBtnMargin" id="closeBtnMargin" required value="15">
                    <div class="input-group-addon">px</div>
                  </div>
                </div>
              </div>
            </div>

          </fieldset>
        </div>

      </div>
      <!-- end col -->

      <div class="col-md-12">
   
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
<?php if (! $first) { ?>
            <a href="#/modals" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
<?php } ?>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
    
      </div>

    </form>

  </div>
</div>

<script>
$(function() {
  checkTrigger();
  $('#trigger').on('change', checkTrigger);

  $('#url_helper').on('change', function() {
    $('#url').val($('#url_helper').val());
    $('#url_helper').val('');
  });

  var $colorpicker_backdrop = $('.colorpicker-backdrop').colorpicker({
    format: 'rgba'
  });
  var $colorpicker_loader = $('.colorpicker-loader').colorpicker({
    format: 'hex'
  });
  var $colorpicker_close = $('.colorpicker-close').colorpicker({
    format: 'hex'
  });

  $('#showModal').on('click', function() {
    var url = $('#url').val();
    if (url == '') url = '{{ $reseller->url }}/modal/get?locale={{ app()->getLocale() }}';
    var shadow = $('#shadow').is(':checked');
    var contentClasses = (shadow) ? '-lm-shadow--8dp': '';
    var backdropVisible = $('#backdropVisible').is(':checked');
    var backdropBgColor = $('#backdrop_color').val();
    var showLoader = $('#showLoader').is(':checked');
    var loaderColor = $('#loader_color').val();
    var position = $('#position').val();
    var width = parseInt($('#width').val());
    var height = parseInt($('#height').val());
    var closeBtnColor = $('#close_color').val();
    var closeBtnMargin = $('#closeBtnMargin').val();

    var cfg = {
      locale: '{{ app()->getLocale() }}',
      modalUrl: url,
      backdropVisible: backdropVisible,
      backdropBgColor: backdropBgColor,
      showLoader: showLoader,
      loaderColor: loaderColor,
      contentPosition: position,
      contentWidth: width,
      contentHeight: height,
      contentClasses: contentClasses,
      closeBtnColor: closeBtnColor,
      closeBtnMargin: closeBtnMargin
    };

    $('#showModal').attr('disabled', true);

    window.showLeadModal(cfg);

    setTimeout(function() {
      $('#showModal').attr('disabled', null);
    }, 3000);

  });
});

function checkTrigger() {
  var trigger = $('#trigger').val();
  if (trigger == 'onscroll') {
    $('#trigger_onscroll').show();
  } else {
    $('#trigger_onscroll').hide();
  }
}
</script>