<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

/**
 * Item Model for a Questionnaire.
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since 1.0
 */
class ProductfinderModelChoices extends JModelItem
{

	protected $_context = 'com_productfinder.choices';
	protected $_is_valid = true;
	protected $choices;
	protected $menuparams;
	
	public function __construct($config = null){
		
		parent::__construct($config);
		
		$app = JFactory::getApplication();
		$active = $app->getMenu()->getActive();
		if(is_object($active)){
			$this->setState('menuparams', $active->params);
			$this->menuparams = $active->params;
		}
		
		if(!isset($this->menuparams)){
			//ain't no menuparams, use components params
			$menuparams = JComponentHelper::getParams('com_productfinder');
			$this->setState('menuparams', $menuparams);
			$this->menuparams = $menuparams;			
		}	
	}
	
	/**
	 * Overloaded method to set object state. Stores the state into session too.
	 * 
	 * @see JModel::setState()
	 * @since 1.0
	 */
	public function setState($property, $value = null){
		$app = JFactory::getApplication('site');
		$app->setUserState($property, $value);
		parent::setState($property, $value);
	}
	
	/**
	 * Overloaded method to get object state. Gets the state from session.
	 *
	 * @see JModel::setState()
	 * @since 1.0
	 */	
	public function getState($property = null, $default = null){
		$app = JFactory::getApplication('site');
		return $app->getUserState($property, $default);
	}

	/**
	 * Reset choices (from session)
	 * 
	 * @since 1.0
	 */
	public function reset(){
		$app = JFactory::getApplication('site');
		$app->setUserState('questionnaire_id', null);
		$app->setUserState('choices', null);
		$app->setUserState('active_question', null);
		$app->setUserState('next_question', null);
		$app->setUserState('menuparams', null);
		$app->setUserState('started', null);
	}
	
	/**
	 * Resets errors
	 * 
	 * @since 1.0
	 */
	public function resetErrors(){
		$this->_errors = array();
	}
	
