<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';

//$result = mod_EquitiesHelper::getResultArray($params);
//echo implode(',', $result);

//$results = mod_EquitiesHelper::getObjectList($params);
//foreach($results as $result) {
 //   echo 'ASSET_NAME='.$result->ASSET_NAME.'<br/>';
//	echo 'ASSET_ID='.$result->ASSET_ID.'<br/>';
//}

$result = mod_EquitiesHelper::getData($params);
echo $result;
//echo implode(', ', $result);

require JModuleHelper::getLayoutPath('mod_equities',$params->get('layout','default'));