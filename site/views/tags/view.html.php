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

class QuestionsViewTags extends QueView
{
	protected $id;
    public function display($tpl = null)
    {
		 		if (count($errors = $this->get('Errors'))) 
                {
                        JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
                        return false;
                }
                // Display the view
    		  	parent::display($tpl);
    }
	function string_to_array( $string, $delimiter = ',', $kv = '=>')
    {
        if ($element = explode( $delimiter, $string ))
        {
            // create parts
            foreach ( $element as $key_value )
            {
                // key -> value pair or single value
                $atom = explode( $kv, $key_value );

                if( trim($atom[1]) )
                {
                  $key_arr[trim($atom[0])] = trim($atom[1]);
                }
                else
                {
                    $key_arr[] = trim($atom[0]);
                }
            }
        }
        else
        {
            $key_arr = false;
        }

        return $key_arr;
    }
		function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
		// search and remove comments like /* */ and //
			$json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
		   
			if(version_compare(phpversion(), '5.4.0', '>=')) {
				$json = json_decode($json, $assoc, $depth, $options);
			}
			elseif(version_compare(phpversion(), '5.3.0', '>=')) {
				$json = json_decode($json, $assoc, $depth);
			}
			else {
				$json = json_decode($json, $assoc);
			}
		
			return $json;
		}
		function string_sanitize($s) {
			$result = preg_replace("/[^a-zA-Z\s0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
			return $result;
		}
		function getTags(){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('qtags');
					$query->from('#__questions_core');
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
					$newrow=$this->array_flatten($newrow);
					$newrows = $this->arrayUnique($newrow);
					$rows = array_filter($newrows);
					sort($rows, SORT_NUMERIC); 
					return $rows;
					
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
		
		function countTags($tag){

					
					$db = JFactory::getDbo();
 
					// Create a new query object.
					$query = $db->getQuery(true);
					 
					// Select all records from the user profile table where key begins with "custom.".
					// Order it by the ordering field.
					$query->select('COUNT(*)');
					$query->from($db->quoteName('#__questions_core'));
					$query->where($db->quoteName('qtags') . ' LIKE '. $db->quote('%'.$tag.'%'));
					//$query->order('ordering ASC');
					 
					// Reset the query using our newly populated query object.
					$db->setQuery($query);
					$count=$db->loadResult();
					return $count;
					 
					// Load the results as a list of stdClass objects (see later for more options on retrieving data).
					//$results = $db->loadObjectList();

					//$db = JFactory::getDBO();
					//$query = $db->getQuery(true);
					/*$query->select('COUNT(*)');
					$query->from('#__questions_core');
					$query->where('tags LIKE %'.$tag.'%');*/
					/*$query= "SELECT COUNT(*) FROM #__questions_core WHERE tags LIKE '%".$tag."%'";
					$db->setQuery($query);
					$count=$db->loadResult();
					return $count;*/
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



		function arrayUnique($myArray){
			if(!is_array($myArray))
				return $myArray;
			//$myArray = $this->getTags();
			foreach ($myArray as &$myvalue){
				$myvalue=serialize($myvalue);
			}
		
			$myArray=array_unique($myArray);
		
			foreach ($myArray as &$myvalue){
				$myvalue=unserialize($myvalue);
			}
		
			return $myArray;
		
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
