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

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';
if (PF_JOOMLA3 && $saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_productfinder&task=questions.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'fieldList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_productfinder&view=questions');?>" method="post" name="adminForm" id="adminForm">

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

	<fieldset id="filter-bar" class="btn-bar btn-toolbar">
	
		<div class="filter-search fltlft btn-group pull-left">
			<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button type="submit" class="btn"><?php echo PF_JOOMLA3 ? '' : JText::_('JSEARCH_FILTER_SUBMIT'); ?><i class="icon-search"></i></button>
			<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo PF_JOOMLA3 ? '' : JText::_('JSEARCH_FILTER_CLEAR'); ?><i class="icon-remove"></i></button>
		</div>
		
		<?php if(!PF_JOOMLA3):?>
		<div class="filter-select fltrt">
		
			<select name="filter_questionnaire" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_PRODUCTFINDER_LBL_SELECT_QUESTIONNAIRE');?></option>
				<?php echo JHtml::_('select.options', $this->questionnaires, 'value', 'text', $this->state->get('filter.questionnaire'));?>
			</select>		

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_AUTHOR');?></option>
				<?php echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));?>
			</select>
		</div>
		<?php endif;?>
		
	</fieldset>
	<div class="clr clearfix"> </div>

	<table  id="fieldList" class="adminlist table table-striped">
		<thead>
			<tr>
				<?php if(PF_JOOMLA3):?>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<?php endif;?>				
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_PRODUCTFINDER_LBL_QUESTION', 'a.question', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'COM_PRODUCTFINDER_LBL_QUESTIONNAIRE', 'questionnaire_title', $listDirn, $listOrder); ?>
				</th>				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_PRODUCTFINDER_LBL_TYPE', 'question_type', $listDirn, $listOrder); ?>
				</th>				
				<th width="5%">
					<?php echo JText::_('JOPTION_REQUIRED'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<?php if(!PF_JOOMLA3):?>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'questions.saveorder'); ?>
					<?php endif; ?>
				</th>
				<?php endif;?>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody class="ui-sortable">
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			?>
			<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
				<?php if(PF_JOOMLA3):?>
				<td class="order nowrap center hidden-phone">
					<span class="sortable-handler hasTooltip">
						<i class="icon-menu"></i>
					</span>
					<input type="text" style="display:none" name="order[]" size="5"
						value="<?php echo $item->ordering;?>" class="width-20 text-area-order" />
				</td>
				<?php endif;?>				
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_productfinder&task=question.edit&id='.$item->id);?>">
						<?php echo $this->escape(strip_tags($item->question)); ?>
					</a>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_productfinder&task=questionnaire.edit&id='.$item->questionnaire_id);?>">
						<?php echo $this->escape($item->questionnaire_title); ?>
					</a>
				</td>
				<td class="center">
					<?php echo $this->escape($item->question_type); ?>
				</td>				
				<td class="center">
					<?php echo $item->min_answers > 0 ? JText::_('JYES') : JText::_('JNO'); ?>
				</td>				
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'questions.', true, 'cb', null, null); ?>
				</td>
				<?php if(!PF_JOOMLA3):?>
				<td class="order">
					<?php if ($saveOrder) :?>
						<?php if ($listDirn == 'asc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, ($item->questionnaire_id == @$this->items[$i-1]->questionnaire_id), 'questions.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->questionnaire_id == @$this->items[$i+1]->questionnaire_id), 'questions.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php elseif ($listDirn == 'desc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, ($item->questionnaire_id == @$this->items[$i-1]->questionnaire_id), 'questions.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->questionnaire_id == @$this->items[$i+1]->questionnaire_id), 'questions.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php endif; ?>
					<?php endif; ?>
					<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
				</td>
				<?php endif;?>
				<td class="center">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<td class="center nowrap">
					<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
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