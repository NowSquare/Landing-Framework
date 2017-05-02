<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.account') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.profile') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 col-lg-2">

      <div class="list-group">
        <a href="#/profile" class="list-group-item active">{{ trans('global.profile') }}</a>
<?php if (Gate::allows('limitation', 'account.plan_visible')) { ?>
        <a href="#/plan" class="list-group-item">{{ trans('global.plan') }}</a>
<?php } ?>
      </div>

    </div>
    <form class="ajax" id="frm" method="post" action="{{ url('platform/profile') }}">
      {!! csrf_field() !!}
      <div class="col-md-5 col-lg-7">
        <ul class="nav nav-tabs navtab-custom">
          <li class="active"> <a href="#general" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.general') }}</span> </a> </li>
          <li> <a href="#localization" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-globe" aria-hidden="true"></i></span> <span class="hidden-xs">{{ trans('global.localization') }}</span> </a> </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="general">
            <fieldset>
              <div class="form-group">
                <label for="email">{{ trans('global.name') }}</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required autocomplete="off">
              </div>
              <div class="form-group">
                <label for="email">{{ trans('global.email_address') }}</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required autocomplete="off">
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
            </fieldset>
          </div>
          <div class="tab-pane" id="localization">
            <fieldset>
              <div class="form-group">
                <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($user->language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
              </div>
              <div class="form-group">
                <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($user->timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <fieldset>
              <div class="form-group">
                <label for="current_password">{{ trans('global.current_password') }}</label>
                <input type="password" class="form-control form-control-lg" name="current_password" id="current_password" autocapitalize="off" autocorrect="off" autocomplete="off" required>
              </div>
              <p class="text-muted">{{ trans('global.current_password_info') }}</p>
            </fieldset>
          </div>
          <div class="panel-body">
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
    </form>

    <div class="col-md-4 col-lg-3">
      <div class="text-center card-box" style="margin-top: 51px">
        <div class="member-card">
          <div class="thumb-xl member-thumb m-b-10 center-block">
            <div style="display:none" class="dropzone-previews" id="dropzone-preview"></div>
            <img src="{{ $user->getAvatar() }}" class="img-circle img-thumbnail avatar" alt="profile-image" style="width:110px;height:110px"> </div>
          <div class="">
            <h4 class="m-b-5">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
          </div>
          <button class="btn btn-warning btn-sm w-sm waves-effect m-t-10 waves-light" id="upload_avatar" type="button">{{ trans('global.upload_avatar') }}</button>
          <button class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light" id="remove_avatar" type="button">{{ trans('global.remove_avatar') }}</button>
          <script>
$('#upload_avatar').dropzone({ 
  url: '{{ url('platform/profile-avatar') }}',
  maxFilesize: 3,
  headers: {
    'X-CSRF-Token': '{{ csrf_token() }}'
  },
  previewsContainer: '#dropzone-preview',
  acceptedFiles: 'image/*',
  sending: function() {
    blockUI();
  },
  success : function(file, response) {
    $('.avatar').each(function() {
      $(this).attr('src', response + "?"+ new Date().getTime());
    });
  },
  complete: function() {
    unblockUI();
  },
});

$('#remove_avatar').on('click', function() {
  _confirm('{{ url('platform/profile-avatar-delete') }}', {'_token': '{{ csrf_token() }}'}, 'POST', function(ar1, ar2, json) {
    $('.avatar').each(function() {
      $(this).attr('src', json.src.encoded);
    });
  });
});

</script>
          <div class="text-left m-t-40">
            <table width="100%" class="table table-cutoff m-b-0">
              <tbody>
                <tr>
                  <td><strong>{{ trans('global.logins') }}:</strong></td>
                  <td>{{ $user->logins }}</td>
                </tr>
                <tr>
                  <td><strong>{{ trans('global.last_login') }}:</strong></td>
                  <td>{!! ($user->last_login != NULL) ? '<div data-moment="fromNowDateTime">' . $user->last_login->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s') . '</div>' : '' !!}</td>
                </tr>
                <tr>
                  <td><strong>{{ trans('global.last_ip') }}</strong></td>
                  <td>{{ $user->last_ip }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end card-box --> 
      
    </div>

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