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
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

/**
 * Item Model for a Questionnaire.
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since 1.0
 */
class ProductfinderModelQuestionnaire extends JModelItem
{

	protected $_context = 'com_productfinder.questionnaire';

	protected function populateState()
	{
		$choices = JModelLegacy::getInstance('Choices', 'ProductfinderModel');
		$pk = $choices->getState('questionnaire_id');
		$this->setState('questionnaire.id', $pk);
	}

	/**
	 * Method to questionnaire
	 *
	 * @param	integer	The id of the questionnaire.
	 * @return	mixed	Questionnaires, false on failure.
	 * @since 1.0
	 */
	public function &getQuestionnaire($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('questionnaire.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select($this->getState(
					'item.select',
						'a.*, 
						qf.id AS first_question, 
						ql.id AS last_question ' 
					)
				);
				
				$query->from('#__pf_questionnaires AS a');
				$query->where('a.id = ' . (int) $pk);
				
				$query->join('LEFT', '#__pf_questions AS qf ON a.id = qf.questionnaire_id AND 1 = qf.state');					
				$query->join('LEFT', '#__pf_questions AS ql ON a.id = ql.questionnaire_id AND 1 = ql.state');	
				$query->order('qf.`ordering` ASC, ql.`ordering` DESC');				

				// Filter by published state.
				$published = $this->getState('filter.published');

				if (is_numeric($published)) {
					$query->where('(a.published = ' . (int) $published);
				}
				
				$db->setQuery($query, 0, 1);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				if (empty($data)) {
					return JError::raiseError(404, JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_NOT_FOUND'));
				}

				$data->questions 	= $this->getQuestions((int) $pk);
				$data->params 		= new JRegistry($data->params );

				$this->_item[$pk] = $data;
			}
			catch (JException $e)
			{
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else {
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	
	/**
	 * Method to return questions of a questionnaire
	 * 
	 * @param integer $questionnaireId
	 * @return array of question objects (with answers) or false if it fails
	 * @since 1.0
	 */
	public function getQuestions($questionnaireId = 0){

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.*');
		$query->from('#__pf_questions AS a');
		$query->where('a.questionnaire_id = ' . $db->Quote($questionnaireId) );
		$query->where('a.state = 1');
		$query->order('a.ordering');
		$db->setQuery((string)$query);
		
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}		
		

		$pQuestions = array();
		if($questions = $db->loadObjectList()){
			$i = 1;
			foreach($questions as $n => $question){

				if(isset($questions[$n-1])){
					$question->prev_question = $questions[$n-1]->id; 
				}
				else{
					$question->prev_question = null;
				}
				
				if(isset($questions[$n+1])){
					$question->next_question = $questions[$n+1]->id; 
				}
				else{
					$question->next_question = null;
				}
				$question->q_number = $i;
				$question->answers = $this->getAnswers($question->id, $question->answers_ordering);
				$pQuestions[$question->id] = $question;
				$i++;
			}
			return $pQuestions;
		}			
		else{
			return false;
		}		
	}
	
	/**
	 * Returns answers of a question
	 * 
	 * @param integer $questionId
	 * @param string $order
	 * @return array of answer objects
	 * @since 1.0
	 */
	private function getAnswers($questionId = 0, $order = 'ordering'){

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.*');
		$query->from('#__pf_answers AS a');
		$query->where('a.question_id = ' . $db->Quote($questionId) );
		
		switch($order){
			case 'values':
				$query->order('a.value, a.id');
				break;
			case 'labels':
				$query->order('a.label, a.id');
				break;
			case 'default':
			case 'ordering':
				$query->order('a.ordering, a.id');
				break;
		}
		$db->setQuery((string)$query);
		
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}			
		
		if($questions = $db->loadObjectList('id')){
			return $questions;
		}			
		else{
			return false;
		}			
	}

	/**
	 * Method to return a single question, from a questionnaire
	 *
	 * @param	integer	The id of the question
	 * @return	mixed	$question, false on failure.
	 * @since 1.0
	 */
	public function getQuestion($pk = null)
	{

		if(empty($pk)) return false;
		
		// Initialise variables.
		$qqId = (!empty($qqId)) ? $qqId : (int) $this->getState('questionnaire.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$qqId])) {
		}
		else{
			$questionnaire = $this->_item[$qqId];
		}

		if(!isset($questionnaire->questions[$pk])) return false;
		
		return $questionnaire->questions[$pk];
		
	}
	
	/**
	 * Method to increase the hit counter of the questionnaire
	 * 
	 * @param int $pk questionnaire_id
	 * @return boolean
	 * @since 1.0
	 */
	public function hit($pk){
		
		if(empty($pk)) return false;
		
		$db = $this->getDbo();

		$db->setQuery(
			'UPDATE #__pf_questionnaires' .
			' SET hits = hits + 1' .
			' WHERE id = '.(int) $pk
		);

		if (!$db->query()) 
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
}
