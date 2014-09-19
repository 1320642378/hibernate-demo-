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

$qid 			= (int) $this->questionnaire->id;
$questionnaire 	= $this->questionnaire;
$qParams 		= $questionnaire->params;
 
$globalParams	= $this->globalparams; 
$menuParams		= $this->menuparams;

$num_columns	= $menuParams->get('num_columns');
$num_columns 	= (int) ($num_columns != 'global' ? $num_columns : $globalParams->get('num_columns', 1));
$grid 			= $num_columns > 1 ? ('-' . chr(95 + $num_columns)) : '';
	 
$show_titles 	= $menuParams->get('show_titles');
$show_titles 	= ($show_titles != 'global' ? $show_titles : $globalParams->get('show_titles'));
$show_score 	= $menuParams->get('show_score');
$show_score 	= ($show_score != 'global' ? $show_score : $globalParams->get('show_score'));
$show_images 	= $menuParams->get('show_images');
$show_images 	= ($show_images != 'global' ? $show_images : $globalParams->get('show_images'));	


$show_category 	= $menuParams->get('show_category');
$show_category 	= ($show_category != 'global' ? $show_category : $globalParams->get('show_category'));

$show_extra 	= $menuParams->get('show_extra');
$extra_label	= $menuParams->get('extra_label');

if($show_images)
{
	$max_images_width = $this->state->get('max_images_width');
	$max_images_height = $this->state->get('max_images_height');
	
	if($max_images_width > 80 || $max_images_height > 80)
	{
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('
		div#pf_result_main .ui-btn-text{min-height: '.$max_images_height.'px;}
		div#pf_result_main .ui-li-has-thumb .ui-btn-inner a.ui-link-inherit,
		div#pf_result_main .ui-li-static.ui-li-has-thumb{
			padding-left:'.($max_images_width + 10).'px;
		}'
		);	
	}
}

$link_to_details 	= $menuParams->get('link_to_details');
$link_to_details 	= ($link_to_details != 'global' ? $link_to_details : $globalParams->get('link_to_details'));

$gotResults = empty($this->results)? false : true;

$no_results_action 	= $menuParams->get('no_results_action');
$no_results_action 	= ($no_results_action != 'global' ? $no_results_action : $globalParams->get('no_results_action'));

$res_not_found_msg 	= $menuParams->get('res_not_found_msg');
$res_not_found_msg 	= (!empty($res_not_found_msg) ? $res_not_found_msg : $globalParams->get('res_not_found_msg'));

if(!$gotResults && ($no_results_action == '2' || $no_results_action == '3') ){
	$res_not_found_ids 	= $menuParams->get('res_not_found_ids');
	$res_not_found_ids 	= (!empty($res_not_found_ids) ? $res_not_found_ids : $globalParams->get('res_not_found_ids'));
	
	if(!empty($res_not_found_ids)){
		$ids = preg_replace('/[^0-9,]/', '', $res_not_found_ids);
		$ids = explode(',', $ids);
		$results = $this->resModel->getItemsById($ids);
	}
	if(isset($results) && !empty($results)){
		$this->results = $results;
	}
}
$buttons = $questionnaire->params->get('buttons', 0);
?>

<div class="pf_questionnaire" data-role="page" id="pf_result_main">

	<form method="post" action="<?php echo JRoute::_('index.php?option=com_productfinder&view=results');?>" data-transition="slide" name="adminForm" id="pf_qresults_<?php echo $qid;?>" data-ajax="false">
	
		<div data-role="header">
			<h1><?php echo JText::_('COM_PRODUCTFINDER_LBL_RESULTS'); ?></h1>
		</div>
		 
		<div data-role="content">
		
			<?php if(!$gotResults && ($no_results_action == 1 || $no_results_action == 3)):?>
			<div class="ui-grid">
				<div class="ui-block">
					<?php echo $res_not_found_msg;?>
				</div>
			</div>
			<?php endif?>
	
			<?php if($gotResults || (!empty($this->results) && ($no_results_action == 2 || $no_results_action == 3)) ):?>
			
			<ul data-role="listview" data-theme="a" class="ui-grid<?php echo $grid?>">
			
				<?php foreach($this->results as $i => $result):?>
				<li class="pf-result ui-block<?php echo $num_columns > 1 ? ('-' .chr(97+ ($i % $num_columns))) : '' ;?>">
				
					<?php if($link_to_details):?>
					<a href="#r_<?php echo $result->pfitem_catid . '_' . $result->pfitem_id ;?>" >
					<?php endif;?>
					
					<?php if($show_images && $result->pfitem_image):?>
							<?php echo $result->pfitem_image;?>
					<?php endif;?>
					
					<?php if($show_titles):?>
						<h3 class="pf-res-title">
							<?php echo $this->escape($result->pfitem_title);?>
						</h3>
					<?php endif;?>
					
					<?php if($show_score):?>
						<p class="pf-res-score">
							<?php echo JText::_('COM_PRODUCTFINDER_LBL_SCORE').': ' . $result->score;?>
						</p>
					<?php endif;?>
					
					<?php if($show_category):?>
						<p class="pf-res-category">
							<?php echo $this->escape($result->pfitem_category_title);?>
						</p>
					<?php endif;?>		
					<?php if($show_extra):?>
						<p class="pf-res-extra">
						<?php if($extra_label):?>
							<span class="pf-res-extra-label"><?php echo $this->escape($extra_label);?>: </span>
						<?php endif;?>
							<?php echo $this->escape($result->$show_extra);?>
						</p>
					<?php endif;?>					

					<?php if($link_to_details):?>
					</a>
					<?php endif;?>
					
				</li>					
				<?php endforeach;?>

			</ul>
			<?php endif;?>
				
		</div>
			
		<div data-role="footer" class="ui-bar">

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

			<?php if($qParams->get('enable_mailing')):?>
				<a href="<?php echo JRoute::_('index.php?option=com_productfinder&view=mailer')?>" 
					data-icon="mail" data-iconpos="right" data-role="button">
					<?php echo JText::_('COM_PRODUCTFINDER_BTN_EMAIL')?>
				</a>
			<?php endif;?>
			
		</div> 
		
		<div>
			<?php echo JHtml::_('form.token'); ?>	
		</div>		
	</form>
</div>

<?php if($link_to_details && ($gotResults || (!empty($this->results) && ($no_results_action == 2 || $no_results_action == 3))) ):?>
	<?php echo $this->loadTemplate('results');?>
<?php endif;?>

