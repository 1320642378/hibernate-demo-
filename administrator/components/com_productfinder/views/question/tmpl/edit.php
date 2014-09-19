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
	// Load Mootools
	JHTML::_('behavior.mootools');
	JHTML::_('behavior.framework', true);
	// Load the tooltip behavior.
	JHtml::_('behavior.tooltip');
}

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

// Create shortcut to parameters.
$params = $this->state->get('params');

$gotAnswers = empty($this->answers) ? false : true;
$numAnswers = 0;
if($gotAnswers){
	$numAnswers = count($this->answers);
}

$onsubmit = ($this->item->id != 0 ? "onsubmit=\"if(this.task.value!='cancel'){return PF.serializeAnswers()};\"" : "");
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'question.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('question_full')->save();?>
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_productfinder&view=question&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" <?php echo $onsubmit ?>>

	<div class="row-fluid">
	
		<div class="span12">
		
			<div class="row-fluid">
			
				<div class="width-60 fltlft span6">
				
					<fieldset class="adminform form-horizontal">
						<legend><?php echo empty($this->item->id) ? JText::_('COM_PRODUCTFINDER_LBL_NEW_QUESTION') : JText::sprintf('COM_PRODUCTFINDER_LBL_EDIT_QUESTION', $this->item->id); ?></legend>
						<div class="clr"></div>
						<?php echo $this->form->getLabel('question'); ?>
						<div class="clr"></div>
						<?php echo $this->form->getInput('question'); ?>	
						<div class="clr"></div>
						<?php echo $this->form->getLabel('question_full'); ?>
						<div class="clr"></div>
						<?php echo $this->form->getInput('question_full'); ?>	
					</fieldset>
					
				</div>
				
				<div class="width-40 fltrt span6">
				<?php if(PF_JOOMLA3):?>
					<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'question-details')); ?>
				<?php else:?>				
					<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
				<?php endif;?>
			
					<?php if(PF_JOOMLA3):?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'question-details', JText::_('COM_PRODUCTFINDER_LBL_QUESTION_OPTIONS', true)); ?>
					<?php else:?>
					<?php echo JHtml::_('sliders.panel', JText::_('COM_PRODUCTFINDER_LBL_QUESTION_OPTIONS'), 'question-details'); ?>
					<?php endif;?>	
					<fieldset class="panelform form-horizontal">
						<ul class="adminformlist">
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('questionnaire_id'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('questionnaire_id'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('question_type'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('question_type'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('answers_ordering'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('answers_ordering'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('min_answers'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('min_answers'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('max_answers'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('max_answers'); ?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="control-group">
									<div class="control-label">								
									<?php echo $this->form->getLabel('auto_next'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('auto_next'); ?>
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
									<?php echo $this->form->getLabel('id'); ?>
									</div>
									<div class="controls">
									<?php echo $this->form->getInput('id'); ?>
									</div>
								</div>
							</li>
						</ul>		
					</fieldset>
					<?php if(PF_JOOMLA3):?>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php endif;?>					
					
					<?php if(PF_JOOMLA3):?>
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing-details', JText::_('COM_PRODUCTFINDER_FIELDSET_PUBLISHING', true)); ?>							
					<?php else:?>					
						<?php echo JHtml::_('sliders.panel', JText::_('COM_PRODUCTFINDER_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
					<?php endif;?>
					<fieldset class="panelform">
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
						</ul>
					</fieldset>
					<?php if(PF_JOOMLA3):?>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
						<?php echo JHtml::_('bootstrap.endTabSet'); ?>
					<?php else:?>	
						<?php echo JHtml::_('sliders.end'); ?>
					<?php endif;?>
				</div>
			
				<div class="clr clearfix"></div>
				
				<div class="row-fluid">
				
					<div class="width-100 fltlft span12">	
						<fieldset class="adminform form-horizontal">
							<legend><?php echo JText::_('COM_PRODUCTFINDER_LBL_ANSWERS'); ?></legend>
							<?php if(!($this->item->id)):?>
								<div class="notice alert alert-info"><?php echo JText::_('COM_PRODUCTFINDER_MSG_SAVE_QUESTION_TO_ADD_ANSWERS');?></div>
							<?php else:?>
							
							<?php if(PF_JOOMLA3):?>
							<div class="pf-buttons">
								<button class="btn btn-small" 
									onclick="return PF.addAnswer();">
									<i class="icon-plus"> </i>
									<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_ANSWER' )?>
								</button> 
						
								<button class="btn btn-small" 
									onclick="return PF.addSeparator();">
									<i class="icon-plus"> </i>
									<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_SEPARATOR' )?>
								</button>
								
								<button class="btn btn-small"
									onclick="if (document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')?>');}else{ Joomla.submitbutton('answers.delete')}" href="javascript:void(0);"> 
									<i class="icon-trash"> </i>
									<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_DELETE_ANSWERS' )?>
								</button>

								<?php if(false): //NOTE test code?>
								<button class="btn btn-small"
									onclick="return PF.serializeAnswers();"> 
									<i class="icon-trash"> </i>
									Serialize Answers
								</button>
								<?php endif;?>
								<span id="pf_answers_status"></span>
							</div>
							<?php else:?>
							<div class="pf-buttons btn">
								<div class="button2-left">
									<div class="blank">
										<a title="<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_ANSWER' )?>" onclick="return PF.addAnswer();" href="javascript:void(0);"><?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_ANSWER' )?></a>
									</div>
								</div>				
								<div class="button2-left">
									<div class="blank">
										<a title="<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_SEPARATOR' )?>" onclick="return PF.addSeparator();" href="javascript:void(0);"><?php echo JText::_( 'COM_PRODUCTFINDER_LBL_ADD_SEPARATOR' )?></a>
									</div>
								</div>				
								<div class="button2-left">
									<div class="blank">
										<a title="<?php echo JText::_( 'COM_PRODUCTFINDER_LBL_DELETE_ANSWERS' )?>" onclick="if (document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')?>');}else{ Joomla.submitbutton('answers.delete')}" href="javascript:void(0);"><?php echo JText::_( 'COM_PRODUCTFINDER_LBL_DELETE_ANSWERS' )?></a>
									</div>
								</div>				
								<div id="pf_answers_status"></div>
							</div>
							<?php endif;?>
							
							<table class="adminlist table table-striped" id="pf_answers">
							<tbody>
								<tr>
									<th width="1%"><input id="toggler" name="toggle" value="" onclick="PF.checkAllValues();" type="checkbox"></th>
									<th width="1%"><?php echo JText::_('JGLOBAL_FIELD_ID_LABEL');?></th>
									<th width="30%"><?php echo JText::_('COM_PRODUCTFINDER_LBL_LABEL');?></th>
									<th width="30%"><?php echo JText::_('COM_PRODUCTFINDER_LBL_VALUE');?></th>
									<th width="1%"><?php echo JText::_('COM_PRODUCTFINDER_LBL_DEFAULT');?></th>
									<th width="5%" class="center" colspan="2">
										<?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
										<?php if($gotAnswers):?>
										<a href="javascript:PF.saveAnswerOrder('<?php echo $numAnswers-1?>')" class="saveorder" title="<?php echo JText::_('JLIB_HTML_SAVE_ORDER')?>"><i class="icon-cogs"></i></a>
										<?php endif;?>
									</th>					
									<th width="1%">&nbsp;</th>
								</tr>
								<?php if($gotAnswers):?>
								<?php foreach($this->answers as $i => $answer):?>
								<tr>
									<td><input type="checkbox" id="ans_sel<?php echo $i?>" name="ans_sel[]" onclick="Joomla.isChecked(this.checked);" value="<?php echo $answer->id;?>"/></td> 
									<td><input type="hidden" name="ans_id[]" value="<?php echo $answer->id;?>"/><?php echo $this->escape($answer->id);?></td> 
									<td><input type="text" name="ans_label[]" value="<?php echo $this->escape($answer->label);?>" size="100"/></td>
									<td>
										<?php if($answer->is_separator):?>
										<input type="hidden" name="ans_value[]" value=""/>&nbsp;
										<?php else:?>
										<input type="text" name="ans_value[]" value="<?php echo $this->escape($answer->value);?>"/>
										<?php endif;?>
									</td> 
									<td>
										<?php if($answer->is_separator):?>
										<input type="hidden" name="ans_def[]" value=""/>&nbsp;
										<?php else:?>
										<input type="checkbox" name="ans_def[]" value="1" <?php echo ($answer->is_default ?'checked="checked"' : '');?>/>
										<?php endif;?>
									</td> 
									<td class="order nowrap">
										<span>
										<?php if ($i > 0):?>
												<?php echo JHtml::_('jgrid.orderUp', $i, 'answers.orderup', '', 'JLIB_HTML_MOVE_UP', true, 'ans_sel');?>
										<?php else:?>
												&#160;
										<?php endif;?>
										</span>			
										<span>	
										<?php if ($i < ($numAnswers - 1) ):?>
												<?php echo JHtml::_('jgrid.orderDown', $i, 'answers.orderdown', '', 'JLIB_HTML_MOVE_DOWN', true, 'ans_sel');?>
										<?php else:?>
												&#160;
										<?php endif;?>
										</span>				
									</td>					
									<td>
										<input type="text" name="ans_order[]" size="5" value="<?php echo $answer->ordering;?>" class="text-area-order input-mini" />
									</td>
									<td>
										<input type="hidden" name="ans_sep[]" value="<?php echo $answer->is_separator;?>"/>
										<?php if(PF_JOOMLA3):?>
										<button class="btn btn-small"
											onclick="PF.deleteAnswer(this, <?php echo $answer->id?>)"> 
											<i class="icon-trash"> </i>
										</button>
										<?php else:?>
										<a href="javascript:void(0);" class="jgrid" onclick="PF.deleteAnswer(this, <?php echo $answer->id?>)">
											<span class="state trash"><span class="text"><?php echo JText::_('COM_PRODUCTFINDER_LBL_DELETE_ROW');?></span></span>                 
										</a>
										<?php endif;?>
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
							</tbody>
							</table>
							
							<div id="ajax_broker_notify_result"></div>
							
							<?php endif;?>
						</fieldset>		
					</div>
					
					<div class="clr clearfix"></div>
				
				</div>
				
				<div>
					<?php echo $this->form->getInput('answers');?>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />		
					<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</div>
		</div>
	</div>
</form>
