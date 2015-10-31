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

abstract class QuestionsHelper {
	
	public static $extension = 'com_questions';
	public static function addSubmenu($submenu){		
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_MAIN'), 'index.php?option=com_questions&view=main', $submenu == "Main");		
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_QUESTIONS'), 'index.php?option=com_questions&view=questions', $submenu == "Questions");
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_ANSWERS'), 'index.php?option=com_questions&view=questions&answers=1', $submenu == "Answers");
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_questions', $submenu == 'categories');
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_PROFILES'), 'index.php?option=com_questions&view=profiles', $submenu == 'Profiles');
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_RANK'), 'index.php?option=com_questions&view=rank', $submenu == 'Rank');
		/*JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_SETTINGS'), 'index.php?option=com_questions&view=settings', $submenu == 'Settings');*/
		JSubMenuHelper::addEntry(JText::_('COM_QUESTIONS_REPORTS'), 'index.php?option=com_questions&view=reports', $submenu == 'Reports');
	}
	
	public static function getActiveSubmenu(){
		$option =  JFactory::getApplication()->input->get("option");
		$view =  JFactory::getApplication()->input->get("view");
		if ( JRequest::getInt("answers" , 0) ){
			return "Answers";
		}
		elseif ( $option == "com_categories" ){
			return "Categories";
		}
		elseif ( $view == "profiles"){
			return "Profiles";
		}
		elseif ( $view == "questions"){
			return "Questions";
		}
		elseif ( $view == "rank"){
			return "Rank";
		}
		/*elseif ( $view == "settings"){
			return "Settings";
		}*/
		elseif ( $view == "reports"){
			return "Reports";
		}
		else {
			return "Main";		
		}
	}
	
	public static function canDo($action){
		$user = JFactory::getUser();
		return $user->authorise($action , "com_questions");
	}
	
	/*public static function getActions($messageId = 0)        
	{  
	jimport('joomla.access.access');
	$user   = JFactory::getUser();
	$result = new JObject;
	if (empty($messageId)){
			$assetName = 'com_questions';
		}
	else
		{
			$assetName = 'com_questions.message.'.(int) $messageId;
	}$actions = JAccess::getActions('com_questions', 'component');
	foreach ($actions as $action){
		$result->set($action->name, $user->authorise($action->name, $assetName));
		}
		return $result;
	}
	*/
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
	public static function addPathway(){
		
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
				
		$viewOptions = QuestionsHelper::getActiveViewOptions( TRUE );

		//00. Generic
		$pathway->addItem( JText::_("COM_QUESTIONS_QUESTIONS") , JRoute::_("index.php?option=com_questions&view=questions") );
       	
		//01. Category
       	if ($viewOptions->catid) {
       		$pathway->addItem( JText::_("COM_QUESTIONS_CATEGORIES") );
       		$pathway->addItem(QuestionsHelper::getCategoryName($viewOptions->catid) , JRoute::_("index.php?option=com_questions&view=questions&catid=" . $viewOptions->catid) );
       	}
		
       	//02. Tag
       	if ($viewOptions->tag){
       		$pathway->addItem( JText::_("COM_QUESTIONS_TAGS") );
       		$pathway->addItem( $viewOptions->tag , JRoute::_("index.php?option=com_questions&view=questions&tag=" . $viewOptions->tag ) );  
       	}
       	
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
		$viewOptions->filter = JRequest::getWord("filter");
		$viewOptions->tag = JRequest::getWord("tag");
		$viewOptions->catid = JRequest::getInt("catid");
		
		if ($returnObject){
			return $viewOptions;
		}
		else {
			return 	"&catid=" 	. $viewOptions->catid	. 
				 	"&tag="		. $viewOptions->tag		.
					"&filter="	. $viewOptions->filter	;
		}
	}
	
	public static function getCategoryName ( $catid ){
		if ($catid==0)
			return;
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(TRUE);
		$query->select("cats.title");
		$query->from("#__categories AS cats");
		$query->where("cats.id=$catid");
		
		$db->setQuery($query);
		$catname = $db->loadObject();
		
		return $catname->title;
		
		/*
		$category = JCategories::getInstance('Questions')->get($catid);
		
		return $category->title;
		*/
	}
	
