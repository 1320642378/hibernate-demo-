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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
defined('PF_JOOMLA3') or define('PF_JOOMLA3', version_compare(JVERSION, '3', '>='));

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'components/com_productfinder/assets/css/productfinder.css');

//we load component backend languagefiles, too
$language = JFactory::getLanguage();
$language->load('com_productfinder', JPATH_ADMINISTRATOR . '/components/com_productfinder');

require_once( JPATH_ADMINISTRATOR .'/components/com_productfinder/helpers/version.php' );
require_once( JPATH_ADMINISTRATOR .'/components/com_productfinder/classes/pf.classes.php' );
require_once( JPATH_COMPONENT . '/helpers/helper.php' );

// Include dependencies
jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Productfinder');

// Perform the Request task
$jinput = JFactory::getApplication()->input;
$task = $jinput->getCmd('task');
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
