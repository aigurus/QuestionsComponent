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
jimport('joomla.application.component.controller');
require_once 'components/com_questions/helpers/mail.php';

class QuestionsControllerAnswer extends QueController
{
	public function __construct(){
		parent::__construct();
	}
	
	public function save(){
		
		//CSRF Anti-spoofing
		JSession::checkToken() or die( 'Invalid Token' );
		$parent = (int) JRequest::getInt("question_id");
		$params = JFactory::getApplication()->getParams();
		//parse config options
		$recaptcha = (int) $params->get('recaptcha');
		$config = JFactory::getConfig();
	
		if ($config->get('captcha') != '0') {
		if($recaptcha){
		$post = JFactory::getApplication()->input->post;
		$responsefield = $post->get('recaptcha_response_field','', 'RAW');
		
		if (empty($responsefield) || (strlen($responsefield)== 0) ){
			$link = JRoute::_('index.php?option=com_questions&view=question&id='.$parent);
			$msg  = "No captcha entered. Please enter the captcha to proceed.";
			$this->setRedirect($link, $msg, 'error');
			return;
		} else {
		$post = JRequest::get('post');      
		JPluginHelper::importPlugin('captcha');
		$dispatcher = JDispatcher::getInstance();
		$res = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);
		if(!$res[0]){
			$link = JRoute::_('index.php?option=com_questions&view=question&id='.$parent);
			$msg  = "Incorrect captcha entered. Please enter the captcha again.";
			$this->setRedirect($link, $msg, 'error');
			return;
		}
		}
		}
		}
		$jinput = JFactory::getApplication()->input;
		//authorization
		$user = JFactory::getUser();
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		       //return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
			   $link = "index.php?option=com_users&view=login";
			   $msg="You need to login";
			   $this->setRedirect($link, $msg, 'error');
	    }
		//Transparent Captcha
		$captcha = $jinput->get("LastName");
		if ($captcha){
			JError::raiseError(404 , ""); 
		}
		
	 	$title = JRequest::getString('title');
        
        $text=JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$refurl1=JRequest::getVar( 'refurl1', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$refurl2=JRequest::getVar( 'refurl2', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$refurl3=JRequest::getVar( 'refurl3', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$regex  = "((https?|ftp)\:\/\/)?"; 
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; 
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; 
        $regex .= "(\:[0-9]{2,5})?"; 
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; 
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; 
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; 
		
		//$name = JRequest::getString("name");
        
		
		$url = JRoute::_('index.php?option=com_questions&view=question&id='.$parent);
		$title2 = JText::_('COM_QUESTIONS_A_NEW_ANSWER_HAS_BEEN_SUBMITTED').'<BR />'.JText::_('COM_QUESTIONS_TITLE').' <a href="'.$url.'">'.$title.'</a>';
		$app = &JFactory::getApplication();
		$params = $app->getParams();
		$jomsocial_activity = $params->get('jomsocial_acivity', 0);
		if($jomsocial_activity == 1){
		JomsocialActivity::QuestionsUpdater( $parent, $title2 );
		}		
		
		
        $submitted = date("Y-m-d H:i");
        $userid_creator = JFactory::getUser()->id;
		$guest = JFactory::getUser()->guest;
		if(!$guest){
		$name = JFactory::getUser()->name;
		}else{
		$name = JRequest::getString("name");
		}

        $ip = JRequest::getString("ip");
        $email = JRequest::getString("email");
        $catid = JRequest::getInt("catid");

        //parse config options
		$params = json_decode(JFactory::getApplication()->getParams());
		$answeringpoints = (int) $params->answeringpoints;
		$bestchosenpoints = (int) $params->bestchosenpoints;
		//Default state
        $published = (int) $params->defaultAnswerState;

        //VALIDATE DATA
        $valid = TRUE;
        $msg = "";

        if ((!JFactory::getUser()->id) && (!$name)) {
            $valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_NONAME");
        }

        if (!$text) {
            $valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_NOTEXT");
        }

        if (!$title) {
            $valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_NOTITLE");
        }
        
        $mailregex = '/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/';
		if (! preg_match($mailregex, $email) ) {
            $valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_INCORRECTMAIL");
        }
		if(! preg_match("/^$regex$/", trim($refurl1)) && $refurl1 != "")
        {
        	$valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_INCORRECTURL");
        }
		if(! preg_match("/^$regex$/", trim($refurl2)) && $refurl2 != "")
        {
        	$valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_INCORRECTURL");
        }
		if(! preg_match("/^$regex$/", trim($refurl3)) && $refurl3 != "")
        {
        	$valid = FALSE;
            $msg.="<br />" . JText::_("COM_QUESTIONS_ERR_ANSWER_INCORRECTURL");
        }
        if (!$valid) {
            $return = JRoute::_("index.php?option=com_questions&view=question&name=$name&title=$title&text=$text&id=" . $parent);
            parent::setRedirect($return, JText::_("COM_QUESTIONS_ERR_FILL_ALL_REQ_FIELDS") . $msg, "ERROR");
            return;
        }

        $db = JFactory::getDBO();

        $data = new stdClass;

        $data->id = NULL;
        $data->title = $title;
        $data->text = $text;
        $data->submitted = $submitted;
        $data->modified = NULL;
        $data->userid_creator = $userid_creator;
        $data->userid_modifier = NULL;
        $data->question = 0;
        $data->votes_positive = 0;
        $data->votes_negative = 0;
        $data->parent = $parent;
        $data->impressions = 0;
		$data->refurl1 = $refurl1;
		$data->refurl2 = $refurl2;
		$data->refurl3 = $refurl3;
        $data->published = $published;
        $data->chosen = 0;
        $data->name = $name;
        $data->ip = $ip;
        $data->email = $email;
        $data->catId = $catid;
        
        if ($db->insertObject('#__questions_core', $data)) {
            $message = JText::_("COM_QUESTIONS_MSG_ANSW_SAVED");
            $type = NULL;
        } else {
            $message = JText::_("COM_QUESTIONS_MSG_ANSW_NOSAVE");
            $type = "ERROR";
        }
		
		$pdatauserrank = getPoints::setRank($userid_creator,$answeringpoints);
				
		$pdata = new stdClass;
        $pdata->id = NULL;
		$pdata->userid = $userid_creator;
		$pdata->username = $name;
		$pdata->answered = $pdata->answered+1;
		$pdata->asked = NULL;
        $pdata->points = $pdata->points+$answeringpoints;
        $pdata->rank = $pdatauserrank;
        $pdata->image = $image;
        $pdata->email = $email;
		$pdata->impressions = NULL;
		
		$db->setQuery("SELECT COUNT(*) FROM `#__questions_userprofile` WHERE userid = '".$userid_creator."'" );
		$count = $db->loadResult();

		if($count>0){
		$query = "UPDATE `#__questions_userprofile` SET answered = answered+1, rank = '".$pdata->rank."' , points = points+'".$answeringpoints."' WHERE userid = '".$userid_creator."'" ;
		$db->setQuery($query);
		$db->execute();
		}
		elseif($db->insertObject('#__questions_userprofile', $pdata)) {
            $type = NULL;
        } 
		else 
		{
            $type = "ERROR";
        }

        $return = JRequest::getString("returnTo");
        parent::setRedirect($return, $message, $type);
		
		
		$application =JFactory::getApplication();
		$application->enqueueMessage(JText::_('COM_QUESTIONS_YOU_JUST_RECIEVED').$answeringpoints);
		$application->enqueueMessage(JText::_('COM_QUESTIONS_POINTS_FOR_ANSWERING_A_QUESTION'));
		
		/*MAIL PART*/
		
		$user = JFactory::getUser();
		$recipient = $email;
		$subject = $data->title;
		$message="Problem sending email. Check SMTP, sendmail settings";
		$body=JText::_('COM_QUESTIONS_FORM_SUBMIT_ANSWER')."<br />"."<span><small>".$data->text."</small></span>";
		MailHelper::sendemail($body,$subject,$recipient,$message);
		
		/*Sending mail to the one who asked it*/
		$model = $this->getModel('question');
		$solution = $model->getItem($parent);
	
		$recipient = $solution->email;
		$subject = $data->title;
		$message="Problem sending email. Check SMTP, sendmail settings";
		$return =  JURI::base().("index.php?option=com_questions&view=question&id=" . $parent);
		$body=JText::_('COM_QUESTIONS_FORM_SUBMIT_ANSWER_ASKING_PERSON')."<br />"."<br />".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK_MESSAGE')."<br />"."<a href=".$return.">".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK')."</a>";
		MailHelper::sendemail($body,$subject,$recipient,$message);
		
	}
	
	public function choose(){
		
		$params = json_decode(JFactory::getApplication()->getParams());
		$bestchosenpoints = (int) $params->bestchosenpoints;
		$bestchooserpoints = (int) $params->bestchooserpoints;
		$user = JFactory::getUser();
		$userid_creator =$user->id;
		
		$ok = FALSE;
		
		//IDs..
		$qid = JRequest::getInt("questionid");
		$aid = JRequest::getInt("answerid");
		
		$q = "UPDATE #__questions_core SET chosen=0 WHERE parent=$qid";
		$db = JFactory::getDbo();
		$db->setQuery($q);
		
		if ($db->execute())
			$ok = TRUE;
		else 
			$err = " - " . $db->getErrorMsg();
		
		if ($ok){
			$q="UPDATE #__questions_core SET chosen=1 WHERE id=$aid";
						
			$db->setQuery($q);
						
			if ($db->execute()){
			/********************For one who has answered the Question*************************/	
			$pdatauserrank2 = getPoints::setRank2($aid,$bestchosenpoints);		
				
			
			$q2="UPDATE #__questions_userprofile AS m LEFT JOIN #__questions_core AS n ON m.userid=n.userid_creator SET m.chosen=m.chosen+1, m.points = m.points+'".$bestchosenpoints."', m.rank ='".$pdatauserrank2."' where n.id=$aid";
			$db->setQuery($q2);
			$db->execute();
			
			/********************For one who has chosen the best answer*************************/	
			
			$pdatauserrank3 = getPoints::setRank($userid_creator,$bestchooserpoints);			
				
			
			$q2="UPDATE #__questions_userprofile SET points = points+'".$bestchooserpoints."', rank ='".$pdatauserrank3."' where userid=".$user->id;
			$db->setQuery($q2);
			$db->execute();
			/****************************************/
			
			$ok = TRUE;
			}
			else 
			{
				$err = $db->getErrorMsg();
			}
		}
			
		if ($ok){
			$msg = JText::_("COM_QUESTIONS_ANSWER_CHOOSE_OK");
			
			$q = "SELECT email FROM #__questions_core WHERE id=$aid";
			$db = JFactory::getDbo();
			$db->setQuery($q);
			$userchosen = $db->loadResult();	
			
			$recipient1 = $user->email;
			$subject = $data->title;
			$message="Problem sending email. Check SMTP, sendmail settings";
			$body=JText::_('COM_QUESTIONS_SUBMIT_ANSWER_CHOSING_BEST')."<br />"."<span><small>".$data->text."</small></span>";
			MailHelper::sendemail($body,$subject,$recipient1,$message);
		
			$recipient2 = $userchosen;
			$subject = $data->title;
			$message="Problem sending email. Check SMTP, sendmail settings";
			$return = JURI::base().("index.php?option=com_questions&view=question&id=" . $qid);
			$body=JText::_('COM_QUESTIONS_SUBMIT_ANSWER_CHOSEN_BEST')."<br />"."<br />".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK_MESSAGE')."<br />"."<a href=".$return.">".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK')."</a>";
			MailHelper::sendemail($body,$subject,$recipient2,$message);
			
		}
		else {
			$msg = JText::_("COM_QUESTIONS_ANSWER_CHOOSE_NOK") . $err;
		}
		$this->setRedirect( JRoute::_("index.php?option=com_questions&view=question&id=$qid") , $msg);
	}
	
	public function chooseReset(){
		$ok = FALSE;
		$parent = (int) JRequest::getInt("question_id");
		//IDs..
		$qid = JRequest::getInt("questionid");
		$aid = JRequest::getInt("answerid");
		
		$params = json_decode(JFactory::getApplication()->getParams());
		$bestchosenpoints = (int) $params->bestchosenpoints;
		$bestchooserpoints = (int) $params->bestchooserpoints;
		$user = JFactory::getUser();
		$userid_creator = JFactory::getUser()->id;
		
		$q = "UPDATE #__questions_core SET chosen=0 WHERE parent=$qid";
		$db = JFactory::getDbo();
		$db->setQuery($q);
		
		if ($db->execute()){
		
	
		$pdatauserrank4 = getPoints::setRank2($aid,-$bestchosenpoints);	
			
			
			$q2="UPDATE #__questions_userprofile AS m LEFT JOIN #__questions_core AS n ON m.userid=n.userid_creator SET m.chosen=m.chosen-1, m.points = m.points-'".$bestchosenpoints."', m.rank ='".$pdatauserrank4."' where n.id=".$aid;
			$db->setQuery($q2);
			$db->execute();
			
		 /********************Points Reset For one who has chosen the best answer*************************/	
		 
		 	$pdatauserrank5 = getPoints::setRank($userid_creator,-$bestchooserpoints);		
			$q2="UPDATE #__questions_userprofile SET points = points-'".$bestchooserpoints."', rank ='".$pdatauserrank5."' where userid=".$user->id;
			$db->setQuery($q2);
			$db->execute();
			/****************************************/
			
			
			$ok = TRUE;
		}
		else {
			$err = " - " . $db->getErrorMsg();
		}
			
		if ($ok) {
			$msg = JText::_("COM_QUESTIONS_ANSWER_CHOOSERESET_OK");
			
			
			$q = "SELECT email FROM #__questions_core WHERE id=$aid";
			$db = JFactory::getDbo();
			$db->setQuery($q);
			$userchosen = $db->loadResult();	
			
			$recipient3 = $user->email;
			$subject = $data->title;
			$message="Problem sending email. Check SMTP, sendmail settings";
			$body=JText::_('COM_QUESTIONS_SUBMIT_ANSWER_CHOSING_BEST_RESET')."<br />"."<span><small>".$data->text."</small></span>";
			MailHelper::sendemail($body,$subject,$recipient3,$message);

		
			$recipient4 = $userchosen;
			$subject = $data->title;
			$message="Problem sending email. Check SMTP, sendmail settings";
			$return = JURI::base().("index.php?option=com_questions&view=question&id=" . $qid);
			$body=JText::_('COM_QUESTIONS_SUBMIT_ANSWER_CHOSEN_BEST_RESET')."<br />"."<br />".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK_MESSAGE')."<br />"."<a href=".$return.">".JText::_('COM_QUESTIONS_SUBMIT_ANSWER_VIEW_LINK')."</a>";
			MailHelper::sendemail($body,$subject,$recipient4,$message);

		}
		else {
			$msg = JText::_("COM_QUESTIONS_ANSWER_CHOOSERESET_NOK") . $err;
		}
		$this->setRedirect( JRoute::_("index.php?option=com_questions&view=question&id=$qid") , $msg);
	}
	
}

       