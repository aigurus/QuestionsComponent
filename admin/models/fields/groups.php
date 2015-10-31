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
 
jimport('joomla.form.formfield');
 
class JFormFieldGroups extends JFormField {
 
        protected $type = 'Groups';
 		
		/*function check_group($group, $inherited){
		   $user =& JFactory::getUser();
		   $user_id = $user->get('id');
		   if($inherited){
			  //include inherited groups
			  jimport( 'joomla.access.access' );
			  $groups = JAccess::getGroupsByUser($user_id);
		   }
		   else {
			  //exclude inherited groups
			  $user =& JFactory::getUser($user_id);
			  $groups = isset($user->groups) ? $user->groups : array();
		   }
		   return (in_array($group, $groups))?true:0;
		}*/
				

        public function getLabel() {
			  	$label = '';
				$replace = '';
		 
				// Get the label text from the XML element, defaulting to the element name.
				$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		 
				// Build the class for the label.
				$class = !empty($this->description) ? 'hasTip' : '';
				$class = $this->required == true ? $class.' required' : $class;
		 
				// Add the opening label tag and main attributes attributes.
				$label .= '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="'.$class.'"';
		 
				// If a description is specified, use it to build a tooltip.
				if (!empty($this->description)) {
						$label .= ' title="'.htmlspecialchars(trim(JText::_($text), ':').'::' .
										JText::_($this->description), ENT_COMPAT, 'UTF-8').'"';
				}
		 
				// Add the label text and closing tag.
				$label .= '>'.$replace.JText::_($text).'</label>';
		 
				return $label; 
                // code that returns HTML that will be shown as the label
				//return JText::_('COM_QUESTIONS_SELECT_GROUP'); 
        }
        // getLabel() left out
 
        public function getInput() {
			$groups = $this->getGroups();
            $html = '<select id="'.$this->id.'" name="'.$this->name.'">';
			$html .= '<option value=0 selected="selected">--None--</option>';
			foreach($groups as $grp)
     		{
			   $html .= '<option value="'.$grp->id.'">';
			   $html .=  $grp->group_name;
			   $html .= '</option>';
			}
			$html .= '</select>';
			return $html;

        }
		
		function getGroups() {

				$db = JFactory::getDBO();
		
				$user = JFactory::getUser();
		
				$query = "select * from #__questions_groups where published = 1 AND userid=".$user->id;
		
				$db->setQuery($query);
		
				$groups = $db->loadObjectList();
				//var_dump($categories);
				return $groups;
							
		}
}