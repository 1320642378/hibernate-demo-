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
<?php foreach($this->results as $i => $result):?>
	<div class="pf-result-detail" data-role="page" id="r_<?php echo $result->pfitem_catid . '_' . $result->pfitem_id ?>">
	
		<div data-role="header">
			<h1><?php echo JText::_('COM_PRODUCTFINDER_LBL_DETAIL');?></h1>
		</div>
	
		<div data-role="content">	
			<h3>
				<a href="<?php echo JRoute::_($result->pfitem_href);?>">
					<?php echo $this->escape($result->pfitem_title);?>
				</a>
			</h3>
			
			<div><?php echo $result->pfitem_html; ?></div>
					
			<div class="clearfix"></div>
			<p><a href="#pf_result_main" data-role="button" data-icon="arrow-l"><?php echo JText::_('COM_PRODUCTFINDER_LBL_BACK');?></a></p>	
		</div>
	
		<div data-role="footer">
			&nbsp;
		</div>
	</div>
<?php endforeach;?>