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

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

if(!class_exists('QueView')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class QueView extends JViewLegacy {
      };
   } else {
      jimport('joomla.application.component.view');
      class QueView extends JView {};
   }
}

if(!class_exists('QueController')) {
   if(version_compare(JVERSION,'3.0.0','ge')) {
      class QueController extends JControllerLegacy {
      };
   } else {
	  jimport('joomla.application.component.controller');
      class QueController extends JController {};
   }
}

if (!JFactory::getUser()->authorise('core.create', 'com_questions')) 
{
	return JError::raiseWarning(404, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
}
 
require_once("components/com_questions/assets/css/admin.css");

$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-questions {background-image: url(../media/com_questions/images/que-48x48.png);}');

jimport('joomla.application.component.controller');
//JLoader::register('QuestionsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'questions.php');
require_once(dirname(__FILE__) . "/" . 'helpers' . "/" . 'questions.php');
$controller = JControllerLegacy::getInstance('questions');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));


// Redirect if set by the controller
$controller->redirect();