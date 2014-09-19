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
 * Table object for Question
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class ProductfinderTableQuestion extends JTable
{

	function __construct(&$db)  {
		parent::__construct('#__pf_questions', 'id', $db);
	}
	
	function bind( $array, $ignore='' )
	{
		$result = parent::bind( $array );
		// cast properties
		$this->id	= (int) $this->id;
		if($this->max_answers < $this->min_answers) $this->max_answers = $this->min_answers;
		if($this->max_answers < 1) $this->max_answers = 1;

		return $result;
	}	

}