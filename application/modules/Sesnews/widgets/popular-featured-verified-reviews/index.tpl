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

<?php if($this->widgetIdentity):?>
	<?php $randonnumber = $this->widgetIdentity;?>
<?php else:?>
	<?php $randonnumber = $this->identity;?>
<?php endif;?>

<ul class="sesnews_review_sidebar_block sesbasic_bxs sesbasic_clearfix"  id="widget_sesnews_<?php echo $randonnumber; ?>" style="position:relative;">
	<div class="sesbasic_loading_cont_overlay" id="sesnews_widget_overlay_<?php echo $randonnumber; ?>"></div>
  <?php include APPLICATION_PATH . '/application/modules/Sesnews/views/scripts/_reviewsidebarWidgetData.tpl'; ?>
</ul>

<?php if(isset($this->widgetName)){ ?>
  <div class="sidebar_privew_next_btns">
    <div class="sidebar_previous_btn">
      <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
        'id' => "widget_previous_".$randonnumber,
        'onclick' => "widget_previous_$randonnumber()",
        'class' => 'buttonlink previous_icon'
      )); ?>
    </div>
    <div class="Sidebar_next_btns">
      <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
        'id' => "widget_next_".$randonnumber,
        'onclick' => "widget_next_$randonnumber()",
        'class' => 'buttonlink_right next_icon'
      )); ?>
    </div>
  </div>
<?php } ?>
</ul>
<?php if(isset($this->widgetName)){ ?>
<script type="application/javascript">
 		var anchor_<?php echo $randonnumber ?> = scriptJquery('#widget_sesnews_<?php echo $randonnumber; ?>').parent();
    function showHideBtn<?php echo $randonnumber ?> (){
			scriptJquery('#widget_previous_<?php echo $randonnumber; ?>').parent().css('display','<?php echo ( $this->results->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>');
    	scriptJquery('#widget_next_<?php echo $randonnumber; ?>').parent().css('display','<?php echo ( $this->results->count() == $this->results->getCurrentPageNumber() ? 'none' : '' ) ?>');	
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
          page : <?php echo sprintf('%d', $this->results->getCurrentPageNumber() - 1) ?>
        },
				success: function(responseHTML) {
					anchor_<?php echo $randonnumber ?>.html(responseHTML);
					<?php if(isset($this->view_type) && $this->view_type == 'gridOutside'){ ?>
					scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({theme:"minimal-dark"});
				<?php } ?>
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
          page : <?php echo sprintf('%d', $this->results->getCurrentPageNumber() + 1) ?>
        },
				success: function(responseHTML) {
					anchor_<?php echo $randonnumber ?>.html(responseHTML);
					<?php if(isset($this->view_type) && $this->view_type == 'gridOutside'){ ?>
					scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({theme:"minimal-dark"});
				<?php } ?>
					scriptJquery('#sesnews_widget_overlay_<?php echo $randonnumber; ?>').hide();
					showHideBtn<?php echo $randonnumber ?> ();
				}
      });
		};

</script>
<?php } ?>
