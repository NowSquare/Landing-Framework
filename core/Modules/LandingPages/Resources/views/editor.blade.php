<iframe id="editor_frame" class="desktop_mode" src="{{ url('landingpages/edit/?sl=' . $sl) }}" frameborder="0" allowtransparency="true" seamless></iframe>
<style type="text/css">
  #view {
    background-color: #21252b;
  }
  .topbar-main {
    box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.04), 0 1px 18px 0 rgba(0, 0, 0, 0.02), 0 3px 5px -1px rgba(0, 0, 0, 0.1);
  }
  #editor_frame {
    display:block;
    margin: 0 auto;
    transition: width 0.5s;
  }
  #editor_frame.desktop_mode {
    width: 1200px;
  }
  #editor_frame.tablet_mode {
    width: 800px;
  }
  #editor_frame.phone_mode {
    width: 450px;
  }
</style>
<script>
$('#editor_frame').removeClass('desktop_mode tablet_mode phone_mode');
$('#editor_frame').addClass($('#device_selector li.active').attr('id'));

$('#device_selector a').on('click', function() {
  $('#device_selector li').removeClass('active');
  $(this).parent('li').addClass('active');
  var mode = $(this).parent('li').attr('id');
  $('#editor_frame').removeClass('desktop_mode tablet_mode phone_mode');
  $('#editor_frame').addClass(mode);
});

previewSiteResize();

$(window).resize($.debounce(100, previewSiteResize));

function previewSiteResize()
{
	$('#editor_frame').css({ 'height' : (parseInt($(window).outerHeight()) - 64) + 'px'});
}
</script>