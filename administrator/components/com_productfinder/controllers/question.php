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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controllerform');

/**
 * Product Finder Component Controller - Question
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderControllerQuestion extends JControllerForm
{
	protected $text_prefix = 'COM_PRODUCTFINDER_QUESTION';
}