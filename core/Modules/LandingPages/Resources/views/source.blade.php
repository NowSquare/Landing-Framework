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
</style>

<div id="editor">{{ $html }}</div>

<script src="{{ url('assets/ace/ace.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/twilight");
  editor.getSession().setTabSize(2);
  editor.getSession().setMode("ace/mode/html");
  document.getElementById('editor').style.fontSize='14px';
</script>