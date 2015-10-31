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
 
/**
 * Script file of com_questions component
 */
class com_questionsInstallerScript
{
	     /*
         * $parent is the class calling this method.
         * $type is the type of change (install, update or discover_install, not uninstall).
         * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
         * If preflight returns false, Joomla will abort the update and undo everything already done.
         */
        function preflight( $type, $parent ) {
                $jversion = new JVersion();
 
                // Installing component manifest file version
                $this->release = $parent->get( "manifest" )->version;
 
                // Manifest file minimum Joomla version
                $this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;   
 
                // Show the essential information at the install/update back-end
                echo '<p> Installing component manifest file version = ' . $this->release;
                echo '<br />Current manifest cache commponent version = ' . $this->getParam('version');

                // abort if the component being installed is not newer than the currently installed version
                if ( $type == 'update' ) {
                        $oldRelease = $this->getParam('version');
                        $rel = $oldRelease . ' to ' . $this->release;
                        if ( version_compare( $this->release, $oldRelease, 'le' ) ) {
                                Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
                                return false;
                        }
                }
                else { $rel = $this->release; }
				
				if(!defined('DS')){
				define('DS',DIRECTORY_SEPARATOR);
				}
				jimport( 'joomla.filesystem.file' );
				$filename = "1.3.0.sql";
				$src = JPATH_ADMINISTRATOR.DS."components".DS."com_questions".DS."sql".DS."updates".DS."mysql".DS.$filename;
	
				if (is_readable($src)) { // && !is_writable($src)) { // file may not be writable by php if via ftp!
					$newName = str_replace("1.3.0","1.2.5",$src);
					rename($src, $newName);
				}
 
                echo '<p>' . JText::_('COM_QUESTIONS_PREFLIGHT_' . $type . ' ' . $rel) . '</p>';
        }
 
        function install( $parent ) {
                echo '<p>' . JText::_('COM_QUESTIONS_INSTALL to ' . $this->release) . '</p>';
                // You can have the backend jump directly to the newly installed component configuration page
                // $parent->getParent()->setRedirectURL('index.php?option=com_questions');
        }
 
        function update( $parent ) {
                echo '<p>' . JText::_('COM_QUESTIONS_UPDATE_TEXT') . $this->release . '</p>';
				// Installing component manifest file version
				$this->release = $parent->get( "manifest" )->version;
				if ( $type == 'update' ) {
					$oldRelease = $this->getParam('version');
					$rel = $oldRelease . ' to ' . $this->release;
					if ( version_compare( $this->release, $oldRelease ) ) {
							Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
							return false;
					}
				}
				$oldRelease = $this->getParam('version');
	
				$db = JFactory::getDBO();
				if(version_compare( $oldRelease, '1.2.6', '<')){
					$sql = "ALTER TABLE `#__questions_userprofile` ADD COLUMN `groups` varchar(255) AFTER `email`";
					$db->setQuery($sql);
					$db->query();
				}
				if(version_compare( $oldRelease, '1.2.1', '<')){
					$sql = "CREATE TABLE IF NOT EXISTS `#__questions_notification` (
						  `id` int(10) NOT NULL AUTO_INCREMENT,
						  `to_user` int(10) NOT NULL DEFAULT '0',
						  `from_user` int(10) NOT NULL DEFAULT '0',
						  `reference` int(10) NOT NULL DEFAULT '0',
						  `type` enum('groupadd','repin') NOT NULL,
						  `seen` tinyint(4) NOT NULL DEFAULT '0',
						  `timestamp` varchar(12) NOT NULL,
						  PRIMARY KEY (`id`),
						  KEY `to_user` (`to_user`),
						  KEY `from_user` (`from_user`),
						  KEY `reference` (`reference`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
					$db->setQuery($sql);
					$db->query();
				
					$sql = "CREATE TABLE IF NOT EXISTS `#__questions_groups` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `userid` int(11) NOT NULL,
						  `group_name` varchar(255) NOT NULL,
						  `image` varchar(255) NOT NULL,
						  `moderators` text NOT NULL,
						  `requestsent` text NOT NULL,
						  `requestreceived` text NOT NULL,
						  `friendsid` text NOT NULL,
						  `published` int(2) NOT NULL,
						  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
					$db->setQuery($sql);
					$db->query();
					
					$sql = "ALTER TABLE `#__questions_core` CHANGE `tags` `qtags` text";
					$db->setQuery($sql);
					$db->query();
					$sql = "ALTER TABLE `#__questions_core` ADD COLUMN `refurl1` varchar(255) AFTER `email`";
					$db->setQuery($sql);
					$db->query();
					$sql = "ALTER TABLE `#__questions_core` ADD COLUMN `refurl2` varchar(255) AFTER `refurl1`";
					$db->setQuery($sql);
					$db->query();
					$sql = "ALTER TABLE `#__questions_core` ADD COLUMN `refurl3` varchar(255) AFTER `refurl2`";
					$db->setQuery($sql);
					$db->query();
					$sql = "ALTER TABLE `#__questions_core` ADD COLUMN `groups` varchar(255) AFTER `refurl3`";
					$db->setQuery($sql);
					$db->query();
				}
				if(version_compare($oldRelease, '1.3', '<')){
					//Alias addition 
					$sql = "ALTER TABLE `#__questions_core` ADD COLUMN `alias` text AFTER `title`";
					$db->setQuery($sql);
					$db->query();
				}
				 // $parent is the class calling this method
				echo '<p>' . JText::sprintf('COM_QUESTIONS_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
				$parent->getParent()->setRedirectURL('index.php?option=com_questions&view=main');
				// You can have the backend jump directly to the newly updated component configuration page
                // $parent->getParent()->setRedirectURL('index.php?option=com_questions');
        }

        function postflight( $type, $parent ) {
                // always create or modify these parameters
                $params['my_param0'] = 'Component version ' . $this->release;
                $params['my_param1'] = 'Another value';
 
                // define the following parameters only if it is an original install
                if ( $type == 'install' ) {
                        $params['my_param2'] = '4';
                        $params['my_param3'] = 'Star';
                }
 
                $this->setParams( $params );
 
                echo '<p>' . JText::_('COM_QUESTIONS_POSTFLIGHT ' . $type . ' to ' . $this->release) . '</p>';
        }

        function uninstall( $parent ) {
                echo '<p>' . JText::_('COM_QUESTIONS_UNINSTALL ' . $this->release) . '</p>';
        }
 
        function getParam( $name ) {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT manifest_cache FROM `#__extensions` WHERE ((name = "com_questions" || element = "com_questions") && type="component")');
                $manifest = json_decode( $db->loadResult(), true );
                return $manifest[ $name ];
        }

        function setParams($param_array) {
                if ( count($param_array) > 0 ) {
                        // read the existing component value(s)
                        $db = JFactory::getDbo();
                        $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_questions"');
                        $params = json_decode( $db->loadResult(), true );
                        // add the new variable(s) to the existing one(s)
                        foreach ( $param_array as $name => $value ) {
                                $params[ (string) $name ] = (string) $value;
                        }
                        // store the combined new and existing values back as a JSON string
                        $paramsString = json_encode( $params );
                        $db->setQuery('UPDATE #__extensions SET params = ' .
                                $db->quote( $paramsString ) .
                                ' WHERE name = "com_questions"' );
                                $db->query();
                }
        }
}