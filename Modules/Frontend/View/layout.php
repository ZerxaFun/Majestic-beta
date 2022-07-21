<?php

$tpl = new Theme;

$tpl->get('layout.mjt');

$tpl->set('{THEME}', $data['module']->theme['public']);
# Вывод результата
$tpl->result('layout');

