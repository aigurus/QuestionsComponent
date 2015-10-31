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

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class QuestionsControllerImport extends QueController
{
	public $default_view = 'tpl';
	
	public $default_redirect = 'index.php?option=com_questions';
	
	public function import_config()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));
		
		
		$options =  JFactory::getApplication()->input->get('options', null, 'request', 'array');
		$data_file =  JFactory::getApplication()->input->get('config_file', null, 'files', 'array');
		if (empty($data_file) || empty($data_file['tmp_name'])) {
			return $this->setRedirect($this->default_redirect, JText::_('COM_QUESTIONS_IMPORT_UPLOAD_FILE_ERROR'), 'error');
		}
		
		$path = $data_file['tmp_name'];
		$serial = file_get_contents($path);
		
		$object = unserialize($serial);
		
		if (!is_object($object)) {
			return $this->setRedirect($this->default_redirect, JText::_('COM_QUESTIONS_IMPORT_INVALID_CONFIG_ERROR'), 'error');
		}
		
		$db = JFactory::getDBO();
		
		/*if (!empty($options['modules'])) {
			$table = JTable::getInstance('Module');
			$access = $table->access;
			foreach ($object->modules as $module) {
				$module = (array) $module;
				$module = array_intersect_key($module, array(
					'title'=>1, 'ordering'=>1, 'position'=>1, 'published'=>1, 'module'=>1, 'showtitle'=>1, 'params'=>1, 'client_id'=>1
				));
				$table->bind($module);
				$table->access = $access;
				
				if ($table->check() && $table->store()) {
					$db->setQuery(sprintf("INSERT INTO #__modules_menu VALUES (%d, %d)", $table->id, 0));
					$db->query();
				}
				
				$table->reset();
				$table->id = null;
			}
		}*/
		
		if (!empty($options['menus'])) {
			$table = JTable::getInstance('Menu');
			$access = $table->access;
			
			$db->setQuery("SELECT extension_id FROM #__extensions WHERE element= 'com_questions' AND type = 'component'");
			$component_id = $db->loadResult();
			
			foreach ($object->menus as $menu) {
				$menu = (array) $menu;
				$menu['title'] = $menu['name'];
				unset($menu['params']['menu_image']);
				
				$menu = array_intersect_key($menu, array(
					'title'=>1, 'menutype'=>1, 'alias'=>1, 'published'=>1, 'params'=>1
				));
				$menu['access'] = $access;
				$menu['component_id'] = $component_id;
				$menu['type'] = 'component';
				$menu['level'] = 1;
				$menu['link'] = 'index.php?option=com_questions';
				
				$table->setLocation(0, 'last-child');
				$table->bind($menu);
				
				$table->check();
				$result = $table->store();
				
				$table->reset();
				$table->id = null;
			}
		}
		
		/*if (!empty($options['config'])) {
			require_once JPATH_ADMINISTRATOR.'/components/com_docman/docman.class.php';
			global $_DOCMAN;
			$_DOCMAN = new dmMainFrame();
			
			foreach ($object->config as $key => $value) {
				if ($key == 'dmpath') {
					continue;
				}
				$_DOCMAN->setCfg($key, $value);
			}
			$_DOCMAN->saveConfig();
		}
		$this->setRedirect($this->default_redirect, JText::_('COM_QUESTIONS_IMPORT_IMPORT_CONFIG_SUCCESS'));*/
	}
		
	public function import_questions()
	{
		JSession::checkToken() or jexit(JText::_('COM_QUESTIONS_JINVALID_TOKEN'));

		$data_file =  JFactory::getApplication()->input->get('data_file', null, 'files', 'array');
		$data_path =  JFactory::getApplication()->input->get('data_path', null);
		if (empty($data_path) && (empty($data_file) || empty($data_file['tmp_name']))) {
			return $this->setRedirect($this->default_redirect, JText::_('COM_QUESTIONS_IMPORT_UPLOAD_FILE_ERROR'), 'error');
		}
		
		$path = $data_path ? $data_path : $data_file['tmp_name'];
		$sql = file_get_contents($path);

		$db = JFactory::getDBO();
		$db->setQuery($sql);
		
		$result = $db->queryBatch();
			
		if ($result) {
			$this->setRedirect($this->default_redirect, JText::_('COM_QUESTIONS_IMPORT_IMPORT_DATA_SUCCESS'));
		}
		else {
			$this->setRedirect($this->default_redirect, $db->getErrorMsg(), 'error');
		}
		
	}
}