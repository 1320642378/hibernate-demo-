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
jimport('joomla.application.component.controller');

/**
 * Product Finder backend main controller
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	1.0
	 */
	protected $default_view = 'questionnaires';

	public function display($cachable = false, $urlparams = false)
	{
		// Load the submenu.
		$view = JFactory::getApplication()->input->getCmd('view', $this->default_view);
		ProductfinderHelper::addSubmenu($view);
		parent::display();
		return $this;
	}
	
	/**
	 * 
	 * Quick and dirty function to update the DB structure
	 * To invoke it set task=updateDB&ver=xx (eg 0.2) 
	 * 
	 */
	//TODO RC delete it or make it official
	public function updateDB(){
		
		$toVer = JFactory::getApplication()->input->getFloat('ver');
		if(empty($toVer)){
			JError::raiseError('500', 'Missing version');
			exit();
		}
		
		//look for update file
		$theUpdateDir = JPATH_ADMINISTRATOR . '/components/com_productfinder/sql/updates/mysql';
		
		$theUpdateFile = $theUpdateDir . '/' . $toVer . '.sql';
		if(!is_file($theUpdateFile)){
			JError::raiseError('404', 'Update file not found');
		}
		
		if($file = file_get_contents ( $theUpdateFile )){
			
			$queries = explode(';', $file);
			
			if(count($queries)){
				$db = JFactory::getDbo();
				foreach($queries as $query){
					if (empty($query)) continue;
					$db->setQuery($query);
					if(!$db->query()){
						JError::raiseError(500, $db->getErrorMsg(true));
						return false;
					}					
				}
			}
		}
		
		echo 'Done';
	}
	

}
