<?php
defined('_JEXEC') or die;
class com_nothingInstallerScript
{
function install($parent)
{
$parent->getParent()->setRedirectURL('index.php?option=com_nothing');
}
function uninstall($parent)
{
echo '<p>' . JText::_('COM_NOTHING_UNINSTALL_TEXT') . '</p>';
}
function update($parent)
{
echo '<p>' . JText::_('COM_NOTHING_UPDATE_TEXT') . '</p>';
}
function preflight($type, $parent)
{
echo '<p>' . JText::_('COM_NOTHING_PREFLIGHT_' . $type .
'_TEXT') . '</p>';
}
function postflight($type, $parent)
{
echo '<p>' . JText::_('COM_NOTHING_POSTFLIGHT_' . $type .
'_TEXT') . '</p>';
}
}