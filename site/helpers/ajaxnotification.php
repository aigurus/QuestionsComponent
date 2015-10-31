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
 include("components/com_socialpinboard/helpers/notification.php");  
 
 $session =JFactory::getSession();
 $id=$session->getId();
 $notification = new notificationHelper();  
 $notification->to_user = $id;  
 $notifications = $notification->getAllNotifications();  
 if ($notifications) {  
   echo $notification->newcount . "|";  
   $unseen_ids = array();
   $db =JFactory::getDBO();  
   while ($object = $db->loadObjectList($notifications)) {  
     if ($object->seen == 0) $unseen_ids[] = $object->id;  
     switch($object->type) {  
       case "likes":  
         ?>  
         <li id="notification_<?php echo $object->id;?>">  
           <div style="width:350px;padding:5px;">  
             <a href="profile.php?id=<?php echo $object->from_user;?>"><img src="<?php echo $object->defaultpic;?>" style="float:left;" width="50px" height="50px"/>&nbsp;<?php echo $displayName;?></a> has liked your pin!<br />  
             &nbsp;<a href="#" onclick="HandleRequest('accept','<?php echo $object->from_user;?>');">Accept</a>&nbsp;&nbsp;<a href="#" onclick="HandleRequest('deny','<?php echo $object->from_user;?>');">Deny</a>  
           </div><br style="clear:both;"/>  
         </li>  
         <?php  
         break;  
       case "comments":  
         ?>  
         <li id="notification_<?php echo $object->id;?>">  
           <div style="width:350px;padding:5px;">  
             <a href="profile.php?id=<?php echo $object->from_user;?>"><img src="<?php echo $object->defaultpic;?>" width="50px" height="50px"/></a>&nbsp;<a href="message.php?id=<?php echo $object->reference;?>"><?php echo $displayName;?> has sent you a message!</a>  
             <a href="javascript:void(0)" onclick="DeleteNotification(<?php echo $object->id;?>)" style="float:right;"><i class="icon-trash"></i></a>  
           </div>  
         </li>  
         <?php  
         break;  
		 case "repins":  
         ?>  
         <li id="notification_<?php echo $object->id;?>">  
           <div style="width:350px;padding:5px;">  
             <a href="profile.php?id=<?php echo $object->from_user;?>"><img src="<?php echo $object->defaultpic;?>" width="50px" height="50px"/></a>&nbsp;<a href="message.php?id=<?php echo $object->reference;?>"><?php echo $displayName;?> has sent you a message!</a>  
             <a href="javascript:void(0)" onclick="DeleteNotification(<?php echo $object->id;?>)" style="float:right;"><i class="icon-trash"></i></a>  
           </div>  
         </li>  
         <?php  
         break;  
		 default:
                    
     }  
   }  
   echo "|".json_encode($unseen_ids);  
 }  
 ?> 