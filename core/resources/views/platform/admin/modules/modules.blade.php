<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
     
       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand" href="#/admin/settings">{{ trans('global.settings') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.modules') }}</a>
          </div>

        </div>
      </nav>
    
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 col-lg-2">

      @include("platform.admin.settings.menu")

    </div>

    <div class="col-md-9 col-lg-10">

      <ul class="nav nav-tabs navtab-custom">
        <li class="active"><a href="#active" data-toggle="tab" aria-expanded="false">{{ trans('global.active') }}</a></li>
        <li><a href="#inactive" data-toggle="tab" aria-expanded="false">{{ trans('global.inactive') }}</a></li>
      </ul>

      <div class="tab-content p-b-0">
        <div class="tab-pane tab-pane active" id="active">

          <div class="row">
<?php
foreach ($items as $item) {
  if ($item['enabled']) {
?>
    <div class="col-sm-6 col-lg-4">
      <div class="card-box <?php if ($item['enabled']) echo 'widget-icon'; ?> widget-user mdl-shadow--2dp" style="border:0">
        <?php if ($item['enabled']) { ?>
        <img src="{{ url('assets/images/icons/color/' . $item['icon']) }}" alt="{{ $item['name'] }}" style="width:64px;posdition: absolute">
        <?php } ?>
        <div class="wid-icon-info">
          <h4 class="m-t-5 m-b-5 font-15 text-uppercase">{{ $item['name'] }}</h4>
            <div class="text-muted">
            {{ trans('global.off') }}
            <label class="switch">
              <input type="checkbox" class="module_switch" <?php if ($item['enabled']) echo 'checked'; ?> data-sl="{{ \Platform\Controllers\Core\Secure::array2string(array('namespace' => $item['namespace'])) }}" data-warning-deactivate="{!! trans('global.do_you_want_to_de_activate', ['module' => '<strong>' . $item['name'] . '</strong>']) !!}" data-warning-activate="{!! trans('global.do_you_want_to_activate', ['module' => '<strong>' . $item['name'] . '</strong>']) !!}">
              <div class="slider round"></div>
            </label>
            {{ trans('global.on') }}
          </div>
        </div>
      </div>
    </div>
<?php
  }
}
?>
          </div>
        </div>
        <div class="tab-pane tab-pane" id="inactive">
          <div class="panel panel-inverse panel-border">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
              <p class="">{!! trans('global.modules_desc') !!}</p>
            </div>
          </div>
          <div class="row">
<?php
foreach ($items as $item) {
  if (! $item['enabled']) {
?>
    <div class="col-sm-6 col-lg-4">
      <div class="card-box <?php if ($item['enabled']) echo 'widget-icon'; ?> widget-user mdl-shadow--2dp" style="border:0">
        <?php if ($item['enabled']) { ?>
        <img src="{{ url('assets/images/icons/color/' . $item['icon']) }}" alt="{{ $item['name'] }}" style="width:64px;posdition: absolute">
        <?php } ?>
        <div class="wid-icon-info">
          <h4 class="m-t-5 m-b-5 font-15 text-uppercase">{{ $item['name'] }}</h4>
            <div class="text-muted">
            {{ trans('global.off') }}
            <label class="switch">
              <input type="checkbox" class="module_switch" <?php if ($item['enabled']) echo 'checked'; ?> data-sl="{{ \Platform\Controllers\Core\Secure::array2string(array('namespace' => $item['namespace'])) }}" data-warning-deactivate="{!! trans('global.do_you_want_to_de_activate', ['module' => '<strong>' . $item['name'] . '</strong>']) !!}" data-warning-activate="{!! trans('global.do_you_want_to_activate', ['module' => '<strong>' . $item['name'] . '</strong>']) !!}">
              <div class="slider round"></div>
            </label>
            {{ trans('global.on') }}
          </div>
        </div>
      </div>
    </div>
<?php
  }
}
?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$('.module_switch').on('click', function(e) {
  //e.preventDefault();
  //e.stopImmediatePropagation();
  //e.stopPropagation();

  var $checkbox = $(this);

  var sl = $(this).attr('data-sl');
  var checked = $(this).is(':checked');

  if(checked) {
    var warning = $(this).attr('data-warning-activate');
  } else {
    var warning = $(this).attr('data-warning-deactivate');
  }

  swal({
    title: warning,
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes']
  }).then(function() {
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/admin/modules/switch') }}",
      data: {sl: sl, checked: (checked) ? 1 : 0,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.type == 'success') {
        document.location.reload();
      } else {
        swal(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });

  }, function (dismiss) {
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
    $checkbox.prop('checked', (checked) ? false : true);
  });

});

function resume_event(type) {
    if (event_store.target.parentNode) {
        var event;

        if (document.createEvent) {
            event = document.createEvent("HTMLEvents");
            event.initEvent(type, true, true);
        } else {
            event = document.createEventObject();
            event.eventType = type;
        }

        event.eventName = type;

        if (document.createEvent) { //Not IE
            event_store.target.parentNode.dispatchEvent(event);
        } else { //IE
            event_store.target.parentNode.fireEvent("on" + event.eventType, event);
        }
    }
}
</script>
<style type="text/css">
.wid-icon-info {
  text-transform: uppercase;
  font-weight: bold;
}
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
  margin-top: 5px;
  margin-bottom: -7px;
  margin-left: 5px;
  margin-right: 5px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #da4429;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #15cd72;
}

input:focus + .slider {
  box-shadow: 0 0 1px #15cd72;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>