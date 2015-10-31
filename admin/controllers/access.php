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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 

class QuestionsControllerAccess extends JControllerAdmin
{   
	function __construct(){	
		parent::__construct();
	} 
	
 
    //Overide core finctions in order to set the redirect correctly
    public function default_access(){
				(string)$rules='{"question.unpublished":[],"question.answer":{"6":1,"7":1,"2":1,"3":1,"4":1,"5":1},"question.edit":{"6":1,"7":1},"core.create":{"7":1,"2":1,"3":1,"4":1,"5":1},"core.edit":{"7":1,"2":1,"3":1,"4":1,"5":1},"core.edit_any":{"6":1,"7":1},"question.delete":{"6":1,"7":1},"question.publish":{"6":1,"7":1},"question.viewanswers":{"1":1,"6":1,"7":1,"2":1,"3":1,"4":1,"5":1},"question.vote":{"6":1,"7":1,"2":1,"3":1,"4":1,"5":1},"core.admin":{"7":1}}';
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query ->UPDATE('`#__assets` SET rules =".$rules." WHERE name = "com_questions"');
             	$db->setQuery( $query );
				$result = $db->query();
            	$db->query(); 
                //$parent->getParent()->setRedirectURL('index.php?option=com_questions&view=main');
    } 
}
