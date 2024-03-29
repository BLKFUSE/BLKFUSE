<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Group
* @copyright  Copyright 2006-2020 Webligo Developments
* @license    http://www.socialengine.com/license/
* @version    $Id: index.tpl 9747 2016-12-08 02:08:08Z john $
* @author     John
*/
?>

<?php $categoriesTable = Engine_Api::_()->getDbTable('categories', 'group'); ?>
<ul class="categories_sidebar_listing">
  <li>
    <a class="catlabel" href="groups?category_id=0"><?php echo $this->translate("Uncategorized"); ?></a>
  </li>
  <?php foreach( $this->categories as $item ): ?>
    <li>
      <?php $subcategory = $categoriesTable->getSubcategory(array('column_name' => "*", 'category_id' => $item->category_id)); ?>
      <?php if(engine_count($subcategory) > 0): ?>
        <a id="group_toggle_<?php echo $item->category_id ?>" class="cattoggel cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $item->getIdentity()  ?>')"></a>
      <?php endif; ?>
      <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), 'group_general', true).'?category_id='.urlencode($item->getIdentity()) ; ?>"><?php echo $this->translate($item->title); ?></a>
      <ul id="subcategory_<?php echo $item->getIdentity() ?>" style="display:none;">          
        <?php foreach( $subcategory as $subCat ): ?>
          <li>
            <?php $subsubcategory = $categoriesTable->getSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
            <?php if(engine_count($subsubcategory) > 0): ?>
              <a id="group_subcat_toggle_<?php echo $subCat->category_id ?>" class="cattoggel cattoggelright" href="javascript:void(0);" onclick="showCategory('<?php echo $subCat->getIdentity(); ?>')"></a>
            <?php endif; ?> 
            <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), 'group_general', true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) ; ?>"><?php echo $this->translate($subCat->title); ?></a>   
              <ul id="subsubcategory_<?php echo $subCat->getIdentity() ?>" style="display:none;">
                <?php $subsubcategory = $categoriesTable->getSubsubcategory(array('column_name' => "*", 'category_id' => $subCat->category_id)); ?>
                <?php foreach( $subsubcategory as $subSubCat ): ?>
                  <li>
                    <a class="catlabel" href="<?php echo $this->url(array('action' => 'browse'), 'group_general', true).'?category_id='.urlencode($item->category_id) . '&subcat_id='.urlencode($subCat->category_id) .'&subsubcat_id='.urlencode($subSubCat->category_id) ; ?>"><?php echo $this->translate($subSubCat->title); ?></a>
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
        scriptJquery('#group_toggle_' + id).removeClass('cattoggel cattoggeldown');
        scriptJquery('#group_toggle_' + id).addClass('cattoggel cattoggelright');
        document.getElementById('subcategory_' + id).style.display = 'none';
      } else {
        scriptJquery('#group_toggle_' + id).removeClass('cattoggel cattoggelright');
        scriptJquery('#group_toggle_' + id).addClass('cattoggel cattoggeldown');
        document.getElementById('subcategory_' + id).style.display = 'block';
      }
    }
    
    if(document.getElementById('subsubcategory_' + id)) {
      if (document.getElementById('subsubcategory_' + id).style.display == 'block') {
        scriptJquery('#group_subcat_toggle_' + id).removeClass('cattoggel cattoggeldown');
        scriptJquery('#group_subcat_toggle_' + id).addClass('cattoggel cattoggelright');      
        document.getElementById('subsubcategory_' + id).style.display = 'none';
      } else {
        scriptJquery('#group_subcat_toggle_' + id).removeClass('cattoggel cattoggelright');
        scriptJquery('#group_subcat_toggle_' + id).addClass('cattoggel cattoggeldown');
        document.getElementById('subsubcategory_' + id).style.display = 'block';
      }
    }
  }
</script>
