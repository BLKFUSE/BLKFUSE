<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()
  ->appendFile($this->layout()->staticBaseUrl . '/application/modules/Siteshare/externals/scripts/core.js'); ?>
<div class="sharelinksblock">
  <a class='siteshare_smoothbox_lightbox_close' onclick="SmoothboxSEAO.close();" href="javascript:void(0);">
    <i class='fa fa-close'></i>
  </a>
  <div class="share_heading">
    <h3><?php echo $this->translate('Social Bookmarks') ?></h3>
    <p>
      <b class="siteshare_ss_pagetitle"><?php echo $this->contentTitle ? $this->translate($this->contentTitle) : ''; ?></b>
    </p>
  </div>
  <div class="siteshare_share_tab_content siteshare_share_tab_content_social_share sitesharebox ">
    <div >
      <ul class="navigation">
        <?php foreach( $this->navigation as $link ): ?>
          <li>
            <?php
            $label = '<span>'.$this->translate($link->getLabel()).'</span>';
            echo $this->htmlLink($link->getHref(), $label, array(
              'class' => ( $link->getClass() ? ' ' . $link->getClass() : '' ),
              'target' => $link->get('target'),
              'data-url' => $link->get('data-url'),
              'data-service' => $link->get('data-service'),
              'onclick' => 'en4.siteshare.socialService.clickHandler(this);'
            ))
            ?>
          </li>
      <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function(){
    $$('.menu_siteshare_social_link').each(function(el){
      var classes = el.get('class').split(' ').filter(function(string) {
        return string.test('siteshare_social_link_');
      });
      el.getParent('li').addClass(classes[0]+'_li');
    });
  });

</script>