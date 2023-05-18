<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _promotePage.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="sescommunityads_create_campaign sesbasic_bxs">
  <div class="sescommunityads_popup_header"><span><?php echo $this->translate('Promote Page'); ?></span></div>
    <div class="sescommunityads_select_part">
     <div class="sescommunityads_select_content">
       <?php 
          $table = Engine_Api::_()->getDbTable('pages','sespage');
          $pages = $table->getPageSelect(array('user_id'=>$this->viewer()->getIdentity(),'fetchAll'=>true));              
        ?>
       <ul>
          <?php if(engine_count($pages)){ ?>
            <?php foreach($pages as $page){ ?>
              <li>
                <div>
                  <div><a href="<?php echo $page->getHref(); ?>" target="_blank"><img src="<?php echo $page->getPhotoUrl(); ?>" style="height:100px; width:100px;" /></a></div>
                  <span><a href="<?php echo $page->getHref(); ?>" target="_blank"><?php  echo $page->getTitle(); ?></a></span>
                  <span><button data-rel="<?php echo $page->getIdentity(); ?>" class="sescommunityads_select_page"><?php echo $this->translate("Select Page"); ?></button></span>
                </div>
              </li>
            <?php } ?>
          <?php }else{ ?>
            <div>
              <span class="tip"><?php echo $this->translate("You don't have created any page yet."); ?></span>
            </div>
          <?php } ?>
       </ul>
       
     </div>
     <div class="sescommmunity_popup_footer sesbasic_bg sesbasic_clearfix">
       <div class="_left"> <a href="javascript:;" class="sesbasic_button" onclick="sescomm_back_btn(2);"><?php echo $this->translate('Back'); ?></a> </div>
       <div class="_right"> </div>
     </div> 
   </div>
</div>
