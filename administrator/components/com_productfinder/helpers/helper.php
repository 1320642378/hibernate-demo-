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
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Product Finder Backend Helper
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderHelper
{
	/**
	 * Adds the submenu
	 * 
	 * @param string $vName view name
	 * @since 1.0
	 */
	public static function addSubmenu($vName)
	{
			
		$subMenuHelper = PF_JOOMLA3 ? 'JHtmlSidebar' : 'JSubMenuHelper';
		
		call_user_func(array($subMenuHelper, 'addEntry'),
			JText::_('COM_PRODUCTFINDER_SUBMENU_QUESTIONNAIRES'),
			'index.php?option=com_productfinder&view=questionnaires',
			$vName == 'questionnaires'
		);

		call_user_func(array($subMenuHelper, 'addEntry'),
			JText::_('COM_PRODUCTFINDER_SUBMENU_QUESTIONS'),
			'index.php?option=com_productfinder&view=questions',
			$vName == 'questions'
		);
		
		call_user_func(array($subMenuHelper, 'addEntry'),
			JText::_('COM_PRODUCTFINDER_SUBMENU_RULES'),
			'index.php?option=com_productfinder&view=rules',
			$vName == 'rules'
		);
		
		if(PFGetLevel())
		{
			call_user_func(array($subMenuHelper, 'addEntry'),
				JText::_('COM_PRODUCTFINDER_SUBMENU_MAILER'),
				'index.php?option=com_productfinder&view=mailtemplates',
				$vName == 'mailtemplates'
			);

			call_user_func(array($subMenuHelper, 'addEntry'),
				JText::_('COM_PRODUCTFINDER_SUBMENU_LOGS'),
				'index.php?option=com_productfinder&view=logs',
				$vName == 'logs'
			);
		}

		call_user_func(array($subMenuHelper, 'addEntry'),
			JText::_('COM_PRODUCTFINDER_SUBMENU_UTILITIES'),
			'index.php?option=com_productfinder&view=utilities',
			$vName == 'utilities'
		);

		call_user_func(array($subMenuHelper, 'addEntry'),
			JText::_('COM_PRODUCTFINDER_SUBMENU_ABOUT'),
			'index.php?option=com_productfinder&view=about',
			$vName == 'about'
		);
		
	}

	/**
	 * Gets the action
	 * 
	 * @param integer $messageId
	 * @return JObject
	 * @since 1.0
	 */
	public static function getActions($messageId = 0)
	{
		jimport('joomla.access.access');
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_productfinder';
		}

		$actions = JAccess::getActions('com_productfinder', 'component');

		foreach ($actions as $action) {
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
	
	/**
	 * Build a list of questionnaires
	 *
	 * @return	object array A list of questionnaires to populate filters options
	 * @since	1.0
	 */
	static public function getQuestionnairesList() {
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('q.id AS value, q.title AS text');
		$query->from('#__pf_questionnaires AS q');
		$query->where('q.state = 1');
		$query->order('q.title');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}
		
	/**
	 * Build a list of questionnaires
	 *
	 * @param	integer $questionnaireId questionnaire id
	 * @return	object array A list of questionnaires to populate filters options
	 * @since	1.0
	 */
	static public function getQuestionnaireQuestionsList($questionnaireId) {
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('q.id AS value, q.question AS text');
		$query->from('#__pf_questions AS q');
		$query->where('q.questionnaire_id = ' . (int) $questionnaireId);
		$query->where('q.state > 0');
		$query->order('q.question');

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObjectList();
	}	
	
	/**
	 * Builds a list of answers
	 *
	 * @param 	int question id
	 * @param 	bool $separator return also separators; default true 
	 * @return	array of objects A list of answers or false
	 * @since	1.0
	 */
	static public function getAnswers($questionId, $separators = true) {
		
		if(empty($questionId)) return false;
		
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('a.*');
		$query->from('#__pf_answers AS a');
		$query->where('a.question_id = ' . (int) $questionId);
		if(!$separators){
			$query->where('a.is_separator = 0');
		}
		$query->order('a.ordering, a.value');

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObjectList();
	}		
	
	/**
	 * Builds a list connectors
	 *
	 * @return	array of objects A list of connectors or false
	 * @since	1.2
	 */	
	static public function getConnectorsList(){
		
		if(!PF_LEVEL) return false;
		
		$connectors =  PFConnectors::getInstance()->getConnectors();
		$result = array();
		foreach($connectors as $connector)
		{
			$conn = new stdClass();
			$conn->value = $connector->name;
			$conn->text = $connector->label;
			$result[] = $conn;
		}
		return $result;
	}
	
	/**
	 * Returns the icon for the rule
	 * 
	 * @param string $status
	 * @return string HTML img tag
	 * @since 1.0
	 */
	static function ruleImg($status = null){
		
		$dir = JURI::base() . 'components/com_productfinder/assets/images/16/';
		switch($status){
			case '1':
				return '<img alt="'.JText::_('COM_PRODUCTFINDER_LBL_RECOMMENDED').'" src="'.$dir.'recommended.png"/>';
				break;
			case 'NOT':
				return '<img alt="'.JText::_('COM_PRODUCTFINDER_LBL_DISALLOWED').'" src="'.$dir.'disallowed.png"/>';
				break;
			case 'REQ':
				return '<img alt="'.JText::_('COM_PRODUCTFINDER_LBL_REQUIRED').'" src="'.$dir.'required.png"/>';
				break;
			default :
				return '<img alt="'.JText::_('COM_PRODUCTFINDER_LBL_IRRELEVANT').'" src="'.$dir.'disabled.png"/>';
				break;
				
		}
	}	
	
	/**
	 * Removes records from answers / questions / rules 
	 * whose questionnaires / questions / answers /contents 
	 * does no longer exist
	 * 
	 * NOTE: it should never happen, but when/if it happens we 
	 * prefer to keep our tables lean and clean 
	 * 
	 * @return bool true / false
	 * @since 1.0
	 */
	static public function pruneOrphanedRecords(){
		
		$db = JFactory::getDbo();
		
		//delete questions with missing questionaire
		$query = 'DELETE FROM #__pf_questions AS q LEFT JOIN #__pf_questionnaires AS qq ' .
				'ON q.questionnaire_id = qq.id WHERE qq.id IS NULL' ;
		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}
									
		//delete answers with missing questions
		$query = 'DELETE #__pf_answers AS a '
				. 'FROM #__pf_answers AS a LEFT JOIN #__pf_questions AS q ON a.question_id = q.id ' 
				. 'WHERE q.id IS NULL ';	
		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}							
							
		//delete rules whose referenced content is missing
		if(PF_LEVEL)
		{
			$connectors = PFConnectors::getInstance()->getConnectors();
		}
		else 
		{
			$connectors[] = PFConnectors::getInstance()->getDefaultConnector();
		}
		
		foreach($connectors as $connector)
		{
			$query = 'DELETE #__pf_rules AS r '
					. 'FROM #__pf_rules AS r LEFT JOIN #__'.$connector->ref_table.' AS c ON r.content_id = c.id AND r.ref_table = "'.$connector->ref_table.'" ' 
					. 'WHERE c.id IS NULL ';
			$db->setQuery($query);
			if(!$db->query()){
				JError::raiseError(500, $db->getErrorMsg(true));
				return false;
			}
		}	

		//delete rules with missing answers
		$query = 'DELETE #__pf_rules AS r '
				. 'FROM #__pf_rules AS r LEFT JOIN #__pf_answers AS a ON r.answer_id = a.id ' 
				. 'WHERE a.id IS NULL ';	
		$db->setQuery($query);
		if(!$db->query()){
			JError::raiseError(500, $db->getErrorMsg(true));
			return false;
		}			
		
		return true;
	}
	
	/**
	 * recursive_remove_directory( directory to delete, empty )
	 * expects path to directory and optional TRUE / FALSE to empty
	 * of course PHP has to have the rights to delete the directory
	 * you specify and all files and folders inside the directory
	 * @param string $directory directory to be emptied  /removed
	 * @param bool $empty if tru, the directory is emptied
	 * examples
	 * to use this function to totally remove a directory, write:
	 * recursive_remove_directory('path/to/directory/to/delete');
	 *
	 * to use this function to empty a directory, write:
	 * recursive_remove_directory('path/to/full_directory',TRUE);
	 * @since 1.3
	 */
	
	public static function recursive_remove_directory($directory, $empty=FALSE)
	{
		// if the path has a slash at the end we remove it here
		if(substr($directory,-1) == '/')
		{
			$directory = substr($directory,0,-1);
		}
	
		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory))
		{
			// ... we return false and exit the function
			return FALSE;
	
			// ... if the path is not readable
		}elseif(!is_readable($directory))
		{
			// ... we return false and exit the function
			return FALSE;
	
			// ... else if the path is readable
		}else{
	
			// we open the directory
			$handle = opendir($directory);
	
			// and scan through the items inside
			while (FALSE !== ($item = readdir($handle)))
			{
				// if the filepointer is not the current directory
				// or the parent directory
				if($item != '.' && $item != '..')
				{
					// we build the new path to delete
					$path = $directory.'/'.$item;
	
					// if the new path is a directory
					if(is_dir($path))
					{
						// we call this function with the new path
						self::recursive_remove_directory($path);
	
						// if the new path is a file
					}else{
						// we remove the file
						unlink($path);
					}
				}
			}
			// close the directory
			closedir($handle);
	
			// if the option to empty is not set to true
			if($empty == FALSE)
			{
				// try to delete the now empty directory
				if(!rmdir($directory))
				{
					// return false if not possible
					return FALSE;
				}
			}
			// return success
			return TRUE;
		}
	}	
	
}