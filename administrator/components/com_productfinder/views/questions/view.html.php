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
 * Product Finder View - Questions List
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderViewQuestions extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	function display($tpl = null) {

		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');
		$this->authors			= $this->get('Authors');		
		$this->questionnaires	= ProductfinderHelper::getQuestionnairesList();

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
	 * @since 1.1
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_PRODUCTFINDER_LBL_QUESTIONS'), 'questions.png');
		JToolBarHelper::addNew('question.add');
		JToolBarHelper::editList('question.edit');
		JToolBarHelper::divider();
		JToolBarHelper::publish('questions.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('questions.unpublish', 'JTOOLBAR_UNPUBLISH', true);

		if ($this->state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('', 'questions.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		else{
			JToolBarHelper::trash('questions.trash');
			JToolBarHelper::divider();
		}
		JToolBarHelper::preferences( 'com_productfinder' );
		
	}
	
	protected function addSideBar(){
		
		JHtmlSidebar::setAction('index.php?option=com_producfinder&view=questions');
			
		JHtmlSidebar::addFilter(
			JText::_('COM_PRODUCTFINDER_LBL_SELECT_QUESTIONNAIRE'),
			'filter_questionnaire',
			JHtml::_('select.options', $this->questionnaires, 'value', 'text', $this->state->get('filter.questionnaire'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_AUTHOR'),
			'filter_author_id',
			JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'))
		);	
			
	}

}