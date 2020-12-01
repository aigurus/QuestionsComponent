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

jimport('joomla.filesystem.file');
/*
DEFINE('PROFILEPICTURE_PATH_50', 	JPATH_ROOT."/".'media'."/".'plg_user_profilepicture'."/".'images'."/".'50'."/");
DEFINE('PROFILEPICTURE_PATH_200', 	JPATH_ROOT."/".'media'."/".'plg_user_profilepicture'."/".'images'."/".'200'."/");
DEFINE('PROFILEPICTURE_PATH_ORIGINAL', 	JPATH_ROOT."/".'media'."/".'plg_user_profilepicture'."/".'images'."/".'original'."/");
DEFINE('PROFILEPICTURE_PATH_FILLER', 	JPATH_ROOT."/".'media'."/".'plg_user_profilepicture'."/".'images'."/".'filler'."/");

DEFINE('PROFILEPICTURE_SIZE_FILLER', 	'filler');
DEFINE('PROFILEPICTURE_SIZE_ORIGINAL', 	'original');
DEFINE('PROFILEPICTURE_SIZE_50', 	50);
DEFINE('PROFILEPICTURE_SIZE_200', 	200);
*/
if(!defined('PROFILEPICTURE_SIZE_FILLERS')){
	DEFINE('PROFILEPICTURE_SIZE_FILLERS', 	'filler');
}
/**
 * Class to retrieve profile picture of a user
 *
 * @package     Mosets
 * @subpackage  ProfileAvatar
 * @since       1.0
 */
class ProfileAvatar
{
	/**
	 * @var    int  User ID
	 * @since  1.0
	 */
	public $userId = null;
	
	/**
	 * @var    string  File name of the profile picture
	 * @since  1.0
	 */
	public $filename = null;
	
	/**
	 * @const  string
	 * @since  1.0
	 */
	const PROFILE_KEY = 'profilepicture.file';
	
	/**
	 * Class constructor.
	 *
	 * @param   int  $userId  User ID
	 *
	 * @since   1.0
	 */
	public function __construct($userId)
	{
		if( is_numeric($userId) )
		{
			$this->userId = $userId;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Method to set User ID
	 *
	 * @param   int  $userId    User ID
	 *
	 * @since   1.0
	 */
	public function setUserId($userId)
	{
		$this->userId = $id;
	}
	
	/**
	 * Method to set Profile Picture filename
	 *
	 * @param   str  $filename    Profile picture filename
	 *
	 * @since   1.0
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	/**
	 * Get the user's profile picture filename.
	 *
	 * @return  str		The profile picture filename.
	 *
	 * @since   1.0
	 */
	public function getFilename($userid1)
	{
		
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)->select('profile_value')->from('#__user_profiles')
				->where('profile_key = ' . $db->quote(ProfileAvatar::PROFILE_KEY))
				->where('user_id = '.(int) $userid1);
			$db->setQuery($query);
			$filename = $db->loadResult();

			if( !is_null($filename) )
			{
				return $filename;
			} else {
				return false;
			}			
	}
	
	/**
	 * Get the user's profile picture URL.
	 *
	 * @return  str		The profile picture URL.
	 *
	 * @since   1.0
	 */
	public function getFillerURL($size=PROFILEPICTURE_SIZE_200)
	{
		return JURI::root().'media/plg_user_profilepicture/images/'.PROFILEPICTURE_SIZE_FILLERS."/".$size.'.png';
	}
	
	/**
	 * Get the user's profile picture URL.
	 *
	 * @return  str		The profile picture URL.
	 *
	 * @since   1.0
	 */
	public function getURL($size=PROFILEPICTURE_SIZE_200,$userid2)
	{
		if( $filename = ProfileAvatar::getFilename($userid2) )
		{
			return JURI::root().'media/plg_user_profilepicture/images/'.$size."/".ProfileAvatar::getFilename($userid2);
		} else {
			return false;
		}
	}

	/**
	 * Get the user's profile picture path.
	 *
	 * @return  str		The profile picture path.
	 *
	 * @since   1.0
	 */
	public function getPath($size=PROFILEPICTURE_SIZE_200,$userid3)
	{
		if( $filename = ProfileAvatar::getFilename($userid3) )
		{
			return JPATH_BASE."/".'media/plg_user_profilepicture/images/'.$size."/".ProfileAvatar::getFilename($userid3);
		} else {
			return false;
		}
	}

	/**
	 * Method to check if a profile picture of a certain size exists
	 *
	 * @param  string	The size of the profile picture to check
	 *
	 * @return  boolean	True if the profile picture exists
	 *
	 * @since   1.0
	 */
	public function exists($size=PROFILEPICTURE_SIZE_200)
	{
		if( $filename = ProfileAvatar::getFilename() )
		{
			return JFile::exists(ProfileAvatar::getPath($size));
		} else {
			return false;
		}
	}
	
	/**
	 * Render the IMG HTML element.
	 *
	 * @param  string	$size		The size of the rendered profile picture image
	 * @param  string	$alt		The IMG element 'alt' attribute.
	 * @param  array 	$attribs	Additional attributes to be inserted in to the rendered HTML
	 *
	 * @return  string The rendered IMG element.
	 *
	 * @since 1.0
	 */
	public function toHTML($size = PROFILEPICTURE_SIZE_200, $alt = '', $attribs = array())
	{
		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
		}

		$html = '';
		if( ProfileAvatar::exists() )
		{
			$html .= '<img src="' . ProfileAvatar::getURL($size).'" alt="' . $alt . '" ' . $attribs . '/>';
		} else {
			$html .= '<img src="' . ProfileAvatar::getFillerURL($size) . '" alt="' . $alt . '" ' . $attribs . '/>';
		}
		return $html;
	}
}
?>