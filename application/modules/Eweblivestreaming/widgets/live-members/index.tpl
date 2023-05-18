<?php

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eweblivestreaming/externals/styles/styles.css'); ?>

<div class="eweblivestreaming_live_members sesbasic_bxs">
	
	<?php // Grid View?>
	<ul class="eweblivestreaming_live_members_grid">
		<?php foreach($this->users as $user){ ?>
		<li class="eweblivestreaming_live_members_item">
			<article>
				<div class="eweblivestreaming_item_thumb">
					<a href="javascript:;" class="elivestreaming_data_a" data-hostid="<?php $user->max_elivehost_id ?>" data-user="<?php $user->user_id ?>" data-action="<?php $user->max_action_id ?>" data-story="<?php $user->max_story_id ?>">
						<span class="_img">
							<img src="<?php echo $user->getPhotoUrl() ?>" alt="<?php echo $user->getTitle() ?>" />
						</span>
						<?php if($this->live_icon){ ?>
						<span class="_label">
							<?php echo $this->translate("Live");?>
						</span>
						<?php } ?>
					</a>
				</div>
				<div class="eweblivestreaming_item_info">
					<div class="eweblivestreaming_item_name"><a href="<?php echo $user->getHref() ?>"><?php echo $user->getTitle() ?></a></div>
					<div class="eweblivestreaming_item_viewers sesbasic_text_light" style="display:none">7.3K viewers</div>
				</div>
			</article>
		</li>
		<?php } ?>
	</ul>

</div>

