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
// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Methods supporting the rule matrix (items X rules)
 *
 * @package		Product Finder
 * @subpackage	com_productfinder
 * @since		1.0
 */
class ProductfinderModelRules extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'pfitem_id',
				'title', 'pfitem_title'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$jinput = JFactory::getApplication()->input;
		// Adjust the context to support modal layouts.
		if ($layout = $jinput->getCmd('layout')) {
			$this->context .= '.'.$layout;
		}
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);		

		$questionnaireId = $this->getUserStateFromRequest($this->context.'.filter.questionnaire', 'filter_questionnaire');
		$this->setState('filter.questionnaire', $questionnaireId);

		$questionId = $this->getUserStateFromRequest($this->context.'.filter.question', 'filter_question');
		$this->setState('filter.question', $questionId);
		
		$pfConnector = $this->getUserStateFromRequest($this->context.'.filter.pfconnector', 'filter_pfconnector', 'articles');
		$this->setState('filter.pfconnector', $pfConnector);		
		
		// List state information.
		parent::populateState('pfitem_title', 'ASC');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.questionnaire');
		$id	.= ':'.$this->getState('filter.category_id');
		$id	.= ':'.$this->getState('filter.question');
		$id	.= ':'.$this->getState('filter.pfconnector');
		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.0
	 */
	protected function getListQuery()
	{
		
 		$db		= $this->getDbo();
		$user	= JFactory::getUser();
		
		if(PF_LEVEL)
		{
			$connector = PFConnectors::getInstance()->getConnector($this->state->get('filter.pfconnector'));	
		}
		else 
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();	
		}

		$query = $connector->getListQuery();
		$ref_table = $connector->ref_table;
		$pffields = $connector->getFields();

		$rulesOrder = '';
		if($questionId = $this->getState('filter.question')){
			
			$numAnswers = count((array) ProductfinderHelper::getAnswers($questionId, false));
			$this->setState('num_answers', $numAnswers);

			$query->select('aa.id AS answer_id');
			$query->select('r.rule');
			$query->join('', '#__pf_answers AS aa LEFT JOIN #__pf_rules AS r ON aa.id = r.answer_id  AND '.$pffields['pfitem_id'].' = r.content_id AND "'.$ref_table.'" = r.ref_table');
			$query->where('aa.question_id = ' . (int) $questionId);
			$query->where('aa.is_separator = 0');
						
			$rulesOrder = ',' . $db->escape('pfitem_id, aa.ordering ASC, aa.value ASC'); //NOTE this must be the same as helper::getAnswers ordering 
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where($pffields['pfitem_id'] . ' = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('('.$pffields['pfitem_title'].' LIKE '.$search.' OR '.$pffields['pfitem_alias'].' LIKE '.$search.')');
			}
		}
		
		//Filter by category, if required and if supported by connector
		$categoryId = $this->getState('filter.category_id');
		if(!empty($categoryId))
		{
			$query = $connector->addCategoryFilter($query, $categoryId);
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'pfitem_title');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol.' '.$orderDirn) . $rulesOrder);

		return $query;
	}

	public function getItems(){
		$items = parent::getItems();
		return $items;
	}		
	
	protected function _getList($query, $limitstart = 0, $limit = 0){
	
		$numAnswers = $this->getState('num_answers', 1);
		$limitstart = $limitstart * (int) $numAnswers;
		$limit 		= $limit * (int) $numAnswers;
		return parent::_getList($query, $limitstart, $limit);
	}
	
	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  PfRulesPagination  A PfRulesPagination object for the data set. (overload of JPagination)
	 * @since   1.0
	 */
	public function getPagination()
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$numAnswers = $this->getState('num_answers', 1);
		
		// Create the pagination object.
		jimport('joomla.html.pagination');
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page = new PfRulesPagination($this->getTotal(), $this->getStart() , $limit, $numAnswers );

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}
	
}