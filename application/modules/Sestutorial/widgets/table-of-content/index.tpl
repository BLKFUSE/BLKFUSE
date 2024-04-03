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

<?php $widgetParams = $this->widgetParams; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $moduleName = $request->getModuleName();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName(); ?>
<?php $tutorial_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tutorial_id', null); ?>
<script>

 function tutorialhideShow(id, param) {
  if(param == 'cat') {
    if(document.getElementById('main_category_'+id).style.display == 'block' || document.getElementById('main_category_'+id).style.display == '') {
      document.getElementById('main_category_'+id).style.display = 'none';
      document.getElementById('maincategory_'+id).innerHTML = '<i class="far fa-plus-square"></i>';
    } else {
      document.getElementById('main_category_'+id).style.display = 'block';
      document.getElementById('maincategory_'+id).innerHTML = '<i class="far fa-minus-square"></i>';
    }
  } else if(param == 'subcat') {
    if(document.getElementById('sub_category_'+id).style.display == 'block' || document.getElementById('sub_category_'+id).style.display == '') {
      document.getElementById('sub_category_'+id).style.display = 'none';
      document.getElementById('subf_category_'+id).style.display = 'none';
      document.getElementById('subcategory_'+id).innerHTML = '<i class="fa fa-caret-right"></i>';
    } else {
      document.getElementById('sub_category_'+id).style.display = 'block';
      document.getElementById('subf_category_'+id).style.display = 'block';
      document.getElementById('subcategory_'+id).innerHTML = '<i class="fa fa-caret-down"></i>';
    }
  } else if(param == 'subsubcat') {
    if(document.getElementById('subsubf_category_'+id).style.display == 'block' || document.getElementById('subsubf_category_'+id).style.display == '') {
      document.getElementById('subsubf_category_'+id).style.display = 'none';
      document.getElementById('subsubcategory_'+id).innerHTML = '<i class="fa fa-caret-right"></i>';
    } else {
      document.getElementById('subsubf_category_'+id).style.display = 'block';
      document.getElementById('subsubcategory_'+id).innerHTML = '<i class="fa fa-caret-down"></i>';
    }
  }
 }
