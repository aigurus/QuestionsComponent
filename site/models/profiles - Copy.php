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

// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.html.parameter' );
require_once 'components/com_questions/helpers/avatar.php';

class QuestionsModelProfiles_OLD extends JModelList
{
		protected $i;
       function GetUserList()
       {
			 $menus = JFactory::getApplication()->getMenu();
			 $menu =$menus->getActive();
			 
			 //$params = new JParameter($menu->params);
			 $mainframe = JFactory::getApplication();
			 $pathway   = $mainframe->getPathway();	
			 
			 $app = JFactory::getApplication();
			 $params = $app->getParams();
			 
		
			 $db =JFactory::getDBO();
				$query = " SELECT username, asked, answered, email, chosen, rank, userid FROM #__questions_userprofile";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				//print_r($rows);
			return $rows;
			 /*$html = '<table id="userprofiletable">';
			 $html .= '<tr><th>' . JText::_('COM_QUESTIONS_PROFILE_USER') . '</th>';
			 
			 $html .= '<th>' . JText::_('COM_QUESTIONS_PROFILE_ASKED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_ANSWERED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_CHOSEN') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_RANK') . '</th></tr>';
			 foreach($rows as $row)
			 {
				   $html .= '<tr>';
				   $userNameToShow = str_replace("[NAME]",$row->username, str_replace("[USERNAME]", $row->username,$params->get('userMask','[USERNAME]')));
				   if($params->get('showDetail') == 0)
				   {
					 $html .= '<td><a href="' . JRoute::_("index.php?option=com_questions&view=profiles&id=".$row->userid . "%3A" . $row->username). '">'.AvatarHelper::getAvatar($row->email,"questions_gravatar_big",64,0,$row->userid).'<br />'.$userNameToShow.'</a></td>';
				   }
				   else
				   {
					 $html .= '<td>'.$userNameToShow.'</td>';
				   }
				   $html .= '<td>'.$row->asked.'</td>';
				   $html .= '<td>'.$row->answered.'</td>';
				   $html .= '<td>'.$row->chosen.'</td>';
				   $html .= '<td>'.$row->rank.'</td>';
				   $html .= '</tr>';

     		}
     $html .= '</table>';

     // region Pages
     
	$html .= '<a href="'.JRoute::_("index.php?option=com_questions&view=profiles&page=".$this->i).'">'.($this->i+1).'</a>';
    return $html;*/
  }

  function getUserActivities($id)
  {
	 $menus = JFactory::getApplication()->getMenu();
     $menu =$menus->getActive();
      //$params = new JParameter($menu->params);
	  $app = JFactory::getApplication();
	  
	  $params = $app->getParams();
      $mainframe = JFactory::getApplication();
      $pathway   = $mainframe->getPathway();	

      $doc = JFactory::getDocument();
	  //$id =  &JRequest::getInt('id', 0);

      $db = JFactory::getDBO();
 
      /*$query = "SELECT * FROM #__questions_userprofile where userid=" . $id;
      $db->setQuery( $query );
      $user = $db->loadObjectList();*/
	  


		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__questions_userprofile');
		$query->where($db->quoteName('userid') . ' = ' .$id);
		$db->setQuery($query);
		//echo($query->__toString()); exit;
		$user = $db->loadObjectList();
		return $user;
		/*$user = $user[0];
	  
      $userNameToShow = str_replace("[NAME]",$user->username, str_replace("[USERNAME]", $user->username,$params->get('userMask','[USERNAME]')));
      $doc->setTitle(JText::_('COM_QUESTIONS_PROFILE_DETAILHEADER') . " " . $userNameToShow);
      $pathway->addItem(JText::_('COM_QUESTIONS_PROFILE_DETAILHEADER') . " " . $userNameToShow, '');
	  ?>
      <table id="userprofiledetailtable">
	  <tr><td>
	  <?php 
						$app = JFactory::getApplication();
						$parameters = $app->getParams();
						$appParams = json_decode(JFactory::getApplication()->getParams());
						if (isset($appParams->display_gravatars) && $appParams->display_gravatars!=0){
						?>
	  <?php //AvatarHelper::getAvatar($user->email,"questions_gravatar_big",64,0,$user->userid); ?>
	  <?php }?>
	   </td>
	   <td>
	   <table id="userprofiledetailtable">
      <tr class="rowstyle"><th class="userprofilekey"><?php echo JText::_('COM_QUESTIONS_PROFILE_USER'); ?></th><td><?php 
	  AvatarHelper::getAvatar($user->email,"questions_gravatar_big",64,0,$user->userid).'<br />'; 
	  echo $userNameToShow; ?></td></tr>
      
     <tr class="rowstyle"><th class="userprofilekey"><?php echo JText::_('COM_QUESTIONS_PROFILE_ASKED'); ?></th><td><?php echo $user->asked; ?></td></tr>
   <tr class="rowstyle"><th class="userprofilekey"><?php echo JText::_('COM_QUESTIONS_PROFILE_ANSWERED'); ?></th><td><?php echo $user->answered; ?></td></tr>
	<tr class="rowstyle"><th class="userprofilekey"><?php echo JText::_('COM_QUESTIONS_PROFILE_CHOSEN') ; ?></th><td><?php echo $user->chosen; ?></td></tr>
	<tr class="rowstyle"><th class="userprofilekey"><?php echo JText::_('COM_QUESTIONS_PROFILE_RANK') ; ?></th><td><?php echo $user->rank; ?></td></tr>
	</table>
	</td>
	</tr>
    </table>

    <br />
	<?php  */
		
  }
  function getMyGroups(){
	  $user = JFactory::getUser();
	  $db =JFactory::getDBO();
	  $query = "SELECT groups FROM #__questions_userprofile where userid=" . $user->id;
      $db->setQuery( $query );
      $groups = $db->loadResult();
	  if(strlen($groups)>0){
		$groups = unserialize($groups);
	  } else {
		$groups = NULL;
	  }
	  return $groups;
  }
  function getMyGroupDetails($gid){
	  $db =JFactory::getDBO();
	  $query = "SELECT * FROM #__questions_groups where id=" . $gid;
      $db->setQuery( $query );
      $groupdetails = $db->loadObjectList();
	  return $groupdetails;
  }
}
