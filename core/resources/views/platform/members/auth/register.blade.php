<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
  <h4 class="modal-title" id="contentModalLabel">{{ trans('global.register') }}</h4>
</div>
<form class="form form-horizontal flat-form ajax" role="form" method="POST" action="{{ url('member/register') }}">
  {{ csrf_field() }}
  <input type="hidden" name="sl" value="{{ $sl }}">
  <div class="modal-body">
    <p><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="{{ url('member/login') }}" class="text-muted ajax-link">{{ trans('global.already_have_account') }}</a></p>


    <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon"><i class="material-icons">&#xE7FD;</i></span>
        <input class="form-control" type="text" placeholder="{{ trans('global.your_name') }}" name="name" value="" required autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <div class="input-group">
        <span class="input-group-addon"><i class="material-icons">&#xE0BE;</i></span>
        <input class="form-control" type="email" placeholder="{{ trans('global.email_address') }}" name="email" value="" required autocomplete="off">
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.close') }}</button> 
    <button class="btn btn-primary" type="submit">{{ trans('global.register') }}</button>
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