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
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Product Finder Frontend Helper
 *
 * @package		Product Finder
 * @subpackage	frontend
 * @since 1.0
 */
class ProductfinderHelper
{
	/**
	 * Returns one more articles
	 * 
	 * @param mixed $article_id could be array or single id
	 * @return array of objects
	 * @since 1.0
	 */
	public static function getArticleById($article_ids){
	
		if(empty($article_ids)) return false;
		
		if(!is_array($article_ids)){
			$article_ids = array($article_ids);	
		}
		
		$article_ids = array_filter($article_ids, 'is_numeric');
		$article_ids = array_map('self::add_quote', $article_ids);
		
		if(empty($article_ids)) return false;
		
		$database 	= JFactory::getDBO();
		$user		= JFactory::getUser();
		$valid_groups	= implode(',',$user->getAuthorisedViewLevels());	
	
		$jnow		= JFactory::getDate();
		$now		= $jnow->toMySQL();
		$nullDate	= $database->getNullDate();
	
		$selstr = 'SELECT DISTINCT "content" AS content_element, c.id AS id, c.title AS title, '
			. 'c.alias AS title_alias, c.catid AS catid , '
			. 'c.introtext AS introtext, c.`fulltext` AS `fulltext`, CONCAT(c.introtext, c.`fulltext`) as html, ' 
			. 'CONCAT("index.php?option=com_content&view=article", "&catid=", c.catid, ":", cat.alias, "&id=", c.id, ":", c.alias) AS href, '
			. 'c.images AS images, c.ordering AS ordering, c.publish_up AS publish_up, c.publish_down AS publish_down, '
			. 'cat.title AS category, cat.alias AS cat_alias, cat.lft AS cat_ordering, '
			. 'c.created, c.images, c.urls, c.hits, "" AS extra ';
		
		$fromstr = "FROM #__content AS c LEFT JOIN #__categories AS cat ON(c.catid = cat.id) \n"  ;
		
		$where = "WHERE c.id IN( " . implode(',', $article_ids) .')';
		
		$where .= ' AND ( c.state = 1' .
				' AND ( c.publish_up = '.$database->Quote($nullDate).' OR c.publish_up <= '.$database->Quote($now).' )' .
				' AND ( c.publish_down = '.$database->Quote($nullDate).' OR c.publish_down >= '.$database->Quote($now).' )' .
				' AND c.access IN(' . $valid_groups .')';
		$where .= '   ) ';
	
	
		$query = $selstr . $fromstr . $where;
	
		$database->setQuery($query);
		
		$result = $database->loadObjectList();

		return $result;
	}

	private static function add_quote($n){
		return "'$n'";
	}	
}
class ProductfinderImagesHelper{
	
	/**
	 * This functions strips images from HTML and puts them
	 * into the 'images' field
	 *
	 * @param row
	 * @returns row
	 * @since 1.0
	 */
	public static function stripImages(&$row){
	
		if(empty($row)){return ;}
	
		//we return an array or empty
		if(!empty($row->pfitem_images)){
			$row->pfitem_images = array();
		}
	
		preg_match_all("/<img[^>]*>/i", $row->pfitem_introtext . $row->pfitem_fulltext, $matches);
		if (!empty($matches[0]))
		{
			foreach ($matches[0] as $txtimg)
			{
				/* strip the img, gonna append later */
				$row->pfitem_introtext = str_replace($txtimg, '',$row->pfitem_introtext);
				$txtimg 		= urldecode($txtimg); // in case it's an URL
	
				if (strpos($txtimg, "http")!== false) {
					// image is remote
					if(preg_match('#src=[\"\']{1}(.*?)["\']#i',$txtimg,$imgsrcs) )
					{
						$row->pfitem_images[] = $imgsrcs[1];
					}
	
				}
				else
				{
					preg_match("#src=\"([^%?*|\"<>]+)\"#i",$txtimg,$imgsrcs);
					$row->pfitem_images[] = $imgsrcs[1];
				}
	
			}
		}
		return $row;
	}
	
