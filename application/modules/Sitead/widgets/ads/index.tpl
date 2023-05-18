<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if (!empty($this->loaded_by_ajax)) : ?>
<?php $content_id= $this->identity?$this->identity: ($this->widgetId?$this->widgetId:rand(1000000000, 9999999999))?>
  <div id="content_<?php echo $content_id ?>"></div>
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      en4.sitead.sendReq($("content_<?php echo $content_id ?>").getParent('.layout_sitead_ads'),'<?php echo $this->identity ?>','<?php echo $this->adboardPage ? 1 :0 ?>',<?php echo $this->params ? $this->jsonInline($this->params):"{}" ?>);
    });
  </script>
<?php endif; ?>
  <?php if(!$this->identity): $this->identity= rand(1000000000, 9999999999); endif;?>
<?php //if (!empty($this->showContent)): ?>

  <div class="cmad_ad_clm">
    <div>
      <?php if ($this->showHeader): ?>
        <?php if ($this->showType == 'all'): ?>
          <?php if(!$this->adboardPage): ?>
          <div class="head">
            <?php if (Engine_Api::_()->sitead()->enableCreateLink()) : ?>
              <?php echo '<a href="' . $this->url(array(), 'sitead_listpackage', true) . '" style="float:left;">' . $this->translate('Create an Ad') . '</a>'; ?>
            <?php endif; ?>
            <?php
            $is_show_adboard = Engine_Api::_()->getApi('settings', 'core')->getSetting('show.adboard', 1);
            if (!empty($is_show_adboard)):
              echo '<div style="float:right;"><a href="' . $this->url(array(), 'sitead_display', true) . '">' . $this->translate('More Ads') . '</a></div>';
            endif;
            ?>
          </div>
        <?php endif; ?>
        <?php else: ?>
          <div class="cmad_bloack_top">
            <b><?php echo $this->showType === 'sponsored' ? $this->translate('Sponsored') : $this->translate('Featured') ?></b>
            <?php if (!$this->adboardPage && Engine_Api::_()->sitead()->enableCreateLink()) : ?>
              <?php echo '<a href="' . $this->url(array(), 'sitead_listpackage', true) . '">' . $this->translate('Create an Ad') . '</a>'; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <div class="cmad_block_wrp">
        <?php
        include APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_adsDisplay.tpl';
        ?>
      </div>
      <?php if ($this->showType !== 'all'): ?>
        <?php if (!$this->adboardPage && Engine_Api::_()->getApi('settings', 'core')->getSetting('show.adboard', 1)): ?>
          <div class="cmaddis_more"><a href="<?php echo $this->url(array(), 'sitead_display', true) ?>"><?php echo $this->translate('More Ads') ?></a></div>
          <?php endif; ?>
        <?php endif; ?>
    </div>
  </div>
<?php //endif; ?>
