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
//JHtml::_('bootstrap.loadCss');
JHtml::_('bootstrap.framework'); 
$app = JFactory::getApplication();
$params = $app->getParams();
$mainpagetitle =  $params->get('mainpagetitle', 'Questions-Categories');
$doc =JFactory::getDocument();
$doc->setTitle($mainpagetitle);
$doc->addStyleSheet("components/com_questions/media/simple.css");
$doc->addStyleSheet("components/com_questions/css/style.css");
//$doc->addScript("components/com_questions/media/js/bundle.js");

//require_once 'components/com_questions/helpers/cat.php';
require_once 'components/com_questions/media/style.php';

$model = JModelLegacy::getInstance('Categories', 'QuestionsModel');
$catarray = $this->categories; 

$count=count($catarray);


?><?php

/*if (isset($this->viewFilteringOptions)){
	echo $this->filteringOptions;
}*/
?>

<main id="tt-pageContent">
    <div class="tt-custom-mobile-indent qcontainer">
        <div class="tt-categories-title">
            <div class="tt-title">Categories</div>
            <div class="tt-search">
                <form class="search-wrapper">
                    <div class="search-form">
                        <input type="text" class="tt-search__input" placeholder="Search Categories">
                        <button class="tt-search__btn" type="submit">
                           <svg class="tt-icon">
                              <use xlink:href="#icon-search"></use>
                            </svg>
                        </button>
                         <button class="tt-search__close">
                           <svg class="tt-icon">
                              <use xlink:href="#icon-cancel"></use>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="tt-categories-list">
            <div class="row">
				<?php
				
				$i=0;
				
				foreach($this->categories as $category) {
					
					$tagnames = $category->tags;
					$ids = $category->tagids;
					$tagarray = array();
					$tagids = array();
					$tagarray = explode(',', $tagnames);
					//var_dump($tagarray);
					$tagids = explode(',', $ids);
					//var_dump($tagids);
					$tags = array_combine($tagids,$tagarray);
					//var_dump($tags);
				if(isset($category->title) && $category->level==1){ ?>
                
                <?php if($i > 21){$i = 0;} ?>
                
                <div class="col-md-6 col-lg-4">
                    <div class="tt-item">
                        <div class="tt-item-header">
                            <ul class="tt-list-badge">
                                <li><a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $model->getAlias($category->id)); ?>"><span class="tt-color<?php echo sprintf("%02d", $i+1); ?> tt-badge"><?php echo $category->title; ?></span></a></li>
                            </ul>
                            <h6 class="tt-title"><a href="<?php echo JURI::base()."index.php?option=com_questions&view=questions&catid=".$category->id; ?>">Threads - <?php echo $model->countCat($category->id); ?></a></h6>
                        </div>
                        <div class="tt-item-layout">
                           <div class="tt-innerwrapper">
                               <?php echo $category->description; ?>
                           </div>
                           <div class="tt-innerwrapper">
                                <h6 class="tt-title">Similar TAGS</h6>
                                <ul class="tt-list-badge">
                                <?php if(!empty($tags) && count($tags) > 0){
										
										  foreach($tags as $tag=>$val) {
											if(strlen($val)>0){ ?>
												<li itemprop="keywords">
                                                <a href="<?php echo JRoute::_(QuestionsHelperRoute::getCategoryRoute($tag . ':' . $val)); ?>">
                                                    <span class="tt-badge"><?php echo $this->escape($val); ?></span>
                                                </a>
                                            </li>
											<?php	  
											}
												//echo '<li><a href="#"><span class="tt-badge">'.$tag.'</span></a></li>';
										 } 
									  }
								 ?>
                                </ul>
                           </div>
                           <div class="tt-innerwrapper">
                                <a href="#" class="tt-btn-icon">
                                    <i class="tt-icon"><svg><use xlink:href="#icon-favorite"></use></svg></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php $i++; }} ?>
                <div class="col-12">
                    <div class="tt-row-btn">
                        <button type="button" class="btn-icon js-topiclist-showmore">
                            <svg class="tt-icon">
                              <use xlink:href="#icon-load_lore_icon"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
