<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x and Joomla 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
* @since 1.3
*/
// no direct access
defined('_JEXEC') or die;
$link = 'index.php?option=com_productfinder&controller=utilities';
?>
<?php if(PF_JOOMLA3):?>
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
<?php endif;?>

	<table class="adminform table" style="width:80%;">
	<tr>
		<td style="width:25%">
			<a href="index.php?option=com_productfinder&task=utilities.refreshthumbnails"><?php echo JText::_('COM_PRODUCTFINDER_LBL_REFRESH_THUMBNAILS')?></a>
		</td>
		<td>
			<p><?php echo JText::_('COM_PRODUCTFINDER_DESC_REFRESH_THUMBNAILS')?></p>
		</td>
	</tr>
	<?php if(is_dir(JPATH_ROOT . '/administrator/components/com_falang')) : ?>
	<tr>
		<td style="width: 25%">
			<a href="index.php?option=com_productfinder&task=utilities.instfalangce"><?php echo JText::_('COM_PRODUCTFINDER_LBL_INSTALL_FALANG_CONTENT_ELEMENTS')?></a>
		</td>
		<td>
			<p><?php echo JText::_('COM_PRODUCTFINDER_DESC_INSTALL_FALANG_CONTENT_ELEMENTS')?></p>
		</td>
	</tr>
	<?php endif;?>		
	</table>


<?php if(PF_JOOMLA3):?>
</div>
<?php endif;?>