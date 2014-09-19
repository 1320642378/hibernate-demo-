<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

//shortcuts
$qid 			= (int) $this->questionnaire->id;
$questionnaire 	= $this->questionnaire;
$choices 		= $this->choices;
$this->errors	= $choices->getErrors();
$activeQuestion = $this->activeQuestion;
$gotErrors 		= count($this->errors) ? true : false;
$buttons		= $questionnaire->params->get('buttons', 0);
$isCover		= empty($activeQuestion);

$bgImage = '';
if($isCover && !empty($questionnaire->image) )
{
	//gather img height
	if(is_file(JPATH_ROOT . '/' . $questionnaire->image) && ($info = getimagesize(JPATH_ROOT . '/' . $questionnaire->image))){
		$width = $info[0];
		$height = $info[1];
		$bgImage = 'style="background-image: url(\''.$questionnaire->image.'\'); height:'.$height.'px "';
	}
}
?>
<div id="pf_questionnaire" data-role="page">

	<form action="<?php echo JRoute::_('index.php?option=com_productfinder');?>" data-transition="slide" method="post" name="adminForm" id="pf_qestionnaire_<?php echo $qid;?>" data-ajax="false">
	
		<div data-role="header">
			<h1><?php echo $this->escape($questionnaire->title) ?></h1>
		</div>
		 
		<?php if($gotErrors):?>
		
		<div class="ui-content ui-body-c" data-theme="c" data-role="content">		
			<?php echo $this->loadTemplate('error');?>
		</div>
		
		<?php elseif($isCover):?>
		
		<div class="ui-content ui-body-a pf_cover" data-theme="a" data-role="content" <?php echo $bgImage?>>
			<?php echo $this->loadTemplate('cover');?>
		</div>
				
		<?php elseif($this->question):?>
		
		<div class="ui-content ui-body-a" data-theme="a" data-role="content" <?php echo $bgImage?>>
			<?php echo $this->loadTemplate($this->question->question_type);?>
		</div>
		
		<?php else:?>
		
		<div class="ui-content ui-body-c" data-theme="c" data-role="content">
			<h3 class="error"><?php echo JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNARE_ERROR')?></h3>
		</div>
		
		<?php endif;?>
			
		<div data-role="footer" class="ui-bar">
		
			<?php if(!$gotErrors && !$isCover && $this->question->q_number > 1 ): ?>
			<input data-icon="arrow-l" data-iconpos="right" name="action"
				value="<?php echo JText::_('JPREVIOUS'); ?>" type="submit" />
			<?php endif;?>

			<?php if($buttons === '1'):?>
		 
			<input
				data-icon="refresh" data-iconpos="right" name="action"
				value="<?php echo JText::_('COM_PRODUCTFINDER_BTN_RESTART'); ?>" type="submit" /> 			
				
			<?php elseif($buttons === '2'):?>
			
			<input
				data-icon="home" data-iconpos="right" name="action"
				value="<?php echo JText::_('COM_PRODUCTFINDER_BTN_HOME'); ?>" type="button"
				onclick="javascript:window.location.href='<?php echo JRoute::_('index.php?action=' . JText::_('COM_PRODUCTFINDER_BTN_HOME'));?>'" />  	
			
			<?php else:?>
			
			<input
				data-icon="home" data-iconpos="right" name="action"
				value="<?php echo JText::_('COM_PRODUCTFINDER_BTN_HOME'); ?>" type="button" 
				onclick="javascript:window.location.href='<?php echo JRoute::_(JURI::base());?>'" /> 					
		 
			<input
				data-icon="refresh" data-iconpos="right" name="action"
				value="<?php echo JText::_('COM_PRODUCTFINDER_BTN_RESTART'); ?>" type="submit" /> 				
			
			<?php endif;?>
			
			<?php if(!$gotErrors && !$isCover):?>	
			<input
				data-icon="arrow-r" data-iconpos="right" name="action" id="pf_next_btn_<?php echo $activeQuestion?>"
				value="<?php echo JText::_('JNEXT'); ?>" type="submit" />
			<?php endif;?>
			
		</div> 
		
		<div>
			<?php echo JHtml::_('form.token'); ?>	
		</div>
	</form>
</div>