	/**
	 * 
	 * Method to return the title of an entry / row (question or answer)
	 * 
	 * @param $id int The row ID
	 * 
	 */
	public static function getTitle ( $id ) {
		 if ($id == 0)
		 	return; 
		 
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(TRUE);
		$query->select("title");
		$query->from("#__questions_core");
		$query->where("id=$id");
		
		$db->setQuery($query);
		$row = $db->loadObject();
		
		return $row->title;	
		 	
	}
	public static function getAlias ( $id ) {
		 if ($id == 0)
		 	return; 
		 
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(TRUE);
	 
		$query = 'select alias, CASE WHEN CHAR_LENGTH(alias)>0 THEN CONCAT_WS(":", id, alias) ELSE id END as slug from #__questions_core WHERE id='.$id;

		$db->setQuery($query);
		$row = $db->loadObjectList();
		
		return $row[0]->slug;	
		 	
	}
	public static function questionsfilterText($text)
	{
		// Filter settings
		$config		= JComponentHelper::getParams('com_config');
		$user		= JFactory::getUser();
		$userGroups	= JAccess::getGroupsByUser($user->get('id'));

		$filters = $config->get('filters');

		$blackListTags			= array();
		$blackListAttributes	= array();

		$customListTags			= array();
		$customListAttributes	= array();

		$whiteListTags			= array();
		$whiteListAttributes	= array();

		$noHtml				= false;
		$whiteList			= false;
		$blackList			= false;
		$customList			= false;
		$unfiltered			= false;

		// Cycle through each of the user groups the user is in.
		// Remember they are included in the Public group as well.
		foreach ($userGroups as $groupId)
		{
			// May have added a group but not saved the filters.
			if (!isset($filters->$groupId)) {
				continue;
			}

			// Each group the user is in could have different filtering properties.
			$filterData = $filters->$groupId;
			$filterType	= strtoupper($filterData->filter_type);

			if ($filterType == 'NH') {
				// Maximum HTML filtering.
				$noHtml = true;
			}
			elseif ($filterType == 'NONE') {
				// No HTML filtering.
				$unfiltered = true;
			}
			else {
				// Black, white or custom list.
				// Preprocess the tags and attributes.
				$tags			= explode(',', $filterData->filter_tags);
				$attributes		= explode(',', $filterData->filter_attributes);
				$tempTags		= array();
				$tempAttributes	= array();

				foreach ($tags as $tag)
				{
					$tag = trim($tag);

					if ($tag) {
						$tempTags[] = $tag;
					}
				}

				foreach ($attributes as $attribute)
				{
					$attribute = trim($attribute);

					if ($attribute) {
						$tempAttributes[] = $attribute;
					}
				}

				// Collect the black or white list tags and attributes.
				// Each lists is cummulative.
				if ($filterType == 'BL') {
					$blackList				= true;
					$blackListTags			= array_merge($blackListTags, $tempTags);
					$blackListAttributes	= array_merge($blackListAttributes, $tempAttributes);
				}
				elseif ($filterType == 'CBL') {
					// Only set to true if Tags or Attributes were added
					if ($tempTags || $tempAttributes) {
						$customList				= true;
						$customListTags			= array_merge($customListTags, $tempTags);
						$customListAttributes	= array_merge($customListAttributes, $tempAttributes);
					}
				}
				elseif ($filterType == 'WL') {
					$whiteList				= true;
					$whiteListTags			= array_merge($whiteListTags, $tempTags);
					$whiteListAttributes	= array_merge($whiteListAttributes, $tempAttributes);
				}
			}
		}

		// Remove duplicates before processing (because the black list uses both sets of arrays).
		$blackListTags			= array_unique($blackListTags);
		$blackListAttributes	= array_unique($blackListAttributes);
		$customListTags			= array_unique($customListTags);
		$customListAttributes	= array_unique($customListAttributes);
		$whiteListTags			= array_unique($whiteListTags);
		$whiteListAttributes	= array_unique($whiteListAttributes);

		// Unfiltered assumes first priority.
		if ($unfiltered) {
			// Dont apply filtering.
		}
		else {
			// Custom blacklist precedes Default blacklist
			if ($customList) {
				$filter = JFilterInput::getInstance(array(), array(), 1, 1);

				// Override filter's default blacklist tags and attributes
				if ($customListTags) {
					$filter->tagBlacklist = $customListTags;
				}
				if ($customListAttributes) {
					$filter->attrBlacklist = $customListAttributes;
				}
			}
			// Black lists take third precedence.
			elseif ($blackList) {
				// Remove the white-listed attributes from the black-list.
				$filter = JFilterInput::getInstance(
					array_diff($blackListTags, $whiteListTags), 			// blacklisted tags
					array_diff($blackListAttributes, $whiteListAttributes), // blacklisted attributes
					1,														// blacklist tags
					1														// blacklist attributes
				);
				// Remove white listed tags from filter's default blacklist
				if ($whiteListTags) {
					$filter->tagBlacklist = array_diff($filter->tagBlacklist, $whiteListTags);
				}
				// Remove white listed attributes from filter's default blacklist
				if ($whiteListAttributes) {
					$filter->attrBlacklist = array_diff($filter->attrBlacklist);
				}

			}
			// White lists take fourth precedence.
			elseif ($whiteList) {
				$filter	= JFilterInput::getInstance($whiteListTags, $whiteListAttributes, 0, 0, 0);  // turn off xss auto clean
			}
			// No HTML takes last place.
			else {
				$filter = JFilterInput::getInstance();
			}

			$text = $filter->clean($text, 'html');
		}

		return $text;
	}
	
}