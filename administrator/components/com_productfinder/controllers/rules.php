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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');

/**
 * Product Finder Component Controller - Rules
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderControllerRules extends JControllerAdmin
{

	function __construct()
	{
		parent::__construct();
		$this->registerTask('unset', 'toggle');
		$this->registerTask('disallowed', 'setRules');
		$this->registerTask('recommended', 'setRules');
		$this->registerTask('required', 'setRules');
	}

	/**
	 * Toggles one or more rules
	 * 
	 * @return boolean
	 * @since 1.0
	 */
	public function toggle(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		
		$connectorName = $jinput->get('filter_pfconnector', 'articles', 'cmd');
		if(PF_LEVEL)
		{
			$connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		$ref_table = $connector->ref_table;		
		
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
		
		$ids	= $jinput->get('cid', null, 'array');
		$aids	= $jinput->get('aid', null, 'array');
		if(empty($ids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ITEMS'), 'error');
			return false;			
		}
		if(empty($aids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ANSWERS'), 'error');
			return false;			
		}
		
		$rulesTable	= JTable::getInstance('Rules', 'ProductfinderTable', array());
		if($this->task == 'toggle' && $toggledRules = $rulesTable->toggleMany($ref_table, $ids, $aids))
		{ 
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_UPDATED', $toggledRules));
			return true;		
		}
		elseif($this->task == 'unset' && ( ($toggledRules = $rulesTable->unsetMany($ref_table, $ids, $aids)) != -1))
		{ 
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_UPDATED', $toggledRules));
			return true;		
		}
		else
		{
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_UNABLE_TO_UPDATE_RULES'), 'error');
			return false;			
		}

	}
	
	/**
	 * Sets many orles at once
	 * @return boolean
	 * @since 1.0
	 */
	public function setRules(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		
		$connectorName = $jinput->get('filter_pfconnector', 'articles', 'cmd');
		if(PF_LEVEL)
		{
			$connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		$ref_table = $connector->ref_table;		
	
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
	
		$ids	= $jinput->get('cid', null, 'array');
		$aids	= $jinput->get('aid', null, 'array');
		if(empty($ids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ITEMS'), 'error');
			return false;
		}
		if(empty($aids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ANSWERS'), 'error');
			return false;
		}
	
		$rulesTable	= JTable::getInstance('Rules', 'ProductfinderTable', array());
		if($this->task == 'disallowed' && $setRules = $rulesTable->setMany($ref_table, $ids, $aids, 'NOT'))
		{
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_UPDATED', $setRules));
			return true;
		}
		elseif($this->task == 'required' && $setRules = $rulesTable->setMany($ref_table, $ids, $aids, 'REQ'))
		{
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_UPDATED', $setRules));
			return true;
		}
		elseif($this->task == 'recommended' && $setRules = $rulesTable->setMany($ref_table, $ids, $aids, '1'))
		{
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_UPDATED', $setRules));
			return true;
		}
		else
		{
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_UNABLE_TO_UPDATE_RULES'), 'error');
			return false;
		}
	
	}	
	
	/**
	 * Deletes all rules
	 * 
	 * @return boolean
	 * @since 1.0
	 */
	public function clearAllRules(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
		
		$ids = $jinput->get('cid', null, 'array');
		if(empty($ids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ITEMS'), 'error');
			return false;			
		}

		$rulesTable	= JTable::getInstance('Rules', 'ProductfinderTable', array());
		
		if(($clearedRules = $rulesTable->clearAll('content', $ids)) != -1){
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_CLEARED', $clearedRules));
			return true;		
		}
		else{
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_UNABLE_TO_UPDATE_RULES'), 'error');
			return false;			
		}
		
	}
	
	/**
	 * Delete all rules from selected questionnaire
	 * 
	 * @return boolean
	 * @since 1.0
	 */
	public function clearQuestionnaire(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
				
		$jinput = JFactory::getApplication()->input;
		$connectorName = $jinput->get('filter_pfconnector', 'articles', 'cmd');
		if(PF_LEVEL)
		{
			$connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		$ref_table = $connector->ref_table;		
		
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
		
		$ids	= $jinput->get('cid', null, 'array');
		$qid	= $jinput->getInt('filter_questionnaire');
		if(empty($ids)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_SELECT_ONE_OR_MORE_ITEMS'), 'error');
			return false;			
		}
		if(empty($qid)){
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_MISSING_QUESTIONNAIRE_ID'), 'error');
			return false;			
		}
		
		$rulesTable	= JTable::getInstance('Rules', 'ProductfinderTable', array());
		if(($clearedRules = $rulesTable->clearQuestionnaire($ref_table, $ids, $qid))!== false){
			$this->setMessage(JText::sprintf('COM_PRODUCTFINDER_MSG_RULES_CLEARED', $clearedRules));
			return true;		
		}
		else{
			$this->setMessage(JText::_('COM_PRODUCTFINDER_ERR_UNABLE_TO_UPDATE_RULES'), 'error');
			return false;			
		}
		
	}
	
	public function getModel($name = 'Rules', $prefix = 'ProductfinderModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}	

}