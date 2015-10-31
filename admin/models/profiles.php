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
defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class QuestionsModelProfiles extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.userid',
				'username', 'a.username',
				'email', 'a.email',
				'points', 'a.points',
				'rank', 'a.rank',
				'asked', 'a.asked',
				'answered', 'a.answered',
				'chosen', 'a.chosen',
				'blocked', 'a.blocked',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout =  JFactory::getApplication()->input->get('layout', 'default')) {
			$this->context .= '.'.$layout;
		}

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$active = $this->getUserStateFromRequest($this->context.'.filter.active', 'filter_active');
		$this->setState('filter.active', $active);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state');
		$this->setState('filter.state', $state);

		$groupId = $this->getUserStateFromRequest($this->context.'.filter.group', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);

		$groups = json_decode(base64_decode( JFactory::getApplication()->input->get('groups', '', 'default', 'BASE64')));
		if (isset($groups)) {
			JArrayHelper::toInteger($groups);
		}
		$this->setState('filter.groups', $groups);

		$excluded = json_decode(base64_decode( JFactory::getApplication()->input->get('excluded', '', 'default', 'BASE64')));
		if (isset($excluded)) {
			JArrayHelper::toInteger($excluded);
		}
		$this->setState('filter.excluded', $excluded);

		// Load the parameters.
		$params		= JComponentHelper::getParams('com_users');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.active');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.group_id');

		return parent::getStoreId($id);
	}

	/**
	 * Gets the list of users and adds expensive joins to the result set.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.6
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store])) {
			$groups = $this->getState('filter.groups');
			$groupId = $this->getState('filter.group_id');
			if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups))) {
				$items = array();
			}
			else {
				$items = parent::getItems();
			}

			// Bail out on an error or empty list.
			if (empty($items)) {
				$this->cache[$store] = $items;

				return $items;
			}

			// Joining the groups with the main query is a performance hog.
			// Find the information only on the result set.

			// First pass: get list of the user id's and reset the counts.
			$userIds = array();
			foreach ($items as $item)
			{
				$userIds[] = (int) $item->id;
				$item->group_count = 0;
				$item->group_names = '';
			}

			// Get the counts from the database only for the users in the list.
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);

			// Join over the group mapping table.
			$query->select('map.user_id, COUNT(map.group_id) AS group_count')
				->from('#__user_usergroup_map AS map')
				->where('map.user_id IN ('.implode(',', $userIds).')')
				->group('map.user_id')

			// Join over the user groups table.
				->select('GROUP_CONCAT(g2.title SEPARATOR '.$db->Quote("\n").') AS group_names')
				->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$db->setQuery($query);

			// Load the counts into an array indexed on the user id field.
			$userGroups = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error) {
				$this->setError($error);

				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($userGroups[$item->id])) {
					$item->group_count = $userGroups[$item->id]->group_count;
					$item->group_names = $userGroups[$item->id]->group_names;
				}
			}

			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__questions_userprofile AS a');

		// If the model is set to check item state, add to the query.
		$state = $this->getState('filter.state');

    	// If the model is set to check the activated state, add to the query.
		$active = $this->getState('filter.active');

	// Filter the items over the group id if set.
		$groupId = $this->getState('filter.group_id');
		$groups = $this->getState('filter.groups');
		if ($groupId || isset($groups)) {
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.id');
			$query->group('a.id');
			if ($groupId) {
				$query->where('map2.group_id = '.(int) $groupId);
			}
			if (isset($groups)) {
				$query->where('map2.group_id IN ('.implode(',', $groups).')');
			}
		}

		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '') {
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.username LIKE '.$token;
			$searches[]	= 'a.email LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}

		// Filter by excluded users
		$excluded = $this->getState('filter.excluded');
		if (!empty($excluded)) {
			$query->where('id NOT IN ('.implode(',', $excluded).')');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.username')).' '.$db->escape($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}
?>