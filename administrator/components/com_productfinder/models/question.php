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
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

/**
 * Item Model for a Question.
 *
 * @package		Product Finder
 * @subpackage	com_productfinder
 * @since		1.0
*/
class ProductfinderModelQuestion extends JModelAdmin
{
	protected $params;

	public function __construct(){
		$app 	= JFactory::getApplication();
		$params = JComponentHelper::getParams('com_productfinder');
		$this->params = $params;
		return parent::__construct();
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_productfinder.question', 'question', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_productfinder.edit.question.data', array());
		if(empty($data)){
			$data = $this->getItem();
		};
		return $data;
	}

	public function getTable($name = 'Question', $prefix = 'ProductfinderTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to save a single question
	 *
	 * @see JModelAdmin::save()
	 * @param array $data
	 * @return boolean true|false
	 * @since 1.0
	 */
	public function save($data){

		if(empty($data)) {
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_NOTHING_TO_SAVE'));
			return false;
		}

		$isNew 	= $data['id'] == 0 ? true : false;
		$user 	= JFactory::getUser();

		$date = JFactory::getDate();
		$now = $date->toSql();

		if($isNew){
			$data['created'] 	= $now;
			$data['created_by'] = $user->id;
			$data['ordering'] 	= $this->getLastQuestionOrder($data['questionnaire_id']) + 1;
		}

		$data['modified'] 		= $now;
		$data['modified_by']	= $user->id;

		if(empty($data['question']))
		{
			$data['question'] = JString::substr(strip_tags($data['question_full']), 0 , 255);
		}

		if( parent::save($data))
		{
			// we need to transform the answers
			$parentId 		= $this->getState('question.id');
			$answerModel	= JModelLegacy::getInstance('Answer', 'ProductfinderModel', array());
			if(!empty($data['answers']))
			{
				$answersData 	= $this->processAnswerData($parentId, $data['answers']);
				if($answersData === false)
				{
					return $this->getErrors();
					return false;
				}

				if(!empty($answersData) && !$answerModel->save($answersData))
				{
					$this->setError(JText::_('COM_PRODUCTFINDER_ERR_CANT_SAVE_ANSWER'));
					return false;
				}
			}

		}

		return true;
	}

	/**
	 * Method to return answers o a question
	 * 
	 * @return JDatabaseQuery and sets the answers property of the question
	 * @since  1.0
	 */
	public function getAnswers(){

		if(!isset($this->answers)){
			$this->answers = null;
			if($questionId = $this->getState('question.id')){

				// Create a new query object.
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				// Select the required fields from the table.
				$query->select('a.*');
				$query->from('#__pf_answers AS a');
				$query->where('a.question_id = ' . $db->Quote($questionId) );
				$query->order('a.ordering, a.id'); //FIX ORDERING
				$db->setQuery((string)$query);

				if($answers = $db->loadObjectList()){
					$this->answers = $answers;
				}
			}
		}
		return $this->answers;
	}
	
	/**
	 * Returns the order number of the last question
	 * 
	 * @param int $questionnaire_id
	 * @return integer or false if errors occurs
	 * @since 1.0
	 */
	private function getLastQuestionOrder($questionnaire_id){

		$db 	= JFactory::getDbo();
		$query 	= 'SELECT ordering ' .
				'FROM #__pf_questions ' .
				'WHERE questionnaire_id = '. (int) $questionnaire_id . ' ' .
				'ORDER BY ordering DESC ' .
				'LIMIT 1 ';
		$db->setQuery($query);

		return $db->loadResult();
			
	}

	/**
	 *
	 * Transform the json encoded object that holds answers
	 * into proper $data array for saving
	 * 
	 * @param int $parentId parent question id
	 * @param string $data json encoded anser object
	 * @return array
	 * @since 1.0
	 */
	private function processAnswerData($parentId, $encodedAnswers){

		if(empty($parentId)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_MISSING_QUESTION_ID_UNABLE_TO_SAVE'));
			return false;
		}

		if(!$answersObj = json_decode($encodedAnswers)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_WRONG_DATA_FORMAT_NOT_JSON'));
			return false;
		}

		if(!is_object($answersObj)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_WRONG_DATA_FORMAT_NOT_AN_OBJEXT'));
			return false;
		}


		// we expect an array for every field
		$ids 				= $answersObj->ans_ids;
		$values				= $answersObj->ans_values;
		$labels 			= $answersObj->ans_labels;
		$separators			= $answersObj->ans_sep;
		$defaults			= $answersObj->ans_defaults;
		$orderings			= $answersObj->ans_orders;

		$data = array();
		if (!empty ($ids)) 
		{
			foreach ($ids as $key => $answer) 
			{
				$label 			= $labels[$key];
				$value 			= $values[$key];
				$is_default 	= $defaults[$key];
				$is_separator 	= $separators[$key];
				if(empty($orderings[$key]))
				{
					$orderings[$key] = max(array_values($orderings)) + 1 ;
				}
				$ordering 		= $orderings[$key];

				if(empty($label) && empty($value)) continue;

				if($is_separator && empty($label))
				{
					continue;
				}
				elseif(!empty($label) && empty($value))
				{
					//automatic value
					$value = strip_tags($label);
				}
				elseif(empty($label) && !empty($value))
				{
					//automatic label
					$label = strip_tags($value);
				}

				$data[$key]['question_id'] 		= $parentId;
				$data[$key]['id'] 				= $ids[$key];
				$data[$key]['label'] 			= $label;
				$data[$key]['is_separator'] 	= $is_separator;
				$data[$key]['is_default'] 		= $is_default;
				$data[$key]['ordering'] 		= $ordering;
				if($is_separator)
				{
					$data[$key]['value'] 		= '';
					$data[$key]['is_default'] 	= '0';
				}
				else{
					$data[$key]['value'] 		= strip_tags($value);
				}
			}

		}

		return $data;
	}

}

