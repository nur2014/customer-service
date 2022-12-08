<?php
if (!function_exists('user_id')) {
  function user_id()
  {
    return app('request')->header('accessUserId');
  }
}

if (!function_exists('username')) {
  function username()
  {
    return app('request')->header('accessUsername');
  }
}

if (!function_exists('per_page')) {
  function per_page()
  {
    return request('per_page', config('app.per_page'));
  }
}