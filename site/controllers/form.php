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
require_once 'components/com_questions/helpers/mail.php';
class QuestionsControllerForm extends JControllerForm {
	
	protected function aliascheck($question)
	{
		jimport( 'joomla.filter.output' );
		$alias = JFilterOutput::stringURLSafe($question);
		return $alias;
	}
	
	/*protected function checkCaptcha($code) {
		$config = JFactory::getConfig();
	
		if ($config->get('captcha') != '0') {
			$dispatcher = JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('captcha');
	
			$result = $dispatcher->trigger('onCheckAnswer', array('captcha', $code));
		} else {
			$result = array(true);
		}
	
		return $result[0];
	}*/
	public function save(){ //Just For Logging Reference
		
		JSession::checkToken() or die( 'Invalid Token' );
		$params = JFactory::getApplication()->getParams();
		//parse config options
		$config = JFactory::getConfig();
	
		if ($config->get('captcha') != '0') {
		$recaptcha = (int) $params->get('recaptcha');
		if($recaptcha){
		$post = JFactory::getApplication()->input->post;
		$responsefield = $post->get('recaptcha_response_field','', 'RAW');
		
		if (empty($responsefield) || (strlen($responsefield)== 0) ){
			$link = "index.php?option=com_questions&view=form&layout=edit";
			$msg  = "No captcha entered. Please enter the captcha to proceed.";
			$this->setRedirect($link, $msg, 'error');
			return;
		} else {
		$post = JRequest::get('post');      
		JPluginHelper::importPlugin('captcha');
		$dispatcher = JDispatcher::getInstance();
		$res = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);
		if(!$res[0]){
			$link = "index.php?option=com_questions&view=form&layout=edit";
			$msg  = "Incorrect captcha entered. Please enter the captcha again.";
			$this->setRedirect($link, $msg, 'error');
			return;
		}
		}
		}
		}
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
		
		//make Alias
		$data = JFactory::getApplication()->input->get('jform', array(), 'post', 'array');
		//var_dump($data); exit;
		if (!empty($data['title'])){
			$alias = $this->aliascheck($data['title']);
		}
			
		//load request data
		//$data = JFactory::getApplication()->input->get('jform', array(), 'post', 'array');
		$params = json_decode(JFactory::getApplication()->getParams());
		//parse config options
		$askingpoints = (int) $params->askingpoints;
		
		//Determine default state 
		// TODO: 	Check if the user is allowed to alter the state
		//			- If so, then do not use the default state, but leave the state as is
		$publishstatus = (int) $params->defaultQuestionState;
		
		//Encode the tags to json..
		$qtags = $data["qtags"];
		
		$qtagsPlainText = $qtags; 	// preserve original tag data as inserted by the user
		//$qtags = array_filter($qtags);
		//$qtags = array_filter(explode(',', $qtags));

		// in order the form to contain the exact tag info, 
									// i.e. data in plain text format and NOT json
		if ($qtags){
			// Strip HTML Tags
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
			
		}	
		//$jinput = JFactory::getApplication()->input;
		//JRequest::setVar("jform" , $data);	
		//$jinput->set('jform', $data);
		
		if (parent::save()) {
			if ($data['id']) { //do we have an id?
				$lastid = $data['id'];	//if so, use it
			}
			else { //if no, use the last inserted id
				$db = JFactory::getDbo();
				$lastid = $db->insertid();
			}
			
			$db = JFactory::getDbo();
			$query = "UPDATE `#__questions_core` SET published='".$publishstatus."', qtags ='". $qtags."', alias ='".$alias."' WHERE id = '".$lastid."'" ;
			$db->setQuery($query);
			$db->execute();
			//redirect & display the inserted data
			$this->setRedirect(JRoute::_("index.php?option=com_questions&view=question&id=".QuestionsHelper::getAlias($lastid)));
			//clear state
			JFactory::getApplication()->setUserState("com_questions.edit.question.data", array());
		}
		else {
			//store temp data
			$data['qtags'] = $qtagsPlainText;
			JFactory::getApplication()->setUserState("com_questions.edit.question.data", $data);
		}
		/*$db = JFactory::getDbo();
		$lastids = $db->insertid();
	
		$ndata->id = $lastids;
		$db->updateObject( '#__questions_core', $ndata, id );*/
		//exit;
		$pdatauserrank = getPoints::setRank($user->id,$askingpoints);
		
		$pdata = new stdClass;
        $pdata->id = NULL;
		$pdata->userid = $user->id;
		$pdata->username = $user->name;
		$pdata->answered = NULL;
		$pdata->asked = 1;
        $pdata->points = $askingpoints;
        $pdata->rank = $pdatauserrank;
        $pdata->logdate = NULL;
        $guest = JFactory::getUser()->guest;
		if(!$guest){
			 $pdata->email = $user->email;
		}else{
			 $pdata->email = $data['email'];
		}
		
        //$pdata->email = $user->email;
		
		/*for updating question count*/
		$db = JFactory::getDbo();
		$db->setQuery("SELECT COUNT(*) FROM `#__questions_userprofile` WHERE userid = '".$user->id."'" );
		$count = $db->loadResult();

		if($count>0){
		$query = "UPDATE `#__questions_userprofile` SET asked = asked+1, rank = '".$pdata->rank."' , points = points+'".$askingpoints."' WHERE userid = '".$user->id."'" ;
		$db->setQuery($query);
		$db->execute();
		}
		else{
		(
		 $db->insertObject('#__questions_userprofile', $pdata)) ;
        }
		
		$url = JRoute::_('index.php?option=com_questions&view=question&id='.$lastid);
		$title2 = JText::_('COM_QUESTIONS_A_NEW_QUESTION_HAS_BEEN_ASKED').'<BR />'.JText::_('COM_QUESTIONS_TITLE').' <a href="'.$url.'">'.$data["title"].'</a>';
		$app = &JFactory::getApplication();
		$params = $app->getParams();
		$jomsocial_activity = $params->get('jomsocial_acivity', 0);
		if($jomsocial_activity == 1){
			JomsocialActivity::QuestionsUpdater( $lastid, $title2 );
		}
		
		$application =JFactory::getApplication();
		$application->enqueueMessage(JText::_('COM_QUESTIONS_YOU_JUST_RECIEVED').$askingpoints);
		$application->enqueueMessage(JText::_('COM_QUESTIONS_POINTS_FOR_ASKING_A_QUESTION'));
		
		$user = JFactory::getUser();
		$guest = $user->guest;
		if(!$guest){
		$recipient = JFactory::getUser()->email;
		}else{
		$recipient = $data["email"];
		}
		
		//$recipient = $user->email;
		$subject = $data["title"];
		$message="Problem sending email. Check SMTP, sendmail settings";
		$body= JText::_('COM_QUESTIONS_FORM_SUBMIT_QUESTION')."<br />".JText::_('COM_QUESTIONS_QUESTION').":-"."<br />"."<span><small>".$data["text"]."</small></span>";
		MailHelper::sendemail($body,$subject,$recipient,$message);
		//}
	}
	
	public function cancel(){
		echo "<script type='text/javascript'>javascript:history.go(-2);</script>";
		//clear state
		JFactory::getApplication()->setUserState("com_questions.edit.question.data", array());
	}
	
}