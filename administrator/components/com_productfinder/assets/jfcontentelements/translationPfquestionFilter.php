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

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationPfquestionFilter extends translationFilter
{
	function translationPfquestionFilter ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="pfquestion";
		$this->filterField = $contentElement->getFilter("pfquestion");
		parent::translationFilter($contentElement);

	}

	/**
 * Creates vm_pollname filter
 *
 * @param unknown_type $filtertype
 * @param unknown_type $contentElement
 * @return unknown
 */
	function _createfilterHTML(){
		$database = JFactory::getDBO();

		if (!$this->filterField) return "";

		$fieldnameOptions=array();
		$fieldnameOptions[] = JHTML::_('select.option', '-1', JText::_('JALL') );

		$sql = 'SELECT DISTINCT q.id, q.question AS label '
      		. 'FROM #__pf_questions as q, #__'.$this->tableName.' as a '
			. 'WHERE a.'.$this->filterField.' = q.id ORDER BY q.question';

		$database->setQuery($sql);
		$fields = $database->loadObjectList();
		$catcount=0;
		foreach($fields as $field){
			$fieldnameOptions[] = JHTML::_('select.option', $field->id, $field->label);
			$catcount++;
		}
		
		$fieldnameList=array();
		$fieldnameList["title"]= 'Question';
		if (FALANG_J30) 
		{
			$fieldnameList["position"] = 'sidebar';
			$fieldnameList["name"]= 'pfquestion_filter_value';
			$fieldnameList["type"]= 'pfquestion';
			$fieldnameList["options"] = $fieldnameOptions;
		} 
		$fieldnameList["html"] = JHTML::_('select.genericlist', $fieldnameOptions, 'pfquestion_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );
		
		return $fieldnameList;
	}

}
