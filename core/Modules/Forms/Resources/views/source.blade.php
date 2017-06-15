<style type="text/css" media="screen">
html, body {
  overflow: hidden;
}

#editor { 
  position: absolute;
  top: 60px;
  right: 0;
  bottom: 0;
  left: 0;
}
body .ace_scrollbar {
}
</style>

<div id="editor">{{ urldecode($html) }}</div>

<script src="{{ url('assets/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/merbivore");
  editor.setOptions({ fontSize: '14px' });
  editor.setDisplayIndentGuides(false);
  editor.setShowPrintMargin(false);
  editor.getSession().setTabSize(2);
  editor.getSession().setMode("ace/mode/html");
  editor.getSession().setUseWrapMode(true);

  $('#generic_title a').text("{!! str_replace('"', '&quot;', $form->name) !!}");

  $('#edit_buttons #save_button').unbind();
  $('#edit_buttons #save_publish_button').unbind();

  $('#edit_buttons #save_button, #edit_buttons #save_publish_button').on('click', function() {
    blockUI();

    var publish = ($(this).attr('id') == 'save_publish_button') ? 1 : 0;

    var jqxhr = $.ajax({
      url: "{{ url('forms/source') }}",
      data: {sl: "{{ $sl }}", html: editor.getValue(), publish: publish, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {

      $.notify({
        title: "{{ trans('javascript.notification') }}",
        text: data.msg,
        image: '<i class="mi save" style="font-size:44px;margin-left: 3px;"></i>'
      }, {
        style: 'metro',
        className: 'success',
        globalPosition: 'top right',
        showAnimation: "show",
        showDuration: 0,
        hideDuration: 200,
        autoHide: true,
        autoHideDelay: 2000,
        clickToHide: true
      });

    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  });
</script>