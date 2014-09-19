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

// No direct access.
defined('_JEXEC') or die;

if(PF_JOOMLA3)
{
	JHtml::_('bootstrap.tooltip');	
}
else 
{
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'questionnaire.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php echo $this->form->getField('description')->save(); ?> 
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_productfinder&view=questionnaire&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="row-fluid">
	
		<div class="span12">
			
			<div class="row-fluid">
						
				<div class="width-60 fltlft span6">
				
					<fieldset class="adminform form-horizontal">
					
						<legend><?php echo empty($this->item->id) ? JText::_('COM_PRODUCTFINDER_LBL_NEW_QUESTIONNAIRE') : JText::sprintf('COM_PRODUCTFINDER_LBL_EDIT_QUESTIONNAIRE', $this->item->id); ?></legend>
						<ul class="adminformlist">
							<li>
								<div class="control-group">
									<div class="control-label">							
									<?php echo $this->form->getLabel('title'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('title'); ?>
									</div>
								</div>
							</li>
			
							<li>
								<div class="control-group">
									<div class="control-label">
									<?php echo $this->form->getLabel('alias'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('alias'); ?>
									</div>
								</div>
							</li>
			
							<li>
								<div class="control-group">
									<div class="control-label">
									<?php echo $this->form->getLabel('state'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('state'); ?>
									</div>
								</div>
							</li>
			
							<li>
								<div class="control-group">
									<div class="control-label">							
									<?php echo $this->form->getLabel('access'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('access'); ?>
									</div>
								</div>
							</li>
			
							<li>
								<div class="control-group">
									<div class="control-label">	
									<?php echo $this->form->getLabel('language'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('language'); ?>
									</div>
								</div>
							</li>
			
							<li>
								<div class="control-group">
									<div class="control-label">
									<?php echo $this->form->getLabel('id'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('id'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">
									<?php echo $this->form->getLabel('image'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('image'); ?>
									</div>
								</div>
							</li>
											
						</ul>
						
						<div class="clr clearfix"></div>
						<?php echo $this->form->getLabel('description'); ?>
						<div class="clr clearfix"></div>
						<?php echo $this->form->getInput('description'); ?>
			
					</fieldset>
				</div>
			
				<div class="width-40 fltrt span6">
				<?php if(PF_JOOMLA3):?>
					<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'publishing-details')); ?>
				<?php else:?>
					<?php echo JHtml::_('sliders.start', 'questionnaire-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
				<?php endif;?>
					
					<?php if(PF_JOOMLA3):?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing-details', JText::_('COM_PRODUCTFINDER_FIELDSET_PUBLISHING', true)); ?>
					<?php else:?>
						<?php echo JHtml::_('sliders.panel', JText::_('COM_PRODUCTFINDER_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
					<?php endif;?>
						<fieldset class="panelform form-horizontal">
							<ul class="adminformlist">
								<li>
									<div class="control-group">
										<div class="control-label">								
										<?php echo $this->form->getLabel('created_by'); ?>
										</div>
										<div class="controls">
										<?php echo $this->form->getInput('created_by'); ?>
										</div>
									</div>
								</li>
			
								<li>
									<div class="control-group">
										<div class="control-label">									
										<?php echo $this->form->getLabel('created'); ?>
										</div>
										<div class="controls">
										<?php echo $this->form->getInput('created'); ?>
										</div>
									</div>
								</li>
			
								<li>
									<div class="control-group">
										<div class="control-label">
										<?php echo $this->form->getLabel('publish_up'); ?>
										</div>
										<div class="controls">
										<?php echo $this->form->getInput('publish_up'); ?>
										</div>
									</div>
								</li>
			
								<li>
									<div class="control-group">
										<div class="control-label">								
										<?php echo $this->form->getLabel('publish_down'); ?>
										</div>
										<div class="controls">
										<?php echo $this->form->getInput('publish_down'); ?>
										</div>
									</div>
								</li>
			
								<?php if ($this->item->modified_by) : ?>
									<li>
										<div class="control-group">
											<div class="control-label">								
											<?php echo $this->form->getLabel('modified_by'); ?>
											</div>
											<div class="controls">
											<?php echo $this->form->getInput('modified_by'); ?>
											</div>
										</div>
									</li>
			
									<li>
										<div class="control-group">
											<div class="control-label">								
											<?php echo $this->form->getLabel('modified'); ?>
											</div>
											<div class="controls">
											<?php echo $this->form->getInput('modified'); ?>
											</div>
										</div>
									</li>
								<?php endif; ?>
			
								<?php if ($this->item->hits) : ?>
									<li>
										<div class="control-group">
											<div class="control-label">								
											<?php echo $this->form->getLabel('hits'); ?>
											</div>
											<div class="controls">
											<?php echo $this->form->getInput('hits'); ?>
											</div>
										</li>
								<?php endif; ?>
							</ul>
						</fieldset>
					<?php if(PF_JOOMLA3):?>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php endif;?>
					
					<?php  $fieldSets = $this->form->getFieldsets('params'); ?>
						<?php foreach ($fieldSets as $name => $fieldSet) : ?>
							<?php if(PF_JOOMLA3):?>
								<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'attrib-' . $name, JText::_($fieldSet->label, true)); ?>							
							<?php else:?>
								<?php echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name.'-options'); ?>
							<?php endif;?>
							
							<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
								<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
							<?php endif; ?>
							<fieldset class="panelform form-horizontal">
								<ul class="adminformlist">
								<?php foreach ($this->form->getFieldset($name) as $field) : ?>
									<li>
										<div class="control-group">
											<div class="control-label">
											<?php echo $field->label; ?>
											</div>
											<div class="controls">
											<?php echo $field->input; ?>
											</div>
										</div>
									</li>
								<?php endforeach; ?>
								</ul>
							</fieldset>
			
							<?php if(PF_JOOMLA3):?>
								<?php echo JHtml::_('bootstrap.endTab'); ?>
							<?php endif;?>	
									
						<?php endforeach; ?>
						
						<?php if(PF_JOOMLA3):?>
							<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'meta-options', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS', true)); ?>
						<?php else:?>
							<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'), 'meta-options'); ?>
						<?php endif;?>
						<fieldset class="panelform">
							<?php echo $this->loadTemplate('metadata'); ?>
						</fieldset>
						<?php if(PF_JOOMLA3):?>
							<?php echo JHtml::_('bootstrap.endTab'); ?>
						<?php endif;?>
					
					<?php if(PF_JOOMLA3):?>
						<?php echo JHtml::_('bootstrap.endTabSet'); ?>
					<?php else:?>
						<?php echo JHtml::_('sliders.end'); ?>
					<?php endif;?>
				</div>
			
				<div class="clr"></div>

			</div>
			
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
			
		</div>
	</div>
</form>
