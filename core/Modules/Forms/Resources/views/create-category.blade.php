<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/forms">{{ trans('forms::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/forms/create">{{ trans('global.category') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('forms::global.' . $category) }}</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/forms/create" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row grid">
    <div class="grid-sizer col-xs-4" style="display:none"></div>
<?php
$i=0;
foreach ($templates as $template) {
  $i++;
?>
    <div class="grid-item col-xs-6 col-sm-2 col-lg-2" style="max-width: 240px">

      <div class="grid-item-content portlet shadow-box box-option" data-template="{{ $template['dir'] }}">
        <div>
          <div>
            <a href="javascript:void(0);" class="item-hover">
              <img src="{{ $template['preview01'] }}" id="box-icon{{ $i }}" style="width:100%;" alt="{{ $template['dir'] }}">
              <div style="position: absolute;width: 75%;">
                <button class="btn btn-success btn-lg btn-block onClickPreview">{{ trans('global.preview') }}</button>
                <button class="btn btn-lg btn-primary btn-block onClickSelect">{{ trans('global.select') }}</button>
              </div>
            </a>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
</div>
<style type="text/css">
  .item-hover {
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: default;
    flex-direction: column;
  }
  .item-hover:hover .btn {
    display: block;
  }
  .item-hover .btn {
    margin: 5px 0;
    display: none;
    text-align: center;
    box-shadow: 0 8px 10px 1px rgba(0, 0, 0, 0.14), 0 3px 14px 2px rgba(0, 0, 0, 0.12), 0 5px 5px -3px rgba(0, 0, 0, 0.2);
  }
</style>
<script>
var $grid = $('.grid').masonry({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  transitionDuration: '0.2s'
});

setTimeout(function() {
  $grid.masonry('reloadItems').masonry();
}, 200);

$('.onClickSelect').on('click', function() {
  var template = $(this).parents('.grid-item-content').attr('data-template');

  swal({
    title: '{{ trans('global.enter_name') }}',
    text: '{{ trans('forms::global.enter_name_text') }}',
    input: 'text',
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#138dfa",
    confirmButtonText: _lang['ok'],
    inputValidator: function (value) {
      return new Promise(function (resolve, reject) {
        if (value) {
          resolve()
        } else {
          reject('{{ trans('global.please_enter_value') }}')
        }
      })
    }
  }).then(function (result) {

    //blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('forms/create') }}",
      data: {name: result, template: template,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if (typeof data.redir !== 'undefined') {
        document.location = '#/forms/editor/' + data.redir;
      } else if (typeof data.msg !== 'undefined') {
        swal(
          "{{ trans('global.oops') }}",
          data.msg,
          'error'
        )
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      //unblockUI();
    });

  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
});

$('.onClickPreview').on('click', function() {
  var template = $(this).parents('.grid-item-content').attr('data-template');

  $.colorbox({
    href: app_root + '/forms/preview/' + template,
    fastIframe: false,
    overlayClose: true,
    fixed: false,
    iframe: true,
    reposition: false,
    transition: 'none', 
    fadeOut: 0,
    onOpen:function() {
      $('#colorbox').addClass('colorbox-xl');
    },
    onLoad:function() {
      //$('html, body').css('overflow', 'hidden'); // page scrollbars off
    }, 
    onClosed:function() {
      //$('html, body').css('overflow', ''); // page scrollbars on
      $('#colorbox').removeClass('colorbox-xl');
    },
    onComplete : function() { 
      $('#colorbox').resize(); 
    }  
  });
});

</script>