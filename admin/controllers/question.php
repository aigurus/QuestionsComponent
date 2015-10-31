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
 

class QuestionsControllerQuestion extends JControllerForm
{
	public function save ($key = NULL, $urlVar = NULL){
		//TODO: Validate & Sanitize
		//Method to save a question - just calls the parent && sets the redirect
		//authorization
		$user = JFactory::getUser();
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		       return JError::raiseWarning(404, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
	    }
		
		$data =  JFactory::getApplication()->input->get('jform', array(), 'post', 'array');

		if ($data['question']){
			$displayAnswers = 0;
		}
		else {
			$displayAnswers = 1;
		}
		
		//tags -- retrieve the and store them as json
		$qtags = $data['qtags'];
		if ($qtags){
			$qtags = explode("," , $qtags);
			$i=0;
			if(is_array($qtags)){
			foreach($qtags as $qtag){
				$qtag = strip_tags($qtag);
				// Clean up things like &amp;
				$qtag = html_entity_decode($qtag);
				// Strip out any url-encoded stuff
				$qtag = urldecode($qtag);
				// Replace non-AlNum characters with space
				$qtag = preg_replace('/[^A-Za-z0-9]/', ' ', $qtag);
				// Replace Multiple spaces with single space
				$qtag = preg_replace('/ +/', ' ', $qtag);
				// Trim the string of leading/trailing space
				$qtag = trim($qtag);
				$qtags[$i] = $qtag;
				$i++;
			}
			}
			/*$qtags = str_replace('"', "", $qtags);
			$qtags = str_replace("'", "", $qtags);*/
			$qtags = array_filter(array_map('trim', $qtags));
			$qtags = implode(',', $qtags);
			$data['qtags'] = $qtags;
 			//replace the original request
			JRequest::setVar("jform" , $data);
		}
		
		if (parent::save()) {
			$msg = JText::_("COM_QUESTIONS_MSG_ITEM_SAVED");
			$url = "index.php?option=com_questions&view=questions&answers=".$displayAnswers;
			$this->setRedirect($url , $msg);
		}		
		
	}
	public function edit($key = NULL, $urlVar = NULL) {
				
		if (!QuestionsHelper::canDo("core.edit")){
			$this->setRedirect("index.php?option=com_questions&view=questions&answers=" . (int)(QuestionsHelper::getActiveSubmenu()=="Answers") , JText::_("COM_QUESTIONS_MSG_NOAUTH") ,  "error");
			return;
		}
		
		$question = JRequest::getInt("question",1);
		$parent = JRequest::getInt("parent",0);
		$catid = JRequest::getInt("catid", 0);
		
		$app = JFactory::getApplication();
		$app->setUserState("isQuestion", $question);
		$app->setUserState("parentID", $parent);
		$app->setUserState("catid", $catid);
		

		parent::edit();
				
	}
	
	function sendemailredirect($body,$subject,$recipient,$message){
	
		$mailer = JFactory::getMailer();
		
		$config = JFactory::getConfig();
		$sender = array( 
		$config->get( 'config.mailfrom' ),
		$config->get( 'config.fromname' ) );
		$mailer->setSender($sender);
		
		//$user =& JFactory::getUser();
		//$recipient = $user->email;
		 
		$mailer->addRecipient($recipient);
				
		//multiple recipients
		/*$recipient = array( 'person1@domain.com', 'person2@domain.com', 'person3@domain.com' );
		$mailer->addRecipient($recipient);*/
		
		//$body   = "Your Report has been submitted succesfully. We will take actions after checking";
		$mailer->setSubject($subject);
		$mailer->setBody($body);


		$app = JFactory::getApplication();
		//$params = $app->getParams();
		$params = JComponentHelper::getParams('com_questions');
		//$this->setState('params', $params);
		$use_mail_system =  $params->get('use_mail_system', 1);
		
		if($use_mail_system == 1){
		
		if($mailer->isHTML(true)){
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		$mailer->setSubject($subject);
		}

		$send = $mailer->Send();
		if ( $send != true ) {
			$msg = JText::_("Error sending email");
			$url = JRoute::_("index.php?option=com_questions&view=bugreports",false);
			$this->setRedirect($url , $msg);
		} else {
			$msg = JText::_("Mail Sent Successfully");
			$url = JRoute::_("index.php?option=com_questions&view=bugreports",false);
			$this->setRedirect($url , $msg);
		}
		}

	}
	function bugreport(){
		//get user object
		$user = JFactory::getUser();
		//authorization
		if (!$user->authorise('core.admin', 'com_questions')) { 
		       return JError::raiseWarning(404, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
	    }
		
			//$data =  JFactory::getApplication()->input->get('username', 'post');
			$username = JFactory::getApplication()->input->get('username', 'post');
			$company = JFactory::getApplication()->input->get('companyname', 'post');
			$phonenumber = JFactory::getApplication()->input->get('phonenumber', 'post');
			$email = JFactory::getApplication()->input->get('emailaddress', 'post','STRING');
			$subject = JFactory::getApplication()->input->get('subject', 'post');
			$summary = JFactory::getApplication()->input->get('bug_summary', 'post');
			$reproducibility = JFactory::getApplication()->input->get('bug_repoducibility', 'post');
			$result = JFactory::getApplication()->input->get('bug_outcome', 'post');
			$suggestion = JFactory::getApplication()->input->get('bug_solution_suggest', 'post');
			$priority = JFactory::getApplication()->input->get('bug_priority', 'post');
			$bugtype = JFactory::getApplication()->input->get('bug_type', 'post');
			/*Mail Define**/
			$body = "<h1>Bug Report</h1>"."<br />";
			$body.= "Priority : ".$priority."<br />";
			$body.= "Username : ".$username."<br />";
			$body.= "Company : ".$company."<br />";
			$body.= "Reproducibility : ".$reproducibility."<br />";
			$body.= "Result : ".$result."<br />";
			$body.= "Suggestion : ".$suggestion."<br />";
			$body.= "Bug Type : ".$bugtype."<br />";
			
			
			$message = $summary;

			$recipient = "admin@phpseo.net";
			
			require_once JPATH_SITE.'/components/com_questions/helpers/mail.php';
			$this->sendemailredirect($body,$subject,$recipient,$message);
			//JFactory::getApplication()->close();
	}
}