	/**
	 * This function returns the thumbnail preview.
	 * If it does not exists or if it is not of the right size,
	 * a new one is generated.
	 *
	 * @param $file image filename (without path)
	 * @param $image_dir images directory respective of site url e.g. images/stories , no trailing slash
	 * @param $width, $heigth size of the thumbnail
	 * @param $extra extra attributes to be given to the returned HTML tag (class, title , alt)
	 * @param string $resize_mode adapt / keep aspect / crop
	 * @param integer $quality quality of the image (0 worst - 100 best)
	 * @param string $thumb_dir directory to save the thumbnails into. Default: component/images
	 * @return HTML tag or false if errors occurred
	 * @since 1.0
	 */
	
	public static function getThumb($file, $image_dir = '',  &$width, &$heigth, $extra="", $resize_mode = 'crop', $quality = '75', $thumb_dir=null, $debug=false)
	{
		$found = false;
		$is_URL = false;
	
		if(empty($file)){return false;}
	
		if(empty($thumb_dir))
		{
			$thumb_dir = 'media/com_productfinder/cache/images';
		}
	
		// define some directories
		$dst_dir = preg_replace("#^(http:)?//?#", "", $file);
	
		$thumb_subdir		= preg_replace('/^\./','',dirname($dst_dir));
		$images_base_path	= ($image_dir ? JPATH_ROOT . '/'. $image_dir : JPATH_ROOT);
	
		if(strpos($file, 'http') === 0)
		{
			$image_path 	= $file;
			$is_URL			= true;
		}
		else{
			$image_path 	= $images_base_path . '/' . $file;
		}
		$thumb_base_path	= JPATH_ROOT. '/' .$thumb_dir. '/' . $thumb_subdir;
		$thumb_base_url		= $thumb_dir .'/'. $thumb_subdir;
		$ext 				= substr(strrchr(basename($file), '.'), 1);
	
		/* make sure directory exists, otherwise create it */
		if(!is_writable( "$thumb_base_path" ))
		{
			if(! mkdir("$thumb_base_path", 0755, true) )
			{
				if($debug) 	{
					return '<span class="error">'.JText::_('COM_PRODUCTFINDER_ERR_CANT_WRITE_TO_DIR'). $thumb_base_path.'</span>';
				}
				return false;
			}
		}
	
		$thumb_name	= str_replace('.'.$ext, '_thumb.'.$ext, basename($file));
		$thumb_path = $thumb_base_path.'/'.$thumb_name;
		$thumb_url	= str_replace('//', '/', $thumb_base_url .'/'.$thumb_name);
	
		$image = '';
	
		if (file_exists($thumb_path))
		{
			// compare found thumb size with teorethical size, for chaching purposes
			$thumb_size = '';
			$twx = $thy = 0;
			if (function_exists( 'getimagesize' ))
			{
				$thumb_size = getimagesize( $thumb_path );
				if (is_array( $thumb_size ))
				{
					$twx = $thumb_size[0];
					$thy = $thumb_size[1];
					$size = 'width="'.$twx.'" height="'.$thy.'"';
				}
	
				$image_size = '';
				$wx = $hy = 0;
				$image_path = self::escapeFileName($image_path);
				$image_size = getimagesize( $image_path );
				if (is_array( $image_size ))
				{
					$wx = $image_size[0];
					$hy = $image_size[1];
				}
			}
				
			if($resize_mode == 'aspect')
			{
				self::calcThumbSize($wx, $hy, $width, $heigth);
			}
			else
			{
				//no aspect and only one dimension, resize to square
				$width = ($width == 0 ? $heigth : $width);
				$heigth = ($heigth == 0 ? $width : $heigth);
			}
	
			if($twx == $width && $thy == $heigth){
				$found = true;
				$size = 'width="'.$width.'" height="'.$heigth.'"';
				$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
			}
		}
	
		if (!$found)
		{
			if($is_URL){
				if(!ini_get('allow_url_fopen'))
				{
					if($debug)
					{
						$image = '<span class="error">'.JText::_('COM_PRODUCTFINDER_ERR_ALLOW_URL_FOPEN_REQUIRED').'</span>';
					}
					return $image;
				};
				//check if remote file is readable
				$image_path = self::escapeFileName($image_path);
				if (false === @file_get_contents($image_path,0,null,0,1)) {
					if($debug)
					{
						$image = '<span class="error">'.JText::sprintf('COM_PRODUCTFINDER_ERR_REMOTE_RESOURCE_IS_NOT_READABLE', $image_path).'</span>';
					}
					return $image;
				}
			}
			else{
				if(!is_file($image_path))
				{
					if($debug)
					{
						$image = '<span class="error">'.JText::sprintf('COM_PRODUCTFINDER_ERR_LOCAL_RESOURCE_IS_NOT_READABLE', $image_path).'</span>';
					}
					return $image;
				}
			}
			// make the thumbnails
			switch (strtolower($ext))
			{
				case 'jpg':
				case 'jpeg':
				case 'png':
					if(!self::createThumbnail($image_path, $thumb_path, $ext, $width, $heigth, $resize_mode, $quality)){
						$image = "";
						if($debug){
							$image = '<span class="error">'.JText::sprintf('COM_PRODUCTFINDER_ERR_SOURCE_IMAGE_MISSING', $image_path).'</span>';
						}
						break;
					}
					$size = 'width="'.$width.'" height="'.$heigth.'"';
					$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
					break;
	
				case 'gif':
					if (function_exists("imagegif")) {
						if(!self::createThumbnail($image_path, $thumb_path, $ext, $width, $heigth, $resize_mode, $quality)){
							$image = "";
							if($debug){
								$image = '<span class="error">'.JText::sprintf('COM_PRODUCTFINDER_ERR_SOURCE_IMAGE_MISSING', $image_path).'</span>';
							}
							break;
						}
						$size = 'width="'.$width.'" height="'.$heigth.'"';
						$image= '<img src="'.$thumb_url.'" '.$size.' '.$extra.'/>';
						break;
					}
					else{
						$image = "";
						if($debug){
							$image = '<span class="error">'.JText::_('COM_PRODUCTFINDER_ERR_CANT_PROCESS_GIF').'</span>';
						}
						break;
					}
	
				default:
					$image = "";
					if($debug){
						$image = '<span class="error">'.JText::sprintf('COM_PRODUCTFINDER_ERR_IMAGE_TYPE_NOT_SUPPORTED', $ext).'</span>';
					}
					break;
			}
		}
		return $image;
	}
	
