<div style="clear:both"></div><br />
//$settings = $model->getSettings ();
//$doc->addStyleSheet('templates/' . $this->template . '/css/style.css');
//$doc->addScript('/templates/' . $this->template . '/js/main.js', 'text/javascript');

//JLoader::register('NewsfeedsHelperRoute', JPATH_ROOT . '/components/com_questions/helpers/route.php');
/* //Usage*/ ?>
<?php /*
<td>
							<a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->name)); ?>', '<?php echo $this->escape($item->catid); ?>', null, '<?php echo $this->escape(NewsfeedsHelperRoute::getNewsfeedRoute($item->id, $item->catid, $item->language)); ?>', '<?php echo $this->escape($lang); ?>', null);">
							<?php echo $this->escape($item->name); ?></a>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
							</div>
						</td> */
?>
<?php
/*var_dump('<pre>');
var_dump($category); var_dump('</pre>'); exit;*/
$params = JComponentHelper::getParams('com_questions');
				$catarray = $this->categories; 
				$count=count($catarray);
				?>
			<div class="div-table">
				<?php
				foreach($this->categories as $category) {
				if(isset($category->title) && $category->level==1){
					?>
						
							<div class="div-tr">
												<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $model->getAlias($category->id)); ?>">
															<?php echo strtoupper($category->title)."(".$model->countCat($category->id).")"; ?> 
												</a>
												<div class="div-feed">
										<a href="<?php echo JURI::base()."index.php?option=com_questions&view=questions&catid=".$category->id."&format=feed"; ?>">
											<img src="components/com_questions/media/images/feed16.gif" />
										</a>
									</div>
															<div class="catdesc"><?php echo $category->description; ?></div>
								
									
							
							<?php
							if ($params->get('show_subcategory_list', 1)) { 
							if(isset($category->lft) && isset($category->rgt)){
							$nestedcat = $model->nested($category->lft,$category->rgt,2);
							foreach($nestedcat as $nc){
							?>
							<div class="div-trsub">
								<a href="<?php echo JRoute::_("index.php?option=com_questions&view=questions&catid=" . $model::getAlias($nc->id)); ?>">
															<?php echo $nc->title."(".$model->countCat($nc->id).")"; ?>  
								</a>
								<div class="div-feed">
									<a href="<?php 
									echo JURI::base()."index.php?option=com_questions&view=questions&catid=".$nc->id."&format=feed"; ?>">
										<img src="components/com_questions/media/images/feed16.gif" />
									</a>
								</div>
								<div class="catdesc"><?php echo $nc->description; ?></div>
							</div>
							
						<?php } ?>
						<?php
						}
						}
						?>
						</div>
					<?php
					}
				} 
				?>
			</div>
            
            
            
            C:\xampp\htdocs\joomla\components\com_questions\views\categories\tmpl\default.php:67:
array (size=3)
  0 => 
    object(stdClass)[378]
      public 'id' => string '23' (length=2)
      public 'parent_id' => string '1' (length=1)
      public 'lft' => string '27' (length=2)
      public 'rgt' => string '28' (length=2)
      public 'level' => string '1' (length=1)
      public 'title' => string 'joomla' (length=6)
      public 'published' => string '1' (length=1)
      public 'created_user_id' => string '289' (length=3)
      public 'created_time' => string '2020-07-17 17:59:46' (length=19)
      public 'description' => string '' (length=0)
      public 'tags' => null
  1 => 
    object(stdClass)[379]
      public 'id' => string '22' (length=2)
      public 'parent_id' => string '1' (length=1)
      public 'lft' => string '25' (length=2)
      public 'rgt' => string '26' (length=2)
      public 'level' => string '1' (length=1)
      public 'title' => string 'sample' (length=6)
      public 'published' => string '1' (length=1)
      public 'created_user_id' => string '289' (length=3)
      public 'created_time' => string '2020-07-17 17:59:40' (length=19)
      public 'description' => string '' (length=0)
      public 'tags' => null
  2 => 
    object(stdClass)[380]
      public 'id' => string '21' (length=2)
      public 'parent_id' => string '1' (length=1)
      public 'lft' => string '23' (length=2)
      public 'rgt' => string '24' (length=2)
      public 'level' => string '1' (length=1)
      public 'title' => string 'test' (length=4)
      public 'published' => string '1' (length=1)
      public 'created_user_id' => string '289' (length=3)
      public 'created_time' => string '2020-07-20 00:59:34' (length=19)
      public 'description' => string '' (length=0)
      public 'tags' => string 'un1,questionstag,question1' (length=26)
      
      
      
      
      
      
      
      
      
      
      
