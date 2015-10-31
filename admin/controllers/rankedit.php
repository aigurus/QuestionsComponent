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
 
// import Joomla controllerform library
 jimport('joomla.application.component.controllerform');

class QuestionsControllerRankedit extends JControllerForm {
	
	public function save(){ //Just For Logging Reference
		
		//authorization
		$user = JFactory::getUser();
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		       return JError::raiseWarning(404, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
	    }
		/*if (!$user->authorise("core.create" , "com_questions")){
			JError::raiseError(403, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
			return false;
		}*/
		
		$data =  JFactory::getApplication()->input->get('jform', array(), 'post', 'array');
		$id = JRequest::getInt('id',0);

		//parse config options
		//$params = json_decode(JFactory::getApplication()->getParams());
		//$askingpoints = (int) $params->askingpoints;
		
		//Determine default state 
		// TODO: 	Check if the user is allowed to alter the state
		//			- If so, then do not use the default state, but leave the state as is
		if (TRUE){
			$data['published'] = (int) $params->defaultRankeditState;
		}
		
		//Encode the tags to json..
		
		
		if (parent::save()) {
			$msg = JText::_("COM_QUESTIONS_MSG_ITEM_SAVED");
			$url = "index.php?option=com_questions&view=rank&id=" . $id;
			$this->setRedirect($url , $msg);
		}
		$pdata = new stdClass;

        $pdata->id = NULL;
        $pdata->pointsreq = NULL;
        $pdata->rank = NULL;

		
		/*for updating question count*/
		$db = &JFactory::getDbo();
		$db->setQuery("SELECT COUNT(*) FROM `#__questions_ranks` WHERE id = '".$id."'" );
		$count = $db->loadResult();
		if($count>0){
		$db->updateObject('#__questions_ranks', $pdata) ;
        }
	}
	
	public function cancel(){
		echo "<script type='text/javascript'>javascript:history.go(-2);</script>";
		//clear state
		JFactory::getApplication()->setUserState("com_questions.edit.rankedit.data", array());
	}
	
	public function edit() {
		
		if (!QuestionsHelper::canDo("core.edit")){
			$this->setRedirect("index.php?option=com_questions&view=rank&id=" . (int)(QuestionsHelper::getActiveSubmenu()=="Rank") , JText::_("COM_QUESTIONS_MSG_NOAUTH") ,  "error");
			return;
		}
		
		$id = JRequest::getInt("id",1);
		$points = JRequest::getInt("pointsreq",0);
		$rank = JRequest::getInt("rank", 0);
		
		$app = JFactory::getApplication();
		$app->setUserState("pointsreq", $pointsreq);
		$app->setUserState("rank", $rank);
		$app->setUserState("id", $id);
		
		
		parent::edit();
	}
	
}