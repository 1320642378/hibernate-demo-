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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controllerform');

/**
 * Product Finder Component Ajax Controller - Question
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderControllerAjax extends JControllerLegacy
{

	/**
	 * Returns an empty answer row in JSON-encoded HTML format
	 * 
	 * @since 1.0
	 */
	function addField(){

		$result = 
			'<td>&nbsp;</td>' .
			'<td><input type="hidden" name="ans_id[]"/><input type="hidden" name="ans_sep[]" value="0"/></td>' .
			'<td><input type="text" name="ans_label[]"/></td>' .
			'<td><input type="text" name="ans_value[]"/></td>' .
			'<td><input type="hidden" name="ans_def[]" value="0"/></td>' .
			'<td>&nbsp;</td>' .
			'<td><input type="hidden" name="ans_order[]" value="" class="input-mini"/></td>';
		if(PF_JOOMLA3)
		{
			$result .=
			'<td><button class="btn btn-small" onclick="PF.deleteRow(this)">' .
			'<i class="icon-trash"> </i>' .
			'</button></td>';
		}
		else
		{
			$result .=
			'<td><a href="javascript:void(0);" class="jgrid" onclick="PF.deleteRow(this)">' .
				'<span class="state trash"><span class="text">'.JText::_('COM_PRODUCTFINDER_LBL_DELETE_ROW').'</span></span>' .                
			'</a></td>';
		}		
		echo json_encode($result);
		JFactory::getApplication()->close();
	}

	/**
	 * Returns an empty separator row in JSON-encoded HTML format
	 * 
	 * @since 1.0
	 */
	function addSeparator(){

		$result = 
			'<td>&nbsp;</td>' .
			'<td><input type="hidden" name="ans_id[]"/><input type="hidden" name="ans_sep[]" value="1"/><input type="hidden" name="ans_def[]" value="0"/></td>' .
			'<td><input type="text" name="ans_label[]"/></td>' .
			'<td><input type="hidden" name="ans_value[]" value="" /></td>' .
			'<td><input type="hidden" name="ans_def[]" value="0"/></td>' .		
			'<td>&nbsp;</td>' .		
			'<td><input type="hidden" name="ans_order[]" value="" class="input-mini"/></td>';
		if(PF_JOOMLA3)
		{
			$result .=
				'<td><button class="btn btn-small" onclick="PF.deleteRow(this)">' .
				'<i class="icon-trash"> </i>' .
				'</button></td>';
		}
		else 
		{
			$result .=
			'<td><a href="javascript: void(0);" class="jgrid" onclick="PF.deleteRow(this)">' .
				'<span class="state trash"><span class="text">'.JText::_('COM_PRODUCTFINDER_LBL_DELETE_ROW').'</span></span>' .                
			'</a></td>';
		}
		echo json_encode($result);
		JFactory::getApplication()->close();
	}
	
	/**
	 * Deletes an answer/separator row
	 * 
	 * @since 1.0
	 */
	function deleteAnswer(){
		$jinput = JFactory::getApplication()->input;
		$result = 0;
		if($answerId = $jinput->getInt('id'))
		{
			$answerTable	= JTable::getInstance('Answers', 'ProductfinderTable', array());
			if($answerTable->delete(array($answerId))){
				$result = 1;
			}
			else{
				$result = 0;
			}
		}
		echo json_encode($result);
		JFactory::getApplication()->close();		
	}
	
	/**
	 * Toggles one rule
	 * 
	 * @since 1.0
	 */	
	function toggleRule(){
		$jinput = JFactory::getApplication()->input;
		$result = 0;
		//data : '&ref=' + ref + '&cid=' + itemId + '&aid=' + ruleId,
		if( ($ref = $jinput->getString('ref')) && ($contentId = $jinput->getUint('cid')) && ($answerId = $jinput->getUint('aid')))
		{
			if(PF_LEVEL)
			{
				$connector = PFConnectors::getInstance()->getConnector($ref);
			}
			else 
			{
				$connector = PFConnectors::getInstance()->getDefaultConnector();	
			}
			$refTable = $connector->ref_table;
			$rulesTable	= JTable::getInstance('Rules', 'ProductfinderTable', array());
			$newStatus = $rulesTable->toggle($refTable, $contentId, $answerId);
			$result = ProductfinderHelper::ruleImg($newStatus);
		}
		echo json_encode($result);
		JFactory::getApplication()->close();				
	}
	
	function getCategories(){
		
		$result['msg'] = true;
		$result['html'] = '<option>Error</option>';
		$app = JFactory::getApplication();
		$connectorName = $app->input->get('conn', 'articles', 'cmd');
		$connector = PFConnectors::getInstance()->getConnector($connectorName);
		$categories = $connector::getCategories();
		
		if(empty($categories))
		{
			$result['msg'] = false;
		}
		else
		{
			$options = array();
			foreach ($categories as $category)
			{
				$options[] = '<option value="'.$category->value.'">'.$category->text.'</option>';
			}
			$result['html'] = implode("\n", $options);
		}
		echo json_encode($result);
		JFactory::getApplication()->close();
	}

}