<div class="col-md-6 col-lg-4">
    <div class="tt-item">
        <div class="tt-item-header">
            <ul class="tt-list-badge">
                <li><a href="#"><span class="tt-color02 tt-badge">video</span></a></li>
            </ul>
            <h6 class="tt-title"><a href="page-categories-single.html">Threads - 368</a></h6>
        </div>
        <div class="tt-item-layout">
           <div class="tt-innerwrapper">
               Lets discuss about whats happening around the world politics.
           </div>
           <div class="tt-innerwrapper">
                <h6 class="tt-title">Similar TAGS</h6>
                <ul class="tt-list-badge">
                    <li><a href="#"><span class="tt-badge">movies</span></a></li>
                    <li><a href="#"><span class="tt-badge">new movies</span></a></li>
                    <li><a href="#"><span class="tt-badge">marvel movies</span></a></li>
                    <li><a href="#"><span class="tt-badge">climate change</span></a></li>
                    <li><a href="#"><span class="tt-badge">netflix</span></a></li>
                    <li><a href="#"><span class="tt-badge">prime</span></a></li>
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




















<div id="js-popup-settings" class="tt-popup-settings">
	<div class="tt-btn-col-close">
		<a href="#">
			<span class="tt-icon-title">
				<svg>
					<use xlink:href="#icon-settings_fill"></use>
				</svg>
			</span>
			<span class="tt-icon-text">
				Settings
			</span>
			<span class="tt-icon-close">
				<svg>
					<use xlink:href="#icon-cancel"></use>
				</svg>
			</span>
		</a>
	</div>
	<form class="form-default">
		<div class="tt-form-upload">
			<div class="row no-gutter">
				<div class="col-auto">
					<div class="tt-avatar">
		                <svg>
		                  <use xlink:href="#icon-ava-d"></use>
		                </svg>
		            </div>
				</div>
				<div class="col-auto ml-auto">
					<a href="#" class="btn btn-primary">Upload Picture</a>
				</div>
			</div>
		</div>
		<div class="form-group">
		    <label for="settingsUserName">Username</label>
		   <input type="text" name="name" class="form-control" id="settingsUserName" placeholder="azyrusmax">
		</div>
		<div class="form-group">
		    <label for="settingsUserEmail">Email</label>
		   <input type="text" name="name" class="form-control" id="settingsUserEmail" placeholder="Sample@sample.com">
		</div>
		<div class="form-group">
		    <label for="settingsUserPassword">Password</label>
		   <input type="password" name="name" class="form-control" id="settingsUserPassword" placeholder="************">
		</div>
		<div class="form-group">
		    <label for="settingsUserLocation">Location</label>
		   <input type="text" name="name" class="form-control" id="settingsUserLocation" placeholder="Slovakia">
		</div>
		<div class="form-group">
		    <label for="settingsUserWebsite">Website</label>
		   <input type="text" name="name" class="form-control" id="settingsUserWebsite" placeholder="Sample.com">
		</div>
		<div class="form-group">
		    <label for="settingsUserAbout">About</label>
		    <textarea name="" placeholder="Few words about you" class="form-control" id="settingsUserAbout"></textarea>
		</div>
		<div class="form-group">
			<label for="settingsUserAbout">Notify me via Email</label>
			<div class="checkbox-group">
		        <input type="checkbox" id="settingsCheckBox01" name="checkbox">
		        <label for="settingsCheckBox01">
		            <span class="check"></span>
		            <span class="box"></span>
		            <span class="tt-text">When someone replies to my thread</span>
		        </label>
		    </div>
		    <div class="checkbox-group">
		        <input type="checkbox" id="settingsCheckBox02" name="checkbox">
		        <label for="settingsCheckBox02">
		            <span class="check"></span>
		            <span class="box"></span>
		            <span class="tt-text">When someone likes my thread or reply</span>
		        </label>
		    </div>
		    <div class="checkbox-group">
		        <input type="checkbox" id="settingsCheckBox03" name="checkbox">
		        <label for="settingsCheckBox03">
		            <span class="check"></span>
		            <span class="box"></span>
		            <span class="tt-text">When someone mentions me</span>
		        </label>
		    </div>
		</div>
		<div class="form-group">
			<a href="#" class="btn btn-secondary">Save</a>
		</div>
		</form>