	/**
	 * This function actually creates a thumbnail preview from an image
	 *
	 * @param string $file source file (complete with path)
	 * @param string $thumb filename to be given to the thumb (complete with path)
	 * @param string $ext Extension of the source file
	 * @param int &$newheight height of the thumbnail
	 * @param int &$newwidth width of the thumbnail
	 * @param bool $resize_mode adapt / keep aspect / crop
	 * @param integer $quality quality of the image (0 worst - 100 best)
	 * @return boolean return true if the image has been created, otherwise false
	 * @since 1.0
	 */
	private static function createThumbnail ($filename, $thumb, $ext, &$new_width, &$new_height, $resize_mode = 'crop', $quality)
	{
	
		$img_info = getimagesize($filename);
		if(!$img_info){return false;}
	
		$orig_width = $img_info[0];
		$orig_height = $img_info[1];
	
		if($resize_mode == 'crop')
		{
			//preserve original thumb size
			$orig_thumb_width = $new_width;
			$orig_thumb_height = $new_height;
			self::calcCropSize($orig_width, $orig_height, $new_width, $new_height);
		}
		elseif($resize_mode == 'aspect')
		{
			self::calcThumbSize($orig_width, $orig_height, $new_width, $new_height);
		}
	
		switch (strtolower($ext)) {
			case 'jpg':
			case 'jpeg':
				$im  = imagecreatefromjpeg($filename);
				$tim = imagecreatetruecolor ($new_width, $new_height);
				imagecopyresampled($tim, $im, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
				if($resize_mode == 'crop')
				{
					// after resizing proportionally, create a thumbnail
					// with the originalthumb size
					$src_x = ($new_width - $orig_thumb_width) / 2;
					$src_y = ($new_height - $orig_thumb_height) / 2;
					//restore thumb sizes
					$new_width = $orig_thumb_width;
					$new_height = $orig_thumb_height;
						
					$cim = imagecreatetruecolor($orig_thumb_width, $orig_thumb_height);
					$res = imagecopy($cim, $tim, 0, 0 , $src_x, $src_y, $orig_thumb_width, $orig_thumb_height);
					imagedestroy($im);
					$res = imagejpeg($cim, rawurldecode($thumb), $quality );
					imagedestroy($cim);
					imagedestroy($tim);
				}
				else
				{
					imagedestroy($im);
					$res = imagejpeg($tim, rawurldecode($thumb), $quality );
					imagedestroy($tim);
				}
				break;
	
			case 'png':
				$im  = imagecreatefrompng($filename);
				$tim = imagecreatetruecolor($new_width, $new_height);
	
				imagealphablending($tim, false);
				imagesavealpha($tim,true);
				$transparent = imagecolorallocatealpha($tim, 255, 255, 255, 127);
				imagefilledrectangle($tim, 0, 0, $new_width, $new_height, $transparent);
				imagecopyresampled($tim, $im, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
				if($resize_mode == 'crop')
				{
					// after resizing proportionally, create a thumbnail
					// with the originalthumb size
					$src_x = ($new_width - $orig_thumb_width) / 2;
					$src_y = ($new_height - $orig_thumb_height) / 2;
					//restore thumb sizes
					$new_width = $orig_thumb_width;
					$new_height = $orig_thumb_height;
	
					$cim = imagecreatetruecolor($orig_thumb_width, $orig_thumb_height);
					imagealphablending($cim, false);
					imagesavealpha($cim,true);
					$transparent = imagecolorallocatealpha($cim, 255, 255, 255, 127);
					imagefilledrectangle($cim, 0, 0, $new_width, $new_height, $transparent);
					$res = imagecopy($cim, $tim, 0, 0 , $src_x, $src_y, $orig_thumb_width, $orig_thumb_height);
					imagedestroy($im);
					$res = imagepng($cim, rawurldecode($thumb));
					imagedestroy($cim);
					imagedestroy($tim);
				}
				else
				{
					imagedestroy($im);
					$res = imagepng($tim, rawurldecode($thumb));
					imagedestroy($tim);
				}
				break;
			case 'gif':
				if (function_exists("imagegif")) {
					$im  = imagecreatefromgif($filename);
					$tim = imagecreatetruecolor ($new_width, $new_height);
						
					imagealphablending($tim, false);
					imagesavealpha($tim,true);
					$transparent = imagecolorallocatealpha($tim, 255, 255, 255, 127);
					imagefilledrectangle($tim, 0, 0, $new_width, $new_height, $transparent);
					imagecopyresampled($tim, $im, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
						
					if($resize_mode == 'crop')
					{
						// after resizing proportionally, create a thumbnail
						// with the originalthumb size
						$src_x = ($new_width - $orig_thumb_width) / 2;
						$src_y = ($new_height - $orig_thumb_height) / 2;
						//restore thumb sizes
						$new_width = $orig_thumb_width;
						$new_height = $orig_thumb_height;
							
						$cim = imagecreatetruecolor($orig_thumb_width, $orig_thumb_height);
						imagealphablending($cim, false);
						imagesavealpha($cim,true);
						$transparent = imagecolorallocatealpha($cim, 255, 255, 255, 127);
						imagefilledrectangle($cim, 0, 0, $new_width, $new_height, $transparent);
						$res = imagecopy($cim, $tim, 0, 0 , $src_x, $src_y, $orig_thumb_width, $orig_thumb_height);
						imagedestroy($im);
						$res = imagepng($cim, rawurldecode($thumb));
						imagedestroy($cim);
						imagedestroy($tim);
					}
					else
					{
						imagedestroy($im);
						$res = imagepng($tim, rawurldecode($thumb));
						imagedestroy($tim);
					}
					break;
				}
	
			default:
				break;
		}
		return true;
	}
	
	/**
	 * This function calculate the new dimension of an
	 * image so that it fits inside a given box
	 *
	 * @param $srcx , $srcy size of the image to be resized
	 * @param $forcewidth, $forcheight size of the resize box
	 * @return alters $forcewidth, $forcheight
	 * @since 1.0
	 *
	 */
	private static function calcThumbSize($srcx, $srcy, &$forcedwidth, &$forcedheight)
	{
		$img_aspect = $srcx / $srcy > 1 ? 'portrait' : 'landscape';
	
		if($forcedheight == 0 && $forcedwidth > 0)
		{
			$forcedheight = ($forcedwidth * $srcy) / $srcx;
			return;
		}
		elseif ($forcedheight > 0 && $forcedwidth == 0)
		{
			$forcedwidth = ($forcedheight * $srcx) / $srcy;
			return;
		}
		elseif($forcedheight == 0 && $forcedheight == 0)
		{
			JError::raiseError(404, JText::_('COM_PRODUCTFINDER_ERR_INCORRECT_THUMBNAIL_SIZE'));
			return false;
		}
		else
		{
			$box_aspect = $forcedwidth / $forcedheight > 1 ? 'portrait' : 'landscape';
		}
	
		if($img_aspect == $box_aspect)
		{
			$target = max($forcedheight , $forcedwidth);
		}
		else{
			$target = min($forcedheight , $forcedwidth);
		}
	
		if($srcx > $srcy){
			$ratio = $target / $srcx ;
		}
		else{
			$ratio = $target / $srcy ;
		}
	
		$forcedwidth = round($srcx * $ratio);
		$forcedheight = round($srcy * $ratio);
	
	}
	
	/**
	 * Calculate the size of the thumbnail prior the cropping operation
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $thumb_width
	 * @param int $thumb_height
	 * @return modify $thumb_width and $thumb_height
	 * @since 1.0
	 */
	private static function calcCropSize($width, $height, &$thumb_width, &$thumb_height)
	{
	
		if($thumb_width == 0 && $thumb_height == 0)
		{
			JError::raiseError(404, JText::_('COM_PRODUCTFINDER_ERR_INCORRECT_THUMBNAIL_SIZE'));
			return false;
		}
	
		$original_aspect = $width / $height;
		$thumb_aspect = $thumb_width / $thumb_height;
	
		if ( $original_aspect >= $thumb_aspect )
		{
			// If image is wider than thumbnail (in aspect ratio sense)
			$new_height = $thumb_height;
			$new_width = $width / ($height / $thumb_height);
		}
		else
		{
			// If the thumbnail is wider than the image
			$new_width = $thumb_width;
			$new_height = $height / ($width / $thumb_width);
		}
	
		$thumb_width = $new_width;
		$thumb_height = $new_height;
	}
	
	/**
	 * Function to escape the path of an URL
	 *
	 * @return string escaped url , of original file if the passed variable is not an url
	 * @since 1.0
	 */
	private static function escapeFileName($file){
	
		if(strpos($file, 'http') === 0){
			$theURL = parse_url($file);
			$thePath = explode('/', $theURL['path']);
			if(empty($thePath[0])) unset($thePath[0]);
	
			$theEscapedPath = join('/', array_map('rawurlencode', $thePath) );
			$file = $theURL['scheme'] . '://' . $theURL['host']. '/'. $theEscapedPath;
				
		}
	
		return $file;
	}	
}

