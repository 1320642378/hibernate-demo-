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
jimport('joomla.application.component.view');

/**
 * Product Finder View - Questionnaire
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderViewQuestionnaire extends JViewLegacy {
	
	protected $form;
	protected $item;
	protected $state;	

	function display($tpl = null) {
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		//TODO re-enable at later stage $this->canDo	= ProductfinderHelper::getActions();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
				
		$this->addToolBar();
		parent::display($tpl);
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JFactory::getApplication()->input->set('hidemainmenu', 1);

		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title($isNew ? JText::_('COM_PRODUCTFINDER_LBL_NEW_QUESTIONNAIRE') : JText::_('COM_PRODUCTFINDER_LBL_EDIT_QUESTIONNAIRE'), 'questionnaire.png');
		JToolBarHelper::apply('questionnaire.apply');
		JToolBarHelper::save('questionnaire.save');
		JToolBarHelper::save2new('questionnaire.save2new');

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('questionnaire.cancel');
		}
		else {
			JToolBarHelper::cancel('questionnaire.cancel', 'JTOOLBAR_CLOSE');
		}
	}	

}