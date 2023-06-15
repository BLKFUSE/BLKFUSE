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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->sesnews->getHref()); ?>
<?php $isNewsAdmin = Engine_Api::_()->sesnews()->isNewsAdmin($this->sesnews, 'edit');?>
<?php $reviewCount = Engine_Api::_()->sesnews()->getTotalReviews($this->sesnews->news_id);?>
<?php $canComment =  $this->sesnews->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($this->sesnews->news_id,$this->sesnews->getType()); ?> 
<?php $likeClass = (!$LikeStatus) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;?>
<?php $likeText = ($LikeStatus) ?  $this->translate('Unlike') : $this->translate('Like');?>
<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$this->sesnews->news_id)); ?>
<?php $enableSharng = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1);?>
<?php if($this->sesnews->style == 1):?>
	<div class="sesnews_layout_contant sesbasic_clearfix sesbasic_bxs">
	  <?php if(isset($this->titleActive)):?>
			<h2><?php echo htmlspecialchars_decode(htmlspecialchars_decode($this->sesnews->getTitle())) ?></h2>
		<?php endif;?>
		<div class="sesnews_entrylist_entry_date">
    	<p><?php echo $this->translate('<i>Posted by -</i>');?> <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) ?> &nbsp;-&nbsp;</p>
			<p><?php echo $this->translate('<i>on - </i>') ?><?php echo $this->timestamp($this->sesnews->creation_date) ?><?php if( $this->category ): ?>&nbsp;-&nbsp;</p>
				<p><?php echo $this->translate('<i>Filed in - </i>') ?>
				<a href="<?php echo $this->category->getHref(); ?>"><?php echo $this->translate($this->category->category_name) ?></a><?php endif; ?>&nbsp;-&nbsp;</p>
			<p><?php if (engine_count($this->sesnewsTags )):?>
				<?php foreach ($this->sesnewsTags as $tag): ?>
					<a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'><?php echo $tag->getTag()->text?></a>&nbsp;
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if(isset($this->staticsActive)):?>
				&nbsp;-&nbsp;</p>
				<p>
					<?php if(isset($this->viewActive)):?>
						<span><?php echo $this->translate(array('%s View', '%s Views', $this->sesnews->view_count), $this->locale()->toNumber($this->sesnews->view_count)) ?>&nbsp;-&nbsp;</span>
					<?php endif;?>
					<?php if(isset($this->commentActive)):?>
						<span><?php echo $this->translate(array('%s Comment', '%s Comments', $this->sesnews->comment_count), $this->locale()->toNumber($this->sesnews->comment_count)) ?>&nbsp;-&nbsp;</span>
					<?php endif;?>
					<?php if(isset($this->likeActive)):?>
						<span><?php echo $this->translate(array('%s Like', '%s Likes', $this->sesnews->like_count), $this->locale()->toNumber($this->sesnews->like_count)) ?>&nbsp;-&nbsp;</span>
					<?php endif;?>
					<?php if(isset($this->reviewActive)):?>
						<span><?php echo $this->translate(array('%s Review', '%s Reviews', $reviewCount), $this->locale()->toNumber($reviewCount)) ?></span>
					<?php endif;?>
				</p>
			<?php endif;?>
		</div>
		<div class="sesnews_entrylist_entry_body">
		  <?php if(isset($this->photoActive) && $this->sesnews->photo_id):?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo Engine_Api::_()->storage()->get($this->sesnews->photo_id)->getPhotoUrl('thumb.main'); ?>" alt="">
				</div>
			<?php elseif(isset($this->photoActive)): ?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo $this->sesnews->image_url; ?>" alt="" referrerpolicy="no-referrer">
				</div>
			<?php endif;?>
			<?php if(isset($this->descriptionActive)):?>
				<?php if($this->sesnews->cotinuereading){
					$check = true;
					$style = 'style="height:400px; overflow:hidden;"';
				}else{
					$check = false;
					$style = '';
				} ?>
				<div class="rich_content_body" style="visibility:hidden"><?php echo $this->sesnews->body;?></div>
				<?php if($check): ?>
					<div class="sesnews_morebtn" style="display:none"><a href="javascript:void(0);" onclick="continuereading();"><?php echo $this->translate("Continue Reading"); ?></a></div>
				<?php endif; ?>
			<?php endif;?>
		</div>
    <div class="sesnews_footer_two_news clear">
      <?php if(isset($this->ratingActive)):?>
				<div class="sesbasic_rating_star floatL">
					<?php $ratingCount = $this->sesnews->rating; $x=0; ?>
					<?php if( $ratingCount > 0 ): ?>
						<?php for( $x=1; $x<=$ratingCount; $x++ ): ?>
							<span id="" class="sesnews_rating_star"></span>
						<?php endfor; ?>
						<?php if( (round($ratingCount) - $ratingCount) > 0){ ?>
						<span class="sesnews_rating_star sesnews_rating_star_half"></span>
						<?php }else{ $x = $x - 1;} ?>
						<?php if($x < 5){ 
						for($j = $x ; $j < 5;$j++){ ?>
						<span class="sesnews_rating_star sesnews_rating_star_disable"></span>
						<?php }   	
						} ?>
					<?php endif; ?>
				</div>
			<?php endif;?>
			<div class="sesnews_deshboard_news floatR">
				<ul>
					<?php if(!empty($this->sesnews->news_link) && isset($this->originalnewsActive)):?>
						<li><a href="<?php echo $this->sesnews->news_link; ?>"  target="_blank" class="sesnews_comment"><i class="sesnews_comment fa fa-commenting"></i><?php echo $this->translate('Read Full News Here');?></a></li>
          <?php endif;?>
          
					<?php if(isset($this->ownerOptionsActive) && $isNewsAdmin):?>
            <?php if($this->canEdit) { ?>
              <li><a href="<?php echo $this->url(array('action' => 'edit', 'news_id' => $this->sesnews->custom_url), 'sesnews_dashboard', 'true');?>"><i class="fa fa-edit"></i><?php echo $this->translate('Dashboard');?></a></li>
						<?php } ?>
						<?php if($this->canDelete) { ?>
              <li><a href="<?php echo $this->url(array('action' => 'delete', 'news_id' => $this->sesnews->getIdentity()), 'sesnews_specific', true);?>" class="smoothbox"><i class="fa fa-trash "></i><?php echo $this->translate('Delete This News');?></a></li>
						<?php } ?>
					<?php endif;?>
					<?php if($this->viewer_id && isset($this->smallShareButtonActive) && $enableSharng):?>
						<li><a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->sesnews->getType(), "id" => $this->sesnews->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="smoothbox share_icon"><i class="fas fa-share-alt"></i> <?php echo $this->translate('Share');?></a></li>
					<?php endif;?>
					<?php if($this->viewer_id && $this->viewer_id != $this->sesnews->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1)):?>
						<li><a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->sesnews->getGuid()),'default', true);?>" class="smoothbox report_link"><i class="fa fa-flag"></i><?php echo $this->translate('Report');?></a></li>
					<?php endif;?>
					<?php if(isset($this->postCommentActive) && $canComment):?>
						<li><a href="javascript:void(0);" class="sesnews_comment"><i class="sesnews_comment fa fa-comment"></i><?php echo $this->translate('Post Comment');?></a></li>
          <?php endif;?>
				</ul>
			</div>
      <div class="sesnews_shear_news sesbasic_bxs">
        <?php if(isset($this->socialShareActive) && $enableSharng):?>
        
          <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->sesnews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
			  <?php endif;?>
				<?php if($this->viewer_id && $enableSharng && isset($this->shareButtonActive)):?>
						<a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->sesnews->getType(), "id" => $this->sesnews->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="share_icon sesbasic_icon_btn smoothbox"><i class="fas fa-share-alt"></i><span><?php echo $this->translate('Share');?></span></a>
				<?php endif;?>
				<?php if($this->viewer_id):?>
					<?php if(isset($this->likeButtonActive) && $canComment):?>
							<a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_like_btn  sesnews_like_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_like_sesnews_news_view <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"><i class="fa <?php echo $likeClass;?>"></i><span><?php echo $this->translate($likeText);?></span></a>
					<?php endif;?>
					<?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)):?>
							<a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_fav_btn  sesnews_favourite_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_favourite_sesnews_news_view <?php echo ($favStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php if($favStatus):?><?php echo $this->translate('Un-Favourite');?><?php else:?><?php echo $this->translate('Favourite');?><?php endif;?></span></a>
					<?php endif;?>
        <?php endif;?>
			</div>
		</div>
	</div>
<?php elseif($this->sesnews->style == 2):?>
	<!--second profile news start-->
	<div class="sesnews_profile_layout_second sesbasic_clearfix sesbasic_bxs">
	
		<?php if(isset($this->photoActive) && $this->sesnews->photo_id):?>
			<div class="sesnews_profile_layout_second_image clear">
				<img src="<?php echo Engine_Api::_()->storage()->get($this->sesnews->photo_id)->getPhotoUrl('thumb.main'); ?>" alt="">
			</div>
		<?php elseif(isset($this->photoActive)): ?>
			<div class="sesnews_profile_layout_second_image clear">
				<img src="<?php echo $this->sesnews->image_url; ?>" alt="" referrerpolicy="no-referrer">
			</div>
		<?php endif;?>
		
	  <?php if( $this->category ): ?>
    				<?php echo $this->translate('') ?>
  	<div class="sesnews_category_teg">
     <p>   
				<a href="<?php echo $this->category->getHref(); ?>"><?php echo $this->translate($this->category->category_name) ?></a>
			</p>
		</div><?php endif; ?>
		<?php if(isset($this->titleActive)):?>
			<h2><?php echo htmlspecialchars_decode($this->sesnews->getTitle()) ?></h2>
		<?php endif;?>
		<div class="sesnews_entrylist_entry_date">
			<p><?php echo $this->translate('<i>Posted by -</i>');?> <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) ?> &nbsp;\&nbsp;</p>
      <p><?php echo $this->translate('<i class="far fa-calendar"></i>') ?>&nbsp;
			<?php echo $this->timestamp($this->sesnews->creation_date) ?>
			<?php  ?>
			<?php if (engine_count($this->sesnewsTags )):?> &nbsp;\&nbsp;
				</p>
        <p><?php echo $this->translate('<i>Filed in - </i>') ?>
				<?php if($this->category) { ?>
				<a href="<?php echo $this->category->getHref(); ?>"><?php echo $this->translate($this->category->category_name) ?></a>
				<?php } ?>
        &nbsp;\&nbsp;</p>
        <p>
        <?php foreach ($this->sesnewsTags as $tag): ?>
					<a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'><?php echo $tag->getTag()->text?></a>&nbsp;
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if(isset($this->staticsActive)):?>
				&nbsp;\&nbsp;</p>
				<p>
				<?php if(isset($this->viewActive)):?>
					<span><i class="sesbasic_icon_view"></i>&nbsp;
					<?php echo $this->translate(array('%s view', '%s views', $this->sesnews->view_count), $this->locale()->toNumber($this->sesnews->view_count)) ?>&nbsp;\&nbsp;</span>
				<?php endif;?>
				<?php if(isset($this->commentActive)):?>
					<span><i class="sesbasic_icon_comment_o"></i>&nbsp;<?php echo $this->translate(array('%s comment', '%s comments', $this->sesnews->comment_count), $this->locale()->toNumber($this->sesnews->comment_count)) ?>&nbsp;\&nbsp;</span>
				<?php endif;?>
				<?php if(isset($this->likeActive)):?>
					<span><i class="sesbasic_icon_like_o"></i>&nbsp;<?php echo $this->translate(array('%s like', '%s likes', $this->sesnews->like_count), $this->locale()->toNumber($this->sesnews->like_count)) ?>&nbsp;\&nbsp;</span>
				<?php endif;?>
				<?php if(isset($this->reviewActive)):?>
					<span><i class="fa fa-edit"></i>&nbsp;<?php echo $this->translate(array('%s review', '%s reviews', $reviewCount), $this->locale()->toNumber($reviewCount)) ?></span>
				<?php endif;?>
				</p>
      <?php endif;?>
		</div>
		<?php if(isset($this->descriptionActive)):?>
			<?php if($this->sesnews->cotinuereading){
					$check = true;
					$style = 'style="height:400px; overflow:hidden;"';
				}else{
					$check = false;
					$style = '';
				} ?>
				<div class="rich_content_body" style="visibility:hidden"><?php echo $this->sesnews->body;?></div>
				<?php if($check): ?>
					<div class="sesnews_entrylist_entry_body sesnews_morebtn" style="display:none"><a href="javascript:void(0);" onclick="continuereading();"><?php echo $this->translate("Continue Reading"); ?></a></div>
				<?php endif; ?>
		<?php endif;?>
    <div class="sesnews_view_footer_top clear sesbasic_clearfix">
      <?php if(isset($this->ratingActive)):?>
				<div class="sesbasic_rating_star floatL">
					<?php $ratingCount = $this->sesnews->rating; $x=0; ?>
					<?php if( $ratingCount > 0 ): ?>
						<?php for( $x=1; $x<=$ratingCount; $x++ ): ?>
							<span id="" class="sesnews_rating_star"></span>
						<?php endfor; ?>
						<?php if( (round($ratingCount) - $ratingCount) > 0){ ?>
						<span class="sesnews_rating_star sesnews_rating_star_half"></span>
						<?php }else{ $x = $x - 1;} ?>
						<?php if($x < 5){ 
						for($j = $x ; $j < 5;$j++){ ?>
						<span class="sesnews_rating_star sesnews_rating_star_disable"></span>
						<?php }   	
						} ?>
					<?php endif; ?>
				</div>
			<?php endif;?>
      <div class="sesnews_view_footer_links floatR">
				<ul>
					<?php if(!empty($this->sesnews->news_link) && isset($this->originalnewsActive)):?>
						<li><a href="<?php echo $this->sesnews->news_link; ?>"  target="_blank" class="sesnews_comment"><i class="sesnews_comment fa fa-commenting"></i><?php echo $this->translate('Read Full News Here');?></a></li>
          <?php endif;?>
					<?php if(isset($this->ownerOptionsActive) && $isNewsAdmin):?>
            <?php if($this->canEdit) { ?>
              <li><a href="<?php echo $this->url(array('action' => 'edit', 'news_id' => $this->sesnews->custom_url), 'sesnews_dashboard', 'true');?>"><i class="fa fa-edit"></i><?php echo $this->translate('Dashboard');?></a></li>
						<?php } ?>
						<?php if($this->canDelete) { ?>
              <li><a href="<?php echo $this->url(array('action' => 'delete', 'news_id' => $this->sesnews->getIdentity()), 'sesnews_specific', true);?>" class="smoothbox"><i class="fa fa-trash "></i><?php echo $this->translate('Delete This News');?></a></li>
						<?php } ?>
					<?php endif;?>
					<?php if($this->viewer_id):?>
						<li><a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->sesnews->getType(), "id" => $this->sesnews->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="smoothbox share_icon"><i class="fas fa-share-alt"></i><?php echo $this->translate('Share');?></a></li>
					<?php endif;?>
					<?php if($this->viewer_id && $this->viewer_id != $this->sesnews->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1)):?>
						<li><a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->sesnews->getGuid()),'default', true);?>" class="smoothbox report_link"><i class="fa fa-flag"></i><?php echo $this->translate('Report');?></a></li>
					<?php endif;?>
				</ul>
			</div>
    </div>
    <div class="sesnews_view_footer_top_bottom clear sesbasic_clearfix">
			<div class="sesnews_view_footer_links floatL">
        <ul>
          <?php if($this->viewer_id):?>
						<?php if(isset($this->likeButtonActive) && $canComment):?>
							<li><a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesnews_like_link  sesnews_like_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_like_sesnews_news_view"><i class="<?php if($LikeStatus):?>fa fa-thumbs-down<?php else:?>fa fa-thumbs-up<?php endif;?>"></i><span><?php if($LikeStatus):?><?php echo $this->translate('Unlike');?><?php else:?><?php echo $this->translate('Like');?><?php endif;?></span></a>&nbsp;/&nbsp;</li>
						<?php endif;?>
						<?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)):?>
							<li><a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesnews_fav_link sesnews_favourite_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_favourite_sesnews_news_view"><i class="fa fa-heart"></i><span><?php if($favStatus):?><?php echo $this->translate('Un-Favourite');?><?php else:?><?php echo $this->translate('Favourite');?><?php endif;?></span></a>&nbsp;/&nbsp;</li>
						<?php endif;?>
					<?php endif;?>
					<?php if(isset($this->postCommentActive) && $canComment):?>
						<li><a href="javascript:void(0);" class="sesnews_comment"><i class="sesnews_comment fa fa-commenting"></i><span><?php echo $this->translate('Post Comment');?></span></a></li>
          <?php endif;?>
        </ul>
			</div>
			<?php if(isset($this->socialShareActive)):?>
				<div class="sesnews_view_footer_social_share floatR">
					<?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->sesnews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
				</div>
			<?php endif;?>
		</div>
	</div>
	<!--second profile news end-->
