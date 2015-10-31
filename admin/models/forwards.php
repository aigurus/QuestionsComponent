<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');
require_once JPATH_COMPONENT.'/helpers/socket.php';

class QuestionsModelForwards extends JModelList
{
	var $_fetchedItems = null;
	var $domain;
	var $url;
	var $port;
	var $user;
	var $password;
	var $socket;
	
	public function __construct($config = array())
	{
		$params = JComponentHelper::getParams('com_questions');
		$this->domain = $params->getValue('directadmin_domain');
		$this->url = $params->getValue('directadmin_url');
		$this->port = $params->getValue('directadmin_port');
		$this->user = $params->getValue('directadmin_user');
		$this->password = $params->getValue('directadmin_password');
		$this->forwardField = $params->getValue('joomla_forwardField');
		$this->forwardTableUserIdField = $params->getValue('joomla_forwardTableUserIdField');
		$this->forwardTable = $params->getValue('joomla_forwardTable');
		
		if (empty($this->url) || empty($this->domain) || empty($this->forwardField) || empty($this->forwardTable) || empty($this->forwardTableUserIdField)) {
				JError::raiseWarning(0, 'Incorrect settings');
		}
		else
		{
			$this->socket = new HTTPSocket;
			$this->socket->connect($this->url, $this->port);
			$this->socket->set_login($this->user, $this->password);
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '');
		$this->setState('filter.state', $state);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_questions');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
	
	/**
	 * Gets an array of objects from the results of database query.
	 *
	 * @param   string   $query       The query.
	 * @param   integer  $limitstart  Offset.
	 * @param   integer  $limit       The number of records.
	 *
	 * @return  array  An array of results.
	 * @since   11.1
	 */
	protected function _getList($query, $limitstart=0, $limit=0)
	{
		$rows = &$this->_fetch();
		$results = array();
		$count = 0;
		$filterState = $this->getState('filter.state');
		
		foreach($rows as $element)
		{
			if (!isset($filterState) || $filterState == '' || $element->state == $filterState)
			{
				if (($count >= $limitstart && $count < $limitstart + $limit) || $limit==0)
				{
					$results[$element->email] = $element;
				}
				$count++;
			}
		}
		return $results;
	}

	/**
	 * Returns a record count for the query
	 *
	 * @param    string  $query  The query.
	 *
	 * @return   integer  Number of rows for query
	 * @since    11.1
	 */
	protected function _getListCount($query)
	{
		$rows = &$this->_fetch();
		$count = 0;
		$filterState = $this->getState('filter.state');
		
		foreach($rows as $element)
		{
			if (!isset($filterState) || $filterState == '' || $element->state == $filterState)
			{
				$count++;
			}
		}
		return $count;
	}
	
	/**	 
	* Fetch all the forwarders	 
	* @return array array(array('user' => 'destination email'))	 
	*/	
	public function &_fetch()
	{
		if ($this->_fetchedItems == null) 
		{
			if (empty($this->url) || empty($this->domain) || empty($this->forwardField) || empty($this->forwardTable) || empty($this->forwardTableUserIdField)) {
				$this->_fetchedItems = array();
				return $this->_fetchedItems;
			}
		
			// First fetch all items from DirectAdmin
			$this->socket->query('/CMD_API_EMAIL_FORWARDERS', array(
				'action' => 'list',
				'domain' => $this->domain
				));
			$directAdminResults = $this->socket->fetch_parsed_body();
			$keys = array_keys($directAdminResults);
			if (isset($keys[1]) && $keys[1] == '#95API')
			{
				$directAdminResults = array();
			}
			
			// Second fetch all items from Joomla DB
			$query	= $this->_db->getQuery(true);

			// Join over the group mapping table.
			$query->select('u.email AS email, c.'.$this->forwardField.' as joomlaforwardaddress')
				->from('#__users AS u')
				->where('u.block=0')
				->join('LEFT', '#__'.$this->forwardTable.' AS c ON c.'.$this->forwardTableUserIdField.' = u.id');

			$this->_db->setQuery($query);
		    $this->_fetchedItems = $this->_db->loadObjectList('email');
			
			// Merge DirectAdmin and Joomla results
			foreach($directAdminResults as $key => $value)
			{
				$email = str_replace('_', '.', $key).'@'.$this->domain;
				if (array_key_exists($email, $this->_fetchedItems))
				{
					$this->_fetchedItems[$email]->directadminforwardaddress = $value;
				}
				else
				{
					$result = new StdClass();
					$result->email = $email;
					$result->directadminforwardaddress = $value;
					$this->_fetchedItems[$email] = $result;
				}
			}
			
			// Fill in empty fileds
			foreach($this->_fetchedItems as $key => $value)
			{
				if (!isset($value->directadminforwardaddress) && !empty($value->joomlaforwardaddress))
				{
					$this->_fetchedItems[$key]->directadminforwardaddress = "";
					$this->_fetchedItems[$key]->state = 'NotListedInDirectAdmin';
				}
				else if (!isset($value->joomlaforwardaddress) && !empty($value->directadminforwardaddress))
				{
					$this->_fetchedItems[$key]->joomlaforwardaddress = "";
					$this->_fetchedItems[$key]->state = 'NotListedInJoomla';
				}
				else if ($value->joomlaforwardaddress == $value->directadminforwardaddress)
				{
					$this->_fetchedItems[$key]->state = 'OK';
				}
				else
				{
					$this->_fetchedItems[$key]->state = 'Mismatch';
				}
			}
			ksort($this->_fetchedItems);
		}
		return $this->_fetchedItems;
	}
	
	/**
	 * Fetch the destination url of a forwarder
	 * @param string $email
	 * @return string
	 */
	public function fetchEmail($email)
	{
		$users = $this->_fetch();
		return isset($users[$email]) ? $users[$email] : null;
	}

	/**
	 * Create a forwarder
	 * @param string $email
	 * @param string $forward address
	 * @return bool
	 */
	public function createOne($email, $forward)
	{
		$user = substr($email, 0, strpos($email, '@'));
		$this->socket->query('/CMD_API_EMAIL_FORWARDERS', array(
			'action' 	=> 'create',
			'domain' 	=> $this->domain,
			'user'		=> $user,
			'email'		=> $forward,
		));

		$ret = $this->socket->fetch_parsed_body();
		return isset($ret['error']) && $ret['error'] == 0;
	}

	/**
	 * Set the password of an emailaddress
	 * @param string $email
	 * @param string $forwardaddress
	 * @param string $domain
	 * @return bool
	 */
	public function modifyOne($email,  $forwardaddress)
	{
		$user = substr($email, 0, strpos($email, '@'));
		$this->socket->query('/CMD_API_EMAIL_FORWARDERS', array(
			'action'	=> 'modify',
			'domain' 	=> $this->domain,
			'user'		=> $user,
			'email'		=> $forwardaddress,
		));

		$ret = $this->socket->fetch_parsed_body();
		return isset($ret['error']) && $ret['error'] == 0;
	}

	/**
	 * Delete an email
	 * @param string $email
	 * @return bool
	 */
	public function deleteOne($email)
	{
		$user = substr($email, 0, strpos($email, '@'));
		
		$this->socket->query('/CMD_API_EMAIL_FORWARDERS', array(
			'action'	=> 'delete',
			'domain' 	=> $this->domain,
			'user'		=> $user,
			'select0'	=> $user
		));

		$ret = $this->socket->fetch_parsed_body();
		return isset($ret['error']) && $ret['error'] == 0;
	}
	
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array    $pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 * @since   11.1
	 */
	public function delete(&$pks)
	{
		// Initialise variables.
		$pks		= (array) $pks;
	
		// Iterate the items to delete each one.
		foreach ($pks as $email) 
		{
			$element = $this->fetchEmail($email);
			if (!empty($element->directadminforwardaddress))
			{
				if (!$this->deleteOne($email))
				{
					JError::raiseWarning(0, JText::plural('DELETE_FAILED', $email));
				}
			}
			else
			{
				JError::raiseWarning(0, JText::plural('DOESNT_EXIST_IN_DIRECTADMIN', $email));
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
	
	public function addOrUpdate(&$pks)
	{
		// Initialise variables.
		$pks		= (array) $pks;
	
		// Iterate the items to delete each one.
		foreach ($pks as $email) 
		{
			$element = $this->fetchEmail($email);
			if (empty($element->directadminforwardaddress))
			{
				// Add
				if (!$this->createOne($email, $element->joomlaforwardaddress))
				{
					JError::raiseWarning(0, JText::plural('FAILED_TO_CREATE', $email));
				}
			}
			else
			{
				// Update
				if (!$this->modifyOne($email, $element->joomlaforwardaddress))
				{
					JError::raiseWarning(0, JText::plural('FAILED_TO_UPDATE', $email));
				}
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

}
?>