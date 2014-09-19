<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die;
?>
<ul class="adminformlist">
	<li><?php echo $this->form->getLabel('metadesc'); ?>
	<?php echo $this->form->getInput('metadesc'); ?></li>

	<li><?php echo $this->form->getLabel('metakey'); ?>
	<?php echo $this->form->getInput('metakey'); ?></li>


<?php foreach($this->form->getGroup('metadata') as $field): ?>
	<li>
		<?php if (!$field->hidden): ?>
			<?php echo $field->label; ?>
		<?php endif; ?>
		<?php echo $field->input; ?>
	</li>
<?php endforeach; ?>
</ul>
