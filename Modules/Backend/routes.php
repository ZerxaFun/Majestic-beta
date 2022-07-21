<?php
/**
 * Возвращение главной страницы модуля Backend
 * @url /backend
 */
Route::get('/admin', [
    'controller'	=>  'HomeController',
    'action'		=>  'home',
]);

/**
 * Страница авторизации пользователя.
 */
Route::get('/admin/account/signin', [
    'controller'	=>  'AccountController',
    'action'		=>  'signin'
]);
