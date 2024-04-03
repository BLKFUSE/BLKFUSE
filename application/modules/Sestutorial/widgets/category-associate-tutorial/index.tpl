<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>

<?php if($this->viewtype == 'listview'): ?>
  <div class="sestutorial_category_tutorial_listing sestutorial_clearfix sestutorial_bxs">
    <?php foreach($this->resultcategories as $resultcategorie): ?>
      <?php if($resultcategorie->total_tutorial_categories == 0) continue; ?>
      <div class="sestutorial_category_tutorial_listing_head">

        <img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" />
        <a href="<?php echo $resultcategorie->getHref(); ?>" class="sestutorial_linkinherit"><?php echo $this->translate($resultcategorie->category_name); ?></a>
        
        <?php if($this->showviewalllink): ?>
          <?php $totalCountTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->countTutorials(array('fetchAll' => 1, 'category_id' => $resultcategorie->category_id)); ?> 
          <?php if(engine_count($totalCountTutorials) > 0): ?>
            <span class="seeall_link"><a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($resultcategorie->category_id) ; ?>"><?php echo $this->translate("See All Tutorials");?> <i class=" fa fa-angle-right"></i></a>
          <?php endif; ?></span>
        <?php endif; ?>
      </div>
      <div class="sestutorial_category_tutorial_listing_cont sestutorial_clearfix">
        <div class="sestutorial_question_answer_list_content">
          <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->category_id, 'order' => $this->tutorialcriteria)); ?>
          <?php if(engine_count($categoriesTutorials) > 0): ?>
            <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
              <div class="sestutorial_question_answer_section" >	
                <div class="sestutorial_question_answer_content_section">
                  <div class="sestutorial_question_answer_title">
                    <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                  </div>
                  <?php if(is_array($this->showinformation) && engine_in_array('description', $this->showinformation)): ?>
                    <div class="sestutorial_question_answer_discription">
                      <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                     </div>
                   <?php endif; ?>
                   <div class="sestutorial_question_answer_stats">
                    <ul>
                      <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
                        <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                      <?php endif; ?>
                      <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
                        <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                      <?php endif; ?>
                      <?php if(engine_in_array('likecount', $this->showinformation)): ?>
                        <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                      <?php endif; ?>
                      <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
                        <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                      <?php endif; ?>
                    </ul>
                    <?php if(is_array($this->showinformation) && engine_in_array('readmorelink', $this->showinformation)): ?>
                      <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?><i class="fa fa-angle-right"></i></a></p>
                    <?php endif; ?>
                   </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>  
  </div>
<?php elseif($this->viewtype == 'onlytutorialview'): ?>
  <div class="sestutorial_category_listing_wrapper sestutorial_clearfix sestutorial_bxs">
  	<div class="sestutorial_clearfix">
      <?php foreach($this->resultcategories as $resultcategorie): ?>
        <?php if($resultcategorie->total_tutorial_categories == 0) continue; ?>
        <div class="sestutorial_category_question_section">	
          <div class="sestutorial_category_question_section_title">
          	<p>
          	
              <?php if($this->showviewalllink): ?>
                <?php $totalCountTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->countTutorials(array('fetchAll' => 1, 'category_id' => $resultcategorie->category_id)); ?> 
                <?php if(engine_count($totalCountTutorials) > 0): ?>
                  <span class="seeall_link"><a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($resultcategorie->category_id) ; ?>"><?php echo $this->translate("See All Tutorials");?> <i class=" fa fa-angle-right"></i></a>
                <?php endif; ?></span>
              <?php endif; ?>
              
              <img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" />
              <a href="<?php echo $resultcategorie->getHref(); ?>" class="sestutorial_linkinherit"><?php echo $this->translate($resultcategorie->category_name); ?></a>
            </p>
          </div>
          <div class="sestutorial_category_question_section_list">
          	<ul>
              <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->category_id, 'order' => $this->tutorialcriteria)); ?>
              <?php if(engine_count($categoriesTutorials) > 0): ?>
                <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
                  <li>	
                  	<a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><i class=" far fa-file-alt"></i><span><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></span></a>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
      	</div>    
      <?php endforeach; ?>
    </div>  
  </div>
<?php elseif($this->viewtype == 'gridview'): ?>
  <div class="sestutorial_category_tutorial_listing sestutorial_clearfix sestutorial_bxs">
    <?php foreach($this->resultcategories as $resultcategorie): ?>
      <?php if($resultcategorie->total_tutorial_categories == 0) continue; ?>
      <div class="sestutorial_category_tutorial_listing_head">
        <img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" />
        <a href="<?php echo $resultcategorie->getHref(); ?>" class="sestutorial_linkinherit"><?php echo $this->translate($resultcategorie->category_name); ?></a>
        <?php if($this->showviewalllink): ?>
          <?php $totalCountTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->countTutorials(array('fetchAll' => 1, 'category_id' => $resultcategorie->category_id)); ?> 
          <?php if(engine_count($totalCountTutorials) > 0): ?>
            <span class="seeall_link"><a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($resultcategorie->category_id) ; ?>"><?php echo $this->translate("See All Tutorials");?> <i class=" fa fa-angle-right"></i></a>
          <?php endif; ?></span>
        <?php endif; ?>
      </div>
      <div class="sestutorial_category_tutorial_listing_cont sestutorial_clearfix">
        <div class="sestutorial_question_answer_grid_content">
          <div class="row">
            <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->category_id, 'order' => $this->tutorialcriteria)); ?>
            <?php if(engine_count($categoriesTutorials) > 0): ?>
              <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
                <div class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-12 col-12">
                  <div class="sestutorial_question_answer_section">
                    <?php if(is_array($this->showinformation) && engine_in_array('photo', $this->showinformation)): ?>
                      <div class="sestutorial_question_answer_img">
                        <a href="<?php echo $tutorial->getHref(); ?>"><img src="<?php echo $tutorial->getPhotoUrl(); ?>" /></a>
                      </div>
                    <?php endif; ?>
                    <div class="sestutorial_question_answer_inner">
                      <div class="sestutorial_question_answer_title">
                        <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                      </div>
                      <div class="sestutorial_question_answer_stats">
                        <ul>
                          <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                          <?php endif; ?>
                          <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
                            <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('likecount', $this->showinformation)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                          <?php endif; ?>
                          <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                      <?php if(is_array($this->showinformation) && (engine_in_array('readmorelink', $this->showinformation) || engine_in_array('description', $this->showinformation))): ?>
                        <div class="sestutorial_question_answer_discription">
                          <?php if(is_array($this->showinformation) && engine_in_array('description', $this->showinformation)): ?>
                          <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                          <?php endif; ?>
                          <?php if(is_array($this->showinformation) && engine_in_array('readmorelink', $this->showinformation)): ?>
                            <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?></a></p>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>  
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>  
  </div>
<?php endif; ?>
