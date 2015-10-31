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
	Email: admin@phpseo.net
	support: support@phpseo.net
	Website: http://www.phpseo.net
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class getPoints{
		
		public function setRank($userid,$option){
			
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('points');
				$query->from('#__questions_userprofile');
				$query->where('userid='.$userid);
				$db->setQuery((string)$query);
				$userpoints = $db->loadResult();
				
				$query = $db->getQuery(true);
				$query->select('rank,pointsreq');
				$query->from('#__questions_ranks');
				$query->order('pointsreq asc');
				$db->setQuery($query);
				$userrank = $db->loadObjectList();
				$rankcount = count($userrank);
				
				if(isset($userrank) && isset($userpoints)){
				for($i=0;$i<$rankcount;$i++)
				{
						if(($userpoints+$option) > $userrank[$i]->pointsreq && ($userpoints+$option)< $userrank[$i+1]->pointsreq)
						{
						$newuserrank = $userrank[$i]->rank;
						}
						elseif(isset($userrank[$i+1]) && ($userpoints+$option)== $userrank[$i+1]->pointsreq)
						{
						$newuserrank = $userrank[$i+1]->rank;
						}
						elseif(($userpoints+$option)== $userrank[$i]->pointsreq)
						{
						 $newuserrank = $userrank[$i]->rank;
						} else {
						 $newuserrank =$userrank[$i]->rank;
						}
				}
				}
			return $newuserrank;
		}
		
		public function setRank2($id,$option){
			
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query =  ' SELECT points '.
						  ' FROM #__questions_core as m '.
						  ' LEFT JOIN #__questions_userprofile as n ' .
						  ' ON n.userid = m.userid_creator ' .
						  ' WHERE m.id ='.$id;
				$db->setQuery((string)$query);
				$userpoints = $db->loadResult();
				
				$query = $db->getQuery(true);
				$query->select('rank,pointsreq');
				$query->from('#__questions_ranks');
				$query->order('pointsreq asc');
				$db->setQuery((string)$query);
				$userrank = $db->loadObjectList();
				$rankcount = count($userrank);
				
				for($i=0;$i<$rankcount;$i++)
				{
						if(($userpoints+$option) > $userrank[$i]->pointsreq && ($userpoints+$option)< $userrank[$i+1]->pointsreq)
						{
						$newuserrank = $userrank[$i]->rank;
						}
						elseif(($userpoints+$option)== $userrank[$i+1]->pointsreq)
						{
						$newuserrank = $userrank[$i+1]->rank;
						}
						elseif(($userpoints+$option)== $userrank[$i]->pointsreq)
						{
						 $newuserrank = $userrank[$i]->rank;
						}
				}
			return $newuserrank;
		
		}
}
?>