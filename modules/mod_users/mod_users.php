<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';
$list = mod_usersHelper::getList();
require JModuleHelper::getLayoutPath('mod_users',$params->get('layout', 'default'));
