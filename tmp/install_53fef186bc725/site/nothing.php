<?php
defined('_JEXEC') or die;
$controller = JControllerLegacy::getInstance('Nothing');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();