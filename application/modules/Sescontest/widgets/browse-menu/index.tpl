<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?> 
<div class="headline sescontest_browse_menu">
    <div class="tabs">
  <h2>
    <?php if(!empty($this->params['title'])): ?>
      <?php echo $this->translate($this->params['title']); ?>
    <?php else: ?>
      <?php echo $this->translate('Contests'); ?>
    <?php endif; ?>
  </h2>
  <?php $countMenu = 0; ?>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) > 0 ): ?>
      <ul class="navigation">
	  <?php foreach( $this->navigation as $navigationMenu ): ?>
	    <?php if( $countMenu < $this->max ): ?>
	      <li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?>>
	      <?php if ($navigationMenu->action): ?>
                <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array('action' => $navigationMenu->action), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
              <?php else : ?>
                <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
              <?php endif; ?>
	      </li>
	    <?php else:?>
	      <?php break;?>
	    <?php endif;?>
	    <?php $countMenu++;?>
	  <?php endforeach; ?>
	<?php if (engine_count($this->navigation) > $this->max):?>
	  <?php $countMenu = 0; ?>
	    <li class="sesbasic_browse_nav_tab_closed sesbasic_browse_nav_pulldown" onclick="sescontestmoreTabSwitch($(this));">
	      <a href="javascript:void(0);"><?php echo $this->translate('More +') ?><span></span></a>
	      <div class="tab_pulldown_contents_wrapper sesbasic_bxs">
          <div class="tab_pulldown_contents">
            <ul>
              <?php foreach( $this->navigation as  $navigationMenu ): ?>
                <?php if ($countMenu >= $this->max): ?>
                <?php $urlNavigation = empty($navigationMenu->uri) ? $this->url(array('action' => $navigationMenu->action), $navigationMenu->route, true) : $navigationMenu->uri ?>
           <?php $http_https = isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; ?>
              <li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?> <?php if ($urlNavigation == "$http_https$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"): ?><?php echo "class='active'";?><?php endif; ?>  >
            <?php if ($navigationMenu->action): ?>
              <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo $urlNavigation ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
            <?php else : ?>
              <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
            <?php endif; ?>
            </li>
                <?php endif;?>
                <?php $countMenu++;?>
              <?php endforeach; ?>
            </ul>
          </div>
	      </div>
	    </li>
	<?php endif;?>
      </ul>
    </div>
	<?php if($this->createButton && $this->createPrivacy) { ?>
	  <div class="sescontest_create_right_btn"><a <?php if(!empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.open.smoothbox', 0))) { ?> class="sessmoothbox" <?php } ?> href="<?php echo $this->url(array('action' => 'create'), 'sescontest_general', true); ?>"><?php echo $this->translate("Create New Contest"); ?></a></div>
	<?php } ?>
  <?php endif; ?>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    var sescontestmoreTabSwitch = window.sescontestmoreTabSwitch = function(el) {
      el.toggleClass('sesbasic_browse_nav_tab_open');
      el.toggleClass('sesbasic_browse_nav_tab_closed');
    }
  });
  <?php if($this->popup && !Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)){ ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
    <?php $this->headScript()->appendFile('externals/tinymce/tinymce.min.js'); ?>
    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/jquery.timepicker.css'); ?>
    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/jquery.timepicker.js'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
    scriptJquery('.sescontest_main_create').addClass('sessmoothbox');
  <?php } ?>
</script>
