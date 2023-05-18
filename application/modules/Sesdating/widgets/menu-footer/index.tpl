<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<div class="sesdating_footer_main sesbasic_bxs clearfix">
<div class="footer_links">
  	<?php  if($this->quicklinksenable) { ?>
      <div class="footer_main_links clearfix">
        <ul class="sesbasic_clearfix">
          <?php foreach( $this->quickLinksMenu as $item ): 
            $attribs = array_diff_key(array_filter($item->toArray()), array_flip(array(
              'reset_params', 'route', 'module', 'controller', 'action', 'type',
              'visible', 'label', 'href'
            )));
            ?>
            <li>
              <?php echo $this->htmlLink($item->getHref(), $this->translate($item->getLabel()), $attribs) ?>
            </li>
            <?php endforeach; ?>
        </ul>
      </div>
    <?php } ?>
    
  </div>
	<div class="footer_top clearfix">
 <!--    <div class="footer_logo">
      <?php echo $this->content()->renderWidget('sesdating.menu-logo',array('logofooter'=>$this->footerlogo)); ?>
    </div>-->

     <?php  if($this->socialenable) { ?>
        <div class="footer_social sesbasic_clearfix">
          <?php if(!empty($this->facebookurl)):?>
            <a href="<?php echo $this->facebookurl;?>" target="_blank">
              <i class="fab fa-facebook-f"></i>
            </a>
          <?php endif;?>
          <?php if(!empty($this->googleplusurl)):?>
            <a href="<?php echo $this->googleplusurl;?>" target="_blank">
              <i class="fab fa-google-plus-g"></i>
            </a>
          <?php endif;?>
          <?php if(!empty($this->twitterurl)):?>
            <a href="<?php echo $this->twitterurl;?>" target="_blank">
              <i class="fab fa-twitter"></i>
            </a>
          <?php endif;?>
          <?php if(!empty($this->pinteresturl)):?>
            <a href="<?php echo $this->pinteresturl;?>" target="_blank">
              <i class="fab fa-pinterest-p"></i>
            </a>
          <?php endif;?>
        </div>
      <?php } ?>
     <?php if (is_countable($this->languageNameList) && 1 !== engine_count($this->languageNameList)): ?>
      <div class="footer_lang">
        <form id="footer_language_<?php echo $this->identity; ?>" method="post" action="<?php echo $this->url(array('controller' => 'utility', 'action' => 'locale'), 'default', true) ?>" style="display:inline-block">
          <?php $selectedLanguage = $this->translate()->getLocale() ?>
          <?php echo $this->formSelect('language', $selectedLanguage, array('onchange' => "setLanguage()"), $this->languageNameList) ?>
          <?php echo $this->formHidden('return', $this->url()) ?>
        </form>
      </div>
    <?php endif; ?>
  </div>
	
  <?php  if($this->quicklinksenable) { ?>
    <div class="clearfix footer_column" style="display:none;">
      <div class="footer_column_heading">
        <?php echo $this->translate($this->quicklinksheading);?>
      </div>
      
    </div>
    <?php } ?>
    <?php  if($this->helpenable) { ?>
    <div class="clearfix footer_column" style="display:none;">
      <div class="footer_column_heading">
        <?php echo $this->translate($this->helpheading);?>
      </div>
      
    </div>
  <?php } ?>

</div>
<?php if	( !empty($this->affiliateCode) ): ?>
  <div class="footer_affiliate_banner">
    <?php 
      echo $this->translate('Powered by %1$s', 
        $this->htmlLink('http://www.socialengine.com/?source=v4&aff=' . urlencode($this->affiliateCode), 
        $this->translate('SocialEngine Community Software'),
        array('target' => '_blank')))
    ?>
  </div>
<?php endif; ?>
<div class="footer_help_links clearfix">
       <?php  if($this->helpenable) { ?>
        <ul class="sesbasic_clearfix">
          <?php foreach( $this->navigation as $item ): 
            $attribs = array_diff_key(array_filter($item->toArray()), array_flip(array(
              'reset_params', 'route', 'module', 'controller', 'action', 'type',
              'visible', 'label', 'href'
            )));
            ?>
            <li>
              <?php echo $this->htmlLink($item->getHref(), $this->translate($item->getLabel()), $attribs) ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php } ?>
      <!--  <div class="footer_copy sesbasic_bxs">
          <?php echo $this->translate('Copyright &copy;%s', date('Y')) ?>
        </div>-->
</div>
<script>
  function setLanguage() {
    scriptJquery('#footer_language_<?php echo $this->identity; ?>').submit();
  }
</script>
