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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');

/**
 * Product Finder Component Controller - Answers
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderControllerAnswers extends JControllerAdmin
{

	function __construct()
	{
		parent::__construct();
	}
	
	public function getModel($name = 'Answers', $prefix = 'ProductfinderModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 * Deletes on or more answers/separators
	 * 
	 * @return boolean true on success 
	 * @see JControllerAdmin::reorder()
	 * @since 1.0
	 */
	public function delete(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		
		if(empty($jform) || ! isset($jform['id']) || ! ((int) $jform['id'])){
			$redirect = 'index.php?option=com_productfinder&view=questions';
			$this->setRedirect($redirect, JText::_('COM_PRODUCTFINDER_ERR_MISSING_QUESTION_ID'), 'error');
			return;			
		}
		
		$question_id = $jform['id'];
		$redirect = 'index.php?option=com_productfinder&view=question&layout=edit&id=' . $question_id ;
		
		$model = $this->getModel();
		$ids = $jinput->get('ans_sel', array(), 'array');
		if(!$model->delete($ids)){
			$this->setRedirect($redirect, $model->getError(), 'error');
			return;
		}

		$this->setRedirect($redirect, JText::_('COM_PRODUCTFINDER_MSG_ANSWERS_DELETED'));
		return true;
	}
		
	/**
	 * Overload of standard reorder function to order answers
	 * 
	 * @return boolean true on success
	 * @see JControllerAdmin::reorder()
	 * @since 1.0
	 */
	public function reorder(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));		
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		
		if(empty($jform) || ! isset($jform['id']) || ! ((int) $jform['id'])){
			$redirect = 'index.php?option=com_productfinder&view=questions';
			$this->setRedirect($redirect, JText::_('COM_PRODUCTFINDER_ERR_MISSING_QUESTION_ID'), 'error');
			return;			
		}
		$question_id = $jform['id'];
		$redirect = 'index.php?option=com_productfinder&view=question&layout=edit&id=' . $question_id ;	
		
		$ids 	= $jinput->get('ans_sel', array(), 'array');		
		$inc	= ($this->getTask() == 'orderup') ? -1 : +1;
		
		$model 	= $this->getModel();
		$return = $model->reorder($ids, $inc);
		if($return === false){
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());
			$this->setRedirect($redirect, $message, 'error');
			return;
		}		
		$this->setRedirect($redirect);
		return true;
	}
	
	/**
	 * Method to save the submitted ordering values for answers.
	 * 
	 * @return  boolean  True on success
	 * @see JControllerAdmin::saveorder()
	 * @since 1.0
	 */
	public function saveorder()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		
		if(empty($jform) || ! isset($jform['id']) || ! ((int) $jform['id'])){
			$redirect = 'index.php?option=com_productfinder&view=questions';
			$this->setRedirect($redirect, JText::_('COM_PRODUCTFINDER_ERR_MISSING_QUESTION_ID'), 'error');
			return;			
		}
		$question_id = $jform['id'];
		$redirect = 'index.php?option=com_productfinder&view=question&layout=edit&id=' . $question_id ;	
		
		$ids 	= $jinput->get('ans_sel', array(), 'array');		
		$order 	= $jinput->get('ans_order', array(), 'array');		

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($ids, $order);

		if ($return === false)
		{
			// Reorder failed
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());
			$this->setRedirect($redirect, $message, 'error');
			return false;
		}
		else
		{
			// Reorder succeeded.
			$this->setRedirect($redirect);
			return true;
		}
	}	
}