<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: delete.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/sticky-sidebar.js'); ?>
<div class="user_setting_menu">
  <ul>
    <li class="user_menu_heading"><?php echo $this->translate("Edit My Profile"); ?></li>
    <?php foreach( $this->user_edit_navigation as $link ): ?>
      <li class="<?php echo $link->get('active') ? 'active' : '' ?>">
        <?php echo $this->htmlLink($link->getHref(), $this->translate($link->getLabel()), array('class' => 'buttonlink' . ( $link->getClass() ? ' ' . $link->getClass() : '' ) . ' ' . (!empty($link->get('icon')) ? $link->get('icon') : ''),'target' => $link->get('target'))) ?>
      </li>
    <?php endforeach; ?>
   <li class="user_menu_heading"> <?php echo $this->translate("Account Settings"); ?><li>
    <?php foreach( $this->navigation as $link ): ?>
      <li class="<?php echo $link->get('active') ? 'active' : '' ?>">
        <?php echo $this->htmlLink($link->getHref(), $this->translate($link->getLabel()), array('class' => 'buttonlink' . ( $link->getClass() ? ' ' . $link->getClass() : '' ) . ' ' . (!empty($link->get('icon')) ? $link->get('icon') : ''),'target' => $link->get('target'))) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<script type="application/javascript">
  	scriptJquery(document).ready(function(){
		var htmlElement = scriptJquery("#global_wrapper");
		htmlElement.addClass('user_settings_page');
	});
</script>

<script>
  if (matchMedia('only screen and (min-width: 768px)').matches) { 
  scriptJquery(document).ready(function() {
    var sidebar = new StickySidebar('.layout_left', {
        containerSelector: '.user_setting_main_page_main',
        innerWrapperSelector: '.theiaStickySidebar',
        topSpacing: 20,
        bottomSpacing: 20
      });
  });
}
</script>