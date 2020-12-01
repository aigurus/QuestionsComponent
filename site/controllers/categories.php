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
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

class QuestionsControllerCategories extends BaseController
{   
	public function cancel($key = null)
    {
        parent::cancel($key);
        
        // set up the redirect back to the same form
        $this->setRedirect(
            (string)JUri::getInstance(), 
            JText::_('COM_QUESTIONS_ADD_GROUP_CANCELLED')
		);
    }
    
    /*
     * Function handing the save for adding a new helloworld record
     * Based on the save() function in the JControllerForm class
     */
    public function search($key = null, $urlVar = null)
    {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
		/*$post = JFactory::getApplication()->input->post;
		$search = $post->get('searchstr','');*/
				
		$app = JFactory::getApplication(); 
		$input = $app->input; 
       
		// Get the current URI to set in redirects. As we're handling a POST, 
		// this URI comes from the <form action="..."> attribute in the layout file above
		$currentUri = (string)JUri::getInstance();

		// Check that this user is allowed to add a new record
		/*if (!JFactory::getUser()->authorise( "core.view", "com_questions"))
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return;
		}*/
        
		// get the data from the HTTP POST request
		$data  = $input->get('jform', array(), 'array');
            
		return true;
        
    }
	
	public function getCategories(){
		$params = JComponentHelper::getParams('com_questions');
		$limit = $params->get('cat_limit', 10);
		$ordering = $params->get('cat_ordering', 'c.id');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title');
		$query->from('#__categories as c');
		$query->where('c.extension= "com_questions"');
		$query->order($ordering. ' ASC LIMIT '. $limit);
		$db->setQuery((string)$query);
		$categories = $db->loadObjectList();
		return $categories;
					
	}

}