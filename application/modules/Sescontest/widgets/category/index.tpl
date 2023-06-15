<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>

<?php if($this->params['placement'] == 'horizontal'):?>
  <!--Category Horizontal View Start-->
  <script>
    function showthirdCategory() {	
        if(document.getElementById('thirdcat')) {
            if (document.getElementById('thirdcat').style.display == 'block') {
                scriptJquery('#thirdcat_toggle').removeClass('minus');
                scriptJquery('#thirdcat_toggle').addClass('plus');      
                document.getElementById('thirdcat').style.display = 'none';
            } else {
                scriptJquery('#thirdcat_toggle').removeClass('plus');
                scriptJquery('#thirdcat_toggle').addClass('minus');
                document.getElementById('thirdcat').style.display = 'block';
            }
        }
    }
  </script>
  <div class="sescontest_category_list sesbasic_bxs sesbasic_clearfix">
      <div class="_row sesbasic_clearfix">
        <?php foreach( $this->categories as $item ): ?>
        <article>
          <div class="_mcat" <?php if($this->category_id == $item->category_id) { ?> class="selected" <?php } ?>>
               <a href="<?php echo $this->url(array('action' => 'browse'), 'sescontest_general', true).'?category_id='.urlencode($item->getIdentity()) ; ?>"><i><img src="" /></i><span><?php echo $item->category_name;?></span></a>
          </div>
          <?php $subcategory = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $item->category_id,'limit' => $this->params['count_subcategory'])); ?>
          <?php if(engine_count($subcategory) > 0):?>
            <div class="_subcat">
              <ul>
                <?php foreach( $subcategory as $subCat ): ?>
                  <li><a href="<?php echo $this->url(array('action' => 'browse'), 'sescontest_general', true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>"><i class="fa fa-caret-right sesbasic_text_light"></i><span><?php echo $subCat->category_name;?></span></a>  
                   <?php if($this->params['showsubsubcategory']):?>
                    <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id, 'limit' => $this->params['count_subsubcategory'])); ?>
                   <?php if(engine_count($subsubcategory) > 0):?>
                     <span><a href="javascript:void(0);" onclick="showthirdCategory()" id="thirdcat_toggle" class="cattoggel"></a></span>
                     <div id="thirdcat" class="_thirdcat" style="display:none;">
                       <?php foreach( $subsubcategory as $subsubCat ): ?>
                         <a href="<?php echo $this->url(array('action' => 'browse'), 'sescontest_general', true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>"><?php echo $subsubCat->category_name;?></a>
                       <?php endforeach;?>
                     </div>
                   <?php endif;?>
                  <?php endif;?>
                  </li>
                <?php endforeach;?>
              </ul>
            </div>	
          <?php endif;?>
        </article>
      <?php endforeach;?>
    </div>
  </div>
