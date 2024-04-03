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

<div class="sestutorial_category_listing_wrapper sestutorial_clearfix sestutorial_bxs">
  <div class="sestutorial_clearfix">
    <?php foreach($this->resultcategories as $resultcategorie): if($resultcategorie->total_tutorial_categories == 0) continue; ?>
      <div class="sestutorial_category_question_section">
        <div class="sestutorial_category_question_section_title">
          <p>
            <?php if(engine_in_array('viewall', $this->showinformation)): ?>
              <?php $totalCountTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->countTutorials(array('fetchAll' => 1, 'category_id' => $resultcategorie->category_id)); ?> 
              <?php if(engine_count($totalCountTutorials) > 0): ?>
                <span class="seeall_link"><a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($resultcategorie->category_id) ; ?>"><?php echo $this->translate("View all %s", engine_count($totalCountTutorials));?> <i class=" fa fa-angle-right"></i></a>
              <?php endif; ?></span>
            <?php endif; ?>
            <?php if(engine_in_array('caticon', $this->showinformation)): ?>
              <img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" />
            <?php endif; ?>
            <a href="<?php echo $resultcategorie->getHref(); ?>"><?php echo $this->translate($resultcategorie->category_name); ?></a>
          </p>
        </div>
        <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $this->limitdatatutorial, 'fetchAll' => 1, 'category_id' => $resultcategorie->category_id, 'order' => $this->tutorialcriteria)); ?>
        <?php if(engine_count($categoriesTutorials) > 0): ?>
          <div class="sestutorial_category_question_section_list">
            <ul>
              <?php foreach($categoriesTutorials as $categoriesTutorial): ?>
                <?php $tutorialpermission = Engine_Api::_()->sestutorial()->tutorialpermission($categoriesTutorial->tutorial_id); ?>
                <?php if(empty($tutorialpermission)) continue; ?>
                <li>
                  <a href="<?php echo $categoriesTutorial->getHref(); ?>" title="<?php echo $categoriesTutorial->title; ?>">
                    <?php if($this->showtutorialicon) { ?>
                      <i class=" far fa-file-alt"></i>
                    <?php } ?>
                    <span>
                      <?php echo $this->string()->truncate($this->string()->stripTags($categoriesTutorial->title), $this->tutorialtitlelimit); ?>
                    </span>
                  </a>
                </li>
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
