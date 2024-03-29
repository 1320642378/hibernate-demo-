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
 * ** Rewrite of Joomla's com_content modal article form field **
 */
defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class JFormFieldModal_Article extends JFormField
{
	
	protected $type = 'Modal_Article';

	protected function getInput()
	{
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectArticle_'.$this->id.'(id, title, catid, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_'.$this->id;

		$db	= JFactory::getDBO();
		$db->setQuery(
			'SELECT title' .
			' FROM #__content' .
			' WHERE id = '.(int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) 
		{
			JError::raiseWarning(500, $error);
		}

		$empty_title = JText::_('COM_PRODUCTFINDER_SELECT_AN_ARTICLE');
		if (empty($title)) 
		{
			$title = $empty_title;
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
		
		/*
		// The active article id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}
		*/		
		
		if(PF_JOOMLA3)
		{
			// The current article display field.
			$html[] = '<span class="input-append">';
			$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
			$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_PRODUCTFINDER_CHANGE_ARTICLE').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('COM_PRODUCTFINDER_CHANGE_ARTICLE_BUTTON').'</a>';
			
			//The clear button
			$html[] = '<button id="'.$this->id.'_clear" class="btn" onclick="document.getElementById(\''.$this->id.'_id\').value=\'\';document.getElementById(\''.$this->id.'_name\').value=\''.$empty_title.'\';return false;"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
			$html[] = '</span>';
		}
		else 
		{
			// The current article display field.
			$html[] = '<div class="fltlft">';
			$html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
			$html[] = '</div>';
	
			// The article select button.
			$html[] = '<div class="button2-left">';
			$html[] = '  <div class="blank">';
			$html[] = '	<a class="modal" title="'.JText::_('COM_PRODUCTFINDER_CHANGE_ARTICLE').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_PRODUCTFINDER_CHANGE_ARTICLE_BUTTON').'</a>';
			$html[] = '  </div>';
			$html[] = '</div>';
			
			//The clear button
			$html[] = '<div class="button2-left">';
			$html[] = '  <div class="blank">';
			$html[] = ' <a href="javascript:void(0)" onclick="document.getElementById(\''.$this->id.'_id\').value=\'\';document.getElementById(\''.$this->id.'_name\').value=\''.$empty_title.'\';">'. JText::_('JCLEAR') .'</a>';
			$html[] = '  </div>';
			$html[] = '</div>';
		}
		
		// The active article id field.
		if (0 == (int)$this->value) 
		{
			$value = '';
		} else 
		{
			$value = (int)$this->value;
		}

		$class = '';
		if ($this->required) 
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}
}
