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
              <label for="support_email">{{ trans('global.support_email') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="support_email" id="support_email" value="" required autocomplete="off">
            </div>

            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
              <span class="help-block"><small>{!! trans('global.active_reseller_desc') !!}</small></span>
            </div>

          </fieldset>
        </div>

      </div>
      <!-- end col -->

      <div class="col-md-8">

        <ul class="nav nav-tabs navtab-custom">
          <li class="active"><a href="#design" data-toggle="tab" aria-expanded="false">{{ trans('global.design') }}</a></li>
          <li><a href="#payment" data-toggle="tab" aria-expanded="false">{{ trans('global.payment_settings') }}</a></li>
          <li><a href="#mail_settings" data-toggle="tab" aria-expanded="false">{{ trans('global.mail_settings') }}</a></li>
          <li><a href="#localization" data-toggle="tab" aria-expanded="false">{{ trans('global.localization') }}</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane tab-pane active" id="design">
            <fieldset>
              <div class="row">
                <div class="col-md-8">
                  <fieldset>
                    <legend>{{ trans('global.branding') }}</legend>
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

                  </fieldset>
                  <br>
                  <fieldset>
                    <legend>{{ trans('global.website') }}</legend>

                    <div class="form-group m-b-30">
                      <div class="checkbox checkbox-primary">
                        <input name="website_active" id="website_active" type="checkbox" value="1" checked>
                        <label for="website_active"> {{ trans('global.active') }}</label>
                      </div>
                      <span class="help-block"><small>{!! trans('global.active_website_desc') !!}</small></span>
                    </div>

                    <div class="form-group m-b-0">
                      <label for="header_gradient_start">{{ trans('global.header_gradient') }}</label>
                    </div>

                    <div class="row">
                      <div class="col-xs-6">

                        <div class="form-group">
                          <div class="colorpicker-default input-group colorpicker-element colorpicker-component">
                            <input type="text" name="header_gradient_start" value="{{ $header_gradient_start }}" class="form-control">
                            <span class="input-group-btn add-on">
                              <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                                <i style="background-color: {{ $header_gradient_start }};height:31px;width:31px"></i>
                              </button>
                            </span>
                          </div>
                        </div>

                      </div>
                      <div class="col-xs-6">

                        <div class="form-group">
                          <div class="colorpicker-default input-group colorpicker-element colorpicker-component">
                            <input type="text" name="header_gradient_end" value="{{ $header_gradient_end }}" class="form-control">
                            <span class="input-group-btn add-on">
                              <button class="btn btn-white" type="button" style="padding:0; background-color:#fff; border:1px solid #eee;">
                                <i style="background-color: {{ $header_gradient_end }};height:31px;width:31px"></i>
                              </button>
                            </span>
                          </div>
                        </div>

                      </div>
                    </div>

                    <div class="form-group">
                      <label for="header_image">{{ trans('global.header_image') }}</label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="header_image" name="header_image" autocomplete="off" value="{{ $header_image }}">
                        <div class="input-group-btn add-on">
                          <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="header_image" data-preview="header_image-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                          <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="header_image-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="header_title">{{ trans('global.header_title') }}</label>
                      <input type="text" class="form-control" name="header_title" id="header_title" value="{{ $header_title }}" autocomplete="off">
                    </div>

                    <div class="form-group">
                      <label for="header_cta">{{ trans('global.header_cta') }}</label>
                      <input type="text" class="form-control" name="header_cta" id="header_cta" value="{{ $header_cta }}" autocomplete="off">
                    </div>

                  </fieldset>

                </div>
              </div>
            </fieldset>
          </div>

          <div class="tab-pane" id="mail_settings">
            <p class="m-b-20">{!! trans('global.mail_settings_help') !!}</p>

            <fieldset>

              <div class="form-group">
                <label for="mail_from_name">{{ trans('global.sender_name') }}</label>
                <input type="text" class="form-control" name="mail_from_name" id="mail_from_name" value="" autocomplete="off" placeholder="{{ $main_reseller->mail_from_name }}">
              </div>

              <div class="form-group">
                <label for="mail_from_address">{{ trans('global.sender_address') }}</label>
                <input type="text" class="form-control" name="mail_from_address" id="mail_from_address" value="" autocomplete="off" placeholder="{{ $main_reseller->mail_from_address }}">
              </div>

              <hr>

              <div class="form-group">
                <?php

                echo Former::select('mail_driver')
                  ->class('select2-required form-control')
                  ->name('mail_driver')
                  ->options(['' => '&nbsp;', 'sendmail' => 'Sendmail', 'smtp' => 'SMTP', 'mailgun' => 'Mailgun'])
                  ->label(trans('global.driver'));
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

              <hr>

              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="mail_host">{{ trans('global.host') }}</label>
                    <input type="text" class="form-control" name="mail_host" id="mail_host" value="" autocomplete="off">
                  </div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="mail_port">{{ trans('global.port') }}</label>
                    <input type="number" class="form-control" name="mail_port" id="mail_port" value="" autocomplete="off">
                  </div>
                </div>
                <div class="col-12 col-md-3">
                <div class="form-group">
                  <?php
                  echo Former::select( 'mail_encryption' )->class( 'select2-required form-control' )->name( 'mail_encryption' )->options( [ '' => '&nbsp;', 'tls' => 'tls', 'ssl' => 'ssl' ] )->label( trans( 'global.encryption' ) );
                  ?>
                </div>                 
                </div>
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
            <p class="m-b-20">{!! trans('global.localization_settings_help') !!}</p>

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

          <div class="tab-pane" id="payment">

            <fieldset>
              <legend><label><input type="radio" name="payment_provider" value="AVANGATE"<?php if ($payment_provider == 'AVANGATE') echo ' checked'; ?>> Avangate</label></legend>
  
              <div class="form-group">
                <label for="avangate_affiliate">{{ trans('global.affiliate_id') }} ({{ trans('global.optional') }})</label>
                <input type="text" class="form-control" name="avangate_affiliate" id="avangate_affiliate" autocomplete="off" value="">
                <span class="help-block"><small>{!! trans('global.avangate_affiliate_id_desc') !!}</small></span>
              </div>

              <div class="form-group">
                <label for="avangate_key">{{ trans('global.key') }}</label>
                <input type="text" class="form-control" name="avangate_key" id="avangate_key" autocomplete="off" value="">
                <span class="help-block"><small>{!! trans('global.avangate_key_desc') !!}</small></span>
              </div>
            </fieldset>

            <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

            <fieldset>
              <legend><label><input type="radio" name="payment_provider" value="STRIPE"<?php if ($payment_provider == 'STRIPE') echo ' checked'; ?>> Stripe</label></legend>

              <div class="form-group">
                <label for="stripe_key">{{ trans('global.publishable_key') }}</label>
                <input type="text" class="form-control" name="stripe_key" id="stripe_key" autocomplete="off" value="">
              </div>

              <div class="form-group">
                <label for="stripe_secret">{{ trans('global.secret_key') }}</label>
                <input type="text" class="form-control" name="stripe_secret" id="stripe_secret" autocomplete="off" value="">
                <span class="help-block"><small>{!! trans('global.stripe_key_desc') !!}</small></span>
              </div>
            </fieldset>

            <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

            <fieldset>
              <legend><label><input type="radio" name="payment_provider" value="CUSTOM"<?php if ($payment_provider == 'CUSTOM') echo ' checked'; ?>> {{ trans('global.custom') }}</label></legend>
              <div class="m-b-20">
              {!! trans('global.custom_payment_help') !!}
              </div>

              <div class="form-group">
                <label for="custom_affiliate_id">{{ trans('global.affiliate_id') }} ({{ trans('global.optional') }})</label>
                <input type="text" class="form-control" name="custom_affiliate_id" id="custom_affiliate_id" autocomplete="off" value="">
                <span class="help-block"><small>{!! trans('global.custom_affiliate_id_desc') !!}</small></span>
              </div>

              <h3>{{ trans('global.custom_url_parameters') }}</h3>
              <div class="alert alert-success"><strong>{!! trans('global.query_parameter_preview', ['user_id' => auth()->user()->id]) !!}</strong></div>
              {!! trans('global.custom_url_parameters_help') !!}

              <div class="form-group m-t-20">
                <label for="user_query_parameter">{{ trans('global.user_query_parameter') }}</label>
                <input type="text" class="form-control" name="user_query_parameter" id="user_query_parameter" value="" autocomplete="off" placeholder="{{ $user_query_parameter_placeholder }}">
                <span class="help-block"><small>{!! trans('global.user_query_parameter_desc') !!}</small></span>
              </div>

              <div class="form-group">
                <label for="affiliate_query_parameter">{{ trans('global.affiliate_query_parameter') }}</label>
                <input type="text" class="form-control" name="affiliate_query_parameter" id="affiliate_query_parameter" value="" autocomplete="off" placeholder="{{ $affiliate_query_parameter_placeholder }}">
                <span class="help-block"><small>{!! trans('global.affiliate_query_parameter_desc') !!}</small></span>
              </div>
<script>
$('#custom_affiliate_id, #user_query_parameter, #affiliate_query_parameter').on('keyup change', updateCustomQueryParametersPreview);
updateCustomQueryParametersPreview();

function updateCustomQueryParametersPreview() {
  var custom_affiliate_id = $('#custom_affiliate_id').val();
  var user_query_parameter = $('#user_query_parameter').val();
  var affiliate_query_parameter = $('#affiliate_query_parameter').val();

  if (user_query_parameter == '') user_query_parameter = $('#user_query_parameter').attr('placeholder');
  if (affiliate_query_parameter == '') affiliate_query_parameter = $('#affiliate_query_parameter').attr('placeholder');

  $('#user_query_parameter_preview').text(user_query_parameter);
  $('#affiliate_query_parameter_preview').text(affiliate_query_parameter);
  $('#custom_affiliate_id_preview').text(custom_affiliate_id);
  
}
</script>


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