<?php elseif($this->sesnews->style == 3):?>
	<!--three profile news start-->
	<div class="sesnews_profile_layout_three sesbasic_clearfix sesbasic_bxs">
		<?php if(isset($this->ratingActive)):?>
			<div class="sesbasic_rating_star floatR">
				<?php $ratingCount = $this->sesnews->rating; $x=0; ?>
				<?php if( $ratingCount > 0 ): ?>
					<?php for( $x=1; $x<=$ratingCount; $x++ ): ?>
						<span id="" class="sesnews_rating_star"></span>
					<?php endfor; ?>
					<?php if( (round($ratingCount) - $ratingCount) > 0){ ?>
					<span class="sesnews_rating_star sesnews_rating_star_half"></span>
					<?php }else{ $x = $x - 1;} ?>
					<?php if($x < 5){ 
					for($j = $x ; $j < 5;$j++){ ?>
					<span class="sesnews_rating_star sesnews_rating_star_disable"></span>
					<?php }   	
					} ?>
				<?php endif; ?>
			</div>
		<?php endif;?>
    <?php if(isset($this->titleActive)):?>
			<h2><?php echo htmlspecialchars_decode($this->sesnews->getTitle()) ?></h2>
		<?php endif;?>
		<div class="sesnews_entrylist_entry_date">
      <?php if( $this->category ): ?>
				<p class="catogery floatR">
				<?php echo $this->translate('<i class="far fa-folder"></i>') ?>
				<a href="<?php echo $this->category->getHref(); ?>"><?php echo $this->translate($this->category->category_name) ?></a>
				</p>
      <?php endif; ?>
			<p class="">
      	<span><i class="far fa-user"></i> <?php echo $this->translate('');?> <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) ?></span>
      	<span><?php echo $this->translate('<i class="far fa-calendar"></i>') ?>&nbsp;<?php echo $this->timestamp($this->sesnews->creation_date) ?></span>
      	<?php if(isset($this->staticsActive)):?>
      	  <?php if(isset($this->viewActive)):?>
						<span><i class="sesbasic_icon_view"></i> <?php echo $this->translate(array('%s view', '%s views', $this->sesnews->view_count), $this->locale()->toNumber($this->sesnews->view_count)) ?></span>
					<?php endif;?>
					<?php if(isset($this->commentActive)):?>
						<span><i class="sesbasic_icon_comment_o"></i><?php echo $this->translate(array('%s Comment', '%s Comments', $this->sesnews->comment_count), $this->locale()->toNumber($this->sesnews->comment_count)) ?></span>
					<?php endif;?>
					<?php if(isset($this->likeActive)):?>
						<span><i class="sesbasic_icon_like_o"></i><?php echo $this->translate(array('%s Like', '%s Likes', $this->sesnews->like_count), $this->locale()->toNumber($this->sesnews->like_count)) ?></span>
					<?php endif;?>
					<?php if(isset($this->reviewActive)):?>
					<span><i class="fa fa-edit"></i><?php echo $this->translate(array('%s Review', '%s Reviews', $reviewCount), $this->locale()->toNumber($reviewCount)) ?></span>
					<?php endif;?>
				<?php endif;?>
				<?php if (engine_count($this->sesnewsTags )):?>
					<span>
						<i class="fa fa-tag"></i>
						<?php foreach ($this->sesnewsTags as $tag): ?>
						<a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'><?php echo $tag->getTag()->text?></a>&nbsp;
					<?php endforeach; ?>
					</span>
				<?php endif; ?>
			</p>
		</div>
		<div class="sesnews_entrylist_entry_body">
			<?php if(isset($this->photoActive) && $this->sesnews->photo_id):?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo Engine_Api::_()->storage()->get($this->sesnews->photo_id)->getPhotoUrl('thumb.main'); ?>" alt="">
				</div>
			<?php elseif(isset($this->photoActive)): ?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo $this->sesnews->image_url; ?>" alt="" referrerpolicy="no-referrer">
				</div>
			<?php endif;?>
		
			<?php if(isset($this->descriptionActive)):?>
				<?php if($this->sesnews->cotinuereading){
					$check = true;
					$style = 'style="height:400px; overflow:hidden;"';
				}else{
					$check = false;
					$style = '';
				} ?>
				<div class="rich_content_body" style="visibility:hidden"><?php echo $this->sesnews->body;?></div>
				<?php if($check): ?>
					<div class="sesnews_entrylist_entry_body sesnews_morebtn" style="display:none"><a href="javascript:void(0);" onclick="continuereading();"><?php echo $this->translate("Continue Reading"); ?></a></div>
				<?php endif; ?>
			<?php endif;?>
		</div>
		<div class="sesnews_three_news_footer">
    	<div class="sesnews_three_news_footer_links floatL">
      <ul>
        <?php if(isset($this->likeButtonActive) && $canComment):?>
					<li><a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesnews_like_link sesnews_like_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_like_sesnews_news_view"><i class="<?php if($LikeStatus):?>fa fa-thumbs-down<?php else:?>fa fa-thumbs-up<?php endif;?>"></i><span><?php if($LikeStatus):?><?php echo $this->translate('Unlike');?><?php else:?><?php echo $this->translate('Like');?><?php endif;?></span></a>&nbsp;|&nbsp;</li>
        <?php endif;?>
        <?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)):?>
					<li><a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesnews_fav_link sesnews_favourite_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_favourite_sesnews_news_view"><i class="fa fa-heart"></i><span><?php if($favStatus):?><?php echo $this->translate('Un-Favourite');?><?php else:?><?php echo $this->translate('Favourite');?><?php endif;?></span></a>&nbsp;|&nbsp;	</li>
        <?php endif;?>
        <?php if(isset($this->postCommentActive) && $canComment):?>
					<li><a href="javascript:void(0);" class="sesnews_comment"><i class="sesnews_comment fa fa-commenting"></i><span><?php echo $this->translate('Post Comment');?></span></a></li>
        <?php endif;?>
      </ul>
			</div>
      <div class="sesnews_three_news_footer_links floatR">
				<ul>
					<?php if(!empty($this->sesnews->news_link) && isset($this->originalnewsActive)):?>
						<li><a href="<?php echo $this->sesnews->news_link; ?>"  target="_blank" class="sesnews_comment"><i class="sesnews_comment fa fa-commenting"></i><?php echo $this->translate('Read Full News Here');?></a></li>
          <?php endif;?>
					<?php if(isset($this->ownerOptionsActive) && $isNewsAdmin):?>
          <?php if($this->canEdit) { ?>
						<li><a href="<?php echo $this->url(array('action' => 'edit', 'news_id' => $this->sesnews->custom_url), 'sesnews_dashboard', 'true');?>" class=""><i class="fa fa-edit"></i><?php echo $this->translate('Dashboard');?></a>&nbsp;|&nbsp;</li>
          <?php } ?>
          <?php if($this->canDelete) { ?>
						<li><a href="<?php echo $this->url(array('action' => 'delete', 'news_id' => $this->sesnews->getIdentity()), 'sesnews_specific', true);?>" class="smoothbox"><i class="fa fa-trash "></i><?php echo $this->translate('Delete This News');?></a>&nbsp;|&nbsp;</li>
          <?php } ?>
					<?php endif;?>
					<?php if($this->viewer_id):?>
						<li><a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->sesnews->getType(), "id" => $this->sesnews->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="smoothbox share_icon"><i class="fas fa-share-alt"></i><?php echo $this->translate('Share');?></a></li>
					<?php endif;?>
					<?php if($this->viewer_id && $this->viewer_id != $this->sesnews->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1)):?>
						<li><a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->sesnews->getGuid()),'default', true);?>" class="smoothbox report_link"><i class="fa fa-flag"></i><?php echo $this->translate('Report');?></a></li>
					<?php endif;?>
				</ul>
			</div>
    </div>
    <?php if(isset($this->socialShareActive)):?>
			<div class="sesnews_footer_news clear">
				<p><?php echo $this->translate('SHARE THIS STORY');?></p>
				<div class="sesnews_footer_news_social_share sesbasic_clearfix">
            <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->sesnews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<!--three profile news start-->
