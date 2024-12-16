<?php

use Illuminate\Support\Facades\Session;

function flashResponse($message, $color = 'primary'){
    Session::flash('message', $message);
    Session::flash('color', $color);
}
