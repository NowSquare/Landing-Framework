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
            <a class="navbar-brand link" href="#/emailcampaigns">{{ trans('emailcampaigns::global.module_name_plural') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $email_campaign->name }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
<?php
foreach($email_campaigns as $campaign) {
  $sl_email_campaign = \Platform\Controllers\Core\Secure::array2string(['email_campaign_id' => $campaign->id]);
  $selected = ($campaign->id == $email_campaign->id) ? ' active' : '';
  echo '<li class="' . $selected . '"><a href="#/emailcampaigns/edit/' . $sl_email_campaign . '">' . $campaign->name . '</a></li>';
}
?>
                </ul>
              </li>
            </ul>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.emails') }} ({{ count($email_campaign->emails) }})</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">

                <div class="input-group input-group" style="margin:0 5px 0 0">
                  <span class="input-group-addon" onClick="if ($('#grid_search:visible').length) { $('#grid_search').delay().animate({width:'0px'}, 150, '').hide(0); } else { $('#grid_search').show().animate({width:'180px'}, 500, 'easeOutBounce'); }"><i class="mi search"></i></span>
                  <input type="text" class="form-control input" id="grid_search" placeholder="{{ trans('global.search_') }}" style="width:0px;display: none">
                </div>
<?php /*
                <div class="input-group input-group" style="margin:0 5px 0 0">
                  <span class="input-group-addon" onClick="if ($('#order_selector:visible').length) { $('#order_selector').delay().animate({width:'0px'}, 150, '').hide(0); } else { $('#order_selector').show().animate({width:'180px'}, 500, 'easeOutBounce'); }"><i class="mi sort"></i></span>
                  <div style="width: 0; overflow: hidden; display: none" id="order_selector">
                  <div style="min-width:180px">
                    <select id="order" class="select2-required-no-search">
                      <option value="new_first"<?php if ($order == 'new_first') echo ' selected'; ?>>{{ trans('global.new_first') }}</option>
                      <option value="old_first"<?php if ($order == 'old_first') echo ' selected'; ?>>{{ trans('global.old_first') }}</option>
                      <option value="high_converting_first"<?php if ($order == 'high_converting_first') echo ' selected'; ?>>{{ trans('global.high_conversion_first') }}</option>
                      <option value="low_converting_first"<?php if ($order == 'low_converting_first') echo ' selected'; ?>>{{ trans('global.low_conversion_first') }}</option>
                      <option value="most_visited_first"<?php if ($order == 'most_visited_first') echo ' selected'; ?>>{{ trans('global.most_visited_first') }}</option>
                      <option value="least_visited_first"<?php if ($order == 'least_visited_first') echo ' selected'; ?>>{{ trans('global.least_visited_first') }}</option>
                    </select>
                  </div>
                  </div>
                </div>
*/ ?>
                <a href="#/emailcampaigns/emails/create/{{ $sl }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('emailcampaigns::global.create_email') }}</a>
            </div>

          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <div class="row grid" id="grid">
    <div class="grid-sizer col-xs-6 col-sm-3 col-lg-3" style="display:none"></div>
<?php 
$i = 1;
foreach($email_campaign->emails as $email) {
  $email_id = $email->id;
  $sl_campaign = \Platform\Controllers\Core\Secure::array2string(['email_campaign_id' => $email_campaign->id]);
  $sl_email = \Platform\Controllers\Core\Secure::array2string(['email_id' => $email_id]);
  $edit_url = '#/emailcampaigns/editor/' . $sl_email;

  $local_domain = 'ec/' . $email->local_domain;
  $url = $email->url();

?>
    <div class="grid-item col-xs-6 col-sm-3 col-lg-3" style="max-width: 250px" id="item{{ $i }}">

      <div class="grid-item-content portlet shadow-box" data-sl="{{ $sl_email }}">

        <div class="btn-group pull-right">
          <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mi more_vert"></i>
          </button>
          <ul class="dropdown-menu m-t-0">
            <li><a href="#/emailcampaigns/emails/editor/{{ $sl_email }}">{{ trans('emailcampaigns::global.edit_email_campaign') }}</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="javascript:void(0);" class="onClickDelete">{{ trans('global.delete') }}</a></li>
          </ul>
        </div>

        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark" title="{{ $email->name }}">{{ $email->name }}</h3>
          <div class="clearfix"></div>
        </div>

        <div class="portlet-body" style="padding:0">
         <table class="table table-hover table-striped" style="margin-bottom: 0">
           <tr>
             <td width="33" class="text-center"><i class="mi open_in_browser"></i></td>
             <td colspan="2"><a href="{{ $url }}" target="_blank" class="link">{{ trans('global.view') }}</a></td>
           </tr>
           <tr>
             <td class="text-center"><i class="mi send"></i></td>
             <td>{{ trans('global.sent') }}:</td>
             <td class="text-right"><strong>{{ number_format($email->conversions) }}</strong></td>
           </tr>
           <tr>
             <td width="33" class="text-center"><i class="mi open_in_new"></i></td>
             <td>{{ trans('global.opens') }}:</td>
             <td class="text-right"><strong>{{ number_format($email->visits) }}</strong></td>
           </tr>
           <tr>
             <td class="text-center"><i class="mi touch_app"></i></td>
             <td>{{ trans('global.clicks') }}:</td>
             <td class="text-right"><strong>{{ number_format($email->conversions) }}</strong></td>
           </tr>
         </table>
        </div>

        <div>
          <a href="#/emailcampaigns/emails/editor/{{ $sl_email }}" class="preview-container" id="container{{ $i }}" title="{{ $email->name }}">
            <iframe src="{{ url($local_domain . '?preview=1') }}" id="frame{{ $i }}" class="preview_frame" frameborder="0" seamless></iframe>
          </a>
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
  border-top: 2px solid #e5e5e5;
  display: block;
  width:100%;
  height: 120px;
}
.loader.loader-xs {
  margin: -6px auto 0;
}
.preview_frame {
  pointer-events: none;
  position: absolute;
  width: 500%;
  -ms-zoom: 0.2;
  -moz-transform: scale(0.2);
  -moz-transform-origin: 0 0;
  -o-transform: scale(0.2);
  -o-transform-origin: 0 0;
  -webkit-transform: scale(0.2);
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
  var $grid = $('.grid').masonry({
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    transitionDuration: '0.2s'
  });

  $('#grid').liveFilter('#grid_search', 'div.grid-item', {
    filterChildSelector: '.portlet-title',
    after: function() {
      $grid.masonry();
    }
  });

  blockUI('.preview-container');
  $(window).resize(resizeEditFrame);

  function resizeEditFrame() {
    $('.preview_frame').each(function() {
      var frame_height = parseInt($(this).contents().find('html').height());
      var frame_width = parseInt($(this).contents().find('html').width());

      $(this).height(frame_height);

      $(this).parent().height(frame_height / 5);
      //$(this).parent().width(frame_width / 4);
      $(this).parent().width('100%');
    });
  }

<?php
$i = 1;
foreach($email_campaign->emails as $email) {
?>
  $('#frame{{ $i }}').on('load', function() {
    resizeEditFrame();
    unblockUI('#container{{ $i }}');
<?php if ($i == count($email)) { ?>
    setTimeout(function() {
      $grid.masonry('reloadItems').masonry();
    }, 200);
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
      url: "{{ url('emailcampaigns/emails/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      document.location.reload();
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