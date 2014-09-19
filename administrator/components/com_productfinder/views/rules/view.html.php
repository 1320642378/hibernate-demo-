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
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 * Product Finder View - Rules Grid
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderViewRules extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	function display($tpl = null) {

		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/productfinder.js');
		
		$this->items		= $this->get('Items'); // [content items X answers] matrix
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->questionnaires	= ProductfinderHelper::getQuestionnairesList();			
		$questionnaireId 	= $this->state->get('filter.questionnaire'); 	
		$this->questionnaireId	= $questionnaireId;
		$this->questions	= ProductfinderHelper::getQuestionnaireQuestionsList($questionnaireId);			
		$questionId 		= $this->state->get('filter.question');	
		$this->questionId	= $questionId;
		$this->answers		= ProductfinderHelper::getAnswers($questionId, false);
		if(PF_LEVEL)
		{
			$this->connectors = ProductfinderHelper::getConnectorsList();
			$connectorName = $this->state->get('filter.pfconnector');
			$this->connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else 
		{
			$this->connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		$connector = $this->connector;
		$this->categories = $connector::getCategories();

		$this->addToolBar();
		if(PF_JOOMLA3)
		{
			$this->sidebar = JHtmlSidebar::render();
		}		
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{

		JToolBarHelper::title(JText::_('COM_PRODUCTFINDER_LBL_RULES'), 'rules.png');
		$bar = JToolBar::getInstance('toolbar');
		
		if(empty($this->questionnaireId))
		{
			$bar->appendButton('Confirm', JText::_('COM_PRODUCTFINDER_MSG_CONFIRM_DELETE_ALL_RULES'), 'unsetall', 'COM_PRODUCTFINDER_BTN_CLEAR_ALL_RULES', 'rules.clearAllRules', true);
			JToolBarHelper::divider();
		}
		elseif(empty($this->questionId))
		{
			$bar->appendButton('Confirm', JText::_('COM_PRODUCTFINDER_MSG_CONFIRM_DELETE_RULES_IN_QUESTIONNAIRE'), 'unsetthis', 'COM_PRODUCTFINDER_BTN_DELETE_IN_QUESTIONNAIRE', 'rules.clearQuestionnaire', true);
			JToolBarHelper::divider();
		}
		else
		{
			JToolBarHelper::custom('rules.disallowed', 'disallowed.png', 'disallowed.png', 'COM_PRODUCTFINDER_BTN_SET_DISALLOWED', true);
			JToolBarHelper::custom('rules.required', 'required.png', 'required_f2.png', 'COM_PRODUCTFINDER_BTN_SET_REQUIRED', true);
			JToolBarHelper::custom('rules.recommended', 'recommended.png', 'recommended_f2.png', 'COM_PRODUCTFINDER_BTN_SET_RECOMMENDED', true);
			JToolBarHelper::divider();
			JToolBarHelper::custom('rules.toggle', 'toggle.png', 'toggle_f2.png', 'COM_PRODUCTFINDER_BTN_TOGGLE_RULES', true);
			JToolBarHelper::custom('rules.unset', 'unset.png', 'unset_f2.png', 'COM_PRODUCTFINDER_BTN_UNSET_RULES', true);		
			JToolBarHelper::divider();
		}
		JToolBarHelper::preferences( 'com_productfinder' );
		
	}


}