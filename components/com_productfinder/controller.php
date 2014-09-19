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
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

/**
 * Product Finder View - Frontend main controller
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since		1.0
 */
class ProductfinderController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	1.0
	 */
	protected $default_view = 'questionnaire';

	public function display($cachable = false, $urlparams = false)
	{

		if(!empty($_POST))
		{
			//validate the form (if a forms has been submitted)
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));		
		}
		$jinput = JFactory::getApplication()->input;		
		$params		= JComponentHelper::getParams('com_productfinder');
		$document 	= JFactory::getDocument();
		$document->addCustomTag('<meta name="viewport" content="width=device-width, initial-scale=1"/>' );
		
		//load CSS
		$cssver = ($params->get('load_minified_css', 1) ? '.min' : '');
		$document->addStyleSheet(JURI::base() . 'components/com_productfinder/assets/themes/default/default'.$cssver.'.css');
		$document->addStyleSheet(JURI::base() . 'components/com_productfinder/assets/themes/default/jquery.mobile.icons.min.css');
		$document->addStyleSheet(JURI::base() . 'components/com_productfinder/assets/css/jquery.mobile.structure-1.4.0'.$cssver.'.css');

		//.. and load JS
		$jsver = ($params->get('load_minified_js', 1) ? '.min' : ''); 
		if($params->get('load_jquery', 1))
		{
			if(PF_JOOMLA3) 
			{
				$document->addScript(JURI::base() . 'media/jui/js/jquery'.$jsver.'.js');		
			}
			else 
			{
				$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/jquery-1.10.2'.$jsver.'.js');
			}
		}
		$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/mobileinit.js');		
		$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/jquery.mobile-1.4.0'.$jsver.'.js');
		$document->addScript(JURI::base() . 'components/com_productfinder/assets/js/productfinder.js');	
			
		$view				= $jinput->getCmd('view', $this->default_view);
		$layout 			= $jinput->getCmd('layout', 'default');
		$action				= $jinput->getString('action');
		$choices 			= JModelLegacy::getInstance('Choices', 'ProductfinderModel', array());
		
		//NOTE this is debug code

		if($action == 'reset'){
			$choices->reset();
		}
		
		$qq 				= $choices->getState('questionnaire_id');
		$questionnaire_id 	= $jinput->getUInt('questionnaire_id');
		if(empty($qq))
		{
			//startin a questionnaire
			$tentativeQQ = $questionnaire_id;
			$choices->setState('questionnaire_id', $questionnaire_id);
		}
		else
		{
			$tentativeQQ = $qq;
			//continuing questionnaire
			if(!empty($questionnaire_id) && ($qq != $questionnaire_id))
			{
				//changing questionnaire
				$choices->reset();
				$tentativeQQ = $questionnaire_id;
			}
		}
		
		$QQModel	= JModelLegacy::getInstance('Questionnaire', 'ProductfinderModel', array());
		if($questionnaire = $QQModel->getQuestionnaire($tentativeQQ))
		{
			$QQModel->setState('questionnaire.id', $tentativeQQ);
			$choices->setState('questionnaire_id', $tentativeQQ);
			if(!$choices->getState('next_question'))
			{
				if(empty($questionnaire->first_question))
				{
					JError::raiseError('404', JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_HAS_NO_QUESTIONS'));
					return false;
				}
				$choices->setState('next_question', $questionnaire->first_question);
			}
			$questionnaire_id = $tentativeQQ;
		}
		else
		{
			$choices->setState('questionnaire_id', null);
			JError::raiseError('404', JText::_('COM_PRODUCTFINDER_ERR_QUESTIONNAIRE_NOT_FOUND'));
			return false;			
		}

		//At this point we surely have a vailid questionnaire
		if($action == JText::_('COM_PRODUCTFINDER_BTN_START'))
		{
			// questionnaire_id from request	 
			$choices->reset();
			$choices->setState('started', true);
			$choices->setState('questionnaire_id', $questionnaire_id);
			$choices->setState('active_question', 'start');
			$QQModel->hit($questionnaire_id);
		}
		
		if($action == JText::_('COM_PRODUCTFINDER_BTN_HOME'))
		{
			//reset questionnaire and go to home page
			$choices->reset();
			$this->setRedirect(JURI::base());
			$this->redirect();
			return;
		}
		
		if($action == JText::_('COM_PRODUCTFINDER_BTN_RESTART'))
		{
			//questionnaire id from state
			$questionnaire_id = $choices->getState('questionnaire_id'); 
			$choices->reset();
			$choices->setState('questionnaire_id', $questionnaire_id);
			if($questionnaire->params->get('show_cover_page'))
			{
				$choices->setState('started', false);
				$choices->setState('active_question', null);
			}
			else
			{
				$QQModel->hit($questionnaire_id);
				$choices->setState('active_question', 'start');
			}			
		}
		
		if((!$aq = $choices->getState('active_question')) || ($choices->getState('started') == false))
		{
			//showing the 'cover' page
			$view = 'questionnaire';
			$view = $this->getView($view, 'html');
			$view->setModel($QQModel, true );
			$view->setModel($choices, false );
			$view->display();
			return $this;			
		}
		
		if($action == JText::_('JPREVIOUS')){
			$prevQuestion = $choices->getPrevQuestion($questionnaire_id, $aq);
			if($prevQuestion !== false)
			{
				$choices->setState('next_question', $prevQuestion);
			}
			else{
				$choices->setState('next_question', $aq);
			}
			$view = 'questionnaire';
			$view = $this->getView($view, 'html');
			$view->setModel($QQModel, true );
			$view->setModel($choices, false );
			$view->display();
			return $this;	
		}		

		if($aq == 'start')
		{
			$choices->setState('next_question', $questionnaire->first_question);
			$view = 'questionnaire';
			$view = $this->getView($view, 'html');
			$view->setModel($QQModel, true );
			$view->setModel($choices, false );
			$view->display();
			return $this;	
		}

		if(!$nq = $choices->getState('next_question'))
		{
			//no active q and no next q, this is bad
			$choices->reset();
			JError::raiseError('500', JText::_('COM_PRODUCTFINDER_ERR_BROKEN_QUESTIONNAIRE'));
			return false;			 
		} 
		
		if($nq != 'end' && !$question = $QQModel->getQuestion($nq))
		{
			//not such a question
			$choices->reset();
			JError::raiseError('500', JText::_('COM_PRODUCTFINDER_ERR_INVALID_QUESTION_ID'));
			return false;			 
		}

		if($action == 'redo')
		{
			$choices->resetErrors();
			$choices->setState('next_question', $aq);
			
			$view = 'questionnaire';
			$view = $this->getView($view, 'html');
			$view->setModel($QQModel, true );
			$view->setModel($choices, false );
			$view->display();
			return $this;					
		}
		
		if($action == JText::_('JNEXT'))
		{
			
			if(!$nq = $choices->validate($questionnaire_id, $aq))
			{
				$choices->setState('next_question', $aq);
			}
			else
			{
				$choices->setState('next_question', $nq);
			}
			
		}
		
		if(PFGetLevel())
		{
			//got mailer
			$gotMailer 		= $questionnaire->params->get('enable_mailing');
			$mailTemplateId	= $questionnaire->params->get('mailtemplate_id');
			
			if(( ($nq == 'end' && $action == JText::_('COM_PRODUCTFINDER_BTN_SEND') && $view == 'mailer') ) && $gotMailer && $mailTemplateId )
			{
				//process and send email
				$view 		= 'mailer';
				$view 		= $this->getView($view, 'html');
				$mailer 	= JModelLegacy::getInstance('Mailer', 'ProductfinderModel', array());
				$results 	= JModelLegacy::getInstance('Results', 'ProductfinderModel', array());
				$menuParams = $choices->getState('menuparams');
				
				$items 		=  $results->getItems(); 
				if(empty($items))
				{
					$no_results_action 	= $menuParams->get('no_results_action');
					$no_results_action 	= ($no_results_action != 'global' ? $no_results_action : $params->get('no_results_action'));		
	
					if($no_results_action == '2' || $no_results_action == '3')
					{
						$res_not_found_ids 	= $menuParams->get('res_not_found_ids');
						$res_not_found_ids 	= (!empty($res_not_found_ids) ? $res_not_found_ids : $params->get('res_not_found_ids'));	
										
						if(!empty($res_not_found_ids))
						{
							$ids = preg_replace('/[^0-9,]/', '', $res_not_found_ids);
							$ids = explode(',', $ids);
							$items = $results->getItemsById($ids);
						}				
					}
				}
				
				if(($result = $mailer->sendResults($questionnaire_id, $mailTemplateId, $questionnaire->params, $choices, $items)) === true)
				{
					//Reset all session vars and go to thank page
					$mailer->resetSubmittedFields();
					$view->setLayout('thankyou');
				}
			
				$view->setModel($mailer, true );
				$view->setModel($QQModel, false );
				$view->setModel($choices, false );
				$view->display();
				return $this;	
			}		
			
			if(( ($nq == 'end' && $action == JText::_('COM_PRODUCTFINDER_BTN_EMAIL'))  || $view == 'mailer' ) && $gotMailer && $mailTemplateId )
			{
				//show results
				$view = 'mailer';
				$view 	 = $this->getView($view, 'html');
				$mailer = JModelLegacy::getInstance('Mailer', 'ProductfinderModel', array());
				$view->setModel($mailer, true );
				$view->setModel($QQModel, false );
				$view->setModel($choices, false );
				$view->display();
				return $this;	
			}
		}
				
		if($nq == 'end')
		{
			//show results
			$results = JModelLegacy::getInstance('Results', 'ProductfinderModel', array());
			$view = 'results';
			$view 	 = $this->getView($view, 'html');
			$view->setModel($results, true );
			$view->setModel($QQModel, false );
			$view->setModel($choices, false );
			$view->display();
			return $this;				
		}
		
		//if I'm here, I'm refreshing the page
		$view = 'questionnaire';
		$view = $this->getView($view, 'html');
		$view->setModel($QQModel, true );
		$view->setModel($choices, false );
		$view->display();
		return $this;	

	}

}
