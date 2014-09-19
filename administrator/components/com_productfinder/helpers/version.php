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
define('PF_LEVEL', 0); 
function PFGetLevel()
{
	return PF_LEVEL;	
}
function PFGetVersion()
{
	switch (PF_LEVEL){
		case 1:
			return 'Advanced';
			break;
		default:
			return '';
	}
}
