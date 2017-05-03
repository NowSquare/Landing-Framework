<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <link class="-x-editor-asset" rel="stylesheet" type="text/css" href="{{ url('assets/css/styles.editor.min.css') }}" />
  <script class="-x-editor-asset" src="{{ url('assets/js/scripts.editor.min.js') }}"></script>

<script>

</script>
<style type="text/css">
  body {
    margin: 20px;
  }
</style>

</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-12">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Email address</label>
          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
          <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <div class="form-group">
          <label for="exampleSelect1">Example select</label>
          <select class="form-control" id="exampleSelect1">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleSelect2">Example multiple select</label>
          <select multiple class="form-control" id="exampleSelect2">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleTextarea">Example textarea</label>
          <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
</body>