</script>
<div class="sestutorial_clearfix sestutorial_bxs sestutorial_tags_cloud_tutorial sestutorial_content_table">
  <?php $i = 1; ?>
  <?php foreach($this->resultcategories as $resultcategorie): ?>
      <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getCategoryTutorialSelect(array('onlyTutorial' => 1, 'category_id' => $resultcategorie->category_id)); //if(engine_count($categoriesTutorials) == 0) continue; 
      
      $tutorialsCount = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->countTutorials(array('category_id' => $resultcategorie->category_id, 'fetchAll' => 1));
      
      ?>
      <div class="maincat">
        <?php if(engine_count($tutorialsCount) > 0) { ?>
      	<a <?php if($this->widgetParams['viewType'] == 'expanded' && !$this->widgetParams['showicons']) { ?> style="display:none;" <?php } ?> class="sestutorial_hideshow_btn" id="maincategory_<?php echo $resultcategorie->getIdentity(); ?>" href="javascript:void(0);" onclick="tutorialhideShow('<?php echo $resultcategorie->getIdentity() ?>', 'cat');"><i class="<?php if($this->widgetParams['viewType'] == 'collapsed') { ?> far fa-plus-square <?php } else { ?> far fa-minus-square <?php } ?>"></i></a>
      	<?php } ?>
      	<span><?php echo $this->translate($resultcategorie->category_name); ?></span>
      </div>  
      <div <?php if(engine_count($tutorialsCount) > 0) { ?><?php if($this->widgetParams['viewType'] == 'collapsed') { ?> style="display:none" <?php } } else { ?> style="display:none" <?php } ?>  class="maincat_cont" id="main_category_<?php echo $resultcategorie->getIdentity(); ?>">
        
        <?php if(engine_count($categoriesTutorials) > 0) { ?>
          <?php $j = 1; ?>
          <?php foreach($categoriesTutorials as $tutorial) { ?>
          	<div class="table_cont 1st-lavel <?php if($controllerName == 'index' && $actionName == 'view' && $tutorial_id == $tutorial->getIdentity()) { ?> _tutorial_active <?php } ?>">
            	<span>
            		<span class="sestutorial_no"><?php echo $i.'.'.$j; ?></span><span class="sestutorial_label"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $tutorial->getTitle(); ?></a></span>
              </span>
          	</div>
          <?php $j++; } ?>
        <?php } ?>
        <?php //subcategory work ?>
        <?php $subCategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getModuleSubcategory(array('category_id' => $resultcategorie->getIdentity(), 'column_name' => '*')); ?>
        <?php $g = !empty($j) ? $j : 1; ?>
        <?php foreach($subCategories as $subCategory) { ?>
          <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getCategoryTutorialSelect(array('onlyTutorial' => 2, 'category_id' => $resultcategorie->category_id, 'subcat_id' => $subCategory->getIdentity())); ?>
          <?php if(engine_count($categoriesTutorials) > 0) { ?>
          	<div class="table_cont first-lavel">
            	<a <?php if($this->widgetParams['viewType'] == 'expanded' && !$this->widgetParams['showicons']) { ?> style="display:none;" <?php } ?>  class="sestutorial_hideshow_btn" id="subcategory_<?php echo $subCategory->getIdentity(); ?>" href="javascript:void(0);" onclick="tutorialhideShow('<?php echo $subCategory->getIdentity() ?>', 'subcat');"><i class="fa fa-caret-down"></i></a>
              <span>
            		<span class="sestutorial_no"><?php echo $i . '.'.$g; ?></span><span class="sestutorial_label"><?php echo $this->translate($subCategory->category_name); ?></span>
              </span>
            	</div>
            <?php $t = 1; ?>
            <div id="subf_category_<?php echo $subCategory->getIdentity(); ?>">
              <?php foreach($categoriesTutorials as $tutorial) { ?>
              	<div class="table_cont second-lavel <?php if($controllerName == 'index' && $actionName == 'view' && $tutorial_id == $tutorial->getIdentity()) { ?> _tutorial_active <?php } ?>">
                	<span>
                		<span class="sestutorial_no"><?php echo $i . '.'.$g.'.'.$t; ?></span><span class="sestutorial_label"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $tutorial->getTitle(); ?></a></span>
                  </span>
              	</div>
              <?php $t++; } ?>
            </div>
          <?php } ?>
          <div id="sub_category_<?php echo $subCategory->getIdentity(); ?>">
            <?php //subsubcategory work ?>
            <?php $subsubCategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getModuleSubsubcategory(array('category_id' => $subCategory->getIdentity(), 'column_name' => '*')); ?>
            <?php $p = !empty($t) ? $t : 1; ?>
            <?php foreach($subsubCategories as $subsubCategory) { ?>
              <?php $categoriesTutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getCategoryTutorialSelect(array('onlyTutorial' => 3, 'category_id' => $resultcategorie->category_id, 'subcat_id' => $subCategory->getIdentity(), 'subsubcat_id' => $subsubCategory->getIdentity())); ?>
              <?php if(engine_count($categoriesTutorials) > 0) { ?>
              	<div class="table_cont second-lavel">
                <a <?php if($this->widgetParams['viewType'] == 'expanded' && !$this->widgetParams['showicons']) { ?> style="display:none;" <?php } ?>  class="sestutorial_hideshow_btn" id="subsubcategory_<?php echo $subsubCategory->getIdentity(); ?>" href="javascript:void(0);" onclick="tutorialhideShow('<?php echo $subsubCategory->getIdentity() ?>', 'subsubcat');"><i class="fa fa-caret-down"></i></a>
                	<span><span class="sestutorial_no"><?php echo $i . '.'.$g.'.'.$p; ?></span><span class="sestutorial_label"><?php echo $this->translate($subsubCategory->category_name); ?></span>
                	</span>
                </div>
                <?php $h = 1; ?>
                <div id="subsubf_category_<?php echo $subsubCategory->getIdentity(); ?>">
                  <?php foreach($categoriesTutorials as $tutorial) { ?>
                  	<div class="table_cont third-lavel <?php if($controllerName == 'index' && $actionName == 'view' && $tutorial_id == $tutorial->getIdentity()) { ?> _tutorial_active <?php } ?>">
                    	<span><span class="sestutorial_no"><?php echo $i . '.'.$g.'.'.$p.'.'.$h; ?></span><span class="sestutorial_label"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $tutorial->getTitle(); ?></a></span></span>
                    </div>
                  <?php $h++; } ?>
                </div>
              <?php } ?>
            <?php $p++; } ?>
          </div>
        <?php $g++; } ?>
      </div>
    <?php $i++; ?>
  <?php endforeach; ?>
</div>
