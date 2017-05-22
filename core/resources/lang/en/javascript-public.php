<?php

$editor = (boolean) request()->get('editor', false);

$return = array(

	/*
	|--------------------------------------------------------------------------
	| Variables used in JavaScript, _trans['ok']
	|--------------------------------------------------------------------------
	*/

  "url" => url(''),
  "csrf" => csrf_token(),
	"ok" => "OK"
);

if ($editor) {
  $merge = [
    "form_post_demo_title" => "Demo",
    "form_post_demo_text" => "No data was posted!"
  ];

  $return = array_merge($return, $merge);
}

return $return;