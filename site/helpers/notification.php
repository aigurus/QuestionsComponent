<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component helper file
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
//Reference http://jasonjitsu.blogspot.in/2013/05/multiple-notification-system-using-php.html
abstract class notificationHelper {
 
	   var $type;  
	   var $to_user;  
	   var $from_user;  
	   var $reference;  
	   var $timestamp;  
	   var $newcount;  
	   
   public function getAllNotifications() { 
   
     $db = JFactory::getDbo();
 	 $query = $db->getQuery(true); 
     $this->newcount = self::newCount($this->to_user);  
     $sql = "SELECT n.*,u.pin_repin_count,u.pin_likes_count,u.pin_comments_count FROM #__questions_notification n INNER JOIN #__pin_pins u ON u.pin_user_id = n.to_user ORDER BY `timestamp` DESC LIMIT 10";  
     $result = $db->setQuery($sql);  
     if ($result) {  
       return $result;  
     }  
     return false; //none found  
   }  
   
   public function Add() {  
   $db = JFactory::getDbo();
 	 $query = $db->getQuery(true); 
     $sql = "INSERT INTO #__questions_notification (to_user,from_user,reference,type) VALUES ({$this->to_user},{$this->from_user},{$this->reference},'{$this->type}')";  
	 $db->setQuery($sql);
	 //$db->query()

   }  
   
   static function Seen($id) { 
     $db = JFactory::getDbo();
 	 $query = $db->getQuery(true); 
     $user = JFactory::getUser(); 
     $sql = "UPDATE #__questions_notification SET seen = 1 WHERE id = {$id} AND to_user = {$user->id}";  
     $db->setQuery($sql);  
   }  
   
   static function newCount($user) { 
     $db = JFactory::getDbo();
 	 $query = $db->getQuery(true);  
     $sqlcnt = "SELECT count(*) FROM #__questions_notification WHERE to_user = {$user} AND seen = 0";  
     $db->setQuery($sqlcnt);  
     $row = $db->query(); 
     return $row[0];  
   }  
   
   static function deleteNotification($id) { 
     $session =JFactory::getSession();
	 $sessionid=$session->getId();
	 $user = JFactory::getUser(); 
     $db = JFactory::getDbo();
 	 $query = $db->getQuery(true);  
     $sql = "DELETE FROM #__questions_notification WHERE id = {$id} AND to_user = {$user->id}";  
     $db->setQuery($sql);  
   }  

}
