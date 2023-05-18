<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php if(!$this->isReview){ ?>
 <div class="sesmember_button sesmember_like_btn">
	<a href="javascript:;" onclick="openSesmemberReviewTab();"  class="sesbasic_animation sesbasic_link_btn"><i class='sesbasic_icon_add'></i><?php echo $this->translate('Write a Review');?></a>
  </div>
<?php }else{ ?>
 <div class="sesmember_button">
	<a href="javascript:;" onclick="openSesmemberReviewTab();"  class="sesbasic_animation sesbasic_link_btn"><i class='sesbasic_icon_edit'></i><?php echo $this->translate('Update Review');?></a>
  </div>
<?php } ?>

<script type="application/javascript">
function openSesmemberReviewTab(){
  var elem = scriptJquery('.tab_layout_sesmember_member_reviews');
  if(elem.find('a').length)
  elem.find('a').trigger('click');
  else
  elem.trigger('click');
  scriptJquery('.sesmember_review_profile_btn').find('a').trigger('click');
}
</script>
