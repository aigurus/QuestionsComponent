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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class QuestionsControllerquazax extends QueController {

    /**
     * Method to display the view
     */
    function display($cachable = false, $urlparams = false) {
        //get user id for logincheck
        parent::display();
    }

    function addnewgroup() {
		$jinput = JFactory::getApplication()->input;
		$pin['userid'] = $jinput->get('userid');
		$pin['group_name'] = $jinput->get('group_name');
        //$pin['userid'] = JRequest::getVar('userid');
        //$pin['group_name'] = JRequest::getVar('group_name');
        $model = $this->getModel('group');
        $arr = $model->addnewgroup($pin);
        echo $arr;
        die;
    }
	
	function deletegroup() {

        $group = JRequest::getVar('id');
        $model = $this->getModel('group');
        $arr = $model->deletegroup($group);
        echo $arr;
        die;
    }

    function followall() {
        $mainframe = JFactory::getApplication();
        $follow['user_id'] = JRequest::getVar('user_id');
        $follow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getFollow($follow);
        echo $result;
        die;
    }
	
	 function getusers() {
        $mainframe = JFactory::getApplication();
        $model 	= $this->getModel('group');
        $result = $model->getUsers();
        echo $result;
        die;
    }
	
	function getgroups() {
        $mainframe = JFactory::getApplication();
        $model 	= $this->getModel('groups');
        $result = $model->getGroups();
        echo $result;
        die;
    }
	
	function sendnotification() {
        $mainframe = JFactory::getApplication();
        $usergroups['userid'] = JRequest::getVar('userid');
        $usergroups['users'] = JRequest::getVar('users');
        $usergroups['groups'] = JRequest::getVar('groups');

        $model = $this->getModel('groups');
        $result = $model->sendNotification($usergroups);
        echo $result;
        die;
    }

    function unfollowall() {
        $mainframe = JFactory::getApplication();
        $unFollow['user_id'] = JRequest::getVar('user_id');
        $unFollow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnFollow($unFollow);
        echo $result;
        die;
    }

    function followboard() {
        $mainframe = JFactory::getApplication();
        $unFollowBoard['user_id'] = JRequest::getVar('user_id');
        $unFollowBoard['fuser_id'] = JRequest::getVar('fuser_id');
        $unFollowBoard['board_id'] = JRequest::getVar('boardid');
        $model = $this->getModel('home');
        $result = $model->getFollowBoard($unFollowBoard);
        echo $result;
        die;
    }

    function unfollowboard() {
        $mainframe = JFactory::getApplication();
        $unFollowBoard['user_id'] = JRequest::getVar('user_id');
        $unFollowBoard['fuser_id'] = JRequest::getVar('fuser_id');
        $unFollowBoard['board_id'] = JRequest::getVar('boardid');

        $model = $this->getModel('home');
        $result = $model->getUnFollowBoard($unFollowBoard);
        echo $result;
        die;
    }

    function getcategoryimage() {
        $mainframe = JFactory::getApplication();
        $category['userId'] = JRequest::getVar('userId');
        $category['category_id'] = JRequest::getVar('cid');

        $model = $this->getModel('userfollow');
        $result = $model->getFollowersCategory($category);
        echo $result;
        die;
    }

    function getuncategoryimage() {
        $mainframe = JFactory::getApplication();
        $category['userId'] = JRequest::getVar('userId');
        $category['category_id'] = JRequest::getVar('cid');

        $model = $this->getModel('userfollow');
        $result = $model->getFollowersUnCategory($category);
        echo $result;
        die;
    }

    function checkUserName() {

        $mainframe = JFactory::getApplication();
        $user_name = JRequest::getVar('username');

        $model = $this->getModel('mailfriends');
        $results = $model->checkUserName($user_name);
        echo $results;
        die();
    }

    function checkEmail() {

        $mainframe = JFactory::getApplication();

        $email = JRequest::getVar('email');

        $model = $this->getModel('mailfriends');
        $results = $model->checkEmail($email);
        echo $results;
        die();
    }

    function addcontributers() {
        $mainframe = JFactory::getApplication();
        $contributors = JRequest::getVar('name_contributers');
        $model = $this->getModel('home');
        $result = $model->contributers($contributors);
        echo $result;
        die;
    }

    function getcontributers() {
        $mainframe = JFactory::getApplication();

        $user_contributers['user'] = JRequest::getVar('user');
        $user_contributers['bidd'] = JRequest::getVar('bidd');

        $model = $this->getModel('boardedit');
        $results = $model->getContributers($user_contributers);

        $inc = 0;
        $username = '';
        if ($results != '') {
            foreach ($results as $result) {

                if ((count($results) - 1) == $inc) {
                    $username['username'][] = $result->username;
                    $username['userid'][] = $result->user_id;
                } else {

                    $username['username'][] = $result->username;
                    $username['userid'][] = $result->user_id;
                }
                $inc++;
            }
        } else {
            $username = "no user";
        }

        echo json_encode($username);

        die;
    }

    function removeContributers() {
        $mainframe = JFactory::getApplication();
        $user_contributers['user_id'] = JRequest::getVar('user_id');
        $user_contributers['bidd'] = JRequest::getVar('bidd');
        $model = $this->getModel('boardedit');
        $results = $model->removeContributers($user_contributers);
        echo $results;
        die;
    }

    function addBoardContributers() {
        $mainframe = JFactory::getApplication();

        $user_contributers['name_contributer'] = JRequest::getVar('name_contributer');
        $user_contributers['user_id_contributors'] = JRequest::getVar('user_id_contributors');
        $user_contributers['bidd'] = JRequest::getVar('bidd');

        $model = $this->getModel('boardedit');
        $results = $model->addBoardContributers($user_contributers);


        echo $results;
        die;
    }

    function justMeContributors() {
        $mainframe = JFactory::getApplication();
        $user_contributers_bidd = JRequest::getVar('bidd');
        $model = $this->getModel('boardedit');
        $results = $model->justMeContributors($user_contributers_bidd);
        echo $results;
        die;
    }

    function deleteComment() {
        $user_comment_id = JRequest::getVar('comment_id');
        $comment_pin_id = JRequest::getVar('comment_pin_id');
        $model = $this->getModel('home');
        $results = $model->deleteComment($user_comment_id, $comment_pin_id);
        echo $results;
        die;
    }

    function checkPassword() {
        $user_email = JRequest::getVar('email');
        $user_password = JRequest::getVar('password');
        $twitter_id = JRequest::getVar('twitter_id');
        $model = $this->getModel('userlogin');
        $username = JRequest::getVar('username');
        $results = $model->checkPassword($user_email, $user_password, $twitter_id, $username);
        echo $results;
        die;
    }

    function block() {
        $mainframe = JFactory::getApplication();
        $follow['user_id'] = JRequest::getVar('user_id');
        $follow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnFollow($follow);
        $result = $model->getBlock($follow);
        echo $result;
        die;
    }

    function unblock() {
        $mainframe = JFactory::getApplication();
        $unFollow['user_id'] = JRequest::getVar('user_id');
        $unFollow['fuser_id'] = JRequest::getVar('fuser_id');
        $model = $this->getModel('home');
        $result = $model->getUnblock($unFollow);
        echo $result;
        die;
    }

}

?>