<?php elseif($this->sesnews->style == 4):?>
	<div class="sesnews_profile_layout_four sesbasic_clearfix sesbasic_bxs">
	 <?php if(isset($this->ratingActive)):?>
			<div class="sesbasic_rating_star floatR">
				<?php $ratingCount = $this->sesnews->rating; $x=0; ?>
				<?php if( $ratingCount > 0 ): ?>
					<?php for( $x=1; $x<=$ratingCount; $x++ ): ?>
						<span id="" class="sesnews_rating_star"></span>
					<?php endfor; ?>
					<?php if( (round($ratingCount) - $ratingCount) > 0){ ?>
					<span class="sesnews_rating_star sesnews_rating_star_half"></span>
					<?php }else{ $x = $x - 1;} ?>
					<?php if($x < 5){ 
					for($j = $x ; $j < 5;$j++){ ?>
					<span class="sesnews_rating_star sesnews_rating_star_disable"></span>
					<?php }   	
					} ?>
				<?php endif; ?>
			</div>
		<?php endif;?>
    <?php if(isset($this->titleActive)):?>
			<h2><?php echo htmlspecialchars_decode($this->sesnews->getTitle()) ?></h2>
		<?php endif;?>
		<div class="sesnews_entrylist_entry_date">
			<span class="sesnews_entry_border"></span>
			<p>
				<span>
        <?php echo $this->translate('');?>&nbsp; <?php echo $this->htmlLink($this->owner->getHref(), 
        $this->itemPhoto($this->owner),
				array('class' => 'sesnews_gutter_photo')) ?> <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) ?> &nbsp;-&nbsp;</span>
				<span>
					<?php echo $this->translate('<i class="far fa-calendar"></i>') ?>
					<?php echo $this->timestamp($this->sesnews->creation_date) ?>
					&nbsp;-&nbsp;
        </span>
        <?php  ?>
				<?php if( $this->category ): ?>
					<span>
					<?php echo $this->translate('<i class="fa fa-tag"></i>') ?>
					<a href="<?php echo $this->category->getHref(); ?>"><?php echo $this->translate($this->category->category_name) ?></a>
				</span>
        <?php endif; ?>
        <?php if (engine_count($this->sesnewsTags )):?>
					<span>
          <?php foreach ($this->sesnewsTags as $tag): ?>
						<a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'><?php echo $tag->getTag()->text?></a>&nbsp;
					<?php endforeach; ?>
					&nbsp;-&nbsp;
        </span>
        <?php endif; ?>
				<?php if(isset($this->staticsActive)):?>
				  <?php if(isset($this->viewActive)):?>
						<span><i class="fa fa-eye"></i>
						<?php echo $this->translate(array('%s view', '%s views', $this->sesnews->view_count), $this->locale()->toNumber($this->sesnews->view_count)) ?>
						&nbsp;-&nbsp;
						</span>
          <?php endif;?>
          <?php if(isset($this->commentActive)):?>
						<span><i class="sesbasic_icon_comment_o"></i><?php echo $this->translate(array('%s Comment', '%s Comments', $this->sesnews->comment_count), $this->locale()->toNumber($this->sesnews->comment_count)) ?>&nbsp;-&nbsp;</span>
					<?php endif;?>
					<?php if(isset($this->likeActive)):?>
						<span><i class="sesbasic_icon_like_o"></i><?php echo $this->translate(array('%s Like', '%s Likes', $this->sesnews->like_count), $this->locale()->toNumber($this->sesnews->like_count)) ?>&nbsp;-&nbsp;</span>
					<?php endif;?>
					<?php if(isset($this->reviewActive)):?>
						<span><i class="fa fa-edit"></i><?php echo $this->translate(array('%s Review', '%s Reviews', $reviewCount), $this->locale()->toNumber($reviewCount)) ?></span>
					<?php endif;?>
				<?php endif;?>
			</p>
		</div>
		<div class="sesnews_entrylist_entry_body">
			<?php if(isset($this->photoActive) && $this->sesnews->photo_id):?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo Engine_Api::_()->storage()->get($this->sesnews->photo_id)->getPhotoUrl('thumb.main'); ?>" alt="">
				</div>
			<?php elseif(isset($this->photoActive)): ?>
				<div class="sesnews_news_image clear">
					<img src="<?php echo $this->sesnews->image_url; ?>" alt="" referrerpolicy="no-referrer">
				</div>
			<?php endif;?>
			
		<div class="sesnews_social_tabs sesbasic_clearfix">
				<?php if(isset($this->postCommentActive) && $canComment):?>
					<a href="javascript:void(0);" class="sesnews_comment commenting"><i class="sesnews_comment fa fa-comment"></i><?php echo ' '.$this->sesnews->comment_count;?></a>
				<?php endif;?>
				<?php if(isset($this->likeButtonActive)):?>
					<a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->sesnews->like_count; ?></span></a>
				<?php endif;?>
				<?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)):?>
						<a href="javascript:;" data-url="<?php echo $this->sesnews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news_<?php echo $this->sesnews->news_id ?> sesnews_favourite_sesnews_news <?php echo ($favStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-heart"></i><span><?php echo $this->sesnews->favourite_count; ?></span></a>
				<?php endif;?>
				<?php if(isset($this->socialShareActive)):?>
          <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->sesnews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
				<?php endif;?>
		</div>
			<?php if(isset($this->descriptionActive)):?>
				<?php if($this->sesnews->cotinuereading){
					$check = true;
					$style = 'style="height:400px; overflow:hidden;"';
				}else{
					$check = false;
					$style = '';
				} ?>
				<div class="rich_content_body" style="visibility:hidden"><?php echo $this->sesnews->body;?></div>
				<?php if($check): ?>
					<div class="sesnews_entrylist_entry_body sesnews_morebtn" style="display:none"><a href="javascript:void(0);" onclick="continuereading();"><?php echo $this->translate("Continue Reading"); ?></a></div>
				<?php endif; ?>
			<?php endif;?>
		</div>
    <div class="sesnews_deshboard_links ">
        <?php if(isset($this->postCommentActive) && $canComment):?>
					<p class="profile_layout_fore_post_com floatL"><a href="javascript:void(0);" class="sesnews_comment"><i class="sesnews_comment fa fa-comment"></i><span><?php echo $this->translate('Post Comment');?></span></a></p>
				<?php endif;?>
				<ul class="floatR">
					<?php if(!empty($this->sesnews->news_link) && isset($this->originalnewsActive)):?>
						<li><a href="<?php echo $this->sesnews->news_link; ?>"  target="_blank" class="sesnews_comment"><i class="sesnews_comment fa fa-comment"></i><?php echo $this->translate('Read Full News Here');?></a></li>
          <?php endif;?>
					<?php if(isset($this->ownerOptionsActive) && $isNewsAdmin):?>
            <?php if($this->canEdit) { ?>
						<li><a href="<?php echo $this->url(array('action' => 'edit', 'news_id' => $this->sesnews->custom_url), 'sesnews_dashboard', 'true');?>" class="sesbasic_button "><i class="fa fa-edit"></i><?php echo $this->translate('Dashboard');?></a></li>
						<?php } ?>
						<?php if($this->canDelete) { ?>
						<li><a href="<?php echo $this->url(array('action' => 'delete', 'news_id' => $this->sesnews->getIdentity()), 'sesnews_specific', true);?>" class="smoothbox sesbasic_button "><i class="fa fa-trash "></i><?php echo $this->translate('Delete This News');?></a></li>
						<?php } ?>
					<?php endif;?>
					<?php if($this->viewer_id):?>
						<li><a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->sesnews->getType(), "id" => $this->sesnews->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="smoothbox sesbasic_button  share_icon"><i class="fas fa-share-alt"></i><?php echo $this->translate('Share');?></a></li>
					<?php endif;?>
					<?php if($this->viewer_id && $this->viewer_id != $this->sesnews->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1)):?>
						<li><a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->sesnews->getGuid()),'default', true);?>" class="smoothbox sesbasic_button report_link"><i class="fa fa-flag"></i><?php echo $this->translate('Report');?></a></li>
					<?php endif;?>
				</ul>
			</div>
	</div>

