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

jimport('joomla.application.component.view');
jimport('joomla.mail.helper');

class QuestionsViewReports extends QueView
{
	protected $form;
	protected $item;

	public function display( $tpl = NULL ){
		
		
		$this->form = $this->get("Form");
		$this->item = $this->get("Item");
		
		$app = JFactory::getApplication();
		//params
		$params = $app->getParams();
		$this->assignRef("params", $params);
		
		$user = JFactory::getUser();
		$qid = JFactory::getApplication()->input->get("qid");
		
		$session =JFactory::getSession();//Thanks to http://www.tutsforu.com/adding-a-view-to-the-site/75-using-session-in-joomla.html
		$token = $session->getToken();
		
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		    $app =JFactory::getApplication();
			$app->redirect('index.php?option=com_users&view=login');
		}
		/*if (!$user->authorise("core.create" , "com_questions")){
			JError::raiseError(403, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
			return false;
		}*/
		
		if ($user->id){
			$this->form->setFieldAttribute("name", "type", "hidden");
		}
		
			
		//Page class suffix
		$this->assignRef("pageclass_sfx", htmlspecialchars($params->get('pageclass_sfx')));
		
		$this->assignRef("user", $user);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);
	}
	
	public function sendemail($subject,$body){
	
		$mailer =JFactory::getMailer();
		
		//$subject = "Re: Report Successfully Submitted";
		//$body = "Your Report has been submitted succesfully. We will take actions after checking.";
		
		$config =JFactory::getConfig();
		$sender = array( 
		$config->get( 'config.mailfrom' ),
		$config->get( 'config.fromname' ) );
		$mailer->setSender($sender);
		
		$user =JFactory::getUser();
		$recipient = $user->email;
		 
		$mailer->addRecipient($recipient);
				
		//multiple recipients
		/*$recipient = array( 'person1@domain.com', 'person2@domain.com', 'person3@domain.com' );
		$mailer->addRecipient($recipient);*/
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		$mailer->Send();

		/*
		# Set some variables for the email message
		$subject = "You have a new message";
		$body = "Here is the body of your message.";
		$to = "someone@yourdomain.com";
		$from = array("me@mydomain.com", "Brian Edgerton");
		 
		# Invoke JMail Class
		$mailer = JFactory::getMailer();
		 
		# Set sender array so that my name will show up neatly in your inbox
		$mailer->setSender($from);
		 
		# Add a recipient -- this can be a single address (string) or an array of addresses
		$mailer->addRecipient($to);
		 
		$mailer->setSubject($subject);
		$mailer->setBody($subject);
		 
		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML();
		 
		# Send once you have set all of your options
		$mailer->send();
		 
		
		That's all there is to it for sending a simple email. If you would like to add carbon copy recipients, include the following before sending the email:
		
		$mailer->addCC("carboncopy@yourdomain.com");
		 
		# Add a blind carbon copy
		$mailer->addBCC("blindcopy@yourdomain.com");
		 
		
		Need to add an attachment? No problem:
		
		 
		
		$file = JPATH_SITE."/path/to/file.doc";
		$mailer->addAttachment($file);
		 
		
		As you can see, it really can't get much simpler or straightforward for sending an email. There are several more methods available in JMail. You should also check out JMailHelper. It provides several functions to help you secure input from users before passing it along in an email. Consider the following:
		
		# Import JMailHelper
		jimport('joomla.mail.helper');
		 
		$to = JFactory::getApplication()->input->get('to', '', 'post');
		$subject = JFactory::getApplication()->input->get('subject', '', 'post');
		$body = JFactory::getApplication()->input->get('body', '', 'post');
		$from = JFactory::getApplication()->input->get('from', '', 'post');
		 
		if (!JMailHelper::isEmailAddress($to) || !JMailHelper::isEmailAddress($from)) :
			return false;
		endif;
		 
		if (!JMailHelper::cleanAddress($to) || !JMailHelper::cleanAddress($from)) :
			return false;
		endif;
		 
		$subject = JMailHelper::cleanSubject($subject);
		$body = JMailHelper::cleanText($body); */
	}
	
}