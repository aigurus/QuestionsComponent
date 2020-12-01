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
//JHTML::_('behavior.tooltip');
jimport( 'joomla.application.component.view' );
//include helper functions
require_once ("administrator/components/com_questions/helpers/questions.php");
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_questions/media/simple-question.css");
//require_once 'components/com_questions/helpers/cat.php';
require_once 'components/com_questions/media/style.php';

$app = JFactory::getApplication();
$parameters = $app->getParams();
$params = $app->getParams();

$articleid = $parameters->get('helparticleid', 1);
$appParams = json_decode(JFactory::getApplication()->getParams());
$chosenanswer=$this->getChosen($this->question->id);

$document =JFactory::getDocument();
$document->setTitle($this->question->title);
$document->setDescription(strip_tags($this->question->text));
$document->addStyleSheet("components/com_questions/css/style.css");

if(is_array($this->question->qtags)){
$keytags = implode(",", $this->question->qtags);
}
//var_dump($this->question->qtags);
$document->setMetaData('keywords', @$keytags);
use Joomla\CMS\Factory;
$user = Factory::getUser($this->question->userid_creator);
$name = substr($user->name,0,1);
$userFirstname = strtoupper($name);
//var_dump($this->question); exit;
/*Date*/
function dtformat($dt){
	$dt = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
	$subdate = $dt->format('M d, Y'); //'M d, Y' ---- 'j M Y'
	return $subdate;
}
?>
<script type="text/javascript">

