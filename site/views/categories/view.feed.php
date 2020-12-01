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
 
// import Joomla view library
jimport('joomla.application.component.view');

class QuestionsViewQuestions extends QueView
{
	function getCatQuestions($catid){
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__questions_core AS a");
		$where = array();
		$where[] = "a.published=1"; //
		$where[] = "a.catid=".$catid; 
		$where[] = "a.question=1"; 
		if ( ! empty( $where ) )
			$query->where( $where );
		$query->order('a.submitted DESC');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getCatName($catid){
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select("title");
		$query->from("#__categories AS a");
		$where = array();
		$where[] = "a.published=1"; //
		$where[] = "a.id=".$catid; 
		//$where[] = "a.extension = com_questions"; 
		if ( ! empty( $where ) )
			$query->where( $where );
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$doc	= JFactory::getDocument();
		
		$doc->link = JRoute::_("index.php?option=com_questions&view=question&id=" . JFactory::getApplication()->input->get('id'));

		JRequest::setVar('limit', $app->getCfg('feed_limit'));
		
		$params = $app->getParams();
		$catid = JRequest::getInt( "catid" , 0 );
		$this->questions = $this->getCatQuestions($catid);
		$item2 = new JFeedItem();
		$item2->title = "Feed for Questions under Category ".$this->getCatName($catid);
		$doc->addItem($item2);	
		foreach ($this->questions as $question2){
			
			// strip html from feed item title
			$title = $this->escape($question2->title);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
			
			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$link = JRoute::_("index.php?option=com_questions&view=question&id=" . $question2->id);
			
			$description	= $this->escape($question2->text);
			$author			= JFactory::getUser($question2->userid_creator)->name;
			@$date			= date('r', strtotime($question2->submitted));
			
			$item = new JFeedItem();
			
			$item->title		= $title;
			$item->link			= $link;
			$item->description	= $description;
			$item->date			= $date;
			//$item->category		= $question->category;
			$item->author		= $author;
			
			$doc->addItem($item);
				
		}
		
	}
}
