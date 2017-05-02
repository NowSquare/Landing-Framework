<?php
// Secured member routes
Route::group(['middleware' => 'auth:member'], function () {

  //Member profile
  Route::get('profile', 'AuthMember\AccountController@showProfile');
  Route::post('profile', 'AuthMember\AccountController@postProfile');
});