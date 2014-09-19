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
 * Product Finder View - Questionnaires List
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderViewQuestionnaires extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	function display($tpl = null) {

		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->authors		= $this->get('Authors');		

		$this->addToolBar();
		if(PF_JOOMLA3)
		{
			$this->addSideBar();
			$this->sidebar = JHtmlSidebar::render();
		}		
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_PRODUCTFINDER_LBL_QUESTIONNAIRES'), 'questionnaires.png');
		JToolBarHelper::addNew('questionnaire.add');
		JToolBarHelper::editList('questionnaire.edit');
		JToolBarHelper::divider();
		JToolBarHelper::publish('questionnaires.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('questionnaires.unpublish', 'JTOOLBAR_UNPUBLISH', true);

		if ($this->state->get('filter.published') == -2) 
		{
			$bar->appendButton('Confirm', JText::_('COM_PRODUCTFINDER_MSG_CONFIRM_DELETE_QUESTIONNAIRE'), 'delete', 'JTOOLBAR_EMPTY_TRASH', 'questionnaires.delete', true);
			JToolBarHelper::divider();
		}
		else
		{
			JToolBarHelper::trash('questionnaires.trash');
			JToolBarHelper::divider();
		}
		
		JToolBarHelper::preferences( 'com_productfinder' );
		
	}

	protected function addSideBar(){
	
		JHtmlSidebar::setAction('index.php?option=com_producfinder&view=questionnaires');
			
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_AUTHOR'),
			'filter_author_id',
			JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
				
	}
}