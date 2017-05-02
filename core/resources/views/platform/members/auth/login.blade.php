<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
  <h4 class="modal-title" id="contentModalLabel">{{ trans('global.login') }}</h4>
</div>
<form class="form form-horizontal flat-form ajax" role="form" method="POST" action="{{ url('member/login') }}">
  {{ csrf_field() }}
  <input type="hidden" name="sl" value="{{ $sl }}">
  <div class="modal-body">
    <p><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="{{ url('member/register') }}" class="text-muted ajax-link">{{ trans('global.create_account') }}</a></p>

    <div class="form-group">
      <div class="input-group"> <span class="input-group-addon"><i class="material-icons">&#xE0BE;</i></span>
        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('global.email') }}" required autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <div class="input-group"> <span class="input-group-addon"><i class="material-icons">&#xE0DA;</i></span>
        <input id="password" type="password" class="form-control" name="password" placeholder="{{ trans('global.password') }}" required>
      </div>
      <small class="form-text text-muted text-xs-right"><i class="fa fa-lock"></i> <a href="{{ url('member/password/reset') }}" class="text-muted ajax-link">{{ trans('global.forgot_password') }}</a></small>
    </div>

    <input name="remember" id="remember" type="hidden" value="1">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.close') }}</button> 
    <button class="btn btn-primary ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.log_in') }}</span></button>
  </div>
</form>
<script>
onPartialLoaded();

$('.ajax-link').on('click', function(event) {
  blockUI('#contentModal .modal-content');
  var href = $(this).attr('href');

  $.ajax({
    url: href,
    data: {sl: '{{ $sl }}'},
    method: 'GET'
  })
  .done(function(html) {
    $('#contentModal .modal-content').html(html);
    unblockUI('#contentModal .modal-content');
  });

  event.preventDefault();
});
</script>