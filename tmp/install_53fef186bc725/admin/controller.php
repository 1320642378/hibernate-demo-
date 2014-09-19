<?php
defined('_JEXEC') or die;
class NothingController extends JControllerLegacy
{
protected $default_view = 'nothings';
public function display($cachable = false, $urlparams = false)
{
require_once JPATH_COMPONENT.'/helpers/nothing.php';
$view = $this->input->get('view', 'nothings');
$layout = $this->input->get('layout', 'default');
$id = $this->input->getInt('id');
if ($view == 'nothing' && $layout == 'edit' && !$this-
>checkEditId('com_nothing.edit.nothing', $id))
{
$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_
ID', $id));
$this->setMessage($this->getError(), 'error');
$this->setRedirect(JRoute::_('index.php?option=com_
nothing&view=nothings', false));
return false;
}
parent::display();
return $this;
}
}