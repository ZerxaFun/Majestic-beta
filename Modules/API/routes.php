<?php
/**
 * Возвращение главной страницы модуля Backend
 * @url /backend
 */
Route::get('/api/', [
    'controller'	=>  'APIController',
    'action'		=>  'home',
], true

);Route::post('/api/', [
    'controller'	=>  'APIController',
    'action'		=>  'home',
], true

);

/**
 * Страница авторизации пользователя.
 */
Route::get('/api/auth/', [
    'controller'	=>  'AccountController',
    'action'		=>  'signin'
]);
