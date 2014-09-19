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

jimport('joomla.application.component.controlleradmin');
/**
 * Product Finder Component Controller - About
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderControllerAbout extends JControllerAdmin
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Proxy for getModel.
	 * @since	1.0
	 */
	public function getModel($name = 'About', $prefix = 'ProductfinderModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

}