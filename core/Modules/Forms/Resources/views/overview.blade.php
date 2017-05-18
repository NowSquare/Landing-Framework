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
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.module_name_plural') }} ({{ count($forms) }})</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">

                <div class="input-group input-group" style="margin:0 15px 0 0">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" class="form-control input" id="grid_search" placeholder="{{ trans('global.search_') }}">
                </div>

                <a href="#/forms/create" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('forms::global.create_form') }}</a>
            </div>

          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <div class="row grid" id="grid">
    <div class="grid-sizer col-xs-4" style="display:none"></div>
<?php 
$i = 1;
foreach($forms as $form) {
  $sl_form = \Platform\Controllers\Core\Secure::array2string(['form_id' => $form->id]);
  $edit_url = '#/forms/editor/' . $sl_form;

  $local_domain = 'f/' . $form->local_domain;
  $url = $form->url();

  $variant = 1;

  // Update files
  $storage_root = 'forms/form/' . \Platform\Controllers\Core\Secure::staticHash(\Platform\Controllers\Core\Secure::userId()) . '/' .  \Platform\Controllers\Core\Secure::staticHash($form->id, true) . '/' . $variant;

  $published = (\Storage::disk('public')->exists($storage_root . '/published/index.blade.php')) ? '<span class="badge badge-success pull-right">published</span>' : '<span class="badge badge-danger pull-right">not published</span>';
?>
    <div class="grid-item col-xs-6 col-sm-2 col-lg-2" style="max-width: 240px" id="item{{ $i }}">

      <div class="grid-item-content portlet shadow-box" data-sl="{{ $sl_form }}">
        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark" title="{{ $form['name'] }}">{{ $form['name'] }}</h3>
          <div class="clearfix"></div>
        </div>
        <div class="portlet-body" style="padding:0">
         <table class="table table-hover" style="margin-bottom: 0">
           <tr>
             <td>{{ trans('global.visits') }}:</td>
             <td class="text-right">{{ $form->visits }}</td>
           </tr>
           <tr>
             <td>{{ trans('global.conversions') }}:</td>
             <td class="text-right">{{ $form->conversions }}</td>
           </tr>
           <tr>
             <td colspan="2"><a href="{{ $url }}" target="_blank" class="link">{{ trans('global.visit_online') }}</a> {!! $published !!}</td>
           </tr>
         </table>
          
        </div>
        <div>
          <a href="{{ $edit_url }}" class="preview-container" id="container{{ $i }}">
            <iframe src="{{ url($local_domain . '?preview=1') }}" id="frame{{ $i }}" class="preview_frame" frameborder="0" seamless></iframe>
          </a>
        </div>
        <div class="panel-footer">
          <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-block onClickDelete">{{ trans('global.delete') }}</a>
        </div>
      </div>

    </div>
<?php 
  $i++;
} 
?>
  </div>
</div>

<style type="text/css">
.panel-footer {
  padding: 0px !important;
}
.preview-container {
  display: block;
  width:100%;
  height: 120px;
}
.loader.loader-xs {
  margin: -6px auto 0;
}
.preview_frame {
  pointer-events: none;
  width: 400%;
  -ms-zoom: 0.25;
  -moz-transform: scale(0.25);
  -moz-transform-origin: 0 0;
  -o-transform: scale(0.25);
  -o-transform-origin: 0 0;
  -webkit-transform: scale(0.25);
  -webkit-transform-origin: 0 0;
}
.portlet-title {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}
</style>

<script>
$(function() {
  var $grid;

  $('#grid').liveFilter('#grid_search', 'div.grid-item', {
    filterChildSelector: '.portlet-title',
    after: function() {
      $grid.masonry('layout');
    }
  });

  blockUI('.preview-container');
  $(window).resize(resizeEditFrame);

  function resizeEditFrame() {
    $('.preview_frame').each(function() {
      var frame_height = parseInt($(this).contents().find('html').height());
      var frame_width = parseInt($(this).contents().find('html').width());

      $(this).height(frame_height);

      $(this).parent().height(frame_height / 4);
      //$(this).parent().width(frame_width / 4);
      $(this).parent().width('100%');
    });
  }

<?php
$i = 1;
foreach($forms as $form) {
?>
  $('#frame{{ $i }}').load(function() {
    resizeEditFrame();
    unblockUI('#container{{ $i }}');
<?php if ($i == count($forms)) { ?>
    setTimeout(function() {
      $grid = $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        percentPosition: true,
        transitionDuration: '0.2s'
      });
    }, 100);
<?php } ?>
  });
<?php
  $i++;
}
?>

$('.onClickDelete').on('click', function() {
  var sl = $(this).parents('.grid-item-content').attr('data-sl');
  var $item = $(this).parents('.grid-item');

  swal({
    title: _lang['delete'],
    text: _lang['confirm'],
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#da4429",
    confirmButtonText: _lang['yes_delete']
  }).then(function (result) {

    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('forms/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      $item.remove();
      $grid.masonry('layout');
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
     unblockUI();
    });

  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
});
});
</script>