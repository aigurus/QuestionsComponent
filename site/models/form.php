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


jimport('joomla.application.component.modeladmin');

class QuestionsModelForm extends JModelAdmin
{

	protected $item;	//The question
	protected $points;
	/*
	 * Method to return a JTable instance of the question table
	 */
	protected function aliascheck($question)
	{
		jimport( 'joomla.filter.output' );
		$alias = JFilterOutput::stringURLSafe($question);
		return $alias;
	}
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
				$alias = $this->aliascheck($data->title); 
			//$jinput = JFactory::getApplication()->input;
			//$jinput->set('alias', $alias);
				$data->alias = $alias;
								
				$data->userid_modifier = $user->id;
				$data->modified = date("Y-m-d H:i:s");
				
				//proccess json tags..
				/*if (is_array($data->qtags)){
					$data->qtags = implode(", " , json_decode($data->qtags));
				}*/
				$qtags = $data->qtags;
				$arr=explode(",",$qtags);
				
				$qtags = ($arr);
				$qtags = NULL;
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
						$data->qtags = $qtags;
						//replace the original request
							
				}	
				
			}
			
			if ( $data->id==0 ) { 
				//New Item.. Fill the form with some defaults values..
				
				if ($user->id){
					$data->userid_creator = $user->id;
				} else {
					$data->userid_creator = 0;
				}
				$data->submitted = date("Y-m-d H:i:s");
				
				$data->parent = 0;
				$data->question = 1;
				$data->impressions = 0;
				$data->votes_positive = 0;
				$data->votes_negative = 0;
				$data->chosen = 0;
				//$data->asked = 1;
				
				//get email from user object
				if ($user->id)
					$data->email = $user->email;
				
				//get user's name
				if ($user->id)
					$data->name = $user->name;
					
				
			}
			
			$this->preprocessForm($form, $data);
			$form->bind($data);
		}
		else {
		}
		
		return $form;		
	}
	
	/*
	 * Method to load the data of the form
	 */
	protected function loadFormData() {
		
		$data = JFactory::getApplication()->getUserState("com_questions.edit.question.data" , array() );
		
		//we got the data, clear state
		JFactory::getApplication()->setUserState("com_questions.edit.question.data" , array() );
		
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
	public function getItem(){
		
		return parent::getItem();
	}
	public function getPoints(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('points');
		$query->from('#__questions_userprofile');
		$query->where('userid='.$user->id);
		$db->setQuery($query);
		$points = $db->loadResult();
		return $points;
	}

}