<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/admin/resellers">{{ trans('global.resellers') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.create_reseller') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/reseller/new') }}">
    <div class="row">
      {!! csrf_field() !!}
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.general') }}</h3>
          </div>
          <fieldset class="panel-body">

            <div class="form-group">
              <label for="name">{{ trans('global.platform_name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>

            <div class="form-group">
              <label for="name">{{ trans('global.domain') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.domain_help', ['host' => '<strong>' . \Request::getHost() . '</strong>']) }}">&#xE887;</i></label>

              <div class="input-group">
                <span class="input-group-addon">http(s)://</span>
                <input type="text" class="form-control" id="domain" name="domain" required autocomplete="off">
              </div>
            </div>

            <div class="form-group">
              <?php

              $account_owner = Former::select( 'user_id' )->addOption( '&nbsp;' )->class( 'select2-required form-control' )->name( 'user_id' )->fromQuery( $users, 'email', 'id' )->required( true )->label( trans( 'global.account_owner' ) );

              if ( isset( $users[ '' ] ) )$account_owner->disabled( true );

              echo $account_owner;
              ?>
            </div>

            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
            <p class="text-muted">{{ trans('global.active_reseller_desc') }}</p>

          </fieldset>
        </div>

      </div>
      <!-- end col -->

      <div class="col-md-8">

        <ul class="nav nav-tabs navtab-custom">
          <li class="active"><a href="#design" data-toggle="tab" aria-expanded="false">{{ trans('global.design') }}</a></li>
          <li><a href="#mail_settings" data-toggle="tab" aria-expanded="false">{{ trans('global.mail_settings') }}</a></li>
          <li><a href="#localization" data-toggle="tab" aria-expanded="false">{{ trans('global.localization') }}</a></li>
          <li><a href="#avangate" data-toggle="tab" aria-expanded="false">Avangate</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane tab-pane active" id="design">
            <fieldset>
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="logo">{{ trans('global.logo') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.logo_help') }}">&#xE887;</i></label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="logo" name="logo" autocomplete="off" value="">
                      <div class="input-group-btn add-on">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="logo" data-preview="logo-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                        <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="logo-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="logo_square">{{ trans('global.logo_square') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.logo_square_help') }}">&#xE887;</i></label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="logo_square" name="logo_square" autocomplete="off" value="">
                      <div class="input-group-btn add-on">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="logo_square" data-preview="logo_square-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                        <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="logo_square-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="favicon">{{ trans('global.favicon') }}</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="favicon" name="favicon" autocomplete="off" value="">
                      <div class="input-group-btn add-on">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="favicon" data-preview="favicon-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                        <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="favicon-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="tab-pane" id="mail_settings">
            <fieldset>

              <div class="form-group">
                <?php
                echo Former::select( 'mail_driver' )->class( 'select2-required form-control' )->name( 'mail_driver' )->options( [ 'smtp' => 'SMTP', 'mailgun' => 'Mailgun' ] )->label( trans( 'global.driver' ) );
                ?>
                <script>
                  $( '#mail_driver' ).on( 'change', checkMailDriver );

                  function checkMailDriver() {
                    if ( $( '#mail_driver' ).val() == 'mailgun' ) {
                      $( '#mailgun_div' ).show();
                    } else {
                      $( '#mailgun_div' ).hide();
                    }
                  }

                  checkMailDriver();
                </script>
              </div>

              <div id="mailgun_div">

                <div class="form-group">
                  <label for="mail_mailgun_domain">{{ trans('global.mailgun_domain') }}</label>
                  <input type="text" class="form-control" name="mail_mailgun_domain" id="mail_mailgun_domain" value="" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="mail_mailgun_secret">{{ trans('global.mailgun_secret') }}</label>
                  <input type="text" class="form-control" name="mail_mailgun_secret" id="mail_mailgun_secret" value="" autocomplete="off">
                </div>

                <hr>

              </div>

              <div class="form-group">
                <label for="mail_from_name">{{ trans('global.sender_name') }}</label>
                <input type="text" class="form-control" name="mail_from_name" id="mail_from_name" value="" autocomplete="off">
              </div>

              <div class="form-group">
                <label for="mail_from_address">{{ trans('global.sender_address') }}</label>
                <input type="text" class="form-control" name="mail_from_address" id="mail_from_address" value="" autocomplete="off">
              </div>

              <hr>

              <div class="form-group">
                <label for="mail_host">{{ trans('global.host') }}</label>
                <input type="text" class="form-control" name="mail_host" id="mail_host" value="" autocomplete="off">
              </div>

              <div class="form-group">
                <label for="mail_port">{{ trans('global.port') }}</label>
                <input type="number" class="form-control" name="mail_port" id="mail_port" value="" autocomplete="off">
              </div>

              <div class="form-group">
                <?php
                echo Former::select( 'mail_encryption' )->class( 'select2-required form-control' )->name( 'mail_encryption' )->options( [ '' => '&nbsp;', 'tls' => 'tls', 'ssl' => 'ssl' ] )->label( trans( 'global.encryption' ) );
                ?>
              </div>

              <div class="form-group">
                <label for="mail_username">{{ trans('global.username') }}</label>
                <input type="text" class="form-control" name="mail_username" id="mail_username" value="" autocomplete="off">
              </div>

              <div class="form-group">
                <label for="mail_password">{{ trans('global.password') }}</label>
                <input type="text" class="form-control" name="mail_password" id="mail_password" value="" autocomplete="off">
              </div>

            </fieldset>

          </div>

          <div class="tab-pane" id="localization">
            <fieldset>
              <div class="form-group">
                <?php
                echo Former::select( 'language' )->class( 'select2-required form-control' )->name( 'language' )->forceValue( $reseller->default_language )->options( \Platform\ Controllers\ Core\ Localization::getLanguagesArray() )->label( trans( 'global.language' ) );
                ?>
              </div>
              <div class="form-group">
                <?php
                echo Former::select( 'timezone' )->class( 'select2-required form-control' )->name( 'timezone' )->forceValue( $reseller->default_timezone )->options( trans( 'timezones.timezones' ) )->label( trans( 'global.timezone' ) );
                ?>
              </div>
            </fieldset>
          </div>

          <div class="tab-pane" id="avangate">
            <fieldset>
              <div class="form-group">
                <label for="avangate_key">{{ trans('global.key') }}</label>
                <input type="text" class="form-control" name="avangate_key" id="avangate_key" autocomplete="off">
              </div>
            </fieldset>
          </div>

        </div>
        <!-- tab-content -->

      </div>
      <!-- end col -->

    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/admin/resellers" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>