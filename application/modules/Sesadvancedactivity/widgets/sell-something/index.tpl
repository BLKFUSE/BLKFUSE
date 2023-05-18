<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css'); ?>

<script type="text/javascript">
  function loadMoreSell() {
    if (document.getElementById('view_more_sell'))
      document.getElementById('view_more_sell').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";

    if(document.getElementById('view_more_sell'))
      document.getElementById('view_more_sell').style.display = 'none';
    
    if(document.getElementById('loading_image_sell'))
     document.getElementById('loading_image_sell').style.display = '';

    (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sesadvancedactivity/name/sell-something',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1,
        params: '<?php echo json_encode($this->all_params); ?>',
        
      },
      success : function(responseHTML) {
        scriptJquery('#sell_results').append(responseHTML);
        if(document.getElementById('view_more_sell'))
          scriptJquery('#view_more_sell').remove();
        
        if(document.getElementById('loading_image_sell'))
         scriptJquery('#loading_image_sell').remove();
        if(document.getElementById('loadmore_list_sell'))
         scriptJquery('#loadmore_list_sell').remove();
      }
    }));
    return false;
  }
</script>

<?php if($this->paginator->getTotalItemCount() > 0) { ?>
  <?php if (empty($this->viewmore)): ?>
  <div class="sesact_sell_main" >
    <div class="sesact_sell_inner" id= "sell_results">
  <?php endif; ?>
      <?php foreach($this->paginator as $item) { ?> 
        <?php $action = Engine_Api::_()->getItem('sesadvancedactivity_action', $item->action_id);
        $attachmentItems = $action->getAttachments();
        $actionAttachment = engine_count($attachmentItems) ? $attachmentItems : array();
        list($attachment) = $actionAttachment;
        $photo = Engine_Api::_()->getItem('album_photo', $attachment->item->photo_id);
        ?>
        <div class="sesact_sell_box">
          <a class="sessmoothbox sesadvancedactivity_buysell" href="javascript:;" data-url="<?php echo 'sesadvancedactivity/ajax/feed-buy-sell/action_id/'.$action->action_id.'/photo_id/'.$attachment->item->photo_id.'/main_action/'.$action->action_id; ?>">
            <div class="_img">
              <?php if($photo) { ?>
                <img src="<?php echo $photo->getPhotoUrl(); ?>" />
              <?php } else { ?>
                <img src="application/modules/Sesadvancedactivity/externals/images/default.png" />
              <?php } ?>
            </div>
            <div class="sesact_feed_item_buysell_title"><?php echo $item->title; ?></div>
            <div class="sesact_feed_item_buysell_price"><?php echo Engine_Api::_()->sesadvancedactivity()->getCurrencySymbol().$item->price; ?></div>
            <?php $locationBuySell = Engine_Api::_()->getDbTable('locations','sesbasic')->getLocationData('sesadvancedactivity_buysell',$item->getIdentity()) ?>
            <?php if($locationBuySell){ ?>
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
                <div class="sesact_feed_item_buysell_location"><span><a href="<?php echo $this->url(array('resource_id' => $item->getIdentity(),'resource_type'=>$item->getType(),'action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" onClick="openSmoothBoxInUrl(this.href);return false;"><?php echo $locationBuySell->venue; ?></a></span></div>
              <?php } else { ?>
                <div class="sesact_feed_item_buysell_location"><span><?php echo $locationBuySell->venue; ?></span></div>
              <?php } ?>
            <?php } ?>
          </a>
        </div>
      <?php } ?>
      
      <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
        <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
          <div class="clr" id="loadmore_list_sell"></div>
          <div class="sesbasic_view_more_sell sesbasic_load_btn" id="view_more_sell" onclick="loadMoreSell();" style="display: block;">
            <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
          </div>
          <div class="sesbasic_view_more_sell_loading sesbasic_load_btn" id="loading_image_sell" style="display: none;">
            <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
          </div>
        <?php endif; ?>
      <?php endif; ?>
<?php if (empty($this->viewmore)): ?>
    </div>
  </div>
<?php endif; ?>
<?php } else { ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('Nobody has posted sell something activity.');?>
    </span>
  </div>
<?php } ?>

