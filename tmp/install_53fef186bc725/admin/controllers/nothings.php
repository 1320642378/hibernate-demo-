<?php
defined('_JEXEC') or die;
class NothingControllerNothings extends JControllerAdmin
{
public function getModel($name = 'Nothing', $prefix ='NothingModel', $config =array('ignore_request' => true))
{
$model = parent::getModel($name, $prefix, $config);
return $model;
}
}