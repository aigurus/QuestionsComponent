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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
require_once 'components/com_questions/helpers/mail.php';
class QuestionsControllerReports extends JControllerForm {
	
	public function save(){ //Just For Logging Reference
		
		//authorization
		$user = JFactory::getUser();
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		       //return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
			   $link = "index.php?option=com_users&view=login";
			   $msg="You need to login";
			   $this->setRedirect($link, $msg, 'error');
	    }
		/*if (!$user->authorise("core.create" , "com_questions")){
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}*/
		
		//Transparent Captcha
		$captcha = JRequest::getString("LastName");
		if ($captcha){
			JError::raiseError(404 , ""); 
		}
			
		//load request data
		$data = JFactory::getApplication()->input->get('jform', array(), 'post', 'array');

		if (parent::save()) {
			if ($data['id']) { //do we have an id?
				$lastid = $data['id'];	//if so, use it
			}
			else { //if no, use the last inserted id
				$db = &JFactory::getDbo();
				$lastid = $db->insertid();
			}
			//redirect & display the inserted data
			$this->setRedirect(JRoute::_("index.php?option=com_questions&view=reports"));
			//clear state
			JFactory::getApplication()->setUserState("com_questions.edit.reports.data", array());
		}
		else {
			//store temp data

			JFactory::getApplication()->setUserState("com_questions.edit.reports.data", $data);
		}
		$user =JFactory::getUser();
		$recipient = $user->email;
		$subject = "Report Against: [".$data["title"]."] Submitted";
		$message="Problem sending email. Check SMTP, sendmail settings";
		$body= JText::_('COM_QUESTIONS_REPORT_SUBMIT_QUESTION')."<br />";
		MailHelper::sendemail($body,$subject,$recipient,$message);
		
	}
	
	public function cancel(){
		echo "<script type='text/javascript'>javascript:history.go(-2);</script>";
		//clear state
		JFactory::getApplication()->setUserState("com_questions.edit.reports.data", array());
	}
	
	
}