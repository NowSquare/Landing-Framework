<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/members">{{ trans('global.members') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_member') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-3 col-md-4">
      <div class="text-center card-box">
        <div class="member-card">
          <div class="thumb-xl member-thumb m-b-10 center-block">
            <div style="display:none" class="dropzone-previews" id="dropzone-preview"></div>
            <img src="{{ $member->getAvatar() }}" class="img-circle img-thumbnail member-avatar" alt="profile-image" style="width:110px;height:110px">
          </div>
          <div>
            <h4 class="m-b-5">{{ $member->name }}</h4>
            <p class="text-muted">{{ $member->email }}</p>
          </div>
          <button class="btn btn-warning btn-sm w-sm waves-effect m-t-10 waves-light" id="upload_avatar" type="button">{{ trans('global.upload_avatar') }}</button>
          <button class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light" id="remove_avatar" type="button">{{ trans('global.remove_avatar') }}</button>
          <script>
$('#upload_avatar').dropzone({ 
  url: '{{ url('platform/member/upload-avatar') }}',
  maxFilesize: 3,
  headers: {
    'X-CSRF-Token': '{{ csrf_token() }}'
  },
  previewsContainer: '#dropzone-preview',
  acceptedFiles: 'image/*',
  sending: function(file, xhr, data) {
    data.append('sl', '{{ $sl }}');
    blockUI();
  },
  success : function(file, response) {
    $('.member-avatar').each(function() {
      $(this).attr('src', response + "?"+ new Date().getTime());
    });
  },
  complete: function() {
    unblockUI();
  },
});

$('#remove_avatar').on('click', function() {
  _confirm('{{ url('platform/member/delete-avatar') }}', {_token: '{{ csrf_token() }}', sl: '{{ $sl }}'}, 'POST', function(ar1, ar2, json) {
    $('.member-avatar').each(function() {
      $(this).attr('src', json.src.encoded);
    });
  });
});
</script>
          <div class="text-left m-t-40">
            <table width="100%" class="table m-b-0">
              <tbody>
                <tr>
                  <td><strong>{{ trans('global.logins') }}:</strong></td>
                  <td>{{ $member->logins }}</td>
                </tr>
                <tr>
                  <td><strong>{{ trans('global.last_login') }}:</strong></td>
                  <td>{{ $member->last_login }}</td>
                </tr>
                <tr>
                  <td>{{ trans('global.last_ip') }}</td>
                  <td>{{ $member->last_ip }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end card-box --> 
      
    </div>
    <!-- end col -->
    
    <form class="ajax" id="frm" method="post" action="{{ url('platform/member/update') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <div class="col-md-8 col-lg-9">
        <ul class="nav nav-tabs navtab-custom">
          <li class="active"> <a href="#general" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.general') }}</span> </a> </li>
          <li> <a href="#localization" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-globe" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.localization') }}</span> </a> </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="general">
            <fieldset>
              <div class="form-group">
                <label for="name">{{ trans('global.name') }}</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $member->name }}" required autocomplete="off">
              </div>
              <div class="form-group">
                <label for="email">{{ trans('global.email_address') }}</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ $member->email }}" required autocomplete="off">
              </div>
              <div class="form-group">
                <label for="new_password">{{ trans('global.new_password') }}</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="off">
                  <div class="input-group-btn add-on">
                    <button class="btn btn-inverse" type="button" id="show_password" data-toggle="tooltip" title="{{ trans('global.show_hide_password') }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <button class="btn btn-inverse" type="button" id="generate_password" data-toggle="tooltip" title="{{ trans('global.generate_password') }}"><i class="fa fa-random" aria-hidden="true"></i></button>
                  </div>
                </div>
              </div>
              <p class="text-muted">{{ trans('global.new_password_info') }}</p>
              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input name="mail_login" id="mail_login" type="checkbox" value="1">
                  <label for="mail_login"> {{ trans('global.mail_login_update') }}</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input name="active" id="active" type="checkbox" value="1"<?php if($member->active == 1) echo ' checked'; ?>>
                  <label for="active"> {{ trans('global.active') }}</label>
                </div>
              </div>
              <p class="text-muted">{{ trans('global.active_user_desc') }}</p>
            </fieldset>
          </div>
          <div class="tab-pane" id="localization">
            <fieldset>
              <div class="form-group">
                <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($member->language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
              </div>
              <div class="form-group">
                <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($member->timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body"> <a href="#/members" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
    </form>
  </div>
  <!-- end row --> 
  
</div>
<script>
  $('#show_password').on('click', function()
  {
    if(! $(this).hasClass('active'))
    {
      $(this).addClass('active');
      togglePassword('new_password', 'form-control', true);
    }
    else
    {
      $(this).removeClass('active');
      togglePassword('new_password', 'form-control', false);
    }
  });
  
  $('#generate_password').on('click', function()
  {
    $('#new_password').val(randomString(8));
  });    
</script>