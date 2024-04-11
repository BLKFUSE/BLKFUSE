<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egames/externals/styles/styles.css'); ?>
<div class="headline">
  <h2 class="page_heading">
    <?php if(!empty($this->params['title'])): ?>
      <?php echo $this->translate($this->params['title']); ?>
    <?php else: ?>
      <?php echo $this->translate('Games'); ?>
    <?php endif; ?>
  </h2>
  <?php $countMenu = 0; ?>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) > 0 ): ?>
    <div class="tabs">
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
	    <li class="sesbasic_browse_nav_tab_closed sesbasic_browse_nav_pulldown">
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
  <?php endif; ?>
</div>
<script type="text/javascript">
  scriptJquery(document).on('click','.sesbasic_browse_nav_pulldown > a',function(){
    if(scriptJquery('.sesbasic_browse_nav_pulldown').hasClass('sesbasic_browse_nav_tab_open')){
      scriptJquery('.sesbasic_browse_nav_pulldown').removeClass('sesbasic_browse_nav_tab_open');
    }else{
      scriptJquery('.sesbasic_browse_nav_pulldown').removeClass('sesbasic_browse_nav_tab_open');
      scriptJquery('.sesbasic_browse_nav_pulldown').addClass('sesbasic_browse_nav_tab_open');
    }
      return false;
  });
  scriptJquery(document).click(function(){
    scriptJquery('.sesbasic_browse_nav_pulldown').removeClass('sesbasic_browse_nav_tab_open');
  });
</script>
