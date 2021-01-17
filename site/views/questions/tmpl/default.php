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

JHTML::_('behavior.modal');
$app = JFactory::getApplication();
$params = $app->getParams();
$mainpagetitle =  $params->get('mainpagetitle', 'Questions');
$articleid = $params->get('helparticleid', 1);
$document =JFactory::getDocument();
$document->setTitle($mainpagetitle);
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/media/simple.css");
//require_once 'components/com_questions/helpers/cat.php';
require_once 'components/com_questions/media/style.php';
$doc->addStyleSheet("components/com_questions/css/style.css");
use Joomla\CMS\Factory;
//var_dump($this->questions); exit;
?>

<main id="tt-pageContent" class="tt-offset-small">
<div style="float:left;">
<form action="<?php echo JRoute::_('index.php');?>" method="post">
<?php
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
$mitemid = @$set_Itemid > 0 ? $set_Itemid : JRequest::getInt('Itemid');
$width			= 37;
$maxlength		= $upper_limit;
$text			= htmlspecialchars($params->get('text', JText::_('COM_QUESTIONS_SEARCH')));

			$output = '<div id="SearchBar"><input name="searchword" maxlength="'.$maxlength.'"  type="text" size="'.$width.'" value="'.$text.'"  onblur="if (this.value==\'\') this.value=\''.$text.'\';" onfocus="if (this.value==\''.$text.'\') this.value=\'\';" /></div>';
			echo $output;
		?>
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="areas" value="questions" />
	<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
</form>
</div>
<?php

if (isset($this->viewFilteringOptions)){
	echo $this->filteringOptions;
}
?>

<div>
<?php

if($this->params->get('sorting_backend')==0 && $this->params->get('display_sorting')==1){
	echo $this->sortingOptions;
}

?>
    <div class="qcontainer">
        <div class="tt-topic-list">
            <div class="tt-list-header">
                <div class="tt-col-topic">Topic</div>
                <div class="tt-col-category">Category</div>
                <div class="tt-col-value hide-mobile">Likes</div>
                <div class="tt-col-value hide-mobile">Replies</div>
                <div class="tt-col-value hide-mobile">Views</div>
                <div class="tt-col-value">Activity</div>
            </div>
            <?php /*
            <div class="tt-topic-alert tt-alert-default" role="alert">
              <a href="#" target="_blank">4 new posts</a> are added recently, click here to load them.
            </div>
			*/ ?>
            <?php $i=0; ?>
            <?php foreach($this->questions as $question){ ?>
            <?php
			

            $user = Factory::getUser($question->userid_creator);
            $name = substr($user->name,0,1);
            $userFirstname = strtoupper($name);
			?>
            
             <?php if($i > 21){$i = 0;} ?>
            	<?php if($question->pinned ==1 ){ ?>
                <div class="tt-item tt-itemselect">
                <?php } else { ?>
                <div class="tt-item">
                 <?php } ?>
                    <div class="tt-col-avatar">
                          <div class="circle c<?php echo sprintf("%02d", $i+1); ?>"><?php echo $userFirstname; ?></div>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                        	<?php 	if($question->pinned == 1){
										echo '<img src="components/com_questions/css/images/pin.png" alt="Pinned">';
									} elseif ($question->locked == 1) {
										echo '<img src="components/com_questions/css/images/lock.png" alt="Locked">';
									} elseif ($question->chosen == 1) {
										echo '<img src="components/com_questions/css/images/tick.png" alt="Chosen">';
									}
                            ?>
                            <a href="<?php echo $question->link; ?>"><?php echo $question->title; ?></a>
                        </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span class="tt-color<?php echo sprintf("%02d", $i+1); ?> tt-badge"><?php echo $question->CategoryName; ?></span></a></li>
                                    
                                    <?php 
									if (!empty($question->qtags[0])):
										//echo JText::_("COM_QUESTIONS_TAGS") . ": ";
										foreach ($question->qtags as $tag):
										$tag=$this->cleanString($tag);
										?>
                                        <li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $tag); ?>"><span class="tt-badge"><?php echo $tag ?></span></a></li>                                      
									<?php 
										endforeach;
									endif;
									?>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">1h</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category">
                    <a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $question->catid); ?>">
                    <span class="tt-color<?php echo sprintf("%02d", $i+1); ?> tt-badge"><?php echo $question->CategoryName; ?></span></div>
                    </a>
                    <div class="tt-col-value hide-mobile"><?php echo $question->likes; ?></div>
                    <div class="tt-col-value tt-color-select hide-mobile"><?php echo $question->answerscount; ?></div>
                    <div class="tt-col-value hide-mobile"><?php echo $question->impressions; ?></div>
                    <div class="tt-col-value hide-mobile"><?php echo$this->getActiveDuration($question->modified) ;?></div>
                </div>
            <?php $i++; } ?>
            
            <div class="pagination_new">
                <p class="counter">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
            <?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
            CopyrightHelper::copyright();
            ?>
            
            <div class="tt-row-btn">
                <button type="button" class="btn-icon js-topiclist-showmore">
                    <svg class="tt-icon">
                      <use xlink:href="#icon-load_lore_icon"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</main>