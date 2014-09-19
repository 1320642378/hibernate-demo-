<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';

$result = ModPortfolioSummaryHelper::getResultArray($params);

require JModuleHelper::getLayoutPath('mod_portfolio_summary',$params->get('layout','default'));