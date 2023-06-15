<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: save-news-admin.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php foreach($this->paginator as $newsAdmin):?>
	<div class="admin_manage" id="admin_manage_<?php echo $newsAdmin->role_id;?>">
		<?php $user = Engine_Api::_()->getItem('user', $newsAdmin->user_id);?>
		<?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon', $user->getTitle())) ?>
		<?php echo $this->htmlLink($user->getHref(), $user->getTitle()) ?>
		<?php if($newsAdmin->user_id != $this->owner_id):?>
			<a class="remove_news" href="javascript:void(0);" onclick="removeUser('<?php echo $newsAdmin->news_id;?>','<?php echo $newsAdmin->role_id;?>');"><i class="fa fa-times"></i></a>
		<?php endif;?>
		<br />
	</div>
<?php endforeach;?>