jQuery(document).ready(function($){

$('#addfav').click(function()  {
	var qid = this.id;
	var idvalue = qid.split('-');
	var id = idvalue[idvalue.length - 1];
	var userid = <?php echo JFactory::getUser()->id; ?>; 
    req = $.ajax({
        type: "POST",
        url: "index.php?option=com_questions&task=question.addFavourite&vardata=quesfav&userid="+userid+"&addfav="+<?php echo $this->question->id ?>,
		cache: false,
        success: function(){
			var oldSrc = 'components/com_questions/media/add.png';
			var newSrc = 'components/com_questions/media/rem.png';
			$('img[src="' + oldSrc + '"]').attr('src', newSrc);
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('#delfav').click(function() {
	var qid = this.id;
	var idvalue = qid.split('-');
	var id = idvalue[idvalue.length - 1];
	var userid = <?php echo JFactory::getUser()->id; ?>; 
    req = $.ajax({
        type: "POST",
		cache: false,
        url: "index.php?option=com_questions&task=question.delFavourite&vardata=quesfav&userid="+userid+"&delfav="+<?php echo $this->question->id ?>,
        success: function(){
            var oldSrc = 'components/com_questions/media/rem.png';
			var newSrc = 'components/com_questions/media/add.png';
			$('img[src="' + oldSrc + '"]').attr('src', newSrc);
			location.reload(); 
        }
    });
})
$('#positive').click(function() {
	var newurl = "index.php?option=com_questions&task=question.votepositive&id="+<?php echo $this->question->id ?>;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
        url: newurl,
		cache: false,
        success: function(){
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('#negative').click(function() {
	var newurl = "index.php?option=com_questions&task=question.votenegative&id="+<?php echo $this->question->id ?>;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
		cache: false,
        url: newurl,
        success: function(){
			location.reload(); 
        }
    });
})

$('.anspositive').click(function() {
	var ansid = this.id;
	var idvalue = ansid.split('-');
	var id = idvalue[idvalue.length - 1];
	var newurl = "index.php?option=com_questions&task=question.votepositive&id="+id;
    req = $.ajax({
        type: "POST",
        url: newurl,
		cache: false,
        success: function(){
			location.reload(); 
            //$('#addfav').html(data);
        }
    });
})
$('.ansnegative').click(function() {
	var ansid = this.id;
	var idvalue = ansid.split('-');
	var id = idvalue[idvalue.length - 1];
	var newurl = "index.php?option=com_questions&task=question.votenegative&id="+id;
	console.log(newurl);
    req = $.ajax({
        type: "POST",
		cache: false,
        url: newurl,
        success: function(){
			location.reload(); 
        }
    });
})
})

</script>


<main id="tt-pageContent">
    <div class="qcontainer">
        <div class="tt-single-topic-list">
            <div class="tt-item">
                 <div class="tt-single-topic">
                    <div class="tt-item-header">
                        <div class="tt-item-info info-top">
                            <div class="tt-avatar-icon">
                      			<i class="tt-icon"><div class="circle c01"><?php echo $userFirstname; ?></div></i>
                    		</div>
                            <div class="tt-avatar-title">
                             <a href="<?php echo JRoute::_("index.php?option=com_questions&view=profiles&id=".$user->id); ?>"><?php echo $user->name; ?></a>
                             <?php if ($this->question->editable):?>
                  
                             <a href="<?php echo JRoute::_("index.php?option=com_questions&task=question.edit&id=" . QuestionsHelper::getAlias($this->question->id)); ?>">
                                    <img src="media/system/images/edit.png" />
                                </a>
                                <?php endif; ?>
                            </div>
                            <a href="#" class="tt-info-time">
                                <i class="tt-icon"><svg><use xlink:href="#icon-time"></use></svg></i><?php echo dtformat($this->question->submitted); ?>
                            </a>
                        </div>
                        <h3 class="tt-item-title">
                            <a href="#"><b><?php echo $this->question->title; ?></b></a>
                        </h3>
                        <div class="tt-item-tag">
                        <?php 
						$i=0;
						if ($this->question->qtags && count($this->question->qtags)>0):
							echo '<ul class="tt-list-badge">';
							foreach ($this->question->qtags as $tag):
								$tag = $this->cleanString($tag);
								if(strlen($tag)>0):
								?>
								<li>
								<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&tag=" . $tag); ?>">
								<span class="tt-color<?php echo sprintf("%02d", $i+1); ?> tt-badge"><?php echo $tag ?></span></a>
								</li>
							<?php 
							endif;
							$i++;
							endforeach;
							echo '</ul>';
						endif;
						?>
                        </div>
                    </div>
                    <div class="tt-item-description">
                        <?php echo $this->question->text; ?>
                    </div>
                    <div class="tt-item-info info-bottom">
                        <a href="javascript:void(0)" class="tt-icon-btn">
                            <div class="tt-icon" id="positive"><?php echo '<img src="components/com_questions/css/images/thumbs-up.png" alt="Thumbs Up">'; ?></div>
                            <span class="tt-text"><?php echo $this->question->votes_positive; ?></span>
                        </a>
                        <a href="javascript:void(0)" class="tt-icon-btn">
                             <div class="tt-icon" id="negative"><?php echo '<img src="components/com_questions/css/images/thumbs-down.png" alt="Thumbs Down">'; ?></div>
                            <span class="tt-text"><?php echo $this->question->votes_negative; ?></span>
                        </a>
                        <?php
                        if ($this->addfavorites):
						$reguser = JFactory::getUser();
						$favarray2 = unserialize($this->getFavourite2('quesfav',$reguser->id));
						if(is_array($favarray2)){
						if(!in_array($this->question->id,$favarray2))
						{
						?>
                        <a href="javascript:void(0)"  class="tt-icon-btn">
                             <i class="tt-icon" id="addfav"><?php echo '<img src="components/com_questions/css/images/unlike.png" alt="Unlike">'; ?></i>
                            <span class="tt-text"><?php echo $this->question->likes; ?></span>
                        </a>
                
						<?php } else { ?>
                        
                        <a href="javascript:void(0)"  class="tt-icon-btn">
                             <i class="tt-icon" id="delfav"><?php echo '<img src="components/com_questions/css/images/like.png" alt="Like">'; ?></i>
                            <span class="tt-text"><?php echo $this->question->likes; ?></span>
                        </a>
            			<?php }}else{ ?>
						<a href="javascript:void(0)"  id="addfav" class="tt-icon-btn">
                             <i class="tt-icon" id="addfav"><?php echo '<img src="components/com_questions/css/images/unlike.png" alt="Unlike">'; ?></i>
                            <span class="tt-text"><?php echo $this->question->likes; ?></span>
                        </a>
						<?php } ?>
						<?php endif;?>

                        <div class="col-separator"></div>

                    </div>
                </div>
            </div>
            <div class="tt-item">
                <div class="tt-info-box">
                    <h6 class="tt-title">Thread Status</h6>
                    <div class="tt-row-icon">
                        <div class="tt-item">
                            <a href="#" class="tt-icon-btn tt-position-bottom">
                                <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/reply.png" alt="Reply">'; ?></i>
                                <span class="tt-text"><?php echo count($this->question->answers); ?></span>
                            </a>
                        </div>
                        <div class="tt-item">
                            <a href="#" class="tt-icon-btn tt-position-bottom">
                                <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/views.png" alt="views">'; ?></i>
                                <span class="tt-text"><?php echo $this->question->impressions; ?></span>
                            </a>
                        </div>
                        <div class="tt-item">
                            <a href="#" class="tt-icon-btn tt-position-bottom">
                                <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/users.png" alt="Users">'; ?></i>
                                <span class="tt-text">168</span>
                            </a>
                        </div>
                        <div class="tt-item">
                              <a href="#" class="tt-icon-btn tt-position-bottom">
                                <!-- <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/share.png" alt="Share">'; ?></i> -->
                                <?php SocialIcons::addsocial();	?>
                                <!-- <span class="tt-text">32</span> -->
                            </a>
                        </div>
                    </div>
                   
                   <hr>
                    
                    <div class="row-object-inline form-default">
                        <h6 class="tt-title">Sort replies by:</h6>
                        <ul class="tt-list-badge tt-size-lg">
                            <li><a href="#"><span class="tt-badge">Recent</span></a></li>
                            <li><a href="#"><span class="tt-color02 tt-badge">Most Liked</span></a></li>
                            <li><a href="#"><span class="tt-badge">Longest</span></a></li>
                        </ul>
                        <select class="tt-select form-control">
                            <option value="Recent">Recent</option>
                            <option value="Most Liked">Most Liked</option>
                            <option value="Longest">Longest</option>
                            <option value="Shortest">Shortest</option>
                            <option value="Accepted Answer">Accepted Answer</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <?php if ($this->viewanswers):?>
            <?php $i = 0; foreach ($this->question->answers as $answer):?>
				<?php $ansuser = Factory::getUser($answer->userid_creator); ?>
                 <?php if($answer->chosen ==1 ): ?>
                    <div class="tt-item tt-wrapper-success">
                 <?php endif; ?>
                 <?php if($answer->chosen !=1 && $answer->flagged != 1): ?>
                    <div class="tt-item">
                 <?php endif; ?>
                 <?php if($answer->flagged ==1 ): ?>
                    <div class="tt-item tt-wrapper-danger">
                 <?php endif; ?>
                     <div class="tt-single-topic">
                        <div class="tt-item-header pt-noborder">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <i class="tt-icon"><div class="circle c<?php echo sprintf("%02d", $i+1); ?>"><?php 
                                    $aname = substr($ansuser->name,0,1);
                                    $userFirstname = strtoupper($aname);
                                    echo $userFirstname; ?></div></i>
                                </div>
                                
                                <div class="tt-avatar-title">
                                   <a href="#"><?php echo $ansuser->name; ?></a>
                               
                                     <?php  
								$check = $this->getBestAnswerid($this->question->id);

								if (!isset($check)):?>
								<?php if ($this->isOwner && $answer->chosen != 1): //Display "Choose" link ?>
								<a href="<?php echo JRoute::_("index.php?option=com_questions&task=answer.choose&questionid=" . $this->question->id . "&answerid=" . $answer->id)?>"><span class="tt-color10 tt-badge"><?php echo JText::_("COM_QUESTIONS_CHOOSE")?></span></a>
								<?php endif;?>
								<?php endif;?>
										
								<?php if ($this->isOwner && $answer->chosen == 1 && $this->getBestAnswerid($this->question->id)): //Display "Unchoose" link ?>
								<a href="<?php echo JRoute::_("index.php?option=com_questions&task=answer.chooseReset&questionid=" . $this->question->id . "&answerid=" . $answer->id)?>"><span class="tt-color11 tt-badge"><?php echo JText::_("COM_QUESTIONS_UNCHOOSE")?></a>
								<?php endif;?>
								<?php if ($answer->chosen == 1): //Display "Unchoose" link ?>
								<span class="tt-color13 tt-badge"><?php echo JText::_("Best Answer"); ?></span>
								<?php endif;?>

                                </div>
                                <a href="#" class="tt-info-time">
                                    <i class="tt-icon"><svg><use xlink:href="#icon-time"></use></svg></i> <?php echo dtformat($answer->submitted); ?>
                                </a>
                            </div>
                        </div>
                        <div class="tt-item-description">
                            <?php if($answer->flagged !=1 ): ?>
                            <?php echo $answer->text; ?>
                            <?php else: 
                                echo "This post has been flagged by a moderator, received too many downvotes.";
                            ?>
                            <?php endif; ?>
                        </div>
                        <div class="tt-item-info info-bottom">
                            <?php if($answer->flagged !=1 ): ?>
                            <a href="javascript:void(0)" class="tt-icon-btn anspositive" id="anspositive-<?php echo $answer->id; ?>">
                                <i class="tt-icon" ><?php echo '<img src="components/com_questions/css/images/thumbs-up.png" alt="Thumbs Up">'; ?></i>
                                <span class="tt-text"><?php echo $answer->votes_positive; ?></span>
                            </a>
                            <?php endif; ?>
                            <a href="javascript:void(0)" class="tt-icon-btn ansnegative" id="ansnegative-<?php echo $answer->id; ?>">
                                 <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/thumbs-down.png" alt="Thumbs Down">'; ?></i>
                                <span class="tt-text"><?php echo $answer->votes_negative; ?></span>
                            </a>
                            <?php if($answer->flagged !=1 ): ?>
                            <a href="#" class="tt-icon-btn">
                                 <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/like.png" alt="Likes">'; ?></i>
                                <span class="tt-text"><?php echo $answer->likes; ?></span>
                            </a>
                            <div class="col-separator"></div>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/share.png" alt="Share">'; ?></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/flag.png" alt="Flag">'; ?></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                 <i class="tt-icon"><?php echo '<img src="components/com_questions/css/images/reply.png" alt="Reply">'; ?></i>
                            </a>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
            	 
				<?php $i++; endforeach;?>
				<?php endif;?>
         
         <hr>
        <?php if ($this->submitanswers && $this->question->closed !=1 ) :?>
			<!-- ANSWER FORM -->
            <a name="newanswer">&nbsp;</a>
            <?php echo $this->loadTemplate('form'); 
            
            ?>
        <?php endif;?> 
</main>
<?php /**********Kindly dont remove this credit. For getting any support from us this link should be intact************/ 
	CopyrightHelper::copyright();
?>