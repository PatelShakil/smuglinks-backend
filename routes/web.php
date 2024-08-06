<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs',function(){
    $txtFile = file_get_contents("https://api.smuglinks.com/api-docs.txt");
    echo $txtFile;
});
