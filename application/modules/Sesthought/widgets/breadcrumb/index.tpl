<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="sesbasic_breadcrumb">
  <a href="<?php echo $this->url(array('action' => 'index'), "sesthought_general"); ?>"><?php echo $this->translate("Browse Thought"); ?></a>&nbsp;&raquo;
  <?php if($this->thought->category_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1)) { ?>
    <?php $category = Engine_Api::_()->getItem('sesthought_category', $this->thought->category_id); ?>
    <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id; ?>"><?php echo $category->category_name; ?></a>
    <?php if($this->thought->subcat_id) { ?>
      &nbsp;&raquo;
      <?php $subcategory = Engine_Api::_()->getItem('sesthought_category', $this->thought->subcat_id); ?>
      <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id.'&subcat_id='.$this->thought->subcat_id; ?>"><?php echo $subcategory->category_name; ?></a>
    <?php } ?>
    <?php if($this->thought->subsubcat_id) { ?>
      &nbsp;&raquo;
      <?php $subsubcategory = Engine_Api::_()->getItem('sesthought_category', $this->thought->subsubcat_id); ?>
      <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id.'&subcat_id='.$this->thought->subcat_id.'&subsubcat_id='.$this->thought->subsubcat_id; ?>"><?php echo $subsubcategory->category_name; ?></a>
    <?php } ?>
  <?php } ?>
  <?php echo $this->string()->truncate($this->string()->stripTags($this->thought->getTitle()), 50) ?> 
</div>
