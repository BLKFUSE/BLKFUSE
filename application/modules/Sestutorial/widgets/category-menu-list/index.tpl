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
<div class="sestutorial_category_menu_list sestutorial_clearfix sestutorial_bxs">
  <ul class="category_menu">
    <?php foreach($this->resultcategories as $resultcategorie): ?>
      <li class="active toggled_menu_parant">
      <img src="<?php echo $resultcategorie->getCategoryIconUrl(); ?>" />
      <?php $subcategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getModuleSubcategory(array('category_id' => $resultcategorie->category_id, 'column_name' => '*')); ?>
      <a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($resultcategorie->category_id) ; ?>" class="sestutorial_linkinherit"><?php echo $this->translate($resultcategorie->category_name); ?> <?php if(engine_count($subcategories) > 0): ?><i class="fa fa-plus dropdown_icon sestutorial_sidebarcategory"></i><?php endif; ?></a>
      <?php if(engine_count($subcategories) > 0): ?>
        <ul class="" style="display:none;">
          <?php foreach($subcategories as $subcategory): ?>
            <li><a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($subcategory->subcat_id) . '&subcat_id='.urlencode($subcategory->category_id) ?>"><?php echo $this->translate($subcategory->category_name); ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<script type="text/javascript">

	scriptJquery(document).on('click','.sestutorial_sidebarcategory',function(e){
	  if(scriptJquery(this).parent().parent().find('ul').children().length == 0)
	  	return true;
	  e.preventDefault();
	  scriptJquery('.sestutorial_sidebarcategory.fa-minus').removeClass('fa-minus').addClass('fa-plus');
	  if(scriptJquery(this).parent().hasClass('open_toggled_menu')) {
      scriptJquery('.open_toggled_menu').parent().find('ul').slideToggle('slow');
      scriptJquery(this).parent().removeClass('open_toggled_menu');
      scriptJquery(this).addClass('fa-plus');
	  } else {
      scriptJquery('.open_toggled_menu').parent().find('ul').slideToggle('slow');
      scriptJquery(this).parent().parent().find('ul').slideToggle('slow');
      scriptJquery('.open_toggled_menu').removeClass('open_toggled_menu');
      scriptJquery(this).parent().addClass('open_toggled_menu');
      scriptJquery(this).removeClass('fa-plus').addClass('fa-minus');
	  }
	  return false;
  });

</script>
