<?php

use Illuminate\Support\Facades\Route;

/**
 * App Routes
 */
Route::redirect('/app/login', '/login');
Route::redirect('/app/register', '/register');

/**
 * Admin Routes
 */
Route::redirect('/admin/login', '/login');
Route::redirect('/admin/register', '/register');
