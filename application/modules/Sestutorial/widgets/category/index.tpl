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
<?php $route = 'sestutorial_general'; ?>
<ul class="sestutorial_sidebar_categories sestutorialc_sidebar_widget">
  <?php foreach( $this->categories as $item ): ?>
    <li>
      <?php $subcategory = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $item->category_id)); ?>
      <?php if(counT($subcategory) > 0): ?>
        <a id="sestutorial_toggle_<?php echo $item->category_id ?>" class="cattoggel cattoggelright fa" href="javascript:void(0);" onclick="showCategory('<?php echo $item->getIdentity()  ?>')"></a>
      <?php endif; ?>
      <?php 
        if($item->cat_icon) { 
          $cat_icon = $this->storage->get($item->cat_icon, '');
          if($cat_icon) {
            $cat_icon = $cat_icon->getPhotoUrl();
          } else {
            $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
          }
        } else {
          $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
        }
      ?>
      <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->getIdentity()) ; ?>" <?php if($this->image == 0 && $item->cat_icon != '' && !is_null($item->cat_icon)){ ?> style="background-image:url(<?php echo $cat_icon; ?>);"<?php } ?>><?php echo $this->translate($item->category_name); ?></a>

      <ul id="subcategory_<?php echo $item->getIdentity() ?>" style="display:none;">          
        <?php foreach( $subcategory as $subCat ): ?>
          <li>
            <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
            <?php if(counT($subsubcategory) > 0): ?>
              <a id="sestutorial_subcat_toggle_<?php echo $subCat->category_id ?>" class="cattoggel cattoggelright fa" href="javascript:void(0);" onclick="showCategory('<?php echo $subCat->getIdentity()  ?>')"></a>
            <?php endif; ?> 
            
            <?php 
              if($subCat->cat_icon) { 
                $cat_icon = $this->storage->get($subCat->cat_icon, '');
                if($cat_icon) {
                  $cat_icon = $cat_icon->getPhotoUrl();
                } else {
                  $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
                }
              } else {
                $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
              }
            ?>
            
            <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>" <?php if($this->image == 0 && $subCat->cat_icon != '' && !is_null($subCat->cat_icon)){ ?> style="background-image:url(<?php echo $cat_icon; ?>);"<?php } ?>><?php echo $this->translate($subCat->category_name); ?></a>   
              
              <ul id="subsubcategory_<?php echo $subCat->getIdentity() ?>" style="display:none;">
                <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
                <?php foreach( $subsubcategory as $subSubCat ): ?>
                  <li>
                  
                    <?php 
                      if($subSubCat->cat_icon) { 
                        $cat_icon = $this->storage->get($subSubCat->cat_icon, '');
                        if($cat_icon) {
                          $cat_icon = $cat_icon->getPhotoUrl();
                        } else {
                          $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
                        }
                      } else {
                        $cat_icon = 'application/modules/Sestutorial/externals/images/category.png';
                      }
                    ?>
                    <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) .'&subsubcat_id='.urlencode($subSubCat->category_id) ; ?>" <?php if($this->image == 0 && $subSubCat->cat_icon != '' && !is_null($subSubCat->cat_icon)){ ?> style="background-image:url(<?php echo $cat_icon; ?>);"<?php } ?>><?php echo $this->translate($subSubCat->category_name); ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>               
            </li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>
<script>
function showCategory(id) {
  if(document.getElementById('subcategory_' + id)) {
    if (document.getElementById('subcategory_' + id).style.display == 'block') {
      scriptJquery('#sestutorial_toggle_' + id).removeClass('cattoggel cattoggeldown');
      scriptJquery('#sestutorial_toggle_' + id).addClass('cattoggel cattoggelright');
      document.getElementById('subcategory_' + id).style.display = 'none';
    } else {
      scriptJquery('#sestutorial_toggle_' + id).removeClass('cattoggel cattoggelright');
      scriptJquery('#sestutorial_toggle_' + id).addClass('cattoggel cattoggeldown');
      document.getElementById('subcategory_' + id).style.display = 'block';
    }
  }
  
  if(document.getElementById('subsubcategory_' + id)) {
    if (document.getElementById('subsubcategory_' + id).style.display == 'block') {
      scriptJquery('#sestutorial_subcat_toggle_' + id).removeClass('cattoggel cattoggeldown');
      scriptJquery('#sestutorial_subcat_toggle_' + id).addClass('cattoggel cattoggelright');      
      document.getElementById('subsubcategory_' + id).style.display = 'none';
    } else {
      scriptJquery('#sestutorial_subcat_toggle_' + id).removeClass('cattoggel cattoggelright');
      scriptJquery('#sestutorial_subcat_toggle_' + id).addClass('cattoggel cattoggeldown');
      document.getElementById('subsubcategory_' + id).style.display = 'block';
    }
  }
}
</script>
