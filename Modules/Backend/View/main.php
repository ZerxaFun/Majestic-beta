<?php

use Core\Services\Template\Theme\Theme;


$tpl = new Theme;
$tpl->get('layout.mjt');


$tpl->set('{content}', Layout::content());


# Вывод результата
$tpl->result('layout');