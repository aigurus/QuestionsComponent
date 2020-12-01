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

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

class QuestionsModelCategories extends JModelList {

	public function getItems(){

		$rows	= parent::getItems();
		
		$categories = $rows;
		
		/*foreach ($categories as $categorie){
				$categories->link = JRoute::_( "index.php?option=com_questions&view=question&id=" .  QuestionsHelper::getAlias($question->id) . QuestionsHelper::getActiveViewOptions() ); 
		}*/

		$items = $categories;
		
		return $items;
	}

	function getListQuery(){
		
		$params = JComponentHelper::getParams('com_questions');
			$limit = $params->get('cat_limit', 5);
			$ordering = $params->get('cat_ordering', 'c.id');
			$extension="com_questions";
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title, c.published, c.created_user_id, c.created_time, c.description, 
			GROUP_CONCAT(DISTINCT d.title) AS tags, GROUP_CONCAT(DISTINCT b.tag_id) AS tagids');
			
			$query->from('#__categories as c');
			
			$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'b') . ' ON (' . $db->quoteName('b.content_item_id') . ' = ' . $db->quoteName('c.id') . ')'
			
			. ' AND ' . $db->quoteName('b.type_alias') . ' = ' . $db->quote($extension . '.category'));
			
			$query->join('LEFT', $db->quoteName('#__tags', 'd') . ' ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('b.tag_id') . ')'
			
			. ' AND ' . $db->quoteName('b.type_alias') . ' = ' . $db->quote($extension . '.category'));
			 $query->group($db->quoteName('c.id'));
			$query->group($db->quoteName('c.id'));
			
			//$query->join('LEFT', $db->quoteName('#__tags', 'd'). 'ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('b.tag_id') . ')');

			//$query->where($db->quoteName('c.id') . ' = '.$id);
			
			
			/*$query->where('c.extension= "com_questions"');
			$query->order($ordering. ' ASC LIMIT '. $limit);
			$db->setQuery((string)$query);
			$categories = $db->loadObjectList();
			return $categories;*/
		
		$show_unpublished = $this->getState("filter.unpublished" , 0);
		
		$where = array();
		
		$where[] = 'c.extension= "com_questions"'; // questions only
		
		if ( ! $show_unpublished )
			$where[] = "c.published=1"; // only published items
		
		
					
		//************* FILTERING - END ***************
		
		//apply filters
		if ( ! empty( $where ) )
			$query->where( $where );
		
		$ordering = $this->getState( "list.ordering" , "id" );
		$direction = $this->getState( "list.direction" , "DESC" );
		
		$query->order("$ordering $direction");

    	return $query;
	}
	
	public function getSearch(){
		$post = JFactory::getApplication()->input->post;
		$search = $post->get('searchstr','');
		$search = "%".$search."%";
		
		try 
		{
			
			$params = JComponentHelper::getParams('com_questions');
			$limit = $params->get('cat_limit', 5);
			$ordering = $params->get('cat_ordering', 'c.id');
			$extension="com_questions";
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title, c.published, c.created_user_id, c.created_time, c.description, 
			GROUP_CONCAT(DISTINCT d.title) AS tags, GROUP_CONCAT(DISTINCT b.tag_id) AS tagids');
			
			$query->from('#__categories as c');
			
			$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'b') . ' ON (' . $db->quoteName('b.content_item_id') . ' = ' . $db->quoteName('c.id') . ')'
			
			. ' AND ' . $db->quoteName('b.type_alias') . ' = ' . $db->quote($extension . '.category'));
			
			$query->join('LEFT', $db->quoteName('#__tags', 'd') . ' ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('b.tag_id') . ')'
			
			. ' AND ' . $db->quoteName('b.type_alias') . ' = ' . $db->quote($extension . '.category'));
			 $query->group($db->quoteName('c.id'));
			$query->group($db->quoteName('c.id'));
			
			//$query->join('LEFT', $db->quoteName('#__tags', 'd'). 'ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('b.tag_id') . ')');

			//$query->where($db->quoteName('c.id') . ' = '.$id);
			
			
			/*$query->where('c.extension= "com_questions"');
			$query->order($ordering. ' ASC LIMIT '. $limit);
			$db->setQuery((string)$query);
			$categories = $db->loadObjectList();
			return $categories;*/
		
		$show_unpublished = $this->getState("filter.unpublished" , 0);
		
		$where = array();
		
		$where[] = 'c.extension= "com_questions"'; // questions only
		//var_dump($db->quote($search)); exit;
		$where[] = 'LOWER(c.title) LIKE '.$db->quote($search);
		if ( ! $show_unpublished )
			$where[] = "c.published=1"; // only published items
		
		
					
		//************* FILTERING - END ***************
		
		//apply filters
		if ( ! empty( $where ) )
			$query->where( $where );
		
		$ordering = $this->getState( "list.ordering" , "id" );
		$direction = $this->getState( "list.direction" , "DESC" );
		
		$query->order("$ordering $direction");

    	$db->setQuery((string)$query);
		$categories = $db->loadObjectList();
		return $categories;
		
			
		}
		catch (Exception $e)
		{
			//var_dump($e->getMessage()); exit;
			//JLog::add('Caught exception: ' . $e->getMessage(), JLog::Error, 'jerror');
		}
	}

	public function populateState( $ordering = "submitted" , $direction = "DESC" ){
		
		$app = JFactory::getApplication();
		
		$this->setState( "list.ordering" , $ordering );
		$this->setState( "list.direction" , $direction );

		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$user = JFactory::getUser();

    	$view_unpublished = 0;
		$viewanswers = 0;

		//Which questions can the user display?
		if ( $user->authorise("question.unpublished","com_questions") ){
			$view_unpublished = 1;
		}

		//view answers??
		if ($user->authorise("question.viewanswers" , "com_questions")){
			$viewanswers = 1;
		}


		//************ Categories & Tags - BEGIN ********** 
		
		//category
		$catid = JRequest::getInt('catid' , 0);
		$this->setState("filter.catid", $catid);
		
		//tag
		$tag = JRequest::getString("tag" , 0);
		$this->setState("filter.tag" , $tag);
		
		//************ Categories & Tags - END **********
		
		
		
		//************* FILTERING - BEGIN ***************
		
		$filter = JRequest::getString("filter");
		
		//answered
		$this->setState("filter.answered" , (int)($filter=="answered"));
		
		//not answered
		$this->setState("filter.notanswered" , (int)($filter=="notanswered"));
		
		//resolved
		$this->setState("filter.resolved" , (int)($filter=="resolved"));
		
		//unresolved
		$this->setState("filter.unresolved" , (int)($filter=="unresolved"));
		
		//user's questions -- ensure that the myquestions filter is only available to logged users
		$this->setState("filter.myquestions" , (JFactory::getUser()->id ? (int)($filter=="myquestions") : 0) );
		
		//if the 'myquestions' filter is active, user is allowed to diplay his unpublished questions
		if ($filter=="myquestions" &JFactory::getUser()->id )
			$view_unpublished=1;
		
		//************* FILTERING - END ***************
		
		
		
		//************* ORDERING - START ***************
		$appParams = json_decode(JFactory::getApplication()->getParams());
		
		if (isset($appParams->sorting_backend) && $appParams->sorting_backend == 1){
		if (isset($appParams->list_ordering)){
		$ordering = $appParams->list_ordering;
		}
		if (isset($appParams->list_direction)){
		$direction = $appParams->list_direction;
		}
		}
		else {
		$ordering = JFactory::getApplication()->input->get("sort");
		$direction = JFactory::getApplication()->input->get("dir");	
		}
		
		$this->setState("list.ordering" , $ordering);
		$this->setState("list.direction" , $direction);
		
		//************* ORDERING - END ***************
		
		$this->setState("filter.unpublished" , $view_unpublished );
		$this->setState("filter.answers" , $viewanswers);

	}
	
 	public function getFilteringOptions(){
        	
        $currentOptions = 
        	"&tag=" . JRequest::getString("tag") . 
        	"&catid=" . JRequest::getInt("catid");
        $home = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="home"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_HOME") . "</a></li>";
			
        $answered = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="answered"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=answered" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_ANSWERED") . "</a></li>";
        
        $notanswered = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="notanswered"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=notanswered" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_NOTANSWERED") . "</a></li>";
        
        $resolved = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="resolved"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=resolved" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_RESOLVED") . "</a></li>";
        
        $unresolved = 
        	"<li><a " . (JRequest::getString("filter", 0)=="unresolved"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=unresolved" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_UNRESOLVED") . "</a></li>";
        
        $myquestions = NULL;
        if ( JFactory::getUser()->id )
        $myquestions = 
        	"<li><a " . (JRequest::getString("filter", 0)=="myquestions"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=myquestions" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_MYQUESTIONS") . "</a></li>";
                
        $options = "<div class='questions_filters'><ul>". $home . $answered . $notanswered . $resolved . $unresolved . $myquestions . "</ul></div>";
        
        return $options;
 	}
	
	public static function countCat($catid){
					//$this->catid = $catid;
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('count(*) FROM #__questions_core AS c WHERE c.catid='.(int)$catid.' AND c.question=1');
					//echo $query;
					$db->setQuery($query);
					$result = $db->loadResult();
					return $result;
	
		}
		
	public static function nested($left,$right,$lvl)
	{
		$params = JComponentHelper::getParams('com_questions');
		$sublimit = $params->get('sub_cat_limit', 5);
		$subordering = $params->get('sub_cat_ordering', 'c.id');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title,c.published, c.created_user_id, c.created_time, c.description');
		$query->from('#__categories as c');
		$query->where('c.extension= "com_questions" AND c.lft >'. $left.' AND c.rgt <'. $right.' AND c.level= '.$lvl);
		$query->order($subordering. ' ASC LIMIT '. $sublimit);
		$db->setQuery((string)$query);
		$nestedcat = $db->loadObjectList();
		return $nestedcat;
	}
	public static function getAlias ( $id ) {
		 if ($id == 0)
			return; 
		 
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(TRUE);
	 
		$query = 'select alias, CASE WHEN CHAR_LENGTH(alias)>0 THEN CONCAT_WS(":", id, alias) ELSE id END as slug from #__categories WHERE id='.$id;

		$db->setQuery($query);
		$row = $db->loadObjectList();
		
		return $row[0]->slug;	
		
	}	
}
