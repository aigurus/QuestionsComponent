<?php
/*
    
    Copyright (C)  2012 Sweta ray.
    Permission is granted to copy, distribute and/or modify this document
    under the terms of the GNU Free Documentation License, Version 1.3
    or any later version published by the Free Software Foundation;
    with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
    A copy of the license is included in the section entitled "GNU
    Free Documentation License"
	@license GNU/GPL http://www.gnu.org/copyleft/gpl.html
    Questions for Joomla
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
   
	Questions for Joomla
	Version 0.0.1
	Created date: Sept 2012
	Creator: Sweta Ray
	Email: admin@extensiondeveloper.com
	support: support@extensiondeveloper.com
	Website: http://www.extensiondeveloper.com
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once 'components/com_questions/helpers/profilepicture.php';

abstract class AvatarHelper{
	
		public static function getAvatar($email,$class,$size,$profile,$userid){
				$app = JFactory::getApplication();
				$params = $app->getParams();
				$method = $params->get('display_gravatars', 1);
				$javatarsize = $params->get('javatarsize', "50");
				$d = "mp";
				$r ="g";
				if($method==1){
						if(!$profile==1){
							
							return '<img class="'.$class.'" src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s='.$size.'&d='.$d.'&r='.$r.' style="float:right; border:2px solid #333;" />';
							
							 } else {
						
							return '<img width="'.$size.'" height="'.$size.'" class="'.$class.'" alt="Profile Picture" src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s='.$size.'&d='.$d.'&r='.$r.'&amp;d=identicon&amp;r=PG"/>';
							
							
						}
				}
				elseif($method==2){
							$jspath = JPATH_ROOT."/".'components'."/".'com_community';
							include_once($jspath."/".'libraries'."/".'core.php');
							// Get CUser object
							$user = CFactory::getUser($userid);
							$avatarUrl = $user->getThumbAvatar();
							return '<img src='.$avatarUrl .'>';
				}
				elseif($method==3){
							$avatarUrl = ProfileAvatar::getURL($javatarsize,$userid);
							$fillerUrl = ProfileAvatar::getFillerURL($javatarsize);
							if(!empty($avatarUrl)){
							return '<img src='.$avatarUrl .'>';
							}
							else
							{
							return '<img src='.$fillerUrl .'>';
							}
				}
		}
}