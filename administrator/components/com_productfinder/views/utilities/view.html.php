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
 * Product Finder View - Utilities
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.3
 */
class ProductfinderViewUtilities extends JViewLegacy {

	function display($tpl = null) {
		
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
	protected function addToolBar(){
		JToolBarHelper::title(JText::_('COM_PRODUCTFINDER_LBL_UTILITIES'), 'utilities.png');
		JToolBarHelper::preferences( 'com_productfinder' );
	}	

}