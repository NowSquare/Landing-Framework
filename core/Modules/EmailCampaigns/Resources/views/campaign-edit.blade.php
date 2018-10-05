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
          </div>

        </div>
      </nav>
     
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">

      <form class="ajax" id="frm" method="post" action="{{ url('emailcampaigns/update') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="sl" value="{{ $sl }}">

        <div class="row">
          <div class="col-xs-12">

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">{{ trans('global.general') }}</h3>
              </div>
              <fieldset class="panel-body">

                <div class="form-group">
                  <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
                  <input type="text" class="form-control" name="name" id="name" value="{{ $email_campaign->name }}" required autocomplete="off">
                </div>


              </fieldset>
            </div>

        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-inverse panel-border">
            <div class="panel-heading"></div>
            <div class="panel-body">
              <a href="#/emailcampaigns" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
              <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
            </div>
          </div>
        </div>
      </div>
    </form>

    </div>
  </div>

</div>

<script>
</script>