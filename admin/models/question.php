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

// import the Joomla modellist library
jimport('joomla.application.component.modeladmin');

class QuestionsModelQuestion extends JModelAdmin {
	
	protected $item;	//The question
	
	/*
	 * Method to return a JTable instance of the question table
	 */
	public function getTable( $type = "Question" , $prefix = "QuestionsTable" , $config = array() ){
		
		return JTable::getInstance($type, $prefix , $config);
	}
	
	/*
	 * Method to return a form object
	 */
	public function getForm ( $data = array(), $loadData = TRUE ){


		$form = $this->loadForm("com_questions.question" , "question" , array("control"=>"jform" , "loadData"=> $loadData) );
		
		if ( empty($form)){
			return FALSE;
		}
				
		//Fill the form with data
		if ( $data = $this->loadFormData() ){
			
			$user = JFactory::getUser();
			
			if (! $data instanceof JObject)
				$data = JArrayHelper::toObject($data);
			
			//Fix issue #10 - https://github.com/alexd3499/Questions/issues/10
			if ( $data->title ){
				//Existing Item..
				//Fill in the apropriate information concerning the modifications

				$data->userid_modifier = $user->id;
				$data->modified = date("Y-m-d H:i:s");
				
				//tags
				if (is_array($data->qtags)){
					$data->qtags = implode(", " , $this->cleanString($data->qtags));
				}
			}
			
			if ( $data->id==0 ) { 
				//New Item.. Fill the form with some defaults values..
				
				$data->userid_creator = $user->id;
				$data->submitted = date("Y-m-d H:i:s");
				
				//get email from user object
				$data->email = $user->email;
				
				$app = JFactory::getApplication();
				$parent = $app->getUserState("parentID");
				$question = $app->getUserState("isQuestion");
				$catid = $app->getUserState("catid");
				
				if (!$parent){
					$parent=0;
				}
				
				//Set data
				$data->parent = $parent;
				$data->question = $question;
				$data->catid = $catid;
				
				//Clear User State
				$app->setUserState("isQuestion", 1);
				$app->setUserState("parentID", 0);
				$app->setUserState("catid", 0);
				
				
			}
			
			$this->preprocessForm($form, $data);
			$form->bind($data);
		}
		else {
		}
		
		return $form;		
	}
	private function cleanString($string) {
		  	$clear = strip_tags($string);
			// Clean up things like &amp;
			$clear = html_entity_decode($clear);
			// Strip out any url-encoded stuff
			$clear = urldecode($clear);
			// Replace non-AlNum characters with space
			$clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
			// Replace Multiple spaces with single space
			$clear = preg_replace('/ +/', ' ', $clear);
			// Trim the string of leading/trailing space
			$clear = trim($clear);
			return $clear;
	}
	/*
	 * Method to load the data of the form
	 */
	protected function loadFormData() {

		
		$data = JFactory::getApplication()->getUserState("com_questions.edit.question.data" , array() );
		
		if (empty($data)){
			$data = $this->getItem();			
		}
		
		return $data;	
	}
	
	/*
	 * Method to return a QUESTION item
	 * 
	 * Here is used only for reference / logging
	 * It just invokes the parent method
	 * 
	 */
	public function getItem($pk=NULL){

		return parent::getItem();
	}
	
}