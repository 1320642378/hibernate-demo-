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
jimport('joomla.application.component.view');

/**
 * Product Finder View - Question
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderViewQuestion extends JViewLegacy {
	
	protected $form;
	protected $item;
	protected $answers;
	protected $state;	

	function display($tpl = null) {
		
		$document = JFactory::getDocument();
		if(PF_JOOMLA3)
		{
			$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/productfinder.jquery.js');
		}
		else 
		{
			$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/productfinder.js');
		}
		
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->answers	= $this->get('Answers');
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

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title($isNew ? JText::_('COM_PRODUCTFINDER_LBL_NEW_QUESTION') : JText::_('COM_PRODUCTFINDER_LBL_EDIT_QUESTION'), 'question.png');
		JToolBarHelper::apply('question.apply');
		JToolBarHelper::save('question.save');
		JToolBarHelper::save2new('question.save2new');

		if (empty($this->item->id))  
		{
			JToolBarHelper::cancel('question.cancel');
		}
		else {
			JToolBarHelper::cancel('question.cancel', 'JTOOLBAR_CLOSE');
		}
	}	

}