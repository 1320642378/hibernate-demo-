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
 * Table object for Answers
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderTableAnswers extends JTable
{

	var $id = 0;
	var $question_id = null;
	var $value = null;
	var $label = null;
	var $is_separator = null;
	var $is_default = null;
	var $ordering = null;

	public function __construct(&$db)  {
		parent::__construct('#__pf_answers', 'id', $db);
	}

	/**
	 * Binds an array to the object
	 * @param 	array	Named array
	 * @param 	string	Space separated list of fields not to bind
	 * @return	boolean
	 */
	public function bind( $array, $ignore='' )
	{
		$result = parent::bind( $array );
		// cast properties
		$this->id	= (int) $this->id;

		return $result;
	}

	/**
	 * Overloaded delete function. Deletes answers and related rules 
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

		$query = 'DELETE FROM #__pf_rules '
			. 'WHERE answer_id IN (' . implode(',', $toDel) . ')';

		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}

		$query = 'DELETE FROM #__pf_answers '
			. 'WHERE id IN('. implode(',', $toDel) . ')';

		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}

		return true;
	}

}