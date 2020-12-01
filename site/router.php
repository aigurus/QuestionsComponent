<?php
/*
    Copyright (C)  2012 Sweta ray.
    Permission is granted to copy, distribute and/or modify this document
    under the terms of the GNU Free Documentation License, Version 1.3
    or any later version published by the Free Software Foundation;
    with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
    A copy of the license is included in the section entitled 'GNU
    Free Documentation License'
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
jimport('joomla.application.categories');
require_once 'administrator/components/com_questions/helpers/questions.php';

function QuestionsBuildRoute( &$query ) {	
	
	$segments = array();
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$params	= JComponentHelper::getParams('com_questions');
	$advanced = $params->get('sef_advanced_link', 0);

	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
	} else {
		$menuItem = $menu->getItem($query['Itemid']);
	}
	
	if ( isset( $query['catid'] ) ) {
		if ( $query['catid']) {
			$segments[] = 'category';
			$segments[] = $query['catid'];
		}
		unset( $query['catid'] );
	}

	if ( isset($query['tag']) ) {
		if ( $query['tag'] ) {
			$segments[] = 'tag';
			$segments[] = $query['tag'];
		}		
		unset($query['tag']);
	}
	if ( isset($query['tags']) ) {
		if ( $query['tags'] ) {
			$segments[] = 'tags';
			$segments[] = $query['tags'];
		}		
		unset($query['tags']);
	}
	
	if ( isset($query['sort']) ) {
		if ( $query['sort'] ) {
			$segments[] = 'sort';
			$segments[] = $query['sort'];
		}		
		unset($query['sort']);
	}
	
	if ( isset($query['dir']) ) {
		if ( $query['dir'] ) {
			$segments[] = 'dir';
			$segments[] = $query['dir'];
		}		
		unset($query['dir']);
	}

	if ( isset($query['filter']) ) {
		if ($query['filter']){
			$segments[] = 'filter';
			$segments[] = $query['filter'];
		}
		unset($query['filter']);
	}
	
	if ( isset($query['view']) ) {
		if ($query['view']) {
			$segments[] = $query['view'];
		}
		unset($query['view']);
	}
	/*
	if ( isset($query['layout']) ) {
		if ($query['layout']) {
			$segments[] = 'layout';
			$segments[] = $query['layout'];
		}
		unset($query['layout']);
	}*/
	if (isset($query['return'])) {
		$segments[] = $query['return'];
		unset($query['return']);
	}

	if (isset($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	}

	if ( isset($query['qid']) ) {
		if ($query['qid']){
			$segments[] = $query['qid']; 
		}
		unset($query['qid']);
	}
	
	if ( isset($query['aid']) ) {
		if ($query['aid']){
			$segments[] = $query['aid']; 
		}
		unset($query['aid']);
	}
	if ( isset($query['uid']) ) {
		if ($query['uid']){
			$segments[] = $query['uid']; 
		}
		unset($query['uid']);
	}
	if( isset($query['id']) ){
		$segments[] = $query['id'];
		unset( $query['id'] );
    };
	return $segments;
}

function QuestionsParseRoute( $segments ){
	$vars = array();
	$app = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();
	$params	= JComponentHelper::getParams('com_questions');
	$advanced = $params->get('sef_advanced_link', 0);
	
	$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
	$count = count($segments);
	if ($count) {
		for ( $i = 0; $i < $count ; $i++ ) {
			//filter
			
			if ( !isset($segments[$i])) {
			   $segments[$i] = NULL;
			}
			//questions listing - view=questions
			if ( $segments[$i] == 'questions' ) { 
				$vars['view'] = 'questions';
			}
			if ( $segments[$i] == 'tags' ) { 

				$vars['view'] = 'tags';
				$id = explode( ':', $segments[$count-1] );

                $vars['limitstart'] = (int) $id[0];
				
			}
			//empty having no results
			if ( $segments[$i] == 'empty' ) { 
				$vars['view'] = 'empty';
			}
			if ( $segments[$i] == 'filter' ) {
				$i++;
				$vars['filter'] = $segments[$i];	
			}
			//category
			if ( $segments[$i] == 'category' ) {
				$i++;
				$category = $segments[$i];
				$category = explode(':' , $category);
				$vars['catid'] = $category[0];
				//$vars['catid'] = $segments[1];
			}
			//tag
			if ( $segments[$i] == 'tag' ) {
				$i++;
				$vars['tag'] = $segments[$i];
			}
			if ( $segments[$i] == 'sort' ) {
				$i++;
				$vars['sort'] = $segments[$i];
			}
			if ( $segments[$i] == 'dir' ) {
				$i++;
				$vars['dir'] = $segments[$i];
			}
			if ( $segments[$i] == 'form' ) {
				$vars['view'] = 'form';
				$vars['layout'] = 'edit';
				if(count($segments)>1 && isset($segments)){
				$id = explode( ':', $segments[1] );
				}
                $vars['id'] = (int) $id[0];
			}
			if ( $segments[$i] == 'question.edit' ) {
				$vars['view'] = 'form';
				$vars['layout'] = 'edit';
				$id = explode( ':', $segments[1] );
                $vars['id'] = (int) $id[0];
			}
			//single question - view=question
			if ( $segments[$i] == 'question' ) {
				
					   $vars['view'] = 'question';
					   if($i>1){
						   $category = explode('=' , $segments[1]);
						   $vars['catid'] = $category[0];
						   $id = explode( ':', $segments[3] );
						   $vars['id'] = (int) $id[0];

					   }else{
						   if(count($segments)>1 && isset($segments)){
						   $id = explode( ':', $segments[1] );
						   }
						   $vars['id'] = (int) $id[0];
					   }
				
			}
			if ( $segments[$i] == 'profiles') { 
				$vars['view'] = 'profiles';
				$layout = explode( ':', $segments[$count-2] );
				$vars['layout'] = $layout[0] ;
				$id = explode( ':', $segments[$count-1] );
                $vars['id'] = (int) $id[0];
			}
			
			if ( $segments[$i] == 'reports' ) {
				$vars['view'] = 'reports';
				$vars['layout'] = $segments[$count-2] ;
                $vars['qid'] = (int) $segments[$count-1];
			}	
		}
	}
	return $vars;
}