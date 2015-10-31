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

jimport('joomla.application.component.controlleradmin');

class QuestionsControllerExport extends JControllerAdmin
{
	protected $_redirectURL = 'index.php?option=com_questions';

	public function display()
	{
		$document = &JFactory::getDocument();

		$vName		= JRequest::getWord('view', 'export');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', 'default');

		if ($view = &$this->getView($vName, $vFormat)) {
			switch ($vName) {
				default:
					$model = &$this->getModel($vName);
					break;
			}

			if (!empty($model)) {
				$view->setModel($model, true);
			}
			$view->setLayout($lName);

			$view->assignRef('document', $document);

			$view->display();
		}
	}
	
	protected function _serve(SplFileObject $file, $filename = null) {
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename='.($filename === null ? $file->getFilename() : $filename));
    	header('Content-Length: '.$file->ftell());
		
		$file->rewind();

		foreach ($file as $line) {
			echo $line;
		}
		
		JFactory::getApplication()->close();
	}
	
	public function export_questions()
	{
		$config = new JConfig;
		
		$this->prefix = $config->dbprefix;
		$this->db = JFactory::getDBO();
		//$this->db = mysql_connect($config->host, $config->user, $config->password) or die(mysql_error());
		//mysql_select_db($config->db, $this->db) or die(mysql_error());
		
		$file = new SplTempFileObject();
		$this->_exportCategoriesSchema($file);
		$this->_exportCategories($file);

		$tables = array(
			'#__questions_core',
			'#__questions_userprofile',
			'#__questions_reports',
			'#__questions_ranks',
			'#__questions_categories'
		);	
		foreach ($tables as $table) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query = "SELECT * FROM ". $table;
			$this->_exportFromQuery($query, $table, $file);
		}
		
		$this->_exportACLQuery($file);
		//JController::createFileName($file);
		return $this->_serve($file, 'questions_export.sql');
	}
	
	protected function _exportCategoriesSchema(&$file)
	{
		$file->fwrite("
CREATE TABLE IF NOT EXISTS `#__questions_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `section` varchar(50) NOT NULL DEFAULT '',
  `image_position` varchar(30) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM;
		");
	}
	
	protected function _exportACLQuery(&$file)
	{
		$file->fwrite("
UPDATE #__questions_categories SET access = access + 1;
		");
	}
	
	protected function _exportCategories(&$file)
	{
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query = "SELECT * FROM #__categories WHERE section = 'com_questions'";
		$this->_exportFromQuery($query, '#__questions_categories', $file);
	}
	
	protected function _exportFromQuery($query, $target_table, $file)
	{
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query = $this->_query($query);
		
		$count = mysql_num_rows($query);
		if ($count == 0) {
			return;
		}
		
		$i = 0;
		while ($row = mysql_fetch_array($query)) {
			$str = '';
			if ($i % 20 === 0) {
				$str .= "\nREPLACE INTO $target_table VALUES\n";
			}
			
			$str .= '(';
			for ($j = 0, $k = count($row)/2; $j < $k; $j++) {
				$field = $row[$j];
				$field = mysql_real_escape_string($field, $this->db);
				$str .= "'$field',";
			}
			$str = substr($str, 0, -1).')';

			$str .= (($i+1) % 20 == 0 || $i+1 == $count ? ';' : ',');
			$str .= "\n";

			$file->fwrite($str);
			$i++;
		}
	}
	
	protected function _query($query)
	{
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query = str_replace('#__', $this->prefix, $query);
		$result = mysql_query($query, $this->db);
		
		return $result;
	}
}
