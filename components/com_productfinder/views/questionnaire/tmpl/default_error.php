<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.3 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<h3><?php echo JText::_('COM_PRODUCTFINDER_MSG_ERROR');?></h3>
<?php foreach($this->errors as $error):?>
<p><?php echo $this->escape($error);?></p>
<?php endforeach;?>

<fieldset>
	<input type="hidden" name="action" value="redo"/>
	<input data-theme="c" data-rel="back" data-direction="reverse" data-icon="arrow-l" data-iconpos="left" value="<?php echo JText::_('COM_PRODUCTFINDER_LBL_BACK'); ?>" type="submit" />
</fieldset>
	
