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
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of questionnaires.
 *
 * @package		Product Finder
 * @subpackage	com_productfinder
 * @since		1.0
 */

class ProductfinderModelQuestions extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'question', 'a.question',
				'question_type', 'a.question_type',
				'questionnaire', 'a.questionnaire_id', 'questionnaire_title',			
				'state', 'a.state',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
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

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
		
		$questionnaireId = $this->getUserStateFromRequest($this->context.'.filter.questionnaire', 'filter_questionnaire');
		$this->setState('filter.questionnaire', $questionnaireId);		

		// List state information.
		parent::populateState('questionnaire_title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.questionnaire');
		$id	.= ':'.$this->getState('filter.language');

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

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.question, a.question_type, a.min_answers, a.max_answers, a.questionnaire_id' .
				', a.state, a.created, a.created_by, a.ordering'
			)
		);
		$query->from('#__pf_questions AS a');

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		
		// Join over the questionnaires.
		$query->select('q.title AS questionnaire_title');
		$query->join('LEFT', '#__pf_questionnaires AS q ON q.id = a.questionnaire_id');		
		$query->where('q.state = 1');

		// Filter by access level of parent questionnaire.
		if ($access = $this->getState('filter.access')) {
			$query->where('c.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
		    $groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('q.access IN ('.$groups.')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId)) {
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by '.$type.(int) $authorId);
		}
		
		// Filter by questionnaire
		$questionnaireId = $this->getState('filter.questionnaire');
		if (is_numeric($questionnaireId)) {
			$type = $this->getState('filter.questionnaire.include', true) ? '= ' : '<>'; 
			$query->where('a.questionnaire_id '.$type.(int) $questionnaireId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.question LIKE '.$search.')');
			}
		}


		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.questionnaire_id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		if ($orderCol == 'a.ordering') {
			$orderCol = 'q.title '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	/**
	 * Build a list of authors
	 *
	 * @return	JDatabaseQuery
	 * @since	1.0
	 */
	public function getAuthors() {
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text');
		$query->from('#__users AS u');
		$query->join('INNER', '#__pf_questions AS c ON c.created_by = u.id');
		$query->group('u.id, u.name');
		$query->order('u.name');

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObjectList();
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		return $user->authorise('core.edit.state', $this->option);
	}

	/**
	 * Overloaded getReorderConditions method
	 *
	 * @param string $table
	 * @return multitype:
	 * @since 1.0
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'questionnaire_id = '.(int) $table->questionnaire_id;
		return $condition;
	}

	public function getTable($name = 'Questions', $prefix = 'ProductfinderTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Toggles on or more question(s) published state
	 *
	 * @param string $pks
	 * @param number $state
	 * @param number $userId
	 * @return boolean
	 * @since 1.0
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Get an instance of the table
		$table = $this->getTable();
				// Access checks.
		if (!$this->canEditState($table))
		{
			// Prune items that you can't change.
			unset($pks[$i]);
			JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		}		
		elseif(! $table->publish($pks, $state, $userId)){
			$this->setError($table->getError());
		}
		return count($this->getErrors())==0;
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 * @return  mixed
	 * @since   1.0
	 */
	public function saveorder($pks = null, $order = null)
	{
		// Initialise variables.
		$table = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_('COM_PRODUCTFINDER_ERR_NO_QUESTIONS_SELECTED'));
		}

		// Update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			if (!$this->canEditState($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);
				JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
			elseif ($table->ordering != $order[$i])
			{
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				// Remember to reorder within position and client_id
				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to adjust the ordering of a row.
	 * Returns NULL if the user did not have edit
	 * privileges for any of the selected primary keys.
	 *
	 * @param   integer  $pks    The ID of the primary key to move.
	 * @param   integer  $delta  Increment, usually +1 or -1
	 * @return  mixed  False on failure or error, true on success, null if the $pk is empty (no items selected).
	 * @since   1.0
	 */
	public function reorder($pks, $delta = 0)
	{
		// Initialise variables.
		$table = $this->getTable();
		$pks = (array) $pks;
		$result = true;

		$allowed = true;

		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{
				// Access checks.
				if (!$this->canEditState($table))
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
					$allowed = false;
					continue;
				}

				$where = array();
				$where = $this->getReorderConditions($table);

				if (!$table->move($delta, $where))
				{
					$this->setError($table->getError());
					unset($pks[$i]);
					$result = false;
				}

			}
			else
			{
				$this->setError($table->getError());
				unset($pks[$i]);
				$result = false;
			}
		}

		if ($allowed === false && empty($pks))
		{
			$result = null;
		}

		// Clear the component's cache
		if ($result == true)
		{
			$this->cleanCache();
		}

		return $result;
	}

	/**
	 * Overloaded delete method
	 * 
	 * @return boolean
	 * @since 1.0
	 */
	public function delete(){

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		
		$toDel = $jinput->get('cid', null, 'array');
		if(empty($toDel)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_NOTHING_TO_DELETE'));
			return false;
		}

		$table = $this->getTable();
		if(!$table->delete($toDel)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_CANT_DELETE_QUESTIONS'));
			return false;
		}

		return true;
	}

}