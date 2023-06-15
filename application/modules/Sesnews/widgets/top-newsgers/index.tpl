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
  <?php if($this->widgetIdentity):?>
    <?php $randonnumber = $this->widgetIdentity;?> 
  <?php else:?>
    <?php $randonnumber = $this->identity;?>
  <?php endif;?>
  <div class="sesnews_top_newser_list" style="position:relative;">
    <?php if($this->view_type == 'Vertical'):?>
      <ul id="widget_sesnews_<?php echo $randonnumber; ?>" class="sesbasic_clearfix sesbasic_bxs">
        <div class="sesbasic_loading_cont_overlay" id="sesnews_widget_overlay_<?php echo $randonnumber; ?>"></div>
        <?php foreach( $this->paginator as $newsger ): ?>
          <?php $item = Engine_Api::_()->getItem('user',$newsger->user_id);?>
          <li class="sesnewser_top_sidebar_list">
              <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon'), array('class' => 'sesnews_top_news_thumb')) ?>
            <div class="sesnews_top_news_info">
              <?php if(isset($this->ownernameActive)):?>
                <div class="sesnews_top_news_title">
                  <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
                </div>
              <?php endif;?>
              <?php if(isset($this->countActive)):?>
                <div class="sesnews_top_news_list_stats">
                  <?php echo $this->translate('Total News:');?>
                  <b><?php echo $newsger->news_count; ?></b>
                </div>
              <?php endif;?>
            </div>
          </li>
        <?php endforeach; ?>
        <?php if(isset($this->widgetName)){ ?>
          <div class="sidebar_privew_next_btns">
            <div class="sidebar_previous_btn">
              <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
                'id' => "widget_previous_".$randonnumber,
                'onclick' => "widget_previous_$randonnumber()",
                'class' => 'buttonlink previous_icon'
              )); ?>
            </div>
            <div class="sidebar_next_btns">
              <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
                'id' => "widget_next_".$randonnumber,
                'onclick' => "widget_next_$randonnumber()",
                'class' => 'buttonlink_right next_icon'
              )); ?>
            </div>
          </div>
        <?php } ?>
      </ul>
    <?php else:?>
      <ul id="widget_sesnews_<?php echo $randonnumber; ?>" class="row sesbasic_clearfix sesbasic_bxs">
        <div class="sesbasic_loading_cont_overlay" id="sesnews_widget_overlay_<?php echo $randonnumber; ?>"></div>
        <?php foreach( $this->paginator as $newsger ): ?>
          <?php $item = Engine_Api::_()->getItem('user',$newsger->user_id);?>
          <li class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
          <div class="sesnewser_top_gird_list">
          <div class="sesnewser_top_gird_thumb_news" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;">
            <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'sesnews_top_news_thumb')) ?>
            <?php if(isset($this->ownernameActive) || isset($this->countActive)):?>
              <div class="sesnews_top_news_info">
                <?php if(isset($this->ownernameActive)):?>
                  <div class="sesnews_top_news_title">
                    <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
                  </div>
                <?php endif;?>
                <?php if(isset($this->countActive)):?>
                  <div class="sesnews_top_news_list_stats">
                    <?php echo $this->translate('Total News:');?>
                    <b><?php echo $newsger->news_count; ?></b>
                  </div>
              <?php endif;?>
              </div>
            <?php endif;?>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
        <?php if(isset($this->widgetName)){ ?>
          <div class="sidebar_privew_next_btns">
            <div class="sidebar_previous_btn">
              <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
                'id' => "widget_previous_".$randonnumber,
                'onclick' => "widget_previous_$randonnumber()",
                'class' => 'buttonlink previous_icon'
              )); ?>
            </div>
            <div class="sidebar_next_btns">
              <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
                'id' => "widget_next_".$randonnumber,
                'onclick' => "widget_next_$randonnumber()",
                'class' => 'buttonlink_right next_icon'
              )); ?>
            </div>
          </div>
        <?php } ?>
      </ul>
    <?php endif;?>
  </div>
  <?php if(isset($this->widgetName)){ ?>
    <script type="application/javascript">
      var anchor_<?php echo $randonnumber ?> = scriptJquery('#widget_sesnews_<?php echo $randonnumber; ?>').parent();
      function showHideBtn<?php echo $randonnumber ?> (){
        scriptJquery('#widget_previous_<?php echo $randonnumber; ?>').parent().css('display','<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>');
        scriptJquery('#widget_next_<?php echo $randonnumber; ?>').parent().css('display','<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>');	
      }
      showHideBtn<?php echo $randonnumber ?> ();
      function widget_previous_<?php echo $randonnumber; ?>(){
        scriptJquery('#sesnews_widget_overlay_<?php echo $randonnumber; ?>').show();
        scriptJquery.ajax({
          dataType: 'html',
          url : en4.core.baseUrl + 'widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
          data : {
            format : 'html',
            is_ajax: 1,
            params :'<?php echo json_encode($this->params); ?>', 
            page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
          },
          success: function(responseHTML) {
            anchor_<?php echo $randonnumber ?>.html(responseHTML);
            scriptJquery('#sesnews_widget_overlay_<?php echo $randonnumber; ?>').hide();
            showHideBtn<?php echo $randonnumber ?> ();
          }
        });
      };
  
      function widget_next_<?php echo $randonnumber; ?>(){
        scriptJquery('#sesnews_widget_overlay_<?php echo $randonnumber; ?>').show();
        scriptJquery.ajax({
          dataType: 'html',
          url : en4.core.baseUrl + 'widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
          data : {
            format : 'html',
            is_ajax: 1,
            params :'<?php echo json_encode($this->params); ?>' , 
            page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
          },
          success: function(responseHTML) {
            anchor_<?php echo $randonnumber ?>.html(responseHTML);
            scriptJquery('#sesnews_widget_overlay_<?php echo $randonnumber; ?>').hide();
            showHideBtn<?php echo $randonnumber ?> ();
          }
        });
      };
    </script>
  <?php } ?>
