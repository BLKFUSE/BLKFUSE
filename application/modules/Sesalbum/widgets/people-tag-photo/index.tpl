<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php
$this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
          . 'application/modules/Sesbasic/externals/scripts/flexcroll.js');

?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesbasic/externals/scripts/tagger.js'); ?> 
                 
<ul class="sesalbum_user_listing sesbasic_clearfix clear">
  <?php foreach( $this->paginator as $item ): ?>
    <li>
      <?php $user = Engine_Api::_()->getItem('user',$item->tag_id); ?>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')); ?>
    </li>
  <?php endforeach; ?>
  <?php if($this->paginator->getTotalItemCount() > $this->data_show){ ?>
  <li>
    <a href="javascript:;" onclick="getTagData('<?php echo $this->photo_id; ?>')" class="sesalbum_user_listing_more">
     <?php echo '+';echo $this->paginator->getTotalItemCount() - $this->data_show ; ?>
    </a>
  </li>
 <?php } ?>
</ul>

<script type="application/javascript">
function getTagData(value){
	if(value){
		url = en4.core.baseUrl+'albums/index/tag-photo/photo_id/'+value;
		openURLinSmoothBox(url);	
		return;
	}
}
</script>
