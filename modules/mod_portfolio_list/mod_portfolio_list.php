<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';
$result = ModPortfolioList::getResult($params);
require JModuleHelper::getLayoutPath('mod_portfolio_list',$params->get('layout', 'default'));
