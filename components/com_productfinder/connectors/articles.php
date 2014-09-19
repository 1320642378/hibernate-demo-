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
jimport('joomla.application.component.modellist');

/**
 * Model to retrieve K2 Items
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since		1.0
 */
class ProductfinderModelArticles extends JModelList
{
	
	var $name 		= 'articles';
	var $label 		= 'Articles';
	var $ref_table 	= 'content';
	var $active 	= true;
	protected static $fields = array();
	protected static $categories = array();

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public static function getFields(){
	
		if(empty(self::$fields))
		{
			self::$fields = array(
					'pfitem_id' => 'a.id',
					'pfitem_title' => 'a.title',
					'pfitem_alias' => 'a.alias',
					'pfitem_introtext' => 'a.introtext',
					'pfitem_fulltext' => 'a.`fulltext`',
					'pfitem_catid' => 'a.catid',
					'pfitem_ordering' => 'a.`ordering`',
					'pfitem_created' => 'a.created',
					'pfitem_images' => 'a.images',
					'pfitem_urls' => 'a.urls',
					'pfitem_hits' => 'a.hits',
					'pfitem_category_title' => 'c.title',
					'pfitem_category_ordering' => 'c.lft',					
			);
		}
	
		return self::$fields;
	}	

	/**
	 * Get the master query for retrieving a list of articles subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.0
	 */
	public function getListQuery()
	{
		$app = JFactory::getApplication();
		$isSite	= $app->isSite();

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$fields = self::getFields();
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				$fields['pfitem_id'] . ' AS pfitem_id, ' . 
				$fields['pfitem_title'] . ' AS pfitem_title, ' .
				$fields['pfitem_alias'] . ' AS pfitem_alias, ' .
				$fields['pfitem_introtext'] . ' AS pfitem_introtext, ' . 
				$fields['pfitem_fulltext'] . ' AS pfitem_fulltext, ' .
				'CONCAT('.$fields['pfitem_introtext'].', '.$fields['pfitem_fulltext'].') AS pfitem_html, ' .
				'CONCAT("index.php?option=com_content&view=article&id=", '.$fields['pfitem_id'].', ":", '.$fields['pfitem_alias'].', "&catid=", '.$fields['pfitem_catid'].') AS pfitem_href, ' .
				$fields['pfitem_catid'] . ' AS pfitem_catid, ' .
				$fields['pfitem_created'] . ' AS pfitem_created, ' .
				$fields['pfitem_images'] . ' AS pfitem_images, ' .
				$fields['pfitem_urls'] . ' AS pfitem_urls, '.
				$fields['pfitem_hits'] . ' AS pfitem_hits, ' .
				$fields['pfitem_category_title'] . ' AS pfitem_category_title, ' .
				$fields['pfitem_category_ordering'] . ' AS pfitem_category_ordering '					
			)
		);

		$query->from('#__content AS a');
		
		//JOIN over category table
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');		

		//if we visit from the frontend, add further filters (access, published, ...)
		if($isSite)
		{
			// Filter by access level.
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
	
			// Filter by published state
			$query->where('a.state = 1 ');
	
			// Filter by start and end dates.
			$nullDate	= $db->Quote($db->getNullDate());
			$nowDate	= $db->Quote(JFactory::getDate()->toSql());
	
			$query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')');
			$query->where('(a.publish_down = '.$nullDate.' OR a.publish_down >= '.$nowDate.')');
		}
		
		return $query;
	}
	
	public function addCategoryFilter(&$query, $categoryIds = null){
		
		if(empty($categoryIds)) return $query;

		$app = JFactory::getApplication();
		if($app->isSite())
		{		
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());		
			$query->where('c.access IN ('.$groups.')');
		}
		
		// Filter by a single or group of categories.
		$baselevel = 1;
		if (is_numeric($categoryIds)) 
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryIds);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= '.(int) $lft);
			$query->where('c.rgt <= '.(int) $rgt);
		}
		elseif (is_array($categoryIds)) 
		{
			JArrayHelper::toInteger($categoryIds);
			$categoryIds = implode(',', $categoryIds);
			$query->where('a.catid IN ('.$categoryIds.')');
		}		
		
		return $query;
	}

	/**
	 * Method to get a list of articles.
	 * Overriden to inject convert the attribs field into a JParameter object.
	 *
	 * @return	mixed	An array of objects on success, false on failure.
	 * @since	1.0
	 */
	public function getItems()
	{
		$items	= parent::getItems();
		return $items;
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}
	
	public static function getCategories()
	{
		
		if (empty(self::$categories))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
		
			$query->select('a.id, a.title, a.level, a.parent_id');
			$query->from('#__categories AS a');
			$query->where('a.parent_id > 0');
		
			// Filter on extension.
			$query->where('extension = "com_content"');
			$query->where('a.published IN (1,0)');
		
			$query->order('a.lft');
		
			$db->setQuery($query);
			$items = $db->loadObjectList();
		
			foreach ($items as &$item)
			{
				$repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
				$item->title = str_repeat('- ', $repeat) . $item->title;
				self::$categories[] = JHtml::_('select.option', $item->id, $item->title);
			}

		}
		return self::$categories;		
	}
	
	public static function getImages(&$item, $mode = 'first'){

		switch($mode)
		{
			case 'intro':
				$registry = new JRegistry;
				$registry->loadString($item->pfitem_images);
				$images = $registry->toArray();
				$item->pfitem_images = $images;
				$item->pfitem_image = $images['image_intro'];
				break;
			case 'full':
				$registry = new JRegistry;
				$registry->loadString($item->pfitem_images);
				$images = $registry->toArray();
				$item->pfitem_images = $images;
				$item->pfitem_image = $images['image_fulltext'];
				break;
			case 'first':
				//need to clear images field because we are replacing it
				// with images extracted from text
				$item->pfitem_image = $item->pfitem_images = null;
				if(ProductFinderImagesHelper::stripImages($item))
				{
					$item->pfitem_image = isset($item->pfitem_images[0]) ? $item->pfitem_images[0] : '';
				}
				break;
			case 'last':
				$item->pfitem_image = $item->pfitem_images = null;
				if(ProductFinderImagesHelper::stripImages($item))
				{
					if(is_array($item->pfitem_images) && ($numImages = count($item->pfitem_images)))
					{
						$item->pfitem_image = $item->pfitem_images[$numImages - 1 ];
					}
				}
				break;
			default:
				break;
		}
	}
	
	public static function getPrimaryOrder($primary_order_field, $primary_order){
	
		$order = array();
	
		switch($primary_order_field)
		{
			case 'title':
				$order_field = 'category_title ASC';
				break;
			case 'order':
			default:
				$order_field = 'category_ordering ASC';
				break;
		}
	
		switch($primary_order)
		{
			case 'score_cat':
				$order[] = 'score DESC';
				$order[] = $order_field;
				break;
			case 'cat_score':
				$order[] = $order_field;
				$order[] = 'score DESC';
				break;
			case 'score':
			default:
				$order[] = 'score DESC';
				break;
		}
	
		return implode(',', $order);
	
	}
	
	public static function getSecondaryOrder($seconday_order){
	
		switch($seconday_order)
		{
			case 'title':
				$order = 'pfitem_title ASC';
				break;
			case 'alias':
				$order= 'pfitem_alias ASC';
				break;
			default:
			case 'newest':
				$order = 'pfitem_created DESC';
				break;
			case 'oldest':
				$order = 'pfitem_created ASC';
				break;
			case 'hits':
				$order = 'pfitem_hits DESC';
				break;
		}
		return $order;
	}	
}