	/**
	 * Validate the choices made, against a given questionnaire and question
	 * 
	 * @param int $questionnaire_id
	 * @param int  $activeQuestion
	 * @return int $next_qestion if valid or false if choices ar invalid. Sets validation error message in choices objects arrors
	 * @since 1.0
	 */
	public function validate($questionnaire_id, $activeQuestion ){
		
		$jinput = JFactory::getApplication()->input;
		
		if(empty($questionnaire_id) || empty($activeQuestion)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_ID'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		
		$questionnaire = JModelLegacy::getInstance('Questionnaire', 'ProductfinderModel', array());
		//make sure the question belongs to the right questionnaire
		if(!$questions = $questionnaire->getQuestions($questionnaire_id)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_IS_EMPTY'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		if(!isset($questions[$activeQuestion])){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTION_NOT_FOUND'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		//accept only valid answers
		$question = $questions[$activeQuestion];
		if(!isset($question->answers) || empty($question->answers)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTION_INCOMPLETE') . ' (' . (int) $activeQuestion . ')');
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		
		$next_question = $questions[$activeQuestion]->next_question ? $questions[$activeQuestion]->next_question : 'end';
		
		$validAnswers = $question->answers;
		$q = $jinput->get('q', null, 'array');
		
		if($question->min_answers > 0){
			//an answer is required
			if(!isset($q) || empty($q)){
				$this->setError(JText::_('COM_PRODUCTFINDER_ERR_PLEASE_MAKE_SOME_SELECTIONS'));
				$this->_is_valid = false;
				return $this->_is_valid;					
			}
			 
		}
		else{
			//no answer required, no answers provided, is valid
			if(!isset($q) || empty($q)) {
				$this->_is_valid = true;
				return $next_question;
			}
		}
		
		if(isset($q) && !isset($q[$activeQuestion])){
			//question is 'n' but I got answers for question 'm'
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_INCOHERENT_DATA'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		
		$submittedAnswers = $q[$activeQuestion];
		
		$savedChoices = $this->getState('choices');
		foreach($submittedAnswers as $k => $submittedAnswer){
			//filter out invalid answers
			if(!array_key_exists($submittedAnswer, $validAnswers)){
				unset($submittedAnswers[$k]);
			}
		}

		$savedChoices[$activeQuestion] = $submittedAnswers;		
		$this->setState('choices', $savedChoices);
		
		$numAnswers = count($submittedAnswers);
		if($numAnswers < $question->min_answers){
			$this->setError(JText::sprintf('COM_PRODUCTFINDER_ERR_THIS_QUESTION_REQUIRES_N_ANSWERS', $question->min_answers ));
			$this->_is_valid = false;
			return $this->_is_valid;			
		}
		if($numAnswers > $question->max_answers){
			$this->setError(JText::sprintf('COM_PRODUCTFINDER_ERR_THIS_QUESTION_ALLOWS_N_ANSWERS', $question->max_answers ));
			$this->_is_valid = false;
			return $this->_is_valid;	
		}
		
		$this->_is_valid =  true;
		return $next_question;
	}		
	
	
	/**
	 * Return questionnaire's previous question
	 * 
	 * @param int $questionnaire_id
	 * @param int  $activeQuestion
	 * @return int $prev_qestion if valid or false if choices ar invalid. Sets validation error message in choices objects arrors
	 * @since 1.0
	 */
	public function getPrevQuestion($questionnaire_id, $activeQuestion ){
		
		if(empty($questionnaire_id) || empty($activeQuestion)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_ID'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		
		$questionnaire = JModelLegacy::getInstance('Questionnaire', 'ProductfinderModel', array());
		//make sure the question belongs to the right questionnaire
		if(!$questions = $questionnaire->getQuestions($questionnaire_id)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_IS_EMPTY'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}
		if(!isset($questions[$activeQuestion])){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_QUESTION_NOT_FOUND'));
			$this->_is_valid = false;
			return $this->_is_valid;
		}	
		
		return $questions[$activeQuestion]->prev_question;
	}
	
	/**
	 * Adds the filtering part to the content query
	 * 
	 * @param JDatabaseQuery $query
	 * @param object $connector
	 * @return JDatabaseQuery
	 * @since 1.0
	 */
	public function filterQuery(&$query, $connector){
		
		if(empty($query) || ! is_a($query, 'JDatabaseQuery') || empty($connector)) return false;

		$globalparams 	= JComponentHelper::getParams('com_productfinder');
		$menuparams 	= $this->getState('menuparams');
		$score_threshold = $menuparams->get('score_threshold') ? $menuparams->get('score_threshold') : $globalparams->get('score_threshold');
		
		$choices = $this->getState('choices');
		if(empty($choices)) 
		{
			if($score_threshold > 0)
			{
				// we have a threshold, but no choices have been made: return no values
				$query->where('0');
				return $query;	
			}
			else
			{
				return $query;
			}
		}
		
		$ref_table = $connector->ref_table;
		$pffields = $connector::getFields();
		
		$query->select('SUM(CASE pfr.rule WHEN "REQ" THEN 1 ELSE pfr.rule END) AS score');
		$query->select('pfrr.rule AS req');
		$query->group('1');	

		$answers = array();
		foreach($choices as $qid => $question){
				foreach($question as $answer){
					$answers[] = $answer;
				}
		}
		
		$query->join('LEFT', '#__pf_rules AS pfr ON ('.$pffields['pfitem_id'].' = pfr.content_id AND "'.$ref_table.'" = pfr.ref_table AND pfr.answer_id IN ('. implode(',', $answers) . '))');
		$query->join('LEFT', '#__pf_rules AS pfn ON ('.$pffields['pfitem_id'].' = pfn.content_id AND "'.$ref_table.'" = pfn.ref_table AND "NOT" = pfn.rule AND pfn.answer_id IN ('. implode(',', $answers) . '))');
		$query->join('LEFT', '#__pf_rules AS pfrr ON ('.$pffields['pfitem_id'].' = pfrr.content_id AND "'.$ref_table.'" = pfrr.ref_table AND "REQ" = pfrr.rule AND pfrr.answer_id IN ('. implode(',', $answers) . '))');
		
		$query->where('(pfn.rule IS NULL)' );
		
		
		if($score_threshold)
		{
			$query->having('score >= ' . (int) $score_threshold); 
		}

		//primary order
		$order = array();
		$order[] = 'req DESC'; //this is the first criteria in any case, as to put REQ products before others 
		$primary_order = $menuparams->get('primary_order');
		$primary_order = ($primary_order != 'global' ? $primary_order : $globalparams->get('primary_order', 'score'));
		
		$primary_order_field = $menuparams->get('primary_order_field');
		$primary_order_field = ($primary_order_field != 'global' ? $primary_order_field : $globalparams->get('primary_order_field', 'order'));

		$order[] = $connector::getPrimaryOrder($primary_order_field, $primary_order);
		
		//secondary order
		$seconday_order = $menuparams->get('secondary_order');
		$seconday_order = ($seconday_order != 'global' ? $seconday_order : $globalparams->get('secondary_order', 'newest'));

		$order[] = $connector::getSecondaryOrder($seconday_order);
		
		$query->order(implode(', ', $order)); 
		
		return $query;
	}
	
	/**
	 * Returns questionnaire validity
	 * 
	 * @return boolean
	 * @since 1.0
	 */
	public function isValid(){
		return $this->_is_valid;
	}
	
	/**
	 * Returns choices
	 * 
	 * @return mixed
	 * @since 1.0
	 */
	public function getChoices(){
		return $this->getState('choices');
	}
}
