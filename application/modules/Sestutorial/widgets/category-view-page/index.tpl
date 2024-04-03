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

<div class="sestutorial_category_tutorial_listing_head">
	<img src="<?php echo $this->subject->getCategoryIconUrl(); ?>" />
  <span><?php echo $this->subject->category_name; ?></span>
</div>
<?php if(engine_count($this->resultcategories) > 0 && $this->viewType == 'subcategoryview' && empty($this->thirdlevelcategory)): ?>
  <?php if($this->viewtype == 'onlytutorialview') { ?>
    <div class="sestutorial_category_listing_wrapper sestutorial_clearfix sestutorial_bxs sestutorial_catview_list">
      <div class="sestutorial_clearfix">
        <?php foreach($this->resultcategories as $resultcategorie): //if($resultcategorie->total_tutorials_categories == 0) continue; ?>
          <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->subcat_id, 'subcat_id' => $resultcategorie->category_id,'order' => $this->tutorialcriteria)); ?>
          <div class="sestutorial_category_question_section">
            <div class="sestutorial_category_question_section_title">
              <p>
                <?php if(engine_in_array('viewall', $this->showinformation)): ?>
                  <span class="seeall_link"><a href="<?php echo $resultcategorie->getHref(); ?>"><?php echo $this->translate("View All"); ?><i class=" fa fa-angle-right"></i></a></span>
                <?php endif; ?>
                <?php if(engine_in_array('caticon', $this->showinformation)): ?><img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" /><?php endif; ?>
                <a href="<?php echo $resultcategorie->getHref(); ?>"><?php echo $this->translate($resultcategorie->category_name); ?></a>
              </p>
            </div>
            <?php if(engine_count($categoriesTutorials) > 0): ?>
              <div class="sestutorial_category_question_section_list">
                <ul>
                  <?php foreach($categoriesTutorials as $categoriesTutorial): ?>
                  <li><a href="<?php echo $categoriesTutorial->getHref(); ?>"><i class=" far fa-file-alt"></i> <span><?php echo $this->string()->truncate($this->string()->stripTags($categoriesTutorial->title), $this->tutorialtitlelimit); ?></span></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php else: ?>
              <div class="tip">
                <span>
                  <?php echo $this->translate("There is no Tutorial created in this category yet.") ?>
                </span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php } else if($this->viewtype == 'listview') { ?>
    <div class="sestutorial_clearfix sestutorial_bxs">
      <div class="sestutorial_clearfix">
        <?php foreach($this->resultcategories as $resultcategorie): //if($resultcategorie->total_tutorials_categories == 0) continue; ?>
          <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->subcat_id, 'subcat_id' => $resultcategorie->category_id,'order' => $this->tutorialcriteria)); ?>
          <div class="sestutorial_clearfix">
            <div class="sestutorial_category_question_section_title">
              <p>
                <?php if(engine_in_array('caticon', $this->showinformation)): ?><img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" /><?php endif; ?>
                <a href="<?php echo $resultcategorie->getHref(); ?>"><?php echo $this->translate($resultcategorie->category_name); ?></a>
              </p>
            </div>
            <?php if(engine_count($categoriesTutorials) > 0): ?>
              <ul class="sestutorial_question_answer_list_content">
                <?php foreach($categoriesTutorials as $tutorial): ?>
                  <div class="sestutorial_question_answer_section" >	
                    <div class="sestutorial_question_answer_content_section">
                      <div class="sestutorial_question_answer_title">
                        <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                      </div>
                      <?php if(engine_in_array('description', $this->showinformation1)): ?>
                        <div class="sestutorial_question_answer_discription">
                          <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                        </div>
                      <?php endif; ?>
                      <div class="sestutorial_question_answer_stats">
                        <ul>
                          <?php if(engine_in_array('commentcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('viewcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('likecount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('ratingcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                          <?php endif; ?>
                        </ul>
                        <?php if(engine_in_array('readmorelink', $this->showinformation1)): ?>
                          <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read Artical"); ?><i class="fa fa-angle-right"></i></a></p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <div class="tip">
                <span>
                  <?php echo $this->translate("There is no Tutorial created in this category yet.") ?>
                </span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php } else if($this->viewtype == 'gridview') { ?>
    <div class="sestutorial_clearfix sestutorial_bxs">
      <div class="sestutorial_clearfix">
        <?php foreach($this->resultcategories as $resultcategorie): //if($resultcategorie->total_tutorials_categories == 0) continue; ?>
          <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->subcat_id, 'subcat_id' => $resultcategorie->category_id,'order' => $this->tutorialcriteria)); ?>
          <div class="sestutorial_clearfix">
            <div class="sestutorial_category_question_section_title">
              <p>
                <?php if(engine_in_array('caticon', $this->showinformation)): ?><img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" /><?php endif; ?>
                <a href="<?php echo $resultcategorie->getHref(); ?>"><?php echo $this->translate($resultcategorie->category_name); ?></a>
              </p>
            </div>
            <?php if(engine_count($categoriesTutorials) > 0): ?>
              <div class="sestutorial_question_answer_grid_content sestutorial_clearfix">
                <ul class="sestutorial_clearfix">
                  <?php foreach($categoriesTutorials as $tutorial): ?>
                    <div class="sestutorial_question_answer_section">
                      <div class="sestutorial_question_answer_inner" style="height:<?php echo $this->gridblockheight ?>px;">
                        <div class="sestutorial_question_answer_title">
                          <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                        </div>
                        <div class="sestutorial_question_answer_stats">
                          <ul>
                            <?php if(engine_in_array('commentcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('viewcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('likecount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('ratingcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                            <?php endif; ?>
                          </ul>
                        </div>
                        <?php if(engine_in_array('readmorelink', $this->showinformation1) || engine_in_array('description', $this->showinformation1)): ?>
                          <div class="sestutorial_question_answer_discription">
                            <?php if(engine_in_array('description', $this->showinformation1)): ?>
                            <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                            <?php endif; ?>
                            <?php if(engine_in_array('readmorelink', $this->showinformation1)): ?>
                              <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?></a></p>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php else: ?>
              <div class="tip">
                <span>
                  <?php echo $this->translate("There is no Tutorial created in this category yet.") ?>
                </span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php } ?>
<?php else: ?>
  <?php //if(engine_count($this->resultcategories) > 0): ?>
    <?php //foreach($this->resultcategories as $resultcategorie): ?>
      <?php //if($resultcategorie->total_tutorial_categories == 0) continue; ?>
      <?php if($this->thirdlevelcategory) { ?>
        <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'subcat_id' => $this->subject->category_id, 'order' => $this->tutorialcriteria)); ?>
      <?php } else { ?>
        <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $this->subject->category_id, 'order' => $this->tutorialcriteria)); ?>
      <?php } ?>
      <?php if(engine_count($categoriesTutorials) > 0): ?>
        <div class="sestutorial_category_tutorial_listing_cont sestutorial_clearfix sestutorial_bxs">
          <?php if($this->viewtype == 'listview'): ?>
            <div class="sestutorial_question_answer_list_content sestutorial_clearfix sestutorial_bxs">
                <?php //if(engine_count($categoriesTutorials) > 0): ?>
                  <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
                    <div class="sestutorial_question_answer_section" >	
                      <div class="sestutorial_question_answer_content_section">
                        <div class="sestutorial_question_answer_title">
                          <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                        </div>
                        <?php if(engine_in_array('description', $this->showinformation1)): ?>
                          <div class="sestutorial_question_answer_discription">
                            <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                          </div>
                        <?php endif; ?>
                        <div class="sestutorial_question_answer_stats">
                          <ul>
                            <?php if(engine_in_array('commentcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('viewcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('likecount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                            <?php endif; ?>
                            <?php if(engine_in_array('ratingcount', $this->showinformation1)): ?>
                              <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                            <?php endif; ?>
                          </ul>
                          <?php if(engine_in_array('readmorelink', $this->showinformation1)): ?>
                            <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read Artical"); ?><i class="fa fa-angle-right"></i></a></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php //endif; ?>
            </div>
          <?php elseif($this->viewtype == 'onlytutorialview'): ?>
            <div class="sestutorial_question_answer_list_content sestutorial_clearfix sestutorial_bxs">
              <?php //if(engine_count($categoriesTutorials) > 0): ?>
                <div class="sestutorial_category_question_section_list">
                  <ul>
                    <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
                      <li>
                      	<a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><i class=" far fa-file-alt"></i> <span><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></span></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php //endif; ?>
            </div>
          <?php elseif($this->viewtype == 'gridview'): ?>
            <!--question answer grid view block-->
            <div class="sestutorial_question_answer_grid_content">
              <?php //$categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->subcat_id, 'subcat_id' => $resultcategorie->category_id, 'order' => $this->tutorialcriteria)); ?>
              <?php //if(engine_count($categoriesTutorials) > 0): ?>
                <?php foreach($categoriesTutorials as $tutorial): //print_r($tutorial->toarray());die; ?>
                  <div class="sestutorial_question_answer_section">
                    <div class="sestutorial_question_answer_inner" style="height:<?php echo $this->gridblockheight ?>px;">
                      <div class="sestutorial_question_answer_title">
                        <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
                      </div>
                      <div class="sestutorial_question_answer_stats">
                        <ul>
                          <?php if(engine_in_array('commentcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('viewcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('likecount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                          <?php endif; ?>
                          <?php if(engine_in_array('ratingcount', $this->showinformation1)): ?>
                            <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                      <?php if(engine_in_array('readmorelink', $this->showinformation1) || engine_in_array('description', $this->showinformation1)): ?>
                        <div class="sestutorial_question_answer_discription">
                          <?php if(engine_in_array('description', $this->showinformation1)): ?>
                          <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                          <?php endif; ?>
                          <?php if(engine_in_array('readmorelink', $this->showinformation1)): ?>
                            <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?></a></p>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php //endif; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="tip">
          <span>
            <?php echo $this->translate("There is no Tutorial created in this category yet.") ?>
          </span>
        </div>
      <?php endif; ?>
    <?php //endforeach; ?>
  <?php //else: ?>
<!--      <div class="tip">
      <span>
        <?php //echo $this->translate("There are no more category yet.") ?>
      </span>
    </div>-->
  <?php //endif; ?>
<?php endif; ?>
