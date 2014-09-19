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

defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

/**
 * Provides a list of questionnaires
 * 
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class JFormFieldQuestionnaire extends JFormFieldList
{

	protected $type = 'questionnaire';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.0
	 */
	protected function getOptions()
	{
		$db = JFactory::getDBO();

		$query = 'SELECT id AS value, title AS text '
		. ' FROM #__pf_questionnaires '
		. ' ORDER BY title';

		$db->setQuery( $query );
		$options[] = array("value" => "", "text" => JText::_('COM_PRODUCTFINDER_LBL_SELECT_QUESTIONNAIRE'));
		$options += $db->loadAssocList('value');
		return $options;
	}
}

