<?php include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/dismiss_message.tpl';?>
<h2 class="page_heading"><?php echo $this->translate('SocialNetworking.Solutions (SNS) Basic Required Plugin'); ?></h2>
<?php if (engine_count($this->navigation)): ?>
  <div class='tabs'>
    <?php
    // Render the menu
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<div class='clear sesbasic-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
       <ul class="navigation">
      	<?php foreach( $this->subNavigation as $navigationMenu ):?>
	      <li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?>>
	      <?php if ($navigationMenu->action): ?>
                <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array('action' => $navigationMenu->action,'controller'=>$navigationMenu->controller,'module'=>$navigationMenu->module), $navigationMenu->route, true) : $navigationMenu->uri; echo "/index/modulename/".str_replace("menu_sesbasic_admin_tooltipsettings sesbasic_admin_main_","",$navigationMenu->class);  ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
              <?php else : ?>
                <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
              <?php endif; ?>
	      </li>
	  <?php endforeach; ?>
   	 </ul>
    </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
      <div class='settings sesbasic_admin_form'>
        <?php echo $this->form->render($this); ?>
      </div>
    </div>
  </div>
</div>
