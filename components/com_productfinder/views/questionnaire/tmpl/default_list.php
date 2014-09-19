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
defined('_JEXEC') or die();
$choices = $this->choices->getState('choices', array());
if(is_array($choices)){
	$choices = isset($choices[$this->activeQuestion]) ? $choices[$this->activeQuestion] : array();
}
else{
	$choices = array();
}
$fieldType 	= $this->question->max_answers == 1 ? 'radio' : 'checkbox';

$autoNext 		= $this->question->auto_next;
$minAnswers 	= $this->question->min_answers;
$maxAnswers 	= $this->question->max_answers;

$js = '';

if($autoNext && ($minAnswers <= 1) && ($maxAnswers == 1)) {
	$js = ' onclick="document.getElementById(\'pf_next_btn_' . $this->activeQuestion .  '\').click();"';
}
if($autoNext && ($maxAnswers > 1)) 
{
	//multiple choices
	$js = ' onclick="if(PF.checkCount(this, '.$maxAnswers.')){document.getElementById(\'pf_next_btn_' . $this->activeQuestion .  '\').click();}"';
}
$checkedInputs = 0;  
?>
<div class="pf_question"><?php echo $this->question->question_full;?></div>

<?php if(empty($this->question->answers)):?>
<div class="ui-bar ui-bar-e">
	<h3><?php echo JText::_('COM_PRODUCTFINDER_ERR_QUESTION_INCOMPLETE');?></h3>
</div>
<?php else:?>
<div>
	<fieldset>
	<div data-role="controlgroup">
		<?php foreach($this->question->answers as $ansId => $answer):?>
		
			<?php if($answer->is_separator):?>
			</div>
			<h3><?php echo $this->escape($answer->label);?></h3>
			<div data-role="controlgroup">
			<?php continue; endif;?>
			<?php 
				$fieldId = 'pf_f_' . $this->question->id . '_' .  $answer->id;
				$selected = '';
				if(in_array($ansId, $choices)){
					$selected = 'checked="checked"';
					$checkedInputs++;
				}
			?>
			<input id="<?php echo $fieldId;?>" name="q[<?php echo $this->question->id;?>][]" value="<?php echo $answer->id; ?>" type="<?php echo $fieldType; ?>" <?php echo $selected?><?php echo $js;?>/><label for="<?php echo $fieldId;?>"><?php echo $this->escape($answer->label);?></label>
		<?php endforeach;?>
	</div>
	</fieldset>
	<?php if($autoNext && $maxAnswers > 1):?>
		<input type="hidden" id="checkedInputs" name="checkedInputs" value="<?php echo $checkedInputs;?>"/>
	<?php endif;?>	
</div>
<?php endif;?>
