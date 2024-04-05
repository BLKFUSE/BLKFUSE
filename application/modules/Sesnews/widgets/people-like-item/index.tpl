<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php if($this->title == ''){ ?>
<h3><?php echo $this->translate('People Like This %s',ucfirst(str_replace('sesnews_','',$this->subject->getType()))); ?></h3>
<?php } ?>
<ul class="sesbasic_user_grid_list sesbasic_clearfix">
  <?php foreach( $this->paginator as $item ): ?>
    <li>
      <?php $user = Engine_Api::_()->getItem('user',$item->poster_id) ?>
        	 <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'),array('title'=>$user->getTitle())); ?>
    </li>
  <?php endforeach; ?>
    <?php if($this->paginator->getTotalItemCount() > $this->data_show){ ?>
  <li>
    <a href="javascript:;" onclick="getLikeData('<?php echo $this->subject()->getIdentity(); ?>','<?php echo urlencode($this->translate($this->title)); ?>')" class="sesbasic_user_grid_list_more">
     <?php echo '+';echo $this->paginator->getTotalItemCount() - $this->data_show ; ?>
    </a>
  </li>
 <?php } ?>
</ul>
<script type="application/javascript">
function getLikeData(value,title){
	if(value){
		url = en4.core.baseUrl+'sesnews/index/like-item/item_id/'+value+'/title/'+title+'/item_type/<?php echo $this->subject()->getType(); ?>';
		openURLinSmoothBox(url);	
		return;
	}
}
</script>
