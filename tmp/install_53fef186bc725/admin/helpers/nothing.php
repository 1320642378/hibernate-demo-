<?php
defined('_JEXEC') or die;
class NothingHelper
{
public static function getActions($categoryId = 0)
{
$user = JFactory::getUser();
$result = new JObject;
if (empty($categoryId))
{
$assetName = 'com_nothing';
$level = 'component';
}
else
{
$assetName = 'com_nothing.category.'.(int) $categoryId;
$level = 'category';
}
$actions = JAccess::getActions('com_nothing', $level);
foreach ($actions as $action)
{
$result->set($action->name, $user->authorise($action->name,
$assetName));
}
return $result;
}
}