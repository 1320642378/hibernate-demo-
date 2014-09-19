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
// no direct access
defined('_JEXEC') or die;

if(PF_JOOMLA3)
{
	JHtml::_('bootstrap.tooltip');	
}
else 
{
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.multiselect');

$gotQuestion = $this->questionId;
$numAnswers	= !empty($this->answers) ? count($this->answers) : 0;
$numCols 	= $gotQuestion ? (4 + $numAnswers) : 4 ; 
$colWidth	= '80';
if($numAnswers)
{
	$colWidth	= round($colWidth / $numAnswers , 0 , PHP_ROUND_HALF_DOWN);
}

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$pf_connector = $this->state->get('filter.pfconnector');
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

<form action="<?php echo JRoute::_('index.php?option=com_productfinder&view=rules');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft pull-left">
			<?php if(PF_LEVEL > 0):?>
			<select name="filter_pfconnector" class="inputbox" onchange="if(document.adminForm.filter_category_id)document.adminForm.filter_category_id.selectedIndex=0;this.form.submit()">
				<?php echo JHtml::_('select.options', $this->connectors, 'value', 'text', $pf_connector);?>
			</select>				
			<?php endif;?>
			
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', $this->categories, 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
		</div>
				
		<div class="filter-search fltlft pull-left btn-group">	
			<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button type="submit" class="btn"><?php echo PF_JOOMLA3 ? '' : JText::_('JSEARCH_FILTER_SUBMIT'); ?><i class="icon-search"></i></button>
			<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo PF_JOOMLA3 ? '' : JText::_('JSEARCH_FILTER_CLEAR'); ?><i class="icon-remove"></i></button>
		</div>
		
		<div class="filter-search fltrt pull-right">
			<div id="pf_rules_status" style="float:left; display:block;"> </div>
			
			<select name="filter_questionnaire" class="inputbox" onchange="if(document.adminForm.filter_question)document.adminForm.filter_question.selectedIndex=0;this.form.submit()">
				<option value=""><?php echo JText::_('COM_PRODUCTFINDER_LBL_SELECT_QUESTIONNAIRE');?></option>
				<?php echo JHtml::_('select.options', $this->questionnaires, 'value', 'text', $this->state->get('filter.questionnaire'));?>
			</select>	

			<select name="filter_question" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_PRODUCTFINDER_SELECT_QUESTION');?></option>
				<?php echo JHtml::_('select.options', $this->questions, 'value', 'text', $this->state->get('filter.question'));?>
			</select>
		</div>
		
	</fieldset>
	<div class="clr clearfix"> </div>
	
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'pfitem_id', $listDirn, $listOrder); ?>
				</th>	
				<th width="18%">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'pfitem_title', $listDirn, $listOrder); ?>
				</th>
				<?php if($gotQuestion && $numAnswers):?>
					<?php foreach($this->answers as $i => $answer):?>
				<th width="<?php echo $colWidth?>%" class="nowrap center">
					<label for="pf<?php echo $i?>" class="hasTip" title="<?php echo $this->escape($answer->label);?>">
						<input id="pf<?php echo $i?>" name="aid[]" value="<?php echo $answer->id;?>" onclick="Joomla.isChecked(this.checked);" type="checkbox"/>
						<?php echo $this->escape($answer->value);?>
					</label>
				</th>	
					<?php endforeach;?>
				<?php else:?>
				<th width="<?php echo $colWidth?>%">
					<?php echo JText::_('COM_PRODUCTFINDER_LBL_ANSWERS');?>
				</th>
				<?php endif;?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo $numCols;?>">
					<?php  echo $this->pagination->getListFooter(); ?>
					<?php if(PF_JOOMLA3):?>
					<div class="pull-right hidden-phone">
						<label for="limit"><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
						<?php echo $this->pagination->getLimitBox(); ?>
						</label>
					</div>
					<?php endif;?>					
				</td>
			</tr>
		</tfoot>		
	<?php if($numItems = count($this->items)):?>
		<?php 
			$numRows = $numAnswers ? ($numItems / $numAnswers) : $numItems; 
			$k = 0;
		?>
		<tbody>

		<?php for($i = 0; $i < $numRows; $i++ ):?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $this->items[$k]->pfitem_id); ?>
				</td>
				<td><?php echo $this->escape($this->items[$k]->pfitem_id); ?></td>
				<td><?php echo $this->escape($this->items[$k]->pfitem_title); ?></td>
				<?php if($gotQuestion):?>
					<?php for($j = 0; $j < $numAnswers; $j++):
						$rule = $this->items[$k + $j];?>
					<td class="center">
						<a title="Toggle to change article state to 'Featured'" onclick="return PF.toggleRule(this, '<?php echo $pf_connector;?>', '<?php echo $rule->pfitem_id?>', '<?php echo $rule->answer_id?>')" href="javascript:void(0);">
							<?php echo ProductfinderHelper::ruleImg($rule->rule);?>
						</a>					
					</td>
					<?php endfor;
					$k += $j-1;
					?>			
				<?php else: ?>
				<td>
					<?php echo JText::_('COM_PRODUCTFINDER_MSG_PLEASE_SELECT_A_QUESTION');?>
				</td>
				<?php endif;
				$k++;
				?>
			</tr>
		<?php endfor;?>			
		</tbody>
	<?php endif;?>
	</table>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>	
	
<?php if(PF_JOOMLA3):?>
</div>
<?php endif;?>		
</form>