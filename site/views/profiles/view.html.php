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

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/css/profiles.css");

class QuestionsViewProfiles extends QueView
{
	protected $id;
	
    public function display($tpl = null)
    {
			  $groupsmodel = JModelLegacy::getInstance('Groups', 'QuestionsModel');
			  //$groupdetails = $groupsmodel->getUserGroups();
		
			  $userquestions = $this->get('UserQuestions');
		
			  $this->userquestions = $userquestions;
		
			  $useranswers = $this->get('UserAnswers');
			
			  $this->useranswers = $useranswers;
		
			  //$this->assignRef('groupdetails',$groupdetails );
			  /*JLoader::import('profiles', JPATH_ROOT.'/components/com_questions/models');
			  $model = JModelLegacy::getInstance('Profiles', 'QuestionsModel');*/
			  $model = JModelLegacy::getInstance('Profiles', 'QuestionsModel');	
		  
			  $data  = $model->GetUserList();
			  
			  $mygroups  = $model->getMyGroups();
			   
			  $this->assignRef('mygroups',$mygroups);
			  	
			  $user = JFactory::getUser();
			  $ownedit = $user->authorise("profile.edit.own" , "com_questions");
			  $alledit = $user->authorise("profile.edit.all" , "com_questions");

        	  $this->assignRef("ownedit", $ownedit);
        	  $this->assignRef("alledit", $alledit);
		
			  //$modelg = JModelLegacy::getInstance('Group', 'QuestionsModel');
			  //$userdetails = $modelg->getUsers();
			  	
			  //$this->assignRef('userdetails',$userdetails );
			
			  

			  $this->data = $data;
			  
			  // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
					
                        JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
                        return false;
                }
				//
                // Display the view
    		  parent::display($tpl);
    }
	public function useractivity($id){
			$model = JModelLegacy::getInstance('Profiles', 'QuestionsModel');	
			$activities = $model->getUserActivities($id);
			return $activities;
	}
	
	public function getQAList($prouser,$varlist){
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$qalimit = $params->get('qalimit', 2);
		$db =JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->select("a.*, a.votes_positive-a.votes_negative as score, a.votes_positive+a.votes_negative as votes, (SELECT COUNT(*) FROM #__questions_core AS b WHERE b.parent=a.id AND b.published=1) as answerscount, c.title AS CategoryName");
		$query->from("#__questions_core AS a");
		$query->leftJoin("#__categories AS c ON c.id=a.catid");
		$where = array();
		
		$where[] = "a.question=".$varlist; // questions only
		$where[] = "a.userid_creator=".$prouser;

		if ( ! @$show_unpublished )
			$where[] = "a.published=1"; // only published items
		
		//apply filters
		if ( ! empty( $where ) )
		$query->where( $where );
		$query->order('a.submitted DESC');
		$db->setQuery($query,0,$qalimit);
		$rows=$db->loadObjectList();
		return $rows;
	}
	
	
	public function GetUserDetails($id)
    {
      $db =JFactory::getDBO();
 
      $query = "SELECT * FROM #__users where id=" . $id;
      $db->setQuery( $query );
      $user = $db->loadObjectList();
	  
	  return $user;
  	}
  	public function GetProfileDetails($id)
    {
      $db =JFactory::getDBO();
 
      $query = "SELECT * FROM #__questions_userprofile where userid=" . $id;
      $db->setQuery( $query );
      $prouser = $db->loadObjectList();
	  
	  return $prouser;
  	}
	
  	public function getFavourite2($userid){
		$db = JFactory::getDBO();
		$query2 = $db->getQuery(true);
		$query2->select("userfav");
		$query2->from('#__questions_favourite');
		$query2->where('userid='.$userid);
		$db->setQuery((string)$query2);
		$result = $db->loadResult();
		return $result;
					
	}
	public function GetProfileHits($id)
    {
      $db =JFactory::getDBO();

      $query = "SELECT impressions FROM #__questions_userprofile where userid=" . $id;
      $db->setQuery( $query );
      $prouser = $db->loadResult();
	  
	  return $prouser;
  	}
	public function profilehits( $id=NULL ){
		if ($id){
			$this->id = $id;
		}
		
		if ( $this->id ){
							
			$db = JFactory::getDbo();
			
			$q = "UPDATE #__questions_userprofile SET impressions = impressions+1 WHERE userid=" . (int)$this->id;				
			$db->setQuery($q);
			
			if (!$db->execute()){
				return FALSE;
			}
			 
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
		function getTags($id){
			
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('qtags');
					$query->from('#__questions_core');
					$query->where('userid_creator='.$id);
					//$query.=' LIMIT ='. $qtagsCount;
					$db->setQuery($query);
					$rows=$db->loadAssoclist();
					$rows=$this->array_flatten($rows);
					$rows = $this->arrayUnique($rows);
					$rows = array_map('trim', $rows);
					//var_dump($rows);
					$newrow = array();
					$j=0;
					foreach($rows as $row){
						$i=$j;
						$newrows=explode(",",$row);
						//preg_match_all('/([a-z0-9_#-]{4,})/i', $newrowss, $newrows);
						foreach($newrows as $rown){
							  if(strlen($rown)>0){
							  $rown = $this->cleanString($rown);
							  $newrow[$i]=$rown;
							  $i+=1;
							  }
						 }
						 $j=count($newrows)+$i;
			
					}
					$newrow= $this->array_flatten($newrow);
					$newrows = $this->arrayUnique($newrow);
					$rows = array_filter($newrows);
					sort($rows, SORT_NUMERIC); 
					return $rows;
					
					
					/*$app = JFactory::getApplication();
					$params = $app->getParams();
					$qtagsCount = $params->get('tagsCount', 5);*/
					/*
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('qtags');
					$query->from('#__questions_core');
					$query->where('userid_creator='.$id);
					//$query.=' LIMIT ='. $qtagsCount;
					$db->setQuery($query);
					$rows=$db->loadObjectList();
					return $rows;*/
		}
		function cleanString($string) {
			
		  	$clear = strip_tags($string);
			// Clean up things like &amp;
			$clear = html_entity_decode($clear);
			// Strip out any url-encoded stuff
			$clear = urldecode($clear);
			// Replace non-AlNum characters with space
			$clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
			// Replace Multiple spaces with single space
			$clear = preg_replace('/ +/', ' ', $clear);
			// Trim the string of leading/trailing space
			$clear = trim($clear);
			return $clear;
		}
		
		function arrayUnique($myArray){
			if(!is_array($myArray))
				return $myArray;
		
			foreach ($myArray as &$myvalue){
				$myvalue=serialize($myvalue);
			}
		
			$myArray=array_unique($myArray);
		
			foreach ($myArray as &$myvalue){
				$myvalue=unserialize($myvalue);
			}
		
			return $myArray;
		
		} 
		
			function getRP($userid){
				
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('rank,points');
					$query->from('#__questions_userprofile');
					$query->where('userid='.$userid);
					$db->setQuery($query);
					$rank = $db->loadAssoc();
					
					return $rank;
					
		   }
		   
		   function getId($userid){
					$rank = $this->getRank($userid);
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('id');
					$query->from('#__questions_ranks');
					$query->where('rank="'.$rank.'"');
					$db->setQuery((string)$query);
					$image = $db->loadResult();
					return $image;
					
		   }
	   public function gettemplate($prouser1,$varlist1){
				   $vararray = $this->getQAList($prouser1,$varlist1);
				   
                   if(isset($this->pageclass_sfx)){ ?>
				   <div class="questions<?php echo $this->pageclass_sfx; ?>">
                   <?php
                   } else { ?>
                   <div class="questions">
                   <?php
                   } ?>
							<div>
							<div>	
							<div>
					<?php
					foreach($vararray as $question): 
						?>
						<div class="question system-<?php echo ($question->published ? 'published' : 'unpublished');?>">
								<div>	
									<h2>
										<a href="<?php echo JRoute::_("index.php?option=com_questions&view=question&id=" . $question->id); ?>"><?php echo $question->title; ?></a>						
									</h2>
								<div style="clear:both"></div>
									<h4><?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name); */?>
					<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$question->userid_creator . "%3A" . JFactory::getUser($question->userid_creator)->name) ?> ><?php echo ($question->userid_creator ? JFactory::getUser($question->userid_creator)->name : $question->name) ?></a>
					
					 <?php echo " On "?> <?php echo JHtml::date($question->submitted); ?> 	
									<h4 class="category">
										<?php if ($question->catid): //if category?>
											<?php echo JText::_("COM_QUESTIONS_CATEGORY"); ?>:
											<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $question->catid); ?>">
												<?php echo $question->CategoryName; ?>
											</a>
										<?php endif; //endif category?>
									</h4>
										
								</div>
							<?php 
							$appParams = json_decode(JFactory::getApplication()->getParams());
							if (isset($appParams->display_stats)):
							$viewStats = $appParams->display_stats;
							if (isset($viewStats)): ?>		
							<div class="boxes">
								<a href="<?php echo JRoute::_("index.php?option=com_questions&view=question&id=" . $question->id); ?>">
									<span class="votes"><?php echo $question->votes; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_VOTES")?></span></span>
									<span class="answers"><?php echo $question->answerscount; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_ANSWERS_LOWERCASE")?></span></span>
									<span class="impressions"><?php echo $question->impressions; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_VIEWS")?></span></span>
								</a>
							 <div style="clear:both"></div>
							<?php endif;?>
							<?php endif;?>
							</div>
						</div>
					<?php 
					endforeach; ?>
					</div>
					</div>
					</div>
					</div>
						<?php
		   }
		   
		   function sumArray($array, $params = array('direction' => 'x', 'key' => 'xxx'), $exclusions = array()) {

					if(!empty($array)) {
				   
						$sum = 0;
				   
						if($params['direction'] == 'x') {
					   
							$keys = array_keys($array);
						   
							for($x = 0; $x < count($keys); $x++) {
						   
								if(!in_array($keys[$x], $exclusions))
									$sum += $array[$keys[$x]];
						   
							}
						   
							return $sum;
					   
						} elseif($params['direction'] == 'y') {
					   
							$keys = array_keys($array);
					   
							if(array_key_exists($params['key'], $array[$keys[0]])) {
						   
								for($x = 0; $x < count($keys); $x++) {
							   
									if(!in_array($keys[$x], $exclusions))
										$sum += $array[$keys[$x]][$params['key']];
								   
								}
                   
								return count($sum);
						   
							} else return false;
					   
						} else return false;
				   
					} else return false;
					/*
				$array1 = array('myKey1' => 2, 'myKey2' => 5, 'myKey3' => 8);
				$array2 = array(array('myKey1' => 2, 'myKey2' => 5, 'myKey3' => 8), array('myKey1' => 2, 'myKey2' => 5, 'myKey3' => 8));
				
				/*Sum an array
				print_r(sumArray($array1)); //outputs: 15
				
				/*Sum an array without adding "myKey2" key
				print_r(sumArray($array1, array('direction' => 'x'), array('myKey2'))); //outputs: 10
				
				/*Sum a multi-dimensional array horizontally without adding "myKey3" key
				print_r(sumArray($array2[0], array('direction' => 'x'), array('myKey3'))); //outputs: 7
				
				/*Sum a multi-dimensional array vertically (by "myKey1" key) without adding [0] row
				print_r(sumArray($array2, array('direction' => 'y', 'key' => 'myKey1'), array('0'))); //outputs: 2
								*/
				
				}
				
				function array_push_array(&$arr) {
						$args = func_get_args();
						array_shift($args);
					
						if (!is_array($arr)) {
						trigger_error(sprintf("%s: Cannot perform push on something that isn't an array!", __FUNCTION__), E_USER_WARNING);
							return false;
						}
					
						foreach($args as $v) {
							if (is_array($v)) {
								if (count($v) > 0) {
									array_unshift($v, $arr);
									call_user_func_array('array_push',  $v);
								}
							} else {
								$arr[] = $v;
							}
						}
						return $arr;
				}
				function getfavouritedata($userid,$varfav){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select($varfav);
					$query->from('#__questions_favourite');
					$query->where('userid='.$userid);
					$db->setQuery($query);
					$favourites = $db->loadResult();
					$result = unserialize($favourites);
					return $result;
				}
				public function getFavQAList($favid,$varlist){
					$db =JFactory::getDBO();
					$query = $db->getQuery(TRUE);
					$query->select("a.*, a.votes_positive-a.votes_negative as score, a.votes_positive+a.votes_negative as votes, (SELECT COUNT(*) FROM #__questions_core AS b WHERE b.parent=a.id AND b.published=1) as answerscount, c.title AS CategoryName");
					$query->from("#__questions_core AS a");
					$query->leftJoin("#__categories AS c ON c.id=a.catid");
					$query->where( "a.id=".$favid." AND a.published=1 AND a.question=".$varlist);
					$db->setQuery($query);
					//$db->setQuery($query);
					$rows=$db->loadObjectList();
					return $rows;
				}
				
				public function getfavtemplate($favid,$varlist){
				   $vararray = $this->getFavQAList($favid,$varlist);
				   //echo count($vararray);
				   ?>
				   <div class="questions<?php echo $this->pageclass_sfx; ?>">
							<div>
					<?php
					foreach($vararray as $question): 
						?>
						<div class="question system-<?php echo ($question->published ? 'published' : 'unpublished');?>">
								<div>	
									<h2>
										<a href="<?php echo JRoute::_("index.php?option=com_questions&view=question&id=" . $question->id); ?>"><?php echo $question->title; ?></a>						
									</h2>
								<div style="clear:both"></div>
									<h4><?php echo JText::_("COM_QUESTIONS_SUBMITTED_BY"); ?> <?php /*echo ($this->question->userid_creator ? JFactory::getUser($this->question->userid_creator)->name : $this->question->name); */?>
					<a href= <?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$question->userid_creator . "%3A" . JFactory::getUser($question->userid_creator)->name) ?> ><?php echo ($question->userid_creator ? JFactory::getUser($question->userid_creator)->name : $question->name) ?></a>
					
					 <?php echo " On "?> <?php echo JHtml::date($question->submitted); ?> 	
									<h4 class="category">
										<?php if ($question->catid): //if category?>
											<?php echo JText::_("COM_QUESTIONS_CATEGORY"); ?>:
											<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $question->catid); ?>">
												<?php echo $question->CategoryName; ?>
											</a>
										<?php endif; //endif category?>
									</h4>
										
								</div>
							<?php 
							$appParams = json_decode(JFactory::getApplication()->getParams());
							if (isset($appParams->display_stats)):
							$viewStats = $appParams->display_stats;
							if (isset($viewStats)): ?>		
							<div class="boxes">
								<a href="<?php echo JRoute::_("index.php?option=com_questions&view=question&id=" . $question->id); ?>">
									<span class="votes"><?php echo $question->votes; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_VOTES")?></span></span>
									<span class="answers"><?php echo $question->answerscount; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_ANSWERS_LOWERCASE")?></span></span>
									<span class="impressions"><?php echo $question->impressions; ?><br /><span class="label"><?php echo JText::_("COM_QUESTIONS_VIEWS")?></span></span>
								</a>
							 <div style="clear:both"></div>
							<?php endif;?>
							<?php endif;?>
							</div>
						</div>
					<?php 
					endforeach; ?>
					</div>
					</div>
						<?php
		   }
		   
		   function parseToXML($htmlStr) 
				{ 
				$xmlStr=str_replace('<','&lt;',$htmlStr); 
				$xmlStr=str_replace('>','&gt;',$xmlStr); 
				$xmlStr=str_replace('"','&quot;',$xmlStr); 
				$xmlStr=str_replace("'",'&#39;',$xmlStr); 
				$xmlStr=str_replace("&",'&amp;',$xmlStr); 
				return $xmlStr; 
				} 
		   
		   function gmaps(){
				$app = JFactory::getApplication();
				$params = $app->getParams();
				$gmapsapi = $params->get('gmapsapi', 'AIzaSyD2YzAdJ5PiyA6tgtkfBR67Lj1g706P214');
				/*gmail bankingcoaching*/
				$apiKey   = $params->get('apiKey', '922f47ca3eab5d062a15c269fcac6fac5bbd6270e632a1c301ec7e6982047317'); 
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$ipAddress = "117.217.168.125";
				$xml = simplexml_load_file('http://api.ipinfodb.com/v2/ip_query.php?key='.$apiKey.'&ip='.$ipAddress.'&timezone=true');
				  echo 'name="' . $this->parseToXML($xml->CountryName) . '" ';
				  echo 'address="' . $this->parseToXML($xml->City) . '" ';
				  echo 'lat="' . $xml->Latitude . '" ';
				  echo 'lng="' . $xml->Longitude . '" ';
				  echo 'type="' . $xml->RegionName . '" ';
				  echo 'status="' .$xml->Status . '" ';
				  echo 'timezone="' .$xml->TimezoneName . '" ';
		   
						   
		   }
		   function array_flatten($input, $maxdepth = NULL, $depth = 0) { 
			  
				if(!is_array($input)){ 
				  return $input;
				}
			
				$depth++;
				$array = array(); 
				foreach($input as $key=>$value){
				  if(($depth <= $maxdepth or is_null($maxdepth)) && is_array($value)){
					$array = array_merge($array, $this->array_flatten($value, $maxdepth, $depth));
				  } else {
					array_push($array, $value);
					// or $array[$key] = $value;
				  }
				}
				return $array;
			}



		function counttotaltags(){
			$i=0;
			$rows= $this->getTags();
			foreach ($rows as $row2){
				$i++;	
			}
			return $i;
		} 
		  	
}
?>
