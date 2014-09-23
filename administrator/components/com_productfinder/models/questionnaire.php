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
jimport('joomla.application.component.modeladmin');

/**
 * Item Model for a Questionnaire.
 *
 * @package		Product Finder
 * @subpackage	com_productfinder
 * @since		1.0
 */
class ProductfinderModelQuestionnaire extends JModelAdmin
{
	protected $params;
	
	public function __construct(){
		$app 	= JFactory::getApplication();
		$params = JComponentHelper::getParams('com_productfinder'); // load the Params
		$this->params = $params;
		return parent::__construct();
	}
	
	public function getForm($data = array(), $loadData = true) 
	{
    	// Get the form.
		$form = $this->loadForm('com_productfinder.questionnaire', 'questionnaire', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}	
	
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_productfinder.edit.questionnaire.data', array());
		if(empty($data)){
			$data = $this->getItem();
		};
		return $data;
	}	
		
	public function getTable($name = 'Questionnaire', $prefix = 'ProductfinderTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
	
	/**
	 * Method to save a questionnaire
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
			$data['ordering'] 	= $this->getLastQuestionnaireOrder() + 1;
		}
		
		$data['modified'] 		= $now;
		$data['modified_by']	 = $user->id;
		if(empty($data['alias'])){
			$data['alias'] = JApplication::stringURLSafe($data['title']);
		}
		
		if(parent::save($data)){
			return true;
		} 
		
		return false;
	}
	
	/**
	 * Returns the order number of the last question
	 * 
	 * @param int $questionnaire_id
	 * @return int or false if errors occurs
	 * @since 1.0
	 */
	private function getLastQuestionnaireOrder(){
	
		$db 	= JFactory::getDbo();
		$query 	= 'SELECT ordering ' .
				'FROM #__pf_questionnaires ' .
				'ORDER BY ordering DESC ' .
				'LIMIT 1 ';
		$db->setQuery($query);
	
		return $db->loadResult();
			
	}
	
}
