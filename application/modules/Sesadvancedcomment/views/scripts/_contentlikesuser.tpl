<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _contentlikesuser.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl'; ?>
<?php
$isPageSubject = !empty($this->isPageSubject) ? $this->isPageSubject : $this->viewer();
foreach($this->users as $user){ 
        if($likes->getType() != "user")
        $user = Engine_Api::_()->getItem($likes->poster_type,$likes->poster_id);
      else
        $user = $likes;
     if(!$user)
      continue;
?>
      <li class="_user">
        <?php if($this->checkbox){ ?>
        <div>
          <input class="sesadvcheckbox" type="checkbox" name="users[]" value="<?php echo $user->getIdentity(); ?>">
        </div>
       <?php } ?>
        <div class="_userphoto">
          <span>
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon', $user->getTitle()), array()) ?>
              <i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($likes->type);?>);"></i>
          </span>
        </div>
        <div class="_userinfo">
          <div class="_username">
            <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </div>
          <div class="_usermutual sesbasic_text_light">
            <?php if($user->getType() == 'user' && ($this->viewer()->getIdentity() && !$this->viewer()->isSelf($user)) && $mcount =  Engine_Api::_()->sesadvancedcomment()->getMutualFriendCount($user, $this->viewer())){ ?>
              <?php echo $this->translate(array('%s mutual friend', '%s mutual friends',  $mcount), $this->locale()->toNumber( $mcount))?>
         <?php } ?>
          </div>
        </div>
         <?php if(!$this->checkbox){ ?>
        <div class="_userlink">
           <?php if($this->viewer()->getIdentity() != 0):?>
    <?php echo '<span>'.$this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $user)).'</span>'; ?>
        <?php endif;?>
        </div>
       <?php } ?>
      </li>
   <?php } ?>
    <?php  $randonNumber = $this->randonNumber;?>
   <script type="application/javascript">
	var page<?php echo $randonNumber; ?> = <?php echo $this->page + 1; ?>;
	function viewMoreHide_<?php echo $randonNumber; ?>() {
			if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
			document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo  ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
		}
   en4.core.runonce.add(function() {
    viewMoreHide_<?php echo $randonNumber; ?>();
   });
	 function viewMore_<?php echo $randonNumber; ?> () {
			scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
			scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
			
			requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
				method: 'post',
				'url': en4.core.baseUrl + "sesadvancedcomment/ajax/comment-likes/",
				'data': {
        format: 'html',
        id: '<?php echo $this->resource_id; ?>',
        resource_type: '<?php echo $this->resource_type; ?>',
        comment_id: '<?php echo $this->comment_id; ?>',
        page: page<?php echo $randonNumber; ?>,    
        is_ajax_content : 1,
            },
            success: function( responseHTML) {
            scriptJquery('#like_contnent').append(responseHTML);
            scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
            viewMoreHide_<?php echo $randonNumber; ?>();
            }
          });
          return false;
		}
</script>
