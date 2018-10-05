<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
         
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/emailcampaigns">{{ trans('emailcampaigns::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('emailcampaigns::global.create_campaign') }}</a>
          </div>
<?php /*
          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
                <a href="#/create" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('global.back') }}</a>
            </div>

          </div>
*/ ?>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
<?php
$i=0;
foreach ($categories as $category) {
  $i++;
?>
    <div class="col-xs-6 col-sm-4 col-lg-3">

      <div class="portlet shadow-box box-option" data-category="{{ $category['category'] }}"
        onMouseOver="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/active/' . $category['icon']) }}';"
        onMouseOut="document.getElementById('box-icon{{ $i }}').src = '{{ url('assets/images/icons/color/' . $category['icon']) }}';"
      >
        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark">{{ $category['name'] }}</h3>
          <div class="clearfix"></div>
        </div>
        <div>
          <div class="text-center">
            <a href="javascript:void(0);" class="onClickSelect">
              <img src="{{ url('assets/images/icons/color/' . $category['icon']) }}" id="box-icon{{ $i }}" class="box-icon" alt="{{ $category['name'] }}">
            </a>
          </div>
          <div class="portlet-body">
            {{ $category['desc'] }}
          </div>
          <div class="panel-footer">
            <a href="javascript:void(0);" class="btn btn-lg btn-primary btn-block onClickSelect">{{ trans('global.select') }}</a>
          </div>
        </div>
      </div>

    </div>
<?php } ?>
  </div>
</div>

<script>
var $grid = $('.grid').masonry({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  transitionDuration: '0.2s'
});

$('.onClickSelect').on('click', function() {
  var category = $(this).parents('.portlet.shadow-box').attr('data-category');

  swal({
    title: '{{ trans('global.enter_name') }}',
    text: '{{ trans('emailcampaigns::global.enter_name_text') }}',
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
      url: "{{ url('emailcampaigns/create') }}",
      data: {name: result, category: category,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if (typeof data.redir !== 'undefined') {
        document.location = '#/emailcampaigns/emails/' + data.redir;
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

</script>