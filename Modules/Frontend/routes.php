<?php
/**
 * Возвращение главной страницы модуля Backend
 * @url /backend
 */
Route::get('/', [
    'controller'	=>  'FrontendController',
    'action'		=>  'home',
]);

Route::get('/test/1', [
    'controller'	=>  'FrontendController',
    'action'		=>  'home',
]);