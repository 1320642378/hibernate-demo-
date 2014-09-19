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
 * Table object for Rules
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderTableRules extends JTable
{
	
	var $id = 0;
	var $ref_table = null;
	var $content_id = null;
	var $answer_id =null;

	function __construct(&$db)  {
		parent::__construct('#__pf_rules', 'id', $db);
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
	 *
	 * gets a single rule
	 * @param string $refTable
	 * @param int $contentId
	 * @param int $answerId
	 * @return object ruleId->ruleValue or empty object
	 * @since 1.0
	 */
	function getRule( $refTable, $contentId, $answerId){
	
		if(empty($refTable) || empty($contentId) || empty($answerId)) return false;
	
		//get rules
		$query = $this->_db->getQuery(true);
		$query->select('id, rule');
		$query->from($this->_db->quoteName($this->_tbl));
		$query->where('ref_table = ' . $this->_db->quote($refTable));
		$query->where('content_id = ' . $this->_db->quote($contentId));
		$query->where('answer_id = ' . $this->_db->quote($answerId));
		$this->_db->setQuery($query, 0, 1);
		return $currentRule = $this->_db->loadObject();	
	
	}
		
	/**
	 * 
	 * Cycles through rules types
	 * @param string $refTable
	 * @param int $contentId
	 * @param int $answerId
	 * @return string the new rule
	 * @since 1.0
	 */
	function toggle( $refTable, $contentId, $answerId){
		
		if(empty($refTable) || empty($contentId) || empty($answerId)) return false;

		$currentRule = $this->getRule($refTable, $contentId, $answerId);
		
		$newRule = '';
		if(empty($currentRule)){
			$newRule = '1';
			$this->add($refTable, $contentId, $answerId, $newRule);
		}
		elseif($currentRule->rule == '1'){
			$newRule = 'NOT';
			$this->updateRule($currentRule->id, $newRule);
		}
		elseif($currentRule->rule == 'NOT'){
			$newRule = 'REQ';
			$this->updateRule($currentRule->id, $newRule);
		}
		else{
			$this->delete($currentRule->id);
		}
		
		return $newRule;

	}
	
	/**
	 * Updates a rule
	 *  
	 * @param integer $ruleId
	 * @param string $rule
	 * @return boolean
	 * @since 1.0
	 */
	function updateRule($ruleId, $rule){
		
		if(empty($ruleId) || empty($rule)) return false;
		
		$query = $this->_db->getQuery(true);
		$query->update($this->_db->quoteName($this->_tbl));
		$query->set($this->_db->quoteName('rule') . ' = ' . $this->_db->quote($rule));
		$query->where('id = ' . (int) $ruleId );
		$this->_db->setQuery($query);
		$this->_db->execute();		
		
	}
	
	/**
	 * Toggles many rules at once
	 * 
	 * @param string $refTable
	 * @param array $contentIds
	 * @param array $answerIds
	 * @return int number of rules toggled or false
	 * @since 1.0
	 */
	public function toggleMany($refTable, $contentIds, $answerIds){
		
		$updatedRules = 0;
		foreach($contentIds as $id){
			
			foreach($answerIds as $aid){
				
				$this->toggle($refTable, $id, $aid);
				$updatedRules++;
			}
		}
		return $updatedRules;
	}
	
	/**
	 * Set many rules at once
	 *
	 * @param string $refTable
	 * @param array $contentIds
	 * @param array $answerIds
	 * @param string $rule 
	 * @return int number of rules toggled or false
	 * @since 1.0
	 */
	public function setMany($refTable, $contentIds, $answerIds, $rule = '1'){
	
		if(empty($refTable) || empty($contentIds) || empty($answerIds)) return false;
		
		$updatedRules = 0;
		foreach($contentIds as $id){
				
			foreach($answerIds as $aid){
	
				$currentRule = $this->getRule($refTable, $id, $aid);
				
				if(empty($currentRule)){
					if($this->add($refTable, $id, $aid, $rule) !== false)
					{
						$updatedRules++;
					}
				}
				else
				{
					if($this->updateRule($currentRule->id, $rule) !== false)
					{
						$updatedRules++;
					}	
				}				

			}
		}
		return $updatedRules;
	}	
	
	/**
	 * Unsets many rules at once
	 * 
	 * @param string $refTable
	 * @param array $contentIds
	 * @param array $answerIds
	 * @return int number of rules toggled or false
	 * @since 1.0
	 */
	public function unsetMany($refTable, $contentIds, $answerIds){
		
		$conditions = array();
		foreach($contentIds as $id)
		{
			foreach($answerIds as $aid)
			{
				$conditions[] = '(content_id = ' . (int) $id . ' AND answer_id = ' . $aid . ')';
			}
		}

		$query = 'DELETE FROM #__pf_rules '
			. ' WHERE (' . implode(' OR ', $conditions) . ') '
			. ' AND ref_table = "' . $this->_db->escape($refTable) .'"';
		
		$this->_db->setQuery($query);
		$this->_db->execute();	
		return $this->_db->getAffectedRows();
	}
	
	/**
	 * Removes all rules from given content items
	 * 
	 * @param string $refTable
	 * @param array $contentIds
	 * @return int number of rules cleared or false
	 * @since 1.0 
	 */
	public function clearAll($refTable, $contentIds){

		JArrayHelper::toInteger($contentIds);

		$query = 'DELETE FROM #__pf_rules '
			. ' WHERE ref_table = "' . $this->_db->escape($refTable) . '" '
			. ' AND content_id IN('. implode(',', $contentIds) . ')';
		
		$this->_db->setQuery($query);
		$this->_db->execute();	
		return $this->_db->getAffectedRows();		
	}
	
	/**
	 * Clears all rules from a questionnaire
	 * 
	 * @param string $refTable
	 * @param array $contentIds
	 * @param integer $questionnaire_id
	 * @return number of rules cleared
	 * @since 1.0
	 */
	public function clearQuestionnaire($refTable, $contentIds, $questionnaire_id){
		
		JArrayHelper::toInteger($contentIds);
		
		$query = 'DELETE #__pf_rules AS r '
			. ' FROM #__pf_rules AS r LEFT JOIN #__pf_answers AS a ON r.answer_id = a.id '
			. ' LEFT JOIN #__pf_questions AS q ON a.question_id = q.id '
			. ' WHERE r.ref_table = "' . $this->_db->escape($refTable) . '" '
			. ' AND r.content_id IN('. implode(',', $contentIds) . ')'
			. ' AND q.questionnaire_id = "'.$this->_db->escape($questionnaire_id).'"';
		
		$this->_db->setQuery($query);
		$this->_db->execute();	
		return $this->_db->getAffectedRows();			
		
	}
	
	/**
	 * Adds a rule
	 * @param string $refTable
	 * @param array $contentId
	 * @param integer $answerId
	 * @param string $rule
	 * @return boolean
	 * @since 1.0
	 */
	function add($refTable, $contentId, $answerId, $rule){
		
		if(empty($refTable) || empty($contentId) || empty($answerId) || empty($rule)) return false;
		
		$query = $this->_db->getQuery(true);
		$query->insert($this->_db->quoteName($this->_tbl));
		$query->columns(array('ref_table', 'content_id', 'answer_id', 'rule'));
		$query->values( $this->_db->quote($refTable) . ',' . (int) $contentId . ',' . (int) $answerId . ',' . $this->_db->quote($rule) );
		$this->_db->setQuery($query);
		$this->_db->execute();	
		
	}

}