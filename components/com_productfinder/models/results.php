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
jimport('joomla.application.component.modellist');

/**
 * Model for results
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since 1.0
 */
class ProductfinderModelResults extends JModelList
{
	var $text_prefix = 'results';
	var $params;
	var $menuparams;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
			);
		}
		$this->params = JComponentHelper::getParams('com_productfinder');
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$jinput = JFactory::getApplication()->input;

		// Adjust the context to support modal layouts.
		if ($layout = $jinput->getCmd('layout')) {
			$this->context .= '.'.$layout;
		}
		parent::populateState();
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.0
	 */
	protected function getListQuery($ids = null)
	{

		$choices = JModelLegacy::getInstance('Choices', 'ProductfinderModel');
		$this->menuparams = $choices->getState('menuparams');
		
		if(PF_LEVEL)
		{
			$connectorName = $this->menuparams->get('connector_name', 'articles');
			$connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else 
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		
		$query = $connector->getListQuery();
		
		$categoryIds = $this->menuparams->get('category_filter');
		if(!empty($categoryIds))
		{
			$query = $connector->addCategoryFilter($query, $categoryIds);
		}

		if(!empty($ids) && is_array($ids)){
			//filtering by ids
			$query->where('a.id IN(' . implode(',', $ids) . ')');
		}
		else{
			//filtering by choices
			$query = $choices->filterQuery($query, $connector);
		}
		return $query;
	}
	
	/**
	 * Overloaded _getList() method 
	 * 
	 * @see JModel::_getList()
	 * @since 1.0
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0){
			
		$limit 	= $this->menuparams->get('num_results');
		$limit 	= (!empty($limit) ? $limit : $this->params->get('num_results', 0));		
		return parent::_getList($query, $limitstart, $limit);
		
	}
	
	/**
	 * Method to get a list of results.
	 * Overridden to add a check for access levels.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.0
	 */
	public function getItems($ids = null)
	{
		$choices = JModelLegacy::getInstance('Choices', 'ProductfinderModel');
		$this->menuparams = $choices->getState('menuparams');
		if(PF_LEVEL)
		{
			$connectorName = $this->menuparams->get('connector_name', 'articles');
			$connector = PFConnectors::getInstance()->getConnector($connectorName);
		}
		else
		{
			$connector = PFConnectors::getInstance()->getDefaultConnector();
		}
		
		if(!empty($ids) && is_array($ids)){
			$query = $this->getListQuery($ids);
			$items = $this->_getList($query);			
		}
		else{
			$items	= parent::getItems();
		}
		
		$show_images 	= $this->menuparams->get('show_images');
		$show_images 	= ($show_images != 'global' ? $show_images : $this->params->get('show_images'));
		
		$resize_images 	= $this->menuparams->get('resize_images');
		$resize_images 	= ($resize_images != 'global' ? $resize_images : $this->params->get('resize_images'));
		
		if($resize_images)
		{
			$img_heigth 	= $this->menuparams->get('img_heigth');
			$img_heigth 	= (($img_heigth != -1) ? $img_heigth : $this->params->get('img_heigth'));
			$img_width 		= $this->menuparams->get('img_width');
			$img_width 		= (($img_width != -1) ? $img_width : $this->params->get('img_width'));
			$resize_mode 	= $this->menuparams->get('resize_mode');
			$resize_mode 	= ($resize_mode != 'global' ? $resize_mode : $this->params->get('resize_mode'));			
	
			$img_quality 	= $this->menuparams->get('img_quality');
			$img_quality 	= (!empty($img_quality) ? $img_quality : $this->params->get('img_quality'));
			$debug_images 	= $this->menuparams->get('debug_images');
			$debug_images 	= ($debug_images != 'global' ? $debug_images : $this->params->get('debug_images'));			
			
			$dst_thumb_dir	= 'media' . '/com_productfinder/cache/images';

		}
		
		$missing_image_action 	= $this->menuparams->get('missing_image_action');
		$missing_image_action 	= ($missing_image_action != 'global' ? $missing_image_action : $this->params->get('missing_image_action'));			
		$default_image 	= $this->menuparams->get('default_image');
		$default_image 	= (!empty($default_image) ? $default_image : $this->params->get('default_image'));	
		
		if(is_array($items) && $show_images)
		{
			$max_images_width = 0;
			$max_images_height = 0;
			
			foreach($items as $item)
			{
				$item->pfitem_image = null;
				if(isset($item->pfitem_images))
				{
					$connector::getImages($item, $show_images);
				}
				
				if(empty($item->pfitem_image) && $missing_image_action && !empty($default_image))
				{
					$item->pfitem_image = $default_image;
				}
				
				//image "post processing"
				if($item->pfitem_image) 
				{
					$title	= htmlspecialchars($item->pfitem_title);		
					$extra 	= 'alt="'.$title.'" title="'.$title.'" class="pf-res-image"';	
					
					if($resize_images)
					{
						$img =  ProductfinderImagesHelper::getThumb($item->pfitem_image, '', $img_width, $img_heigth, $extra, $resize_mode, $img_quality, $dst_thumb_dir, $debug_images);
						$max_images_width = ($img_width > $max_images_width ? $img_width : $max_images_width);
						$max_images_height = ($img_heigth > $max_images_height ? $img_heigth : $max_images_height); 
						$item->pfitem_image = $img;
					}
					else
					{
						$img_info = getimagesize($item->pfitem_image);
						$size = '';
						if($img_info)
						{
							$orig_width = $img_info[0];
							$orig_height = $img_info[1];
							$max_images_width = ($orig_width > $max_images_width ? $orig_width : $max_images_width);
							$max_images_height = ($orig_height > $max_images_height ? $orig_height : $max_images_height);							
							$size = 'width="'.$orig_width.'" height="'.$orig_height.'"';
						}
						$item->pfitem_image = '<img src="' .$item->pfitem_image. '" '.$size.' '.$extra.'/>';
					}
				}
			}
			$this->setState('max_images_width', $max_images_width);
			$this->setState('max_images_height', $max_images_height);
		}

		return $items;
	}
	
	/**
	 * Returns content items by id
	 * 
	 * @param string $ids
	 * @return array of content items object
	 * @since 1.0
	 */
	public function getItemsById($ids = null){
		if(empty($ids)) return false;
		
		return $this->getItems($ids);
	}
	
}
