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
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 * Product Finder View - Questionnaire
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since		1.0
 */
class ProductfinderViewQuestionnaire extends JViewLegacy {
	
	protected $questionnaire;
	protected $activeQuestion;
	protected $choices;
	protected $menuparams; //aka questionnaire params

	function display($tpl = null) {
		
		$choices		= $this->getModel('Choices');
		$QQModel		= $this->getModel('Questionnaire');
		$questionnaire	= $this->get('Questionnaire');
		
		$activeQuestion = $choices->getState('next_question');
		
		$this->questionnaire 	= $questionnaire;
		$this->choices 			= $choices;
		$this->activeQuestion 	= $activeQuestion;
		$this->question			= $QQModel->getQuestion($activeQuestion);
			
		if((!$aq = $choices->getState('active_question')) || ($choices->getState('started') == false)){
			if($questionnaire->params->get('show_cover_page'))
			{
				//show the cover
				$this->question = null;
				$this->activeQuestion = null;
				$choices->setState('active_question', 'start');
			}
			else
			{
				$choices->setState('started', true);
				$this->activeQuestion 	= $questionnaire->first_question;
				$this->question			= $QQModel->getQuestion($this->activeQuestion);
				$choices->setState('active_question', $this->activeQuestion);
			}
		}
		else{
			$choices->setState('active_question', $activeQuestion);
		}		

		parent::display($tpl);
	}
	
}