<?php

$tpl = new Theme;

$tpl->get('account/signin.mjt');

$tpl->set('{THEME}', $data['module']->theme['public']);
# Вывод результата
$tpl->result('signin');