</div>
<a href="page-create-topic.html" class="tt-btn-create-topic">
    <span class="tt-icon">
        <svg>
          <use xlink:href="#icon-create_new"></use>
        </svg>
    </span>
</a>

<div class="modal fade" id="modalAdvancedSearch" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="tt-modal-advancedSearch">
				<div class="tt-modal-title">
					<i class="pt-icon">
						<svg>
							<use xlink:href="#icon-advanced_search"></use>
						</svg>
					</i>
					Advanced Search
					<a class="pt-close-modal" href="#" data-dismiss="modal">
						<svg class="icon">
							<use xlink:href="#icon-cancel"></use>
						</svg>
					</a>
				</div>
				<form class="form-default">
					<div class="form-group">
						<i class="pt-customInputIcon">
                           <svg class="tt-icon">
                              <use xlink:href="#icon-search"></use>
                            </svg>
                        </i>
						<input type="text" name="name" class="form-control pt-customInputSearch" id="searchForum" placeholder="Search all forums">
					</div>
					<div class="form-group">
						<label for="searchName">Posted by</label>
						<input type="text" name="name" class="form-control" id="searchName" placeholder="Username">
					</div>
					<div class="form-group">
						<label for="searchCategory">In Category</label>
						<input type="text" name="name" class="form-control" id="searchCategory" placeholder="Add Category">
					</div>
					<div class="checkbox-group">
				        <input type="checkbox" id="searcCheckBox01" name="checkbox">
				        <label for="searcCheckBox01">
				            <span class="check"></span>
				            <span class="box"></span>
				            <span class="tt-text">Include all tags</span>
				        </label>
				    </div>
					<div class="form-group">
						<label>Only return topics/posts that...</label>
						<div class="checkbox-group">
					        <input type="checkbox" id="searcCheckBox02" name="checkbox">
					        <label for="searcCheckBox02">
					            <span class="check"></span>
					            <span class="box"></span>
					            <span class="tt-text">I liked</span>
					        </label>
					    </div>
					    <div class="checkbox-group">
					        <input type="checkbox" id="searcCheckBox03" name="checkbox">
					        <label for="searcCheckBox03">
					            <span class="check"></span>
					            <span class="box"></span>
					            <span class="tt-text">are in my messages</span>
					        </label>
					    </div>
					    <div class="checkbox-group">
					        <input type="checkbox" id="searcCheckBox04" name="checkbox">
					        <label for="searcCheckBox04">
					            <span class="check"></span>
					            <span class="box"></span>
					            <span class="tt-text">I’ve read</span>
					        </label>
					    </div>
					</div>
					<div class="form-group">
						<select class="form-control" id="searchTop">
							<option>any</option>
							<option>value 01</option>
							<option>value 02</option>
							<option>value 03</option>
						</select>
					</div>
					<div class="form-group">
						<label for="searchaTopics">Where topics</label>
						<select class="form-control" id="searchaTopics">
							<option>any</option>
							<option>value 01</option>
							<option>value 02</option>
							<option>value 03</option>
						</select>
					</div>
					<div class="form-group">
						<label for="searchAdvTime">Posted</label>
						<div class="row">
							<div class="col-6">
								<select class="form-control">
									<option>any</option>
									<option>value 01</option>
									<option>value 02</option>
									<option>value 03</option>
								</select>
							</div>
							<div class="col-6">
								<input type="text" name="name" class="form-control" id="searchAdvTime" placeholder="dd-mm-yyyy">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="minPostCount">Minimum Post Count</label>
						<select class="form-control" id="minPostCount">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option selected>10</option>
						</select>
					</div>
					<div class="form-group">
						<a href="#" class="btn btn-secondary btn-block">Search</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>