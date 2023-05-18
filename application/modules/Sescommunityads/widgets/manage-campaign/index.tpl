<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php 
$randonNumber = $this->identity;
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<?php if($this->paginator->getTotalItemCount() > 0){ ?>
  <?php if(empty($this->is_ajax)){ ?>
  	<div class="sescommunityads_campaign_page sesbasic_clearfix sesbasic_bxs">
  <?php } ?>
  <?php if(empty($this->is_ajax)){ ?>
  <form method="post" id="sescommunityads_campaign_frm" onSubmit="return multiDelete()">
  	<div class="sescmads_campaign_table">
      <table>
        <thead>
          <tr>
            <th class="_select"><input type="checkbox" name="camapign_delete" onClick="selectAll();"></th>
            <th class="_name"><?php echo $this->translate('Campaign Name'); ?></th>
            <th class="centerT"><?php echo $this->translate('Ads'); ?></th>
            <th class="centerT"><?php echo $this->translate('SESCOMMViews'); ?></th>
            <th class="centerT"><?php echo $this->translate('SESCOMMClicks'); ?></th>
            <th class="centerT"><?php echo $this->translate('CTR (%)'); ?></th>
            <th class="_option"><?php echo $this->translate('SESCOMMOptions'); ?></th>
          </tr>
        </thead>
        <tbody id="sescommunityads_campaigns">
    <?php } ?>
        <?php foreach($this->paginator as $campaign){ ?>
          <tr id="sescommunityads_tr_<?php echo $campaign->getIdentity(); ?>">
            <td class="_select"><input type="checkbox" name="camapign_delete_<?php echo $campaign->getIdentity(); ?>"></td>
            <td class="_name" id="title_sescomm_<?php echo $campaign->getIdentity(); ?>"><a href="<?php echo $this->url(array('action'=>'manageads','campaign_id'=>$campaign->getIdentity()),'sescommunityads_general',true); ?>"><?php echo $campaign->getTitle(); ?></a></td>
            <td class="centerT"><?php echo $campaign->count();  ?></td>
            <td class="centerT"><?php echo $campaign->views_count();  ?></td>
            <td class="centerT"><?php echo $campaign->click_count();  ?></td>
            <td class="centerT"><?php echo $campaign->views_count() > 0 ? $campaign->ctr(): number_format("0", 4);  ?></td>
            <td class="_options">
              <a href="<?php echo $this->url(array('action'=>'manageads','campaign_id'=>$campaign->getIdentity()),'sescommunityads_general',true); ?>"><?php echo $this->translate('Manage'); ?></a>
              <?php if($this->can_edit){ ?>
              |
              <a class="openSmoothbox" href="<?php echo $this->url(array('action'=>'edit-campaign','campaign_id'=>$campaign->getIdentity()),'sescommunityads_general',true); ?>"><?php echo $this->translate('SESCOMMEdit'); ?></a>
               <?php } ?>
               <?php if($this->can_delete){ ?>
              |
              <a class="openSmoothbox" href="<?php echo $this->url(array('action'=>'delete-campaign','campaign_id'=>$campaign->getIdentity()),'sescommunityads_general',true); ?>"><?php echo $this->translate('SESCOMMDelete'); ?></a>
               <?php } ?>
            </td>
          </tr>        
        <?php } ?>
        <?php if($this->loadType == 'pagging'): ?>
        <tr>
          <td colspan="7">
          <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescommunityads"),array('identityWidget'=>$this->identity)); ?>
          </td>
       </tr>
       <?php endif;?>
     <?php if(empty($this->is_ajax)){ ?>
        </tbody>
      </table>
    </div>
    <div class="_buttons">
      <button type="submit" class="sescomm_campaign_del"><?php echo $this->translate('Delete Selected'); ?></button>
    </div>
  </form>
  <?php } ?>
  
<?php }else{ ?>
  <div class="tip">
    <span><?php echo $this->translate('No campaign created by you yet.'); ?></span>
  </div>
<?php } ?>
 
 <?php if(empty($this->is_ajax)){ ?>
    <?php if($this->loadType != 'pagging'): ?>
    <div class="sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $this->identity;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
    </div>  
    <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
  <?php endif;?>
<?php if(empty($this->is_ajax)){ ?>
  </div>
<?php } ?>  
<script type="application/javascript">
function multiDelete(){
  return confirm("<?php echo $this->translate('Are you sure you want to delete selected Ads?');?>");
}
  function selectAll() {
    var i;
    var multidelete_form = document.getElementById('sescommunityads_campaign_frm');
    var inputs = multidelete_form.elements;
    for (i = 1; i < inputs.length; i++) {
      if (!inputs[i].disabled) {
        inputs[i].checked = inputs[0].checked;
      }
    }
  }
  <?php if($this->loadType == 'auto_load'){ ?>    
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
				var containerId = '#sescommunityads_campaign_frm';
				if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
					var hT = scriptJquery('#view_more_<?php echo $randonNumber; ?>').offset().top,
					hH = scriptJquery('#view_more_<?php echo $randonNumber; ?>').outerHeight(),
					wH = scriptJquery(window).height(),
					wS = scriptJquery(this).scrollTop();
					if ((wS + 30) > (hT + hH - wH) && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block') {
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
					}
				}      
      });
    });
  <?php } ?>
</script>
 <?php } ?>

<script type="application/javascript">
<?php if($this->loadType != 'pagging'){ ?>
    viewMoreHide_<?php echo $randonNumber; ?>();	
    function viewMoreHide_<?php echo $randonNumber; ?>() {
      if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
       scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/name/manage-campaign",
        'data': {
          format: 'html',
          page: <?php echo $this->page + 1; ?>,    
          is_ajax : 1,
          identity:'<?php echo isset($this->identity) ? $this->identity : "" ?>',
        },
        success: function(responseHTML) {
          scriptJquery('#sescommunityads_campaigns').append(responseHTML);
          scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide();
        }
      });
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
       (scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/name/manage-campaign",
        'data': {
          format: 'html',
          page: pageNum,    
          is_ajax : 1,
          identity:'<?php echo isset($this->identity) ? $this->identity : "" ?>',
        },
        success: function(responseHTML) {
          scriptJquery('.sesbasic_loading_cont_overlay').hide();
          document.getElementById('sescommunityads_campaigns').innerHTML =  responseHTML;          
        }
      }));
      return false;
    }
  <?php } ?>
</script>
