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

require_once(JPATH_ROOT . '/administrator/components/com_productfinder/helpers/version.php');
require_once(JPATH_ROOT . '/administrator/components/com_productfinder/classes/pf.classes.php');
require_once(JPATH_ROOT . '/administrator/components/com_productfinder/helpers/helper.php');

/**
 * Provides a list of categories for the given connector
 * 
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class JFormFieldPFCategory extends JFormFieldList
{

	protected $type = 'pfcategory';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.0
	 */
	protected function getOptions()
	{
		$connectorField = $this->form->getField('connector_name', 'params');
		$connectorName = $connectorField->__get('value', 'articles');
		$connector = PFConnectors::getInstance()->getConnector($connectorName);
		$categories = $connector::getCategories();
		return $categories;
	}
}

