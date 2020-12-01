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
 
// import joomla controller library
jimport('joomla.application.component.controller');
if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

if(!class_exists('QueView')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class QueView extends JViewLegacy {
      };
   } else {
      jimport('joomla.application.component.view');
      class QueView extends JView {};
   }
}

if(!class_exists('QueController')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class QueController extends JControllerLegacy {
      };
   } else {
	  jimport('joomla.application.component.controller');
      class QueController extends JController {};
   }
}

$user =JFactory::getUser();

$app = JFactory::getApplication();
$params = $app->getParams();

$db = JFactory::getDBO();
$query = "
  SELECT blocked
    FROM #__questions_userprofile
    WHERE userid='".$user->id."';
  ";
$db->setQuery($query);
$result = $db->loadResult();

if($result ==1 && $user->id!=0){
//$app =JFactory::getApplication();
//$app->redirect('index.php?option=com_user&view=login');
return JError::raiseWarning(404, JText::_('COM_QUESTIONS_Your Access is Restricted. You have been Blocked by Admin of this Site.'));
}
else
{

//Import the helper
require_once 'components/com_questions/helpers/points.php';
require_once 'components/com_questions/helpers/avatar.php';
require_once 'components/com_questions/helpers/jomsocial.php';
require_once 'components/com_questions/helpers/copyright.php';
require_once 'components/com_questions/helpers/route.php';
require_once 'components/com_questions/helpers/social.php';
require_once 'administrator/components/com_questions/helpers/questions.php';

$db = JFactory::getDBO();

$query = "
  SELECT * FROM #__questions_userprofile
    WHERE userid='".$user->id."';
  ";

$db->setQuery($query);
$result = $db->loadObjectList(); 
function dateDiff ($d1, $d2) {
// Return the number of days between the two dates:

  return round(abs(strtotime($d1)-strtotime($d2))/86400);

} 
if(count($result)<1 && !JFactory::getUser()->guest){
	$app = JFactory::getApplication();
	$params = $app->getParams();
	$joiningpoints =  $params->get('joiningpoints', 25);
	$points = new getPoints();
	$pdatauserrank = $points->setRank($user->id,$joiningpoints);
	//var_dump($pdatauserrank);
	$db = JFactory::getDbo();
	
	$pdata = new stdClass;
	$pdata->id = NULL;
	$pdata->userid = $user->id;
	$pdata->username = $user->name;
	$pdata->answered = NULL;
	$pdata->asked = NULL;
	$pdata->points = $joiningpoints;
	$pdata->rank = $pdatauserrank;
	$pdata->logdate = date("Y-m-d H:i:s"); 
	$pdata->email = $user->email;
	
	$db->insertObject('#__questions_userprofile', $pdata);
	$application =JFactory::getApplication();
	$application->enqueueMessage(JText::_('COM_QUESTIONS_YOU_JUST_RECIEVED').$joiningpoints);
	$application->enqueueMessage(JText::_('COM_QUESTIONS_POINTS_FOR_FIRSTTIME_LOGGING_INTO_QUESTIONS'));
	//JFactory::getApplication()->enqueueMessage(JText::_('COM_QUESTIONS_SOME_ERROR_OCCURRED'), 'error');

	} elseif(count($result)>=1 && !JFactory::getUser()->guest) {
		
	$db = JFactory::getDBO();
	$query = "
		  SELECT logdate
			FROM #__questions_userprofile
			WHERE userid='".$user->id."';
		  ";
	$db->setQuery($query);
	$date1 = $db->loadResult(); 
	$d2 = date("Y-m-d H:i:s"); 
	$difference = dateDiff($date1, $d2);
	if($difference >1){
	$app = JFactory::getApplication();
	$params = $app->getParams();
	$loggingpoints =  $params->get('loggingpoints', 1);
	$points = new getPoints();
	$pdatauserrank = $points->setRank($user->id,$loggingpoints);
	
	$q2="UPDATE #__questions_userprofile SET points = points+'".$loggingpoints."', rank ='".$pdatauserrank."', logdate='".date("Y-m-d H:i:s")."' where userid=".$user->id;
	$db->setQuery($q2);
	$db->execute();
	$application =JFactory::getApplication();
	$application->enqueueMessage(JText::_('COM_QUESTIONS_YOU_JUST_RECIEVED').$loggingpoints);
	$application->enqueueMessage(JText::_('COM_QUESTIONS_POINTS_FOR_LOGGING_INTO_QUESTIONS'));
	//JFactory::getApplication()->enqueueMessage(JText::_('COM_QUESTIONS_SOME_ERROR_OCCURRED'), 'error');
	}
			
	}

$doc = JFactory::getDocument();

$controller = JControllerLegacy::getInstance('questions');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
}