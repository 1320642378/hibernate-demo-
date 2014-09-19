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
 * Item Model for an Answer.
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderModelAnswer extends JModelLegacy
{
	
	public function __construct(){
		return parent::__construct();
	}

	public function getTable($name = 'Answers', $prefix = 'ProductfinderTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}	
	
	/**
	 * Saves a single question
	 * @param array $data
	 * @return boolean true|false
	 * @since 1.0
	 */
	public function save($data){
		
		if(empty($data)) {
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_NOTHING_TO_SAVE'));
			return false;
		}		
		
		$result = true;
		$table = $this->getTable();
		foreach($data as $answer){
			if(!$table->save($answer)){
				$result = false;
			}
		}
		return $result;
	}

}
