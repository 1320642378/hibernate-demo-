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
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of questionnaires.
 *
 * @package		Product Finder
 * @subpackage	administrator
 * @since 1.0
 */
class ProductfinderModelAnswers extends JModelList
{
	
	/**
	 * Gets reorder condition
	 * 
	 * @see JModelList::getReorderConditions
	 * @param unknown $table
	 * @return multitype:string
	 * @since 1.0
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'question_id = '.(int) $table->question_id;
		return $condition;
	}

	public function getTable($name = 'Answers', $prefix = 'ProductfinderTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 * @return  mixed
	 * @since   1.0
	 */
	public function saveorder($pks = null, $order = null)
	{

		$table = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_('COM_PRODUCTFINDER_ERR_NO_ANSWERS_SELECTED'));
		}

		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			if ($table->ordering != $order[$i]){
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to adjust the ordering of a row.
	 *
	 * @param   integer  $pks    The ID of the primary key to move.
	 * @param   integer  $delta  Increment, usually +1 or -1
	 * @return  mixed  False on failure or error, true on success, null if the $pk is empty (no items selected).
	 * @since   1.0
	 */
	public function reorder($pks, $delta = 0){

		$table = $this->getTable();
		$pks = (array) $pks;
		$result = true;

		$allowed = true;

		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{

				$where = array();
				$where = $this->getReorderConditions($table);

				if (!$table->move($delta, $where))
				{
					$this->setError($table->getError());
					unset($pks[$i]);
					$result = false;
				}

			}
			else
			{
				$this->setError($table->getError());
				unset($pks[$i]);
				$result = false;
			}
		}

		if ($allowed === false && empty($pks))
		{
			$result = null;
		}

		// Clear the component's cache
		if ($result == true)
		{
			$this->cleanCache();
		}

		return $result;
	}

	/**
	 * Deletes one or more answers
	 * 
	 * @param unknown $toDel
	 * @return boolean true|false
	 * @since 1.0
	 */
	public function delete($toDel){

		$table = $this->getTable();
	
		if(!$table->delete($toDel)){
			$this->setError(JText::_('COM_PRODUCTFINDER_ERR_CANT_DELETE_ANSWERS'));
			return false;
		}
		return true;
	}

}