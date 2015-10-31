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

abstract class QuestionsHelperRank extends QuestionsHelper{
	
	public static function canDo($action){
		$user = JFactory::getUser();
		return $user->authorise($action , "com_questions");
	}
	
	public static function getCurrentPageURL(){
		$pageURL = 'http';
 		if (isset($_SERVER["HTTPS"]) &&  $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 		}
 		return $pageURL;
	}

	/**
	 * Method to add a pathway to the current view
	 *
	 *	@param $viewObject: a reference to the current view object
	 *
	 */
	public function addPathway(){
		
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
				
		$viewOptions = QuestionsHelper::getActiveViewOptions( TRUE );

		//00. Generic
		$pathway->addItem( JText::_("COM_QUESTIONS_RANK") , JRoute::_("index.php?option=com_questions&view=rank") );
       	
    	
        //03. Filter
        if ($viewOptions->filter){
        	$pathway->addItem( JText::_("COM_QUESTIONS_FILTER_" .$viewOptions->filter ) );
        }
       	
	}
	
	/**
	 * Method to get the active vie options
	 *
	 *	@param bool $viewObject: 	if true, an object will be returned
	 *								if false, query string vars will be returned
	 *
	 */
	public static function getActiveViewOptions( $returnObject = FALSE ){
		$viewOptions = new stdClass();
		$viewOptions->id = JRequest::getWord("id");
		$viewOptions->points = JRequest::getWord("points");
		$viewOptions->filter = JRequest::getInt("filter");
		
		if ($returnObject){
			return $viewOptions;
		}
		else {
			return 	"&id=" 	. $viewOptions->id	. 
				 	"&points="		. $viewOptions->points		.
					"&filter="	. $viewOptions->filter	;
		}
	}
	
}