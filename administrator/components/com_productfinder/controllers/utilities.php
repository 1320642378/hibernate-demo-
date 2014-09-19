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
 * Product Finder Component Controller - Utilities
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.3
 */
class ProductfinderControllerUtilities extends JControllerAdmin
{

	function __construct()
	{
		parent::__construct();
		$this->registerTask('instfalangce', 	'installFalangContentElements');
	}

	/**
	 * Proxy for getModel.
	 * @since	1.0
	 */
	public function getModel($name = 'Utilities', $prefix = 'ProductfinderModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/** (re)install Falang content elements */
	function installFalangContentElements() {
	
		$return_to = 'index.php?option=com_productfinder&view=utilities';
		$srcPathRoot = JPATH_ROOT . '/administrator/components/com_productfinder/assets/jfcontentelements/';
		$dstPathRoot = JPATH_ROOT . '/administrator/components/com_falang/contentelements/';
		
		// install Falang plugin
		$got_falang = false;
		if (is_dir(JPATH_ROOT . '/administrator/components/com_falang')) 
		{
			$got_falang = true;
			// copy  file to Falang directory
			@copy($srcPathRoot . 'translationPfquestionFilter.php', $dstPathRoot . 'translationPfquestionFilter.php');
			@copy($srcPathRoot . 'pf_answers.xml', 					$dstPathRoot . 'pf_answers.xml');
			@copy($srcPathRoot . 'pf_questionnaires.xml', 			$dstPathRoot . 'pf_questionnaires.xml');
			@copy($srcPathRoot . 'pf_questions.xml', 				$dstPathRoot . 'pf_questions.xml');
			if(PF_LEVEL)
			{
				@copy($srcPathRoot . 'pf_mailtemplates.xml', 				$dstPathRoot . 'pf_mailtemplates.xml');
			}
	
		}
	
		if ($got_falang) 
		{
			$this->setRedirect($return_to, JText::_('COM_PRODUCTFINDER_MSG_FALANG_CONTENT_ELEMENTS_INSTALLED'));
		} 
		else 
		{
			$this->setRedirect($return_to, JText::_('COM_PRODUCTFINDER_ERR_FALANG_NOT_FOUND'), 'error');
		}
	
	}
	
	/**
	 * deletes all automaticclly generated thumbnails
	 */
	function refreshThumbnails() {
	
		$return_to = 'index.php?option=com_productfinder&view=utilities';
	
		$thumbPath = JPATH_ROOT . '/media/com_productfinder/cache/images';
		$totfiles = 0;
		$totdirs = 0;
		if ($handle = opendir($thumbPath)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && $file != 'index.html') 
				{
					if (is_file($thumbPath . '/' . $file)) 
					{
						unlink($thumbPath . '/' . $file);
						$totfiles++;
					}
					elseif (is_dir($thumbPath . '/' . $file)) 
					{
						ProductfinderHelper::recursive_remove_directory($thumbPath . '/' . $file, FALSE);
						$totdirs++;
					}
				}
			}
			closedir($handle);
		}
	
		$this->setRedirect($return_to, JText::sprintf('COM_PRODUCTFINDER_MSG_DELETE_FILES_AND_DIRS', $totfiles, $totdirs));
	
	}	

}