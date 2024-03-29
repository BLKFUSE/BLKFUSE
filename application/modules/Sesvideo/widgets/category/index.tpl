<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php if($this->contentType == 'video'): ?>
  <?php $route = 'sesvideo_general'; ?>
<?php else: ?>
  <?php $route = 'sesvideo_chanel'; ?>
<?php endif; ?>

<?php if($this->showType == 'tagcloud'): ?>
  
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.tagcanvas.min.js'); ?>
  
  <div class="sesbasic_cloud_widget">
    <div id="myCanvasContainer_<?php echo $this->identity ?>" style="width:100%;height:<?php echo $this->height; ?>px">
      <canvas style="width:100%;height:100%;" id="myCanvas_<?php echo $this->identity ?>">
        <ul>
          <?php foreach($this->categories as $value): ?>
            <li><a title="<?php echo $value['category_name'] ?>" href="<?php echo $this->url(array('action' => 'browse'),$route,true).'?category_id='.$value['category_id']; ?>"><?php if(($this->image == 0 || $this->image == 2 ) && $value['cat_icon'] != '' && !is_null($value['cat_icon'])): ?><img src="<?php echo  $this->storage->get($value->cat_icon, '')->getPhotoUrl() ?>" alt="<?php echo $value['category_name'] ?>" style=" width:20px; height:20px;" /><?php endif; ?><?php echo $this->translate($value['category_name']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </canvas>
    </div>
  </div>

  <script type="text/javascript">
    scriptJquery(document).ready(function() {
      if (!scriptJquery('#myCanvas_<?php echo $this->identity ?>').tagcanvas({
        textFont: 'Impact,"Arial Black",sans-serif',
        textColour: "<?php echo $this->color;  ?>",
        textHeight: "<?php echo $this->textHeight;  ?>",
        maxSpeed : 0.03,
        depth : 0.75,
        shape : 'sphere',
        shuffleTags : true,
        reverse : false,
        initial :  [0.1,-0.0],
        minSpeed:.1
      })) {
        // TagCanvas failed to load
          scriptJquery('#myCanvasContainer_<?php echo $this->identity ?>').hide();
      }
    });
  </script>
<?php elseif($this->showType = 'simple'): ?>
  <ul class="sesbasic_sidebar_categories">
    <?php foreach( $this->categories as $item ): ?>
      <li>
        <?php $subcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $item->category_id)); ?>
        <?php if(counT($subcategory) > 0): ?>
          <a id="sesvideo_toggle_<?php echo $item->category_id ?>" class="cattoggel cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $item->getIdentity()  ?>')"></a>
        <?php endif; ?>
        <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->getIdentity()) ; ?>" <?php if($this->image == 0 && $item->cat_icon != '' && !is_null($item->cat_icon)){ ?> style="background-image:url(<?php echo $this->storage->get($item->cat_icon, '')->getPhotoUrl(); ?>);"<?php } ?>><?php echo $this->translate($item->category_name); ?></a>

        <ul id="subcategory_<?php echo $item->getIdentity() ?>" style="display:none;">          
          <?php foreach( $subcategory as $subCat ): ?>
            <li>
              <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
              <?php if(counT($subsubcategory) > 0): ?>
                <a id="sesvideo_subcat_toggle_<?php echo $subCat->category_id ?>" class="cattoggel cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $subCat->getIdentity()  ?>')"></a>
              <?php endif; ?> 
              <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>" <?php if($this->image == 0 && $subCat->cat_icon != '' && !is_null($subCat->cat_icon)){ ?> style="background-image:url(<?php echo $this->storage->get($subCat->cat_icon, '')->getPhotoUrl(); ?>);"<?php } ?>><?php echo $this->translate($subCat->category_name); ?></a>   
                
                <ul id="subsubcategory_<?php echo $subCat->getIdentity() ?>" style="display:none;">
                  <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
                  <?php foreach( $subsubcategory as $subSubCat ): ?>
                    <li>                      
                      <a class="catlabel <?php echo $this->image == 0 ? '' : 'noicon' ?>" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) .'&subsubcat_id='.urlencode($subSubCat->category_id) ; ?>" <?php if($this->image == 0 && $subSubCat->cat_icon != '' && !is_null($subSubCat->cat_icon)){ ?> style="background-image:url(<?php echo $this->storage->get($subSubCat->cat_icon, '')->getPhotoUrl(); ?>);"<?php } ?>><?php echo $this->translate($subSubCat->category_name); ?></a>
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
      scriptJquery('#sesvideo_toggle_' + id).removeClass('cattoggel cattoggeldown');
      scriptJquery('#sesvideo_toggle_' + id).addClass('cattoggel cattoggelright');
      document.getElementById('subcategory_' + id).style.display = 'none';
    } else {
      scriptJquery('#sesvideo_toggle_' + id).removeClass('cattoggel cattoggelright');
      scriptJquery('#sesvideo_toggle_' + id).addClass('cattoggel cattoggeldown');
      document.getElementById('subcategory_' + id).style.display = 'block';
    }
  }
  
  if(document.getElementById('subsubcategory_' + id)) {
    if (document.getElementById('subsubcategory_' + id).style.display == 'block') {
      scriptJquery('#sesvideo_subcat_toggle_' + id).removeClass('cattoggel cattoggeldown');
      scriptJquery('#sesvideo_subcat_toggle_' + id).addClass('cattoggel cattoggelright');      
      document.getElementById('subsubcategory_' + id).style.display = 'none';
    } else {
      scriptJquery('#sesvideo_subcat_toggle_' + id).removeClass('cattoggel cattoggelright');
      scriptJquery('#sesvideo_subcat_toggle_' + id).addClass('cattoggel cattoggeldown');
      document.getElementById('subsubcategory_' + id).style.display = 'block';
    }
  }
}
</script>
<?php endif; ?>
