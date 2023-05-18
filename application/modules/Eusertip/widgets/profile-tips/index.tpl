<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<?php if(engine_count($this->enableTip)){ ?>
  <div class="eusertip_tips_main sesbasic_clearfix sesbasic_bxs">
  	<div class="eusertip_tips_main_header">
      <h2><?php echo $this->translate("Choose A Tip")?></h2>
      <p><?php echo $this->translate('Select a tip that suits you most to start creating pages on this website.');?></p>
    </div>
    <div class="eusertip_tips_table_container">
      <ul class="eusertip_tips_table">
        <?php $existing = 0;?>
      	<?php foreach($this->enableTip as $tip)	{ ?>
          <li class="eusertip_tips_table_item">
            <section>
                <div class="title_price sesbasic_clearfix">
                <h5><?php echo $this->translate($tip->title); ?></h5>
                <?php if(!$tip->isFree()){ ?>
                  <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($tip->price,'','',true); ?></span>
                <?php }else{ ?>
                  <span><?php echo $this->translate("FREE"); ?></span>
                <?php } ?>
              </div>
              <div class="select_packeges_button">
                <a class="eusertip_tips_create_btn sesbasic_animation" href="<?php echo $this->url(array('action' => 'makepayment', 'tip_id' => $tip->tip_id),'eusertip_general',true); ?>"><?php echo $this->translate('Select Tip');?></a>
              </div>
            </section>
          </li>
      	<?php } ?>
      </ul>
		</div>
  </div>
<?php } ?>
