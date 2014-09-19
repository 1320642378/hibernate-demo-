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
defined( '_JEXEC' ) or die( 'Restricted access' );

define('PF_JOOMLA3', version_compare(JVERSION, '3', '>='));

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'/administrator/components/com_productfinder/assets/css/productfinder.css');

// Set the table directory
JTable::addIncludePath( JPATH_COMPONENT.'/tables' );

// Load component helper
require_once( JPATH_COMPONENT . '/helpers/version.php' );
require_once( JPATH_COMPONENT . '/helpers/helper.php' );

// Load component classes
require_once( JPATH_COMPONENT_ADMINISTRATOR . '/classes/pf.classes.php' );

// Include dependencies
jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Productfinder');

$task = JFactory::getApplication()->input->getCmd('task');
$controller->execute($task);
$controller->redirect();