<?php endif;?>

<script type="text/javascript">

	scriptJquery(document).ready(function() {
		var height = scriptJquery('.rich_content_body').height();
		<?php if($this->sesnews->cotinuereading) { ?>
		if(height > 400){
			scriptJquery('.sesnews_morebtn').css("display","block");
			scriptJquery('.rich_content_body').css("height","400");
			scriptJquery('.rich_content_body').css("overflow","hidden");
		}
		<?php } ?>
		scriptJquery('.rich_content_body').css("visibility","visible");
  });

  scriptJquery('.core_main_sesnews').parent().addClass('active');
  scriptJquery('.sesnews_comment').click(function() {
    scriptJquery('.comments_options').find('a').eq(0).trigger('click');
    scriptJquery('#adv_comment_subject_btn_<?php echo $this->sesnews->news_id; ?>').trigger('click');
  });
	
	function tagAction(tag_id){
		window.location.href = '<?php echo $this->url(array("action"=>"browse"),"sesnews_general",true); ?>'+'?tag_id='+tag_id;
	}
	var logincheck = '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.login.continuereading', 1); ?>';
	
	var viwerId = <?php echo $this->viewer_id ?>;
	function continuereading(){
		
		if(logincheck>0 && !viwerId){
			window.location.href = en4.core.baseUrl +'login';
		}else{
			scriptJquery('.rich_content_body').css('height', 'auto');
			scriptJquery('.sesnews_morebtn').hide();
		}
	}
</script>
