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

/**
 * Product Finder - Installer Script
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since		1.0
 */
class com_ProductFinderInstallerScript
{
	function install($parent){}
	 
	function uninstall($parent)	{
		$database = JFactory::getDBO();
		
		
		// uninstall Falang plugin
		$falangDir = JPATH_ROOT . '/administrator/components/com_falang/';
		if (is_dir($falangDir))
		{
			echo '<p>Falang discovered. Removing Content Elements and translations.</p>';
			// delete content elements files from Falang directory
			@unlink($falangDir . 'translationPfquestionFilter.php');
			@unlink($falangDir . 'pf_answers.xml');
			@unlink($falangDir . 'pf_questionnaires.xml');
			@unlink($falangDir . 'pf_questions.xml');
			// delete entries in Falang
			$database->setQuery( "DELETE FROM #__falang_tableinfo WHERE joomlatablename='pf_answers'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_tableinfo WHERE joomlatablename='pf_questionnaires'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_tableinfo WHERE joomlatablename='pf_questions'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_tableinfo WHERE joomlatablename='pf_mailtemplates'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_content WHERE reference_table='pf_answers'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_content WHERE reference_table='pf_questionnaires'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_content WHERE reference_table='pf_questions'");
			$database->query();
			$database->setQuery( "DELETE FROM #__falang_content WHERE reference_table='pf_mailtemplates'");
			$database->query();
		
		}
		
	}
	 
	function update($parent){}
	 
	function preflight($type, $parent){}
	 
	function postflight($type, $parent)	{
		
		if ($type == 'install') 
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$defaults = '{"num_results":8,"score_threshold":1,"primary_order":"score","primary_order_field":"order","secondary_order":"newest","no_results_action":"1","res_not_found_msg":"Sorry, there are no items to display.","res_not_found_ids":"","num_columns":"1","show_titles":"1","show_score":"0","show_images":"first","link_to_details":"1","resize_images":"1","resize_mode":"crop","img_heigth":100,"img_width":100,"image_quality":75,"debug_images":"0","missing_image_action":"0","default_image":"","load_jquery":"1","load_minified_js":"1","load_minified_css":"1"}'; // JSON format for the parameters
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('element') . ' = ' . $db->quote('com_productfinder'));
			$db->setQuery($query);
			$db->query();
		}
		
		$this->installFalangContentElements();		
	}
	
	public function installFalangContentElements(){
		echo '<p>Discovering third party compatible extensions.</p>';
		
		$srcPathRoot = JPATH_ROOT . '/administrator/components/com_productfinder/assets/jfcontentelements/';
		$dstPathRoot = JPATH_ROOT . '/administrator/components/com_falang/contentelements/';
		
		// install Falang plugin
		$got_falang = is_dir(JPATH_ROOT . '/administrator/components/com_falang') ? 1 : 0 ;
		if ($got_falang)
		{
			echo '<p>Falang detected, installing Falang content elements...</p>';
			// move file to Falang directory
			@copy($srcPathRoot . 'translationPfquestionFilter.php', $dstPathRoot . 'translationPfquestionFilter.php');
			@copy($srcPathRoot . 'pf_answers.xml', 					$dstPathRoot . 'pf_answers.xml');
			@copy($srcPathRoot . 'pf_questionnaires.xml', 			$dstPathRoot . 'pf_questionnaires.xml');
			@copy($srcPathRoot . 'pf_questions.xml', 				$dstPathRoot . 'pf_questions.xml');
			@copy($srcPathRoot . 'pf_mailtemplates.xml', 			$dstPathRoot . 'pf_mailtemplates.xml');
			echo '<p>Done.</p>';
		}		
	}
}
