<?php
defined('_JEXEC') or die;
abstract class mod_usersHelper
{
public static function getList()
  {
     $db = JFactory::getDbo();
     $query = $db->getQuery(true);
     $query->select('name, username, email');
     $query->from('#__users');
    $db->setQuery($query);
    try
      {
         $results = $db->loadObjectList();
      }
    catch (RuntimeException $e)
     {
         JError::raiseError(500, $e->getMessage());
         return false;
     }
          foreach ($results as $k => $result)
         {
         $results[$k] = new stdClass;
         $results[$k]->name = htmlspecialchars( $result->name );
          $results[$k]->username = (int)$result->username;
          $results[$k]->email = htmlspecialchars( $result->email );
          }
        return $results;
     }
 }
?>
