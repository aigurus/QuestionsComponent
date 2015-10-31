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

defined('_JEXEC') or die('Restricted access'); 
$app = JFactory::getApplication();
$params = $app->getParams();
JHTML::_('behavior.modal');
$articleid = $this->escape($params->get('helparticleid', 1));
$favlimit = $this->escape($params->get('favlimit', 2));
$qtagsCount = $this->escape($params->get('tagsCount', 5));

//echo $profile->profile['address1'];
//var_dump($user[0]);

$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/css/profiles.css");
$doc->addStyleSheet("components/com_questions/css/simple-profile.css");

//$doc->addScript("http://maps.google.com/maps/api/js?sensor=false");
jimport('joomla.html.pagination');
$app	= JFactory::getApplication();
$total= 13;
$mainframe =JFactory::getApplication();

$limit = $mainframe->getUserStateFromRequest('global.list.limit','limit', $mainframe->getCfg('list_limit'));
$limitstart = $mainframe->getUserStateFromRequest(JFactory::getApplication()->input->get('option').'limitstart','limitstart', 0);
$total=$this->counttotaltags();

// List state information

?>
<h1><?php echo JText::_('COM_QUESTIONS_TAGS_PAGE') ?></h1>
 <form action="<?php echo JRoute::_('index.php?option=com_questions&view=tags'); ?>" method="post" name="adminForm" id="adminForm">  
<div style="position: relative;">

<ul style="float:left;list-style-type: none;">
    <?php
	    //$obj = json_decode($rows);
		$row = $this->getTags();
		//print_r($row);
		//sort($row, SORT_NUMERIC); 
		//print_r($row);
		//var_dump($row);
				if(is_array($row)):
					/*var_dump($total);
					var_dump($limitstart);
					var_dump(intval($limit));*/
					/*$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
					$limitstart = JFactory::getApplication()->input->get('limitstart', 0, '', 'int');*/
					
					if(intval($limit+intval($limitstart))<$total){
							$limit2=intval($limit)+intval($limitstart);
					}else{
					$limit2=$total;	
					}
					?>
                   
                    <?php
					// $limitstart to $limistrat so 0 results
					for($ti=intval($limitstart);$ti<($limit2);$ti++){	
					
					?>
               		
                    <div class="question-li-box">
                        <li rel="tag" title="" >
                        <a class="question-tag" href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $row[$ti]); ?>"><?php echo $row[$ti]; ?></a>
                        <span class="tag-multiples"><span class="x-factor">×</span>&nbsp;<span class="tag-count">
                        <?php echo $this->countTags($row[$ti]); ?>
                        </span>
                        </span>
                        </li>
                        
                    </div>
                    <?php 
					} ?>
					
</ul>
<div style="clear:both"></div>	
</div>	
					<?php
					
					//var_dump($total);
					$pagination = new JPagination($total, intval($limitstart),intval($limit));
					$pagination_footer = $pagination->getListFooter();
					echo $pagination_footer;
					endif;
					?>
</form>
<div>
<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	CopyrightHelper::copyright();
?>

</div>