<!--Category Horizontal View End-->
<?php else:?>
  <?php if($this->params['showType'] == 'tagcloud'): ?>
    
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.tagcanvas.min.js'); ?>
    <div class="sesbasic_cloud_widget">
      <div id="myCanvasContainer_<?php echo $this->identity ?>" style="width:100%;height:<?php echo $this->params['height']; ?>px">
        <canvas style="width:100%;height:100%;" id="myCanvas_<?php echo $this->identity ?>">
          <ul>
            <?php foreach($this->categories as $value): ?>
            <li><a title="<?php echo $value->category_name ?>" href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true).'?category_id='.$value->category_id; ?>"><?php echo $this->translate($value->category_name); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </canvas>
      </div>
    </div>
    <script type="text/javascript">
      scriptJquery(document).ready(function() {
        if (!scriptJquery('#myCanvas_<?php echo $this->identity ?>').tagcanvas({
          textFont: 'Impact,"Arial Black",sans-serif',
          textColour: "<?php echo $this->params['color'];  ?>",
          textHeight: "<?php echo $this->params['text_height'];  ?>",
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
  <?php elseif($this->params['showType'] = 'simple'): ?>
      <div class="sesbasic_sidebar_block">
      <ul class="sescontest_sidebar_category _noicons <?php if(!$this->params['styleType']):?>_hover<?php endif;?>">
        <li <?php if(empty($this->category_id)) { ?> class="selected" <?php } ?>>
          <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), 'sescontest_general', true) ; ?>">
            <i class="fa"><img src="application/modules/Sesbasic/externals/images/category.png" /></i>
            <span><?php echo $this->translate("All Categories"); ?></span>
          </a>
        </li>
        <?php foreach( $this->categories as $item ): ?>
          <li <?php if($this->category_id == $item->category_id) { ?> class="selected" <?php } ?>>
            <?php if($this->params['showSubcategory']):?>
              <?php $subcategory = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $item->category_id, 'limit' => $this->params['count_subcategory'])); ?>
              <?php if(is_countable($subcategory) && engine_count($subcategory) > 0): ?>
                <a id="sescontest_toggle_<?php echo $item->category_id ?>" class="cattoggel fa cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $item->getIdentity()  ?>')"></a>
              <?php endif; ?>
            <?php endif;?>
            <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), 'sescontest_general', true).'?category_id='.urlencode($item->getIdentity()) ; ?>">
              <i class="fa"><img src="application/modules/Sesbasic/externals/images/category.png" /></i>
              <span><?php echo $item->category_name; ?></span>
            </a>
            <?php if($this->params['showSubcategory'] && engine_count($subcategory) > 0):?>
              <ul id="subcategory_<?php echo $item->getIdentity() ?>">          
                <?php foreach( $subcategory as $subCat ): ?>
                  <li>
                    <?php if($this->params['showsubsubcategory']):?>
                      <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id,'limit' => $this->params['count_subsubcategory'])); ?>
                      <?php if(is_countable($subsubcategory) && engine_count($subsubcategory) > 0): ?>
                        <a id="sescontest_subcat_toggle_<?php echo $subCat->category_id ?>" class="cattoggel fa cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $subCat->getIdentity()  ?>')"></a>
                      <?php endif; ?> 
                    <?php endif;?>
                    <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>">
                      <i class="fa"><img src="application/modules/Sesbasic/externals/images/category.png" /></i>
                      <span><?php echo $subCat->category_name; ?>
                      </span>
                    </a>  
                    <?php if($this->params['showsubsubcategory'] && engine_count($subsubcategory) > 0):?>
                      <ul id="subsubcategory_<?php echo $subCat->getIdentity() ?>">
                        <?php $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
                        <?php foreach( $subsubcategory as $subSubCat ): ?>
                          <li>                      
                            <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), $route, true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) .'&subsubcat_id='.urlencode($subSubCat->category_id) ; ?>">
                              <i class="fa"><img src="application/modules/Sesbasic/externals/images/category.png" /></i>
                              <span><?php echo $subSubCat->category_name; ?></span>
                            </a>
                          </li>
                        <?php endforeach; ?>
                      </ul>   
                    <?php endif;?>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif;?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <script>
    function showCategory(id) {
      if(document.getElementById('subcategory_' + id)) {
        if (document.getElementById('subcategory_' + id).style.display == 'block') {
          scriptJquery('#sescontest_toggle_' + id).removeClass('cattoggel cattoggeldown');
          scriptJquery('#sescontest_toggle_' + id).addClass('cattoggel cattoggelright');
          document.getElementById('subcategory_' + id).style.display = 'none';
        } else {
          scriptJquery('#sescontest_toggle_' + id).removeClass('cattoggel cattoggelright');
          scriptJquery('#sescontest_toggle_' + id).addClass('cattoggel cattoggeldown');
          document.getElementById('subcategory_' + id).style.display = 'block';
        }
      }
      if(document.getElementById('subsubcategory_' + id)) {
        if (document.getElementById('subsubcategory_' + id).style.display == 'block') {
          scriptJquery('#sescontest_subcat_toggle_' + id).removeClass('cattoggel cattoggeldown');
          scriptJquery('#sescontest_subcat_toggle_' + id).addClass('cattoggel cattoggelright');      
          document.getElementById('subsubcategory_' + id).style.display = 'none';
        } else {
          scriptJquery('#sescontest_subcat_toggle_' + id).removeClass('cattoggel cattoggelright');
          scriptJquery('#sescontest_subcat_toggle_' + id).addClass('cattoggel cattoggeldown');
          document.getElementById('subsubcategory_' + id).style.display = 'block';
        }
      }
    }
    </script>
  <?php endif; ?>
<?php endif;?>
