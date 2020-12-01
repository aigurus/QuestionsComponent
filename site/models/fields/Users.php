<?php
/**
 * @version		$Id: projectmanagement.php 2020-05-10 00:00:00 admin $
 * @package		Joomla 3.0+
 * @component	ProjectManagement
 * @author		Amreeta Ray www.scriptplaza.com
 * @/**	Copyright (C) 2020 Script Plaza. All rights reserved.
 * @license		GNU/GPL v3, see license.txt
 */

defined ('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

class JFormFieldUsers extends JFormField
{
	protected $type 		= 'Users';

	protected function getInput() {
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$app   = JFactory::getApplication();
  		
		$query->select('*');
		$query->from('#__users');
		//$query->where('(block = 0) AND (project_id='.$project_id.')' );
		$query->order('ID asc');

		$db->setQuery((string)$query);
		$types = $db->loadObjectList();
		
		$typ[0] = JHTML::_('select.option', '', '- '. JText::_( 'COM_QUESTIONS_MODERATORS_SELECT' ) .' -');
  		foreach($types as $type) {
    		$typ[] = JHTML::_('select.option', $type->id, $type->name);
		};
  		return JHTML::_('select.genericlist', $typ, $this->name, '', 'value', 'text',  $this->value, $this->id);
	}
	public function getLabel() {
		return '<span style="text-decoration: underline;">' . parent::getLabel() . '</span>';
	}
}
?>