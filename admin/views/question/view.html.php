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
 
// import Joomla view library
jimport('joomla.application.component.view');


class QuestionsViewQuestion extends QueView
{
	public function display( $tpl = null ){
				
		$form = $this->get("Form");
		$item = $this->get("Item");
		
		if (count($errors = $this->get("Errors"))){
			JError::raiseError(500, implode("<br />" , $errors));
			return false;
		}
		
		$this->form = $form;
		$this->item = $item;
		
		//Hide the category, tags field for answers - inherit question's category
		if (!$this->form->getValue("question")) {
			$this->form->setFieldAttribute("catid", "type", "hidden");
			$this->form->setFieldAttribute("qtags", "type", "hidden");
		}
		
		$this->addToolBar();
		
		parent::display($tpl);
		
	}
	
	//public function addToolBar(){ //Changed to protected. TODO Test
	protected function addToolBar(){
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_("COM_QUESTIONS_NEW_QUESTION") : JText::_("COM_QUESTIONS_EDIT_QUESTION"));
		QuestionsHelper::canDo("question.publish") ? JToolBarHelper::save("question.save") : NULL;
		QuestionsHelper::canDo("question.publish") ? JToolBarHelper::cancel( "question.cancel" , $isNew ? "JTOOLBAR_CANCEL" : "JTOOLBAR_CLOSE" ) : NULL;
	}	
	
}