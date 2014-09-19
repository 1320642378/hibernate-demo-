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
defined('_JEXEC') or die();
?>

<?php echo $this->questionnaire->description; ?>

<div class="ui-grid-b">
	<input type="hidden" name="questionnaire_id" value="<?php echo $this->questionnaire->id?>"/>
	<div class="ui-block-a"> </div>
	<div class="ui-block-b"><input name="action" value="<?php echo JText::_('COM_PRODUCTFINDER_BTN_START');?>" type="submit" /></div>
	<div class="ui-block-c"> </div>
</div>	


