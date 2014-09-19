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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.database.table');

/**
 * Table object for Questionnaire
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderTableQuestionnaires extends JTable
{

	var $id = 0;
	var $asset_id = null;
	var $title = null;
	var $alias = null;
	var $description = null;
	var $state = null;
	var $image = null;
	var $created = null;
	var $created_by = null;
	var $modified = null;
	var $modified_by = null;
	var $publish_up = null;
	var $publish_down = null;
	var $params = null;
	var $ordering = null;
	var $metakey = null;
	var $metadesc = null;
	var $access = null;
	var $hits = null;
	var $metadata = null;
	var $language = null;

	function __construct(&$db)  {
		parent::__construct('#__pf_questionnaires', 'id', $db);
	}

	/**
	 * Binds an array to the object
	 * @param 	array	Named array
	 * @param 	string	Space separated list of fields not to bind
	 * @return	boolean
	 */
	function bind( $array, $ignore='' )
	{
		$result = parent::bind( $array );
		// cast properties
		$this->id	= (int) $this->id;

		return $result;
	}

	/**
	 * Overloaded delete function.
	 * 
	 * @return boolean true if check is positive, false otherwise
	 * @since 1.0
	 */
	public function delete($pk = null){
		
		if(empty($pk) || !is_array($pk)) return false;
	
		$db = $this->_db;

		foreach($pk as $id){
			if(empty($id)) continue;
			$toDel[] = "'" . $db->escape($id) . "'";
		}
		
		$query = 'DELETE FROM #__pf_questionnaires '
			. 'WHERE id IN('. implode(',', $toDel) . ')';
			
		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}
		
		//delete orphaned questions, answers, rules ...
		ProductfinderHelper::pruneOrphanedRecords();
		
		return true;
	}
	
	/**
	 * Overloaded publish method
	 * 
	 * @see JTable::publish()
	 * @since 1.0
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Get the JDatabaseQuery object
		$query = $this->_db->getQuery(true);

		// Update the publishing state for rows with the given primary keys.
		$query->update($this->_db->quoteName($this->_tbl));
		$query->set($this->_db->quoteName('state') . ' = ' . (int) $state);
		$query->where('(' . $where . ')' );
		$this->_db->setQuery($query);
		$this->_db->execute();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}		

}