<?php
use Illuminate\Support\Facades\Route;
//Contact
Route::get('/page/contact-us','ContactController@index')->name("home.help.index");

Route::post('/contact/store','ContactController@store')->name("contact.store");
