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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 * Product Finder View - Results
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since		1.0
 */
class ProductfinderViewResults extends JViewLegacy {
	
	protected $questionnaire;
	protected $choices;
	protected $results;
	protected $state;
	protected $globalparams;
	protected $menuparams;
	
	function display($tpl = null) {
		
		$jinput = JFactory::getApplication()->input;
		$this->resModel 	= $this->getModel(); 
		$this->globalparams = JComponentHelper::getParams('com_productfinder');
		
		$choices 			= $this->getModel('Choices');
		$this->menuparams 	= $choices->getState('menuparams'); //NOTE you really got to get menuparams from choices
		
		$questionnaire_id 	= $jinput->getUInt('questionnaire_id');
		if(empty($questionnaire_id)) $questionnaire_id = $choices->getState('questionnaire_id');
		
		$questionnaire		= $this->getModel('Questionnaire');
		$this->questionnaire= $questionnaire->getQuestionnaire($questionnaire_id);
		$this->results 		= $this->get('Items');
		$this->state		= $this->get('State');
		
		parent::display($tpl);
	}
	
}