<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _sitead-help-navigation.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="seaocore_db_tabs">
	<ul>
	 <?php $count = 0; $contact_team = 0; ?>
   <?php foreach ($this->pageObject as $module):
					if( !empty($module['contect_team']) ) {
						$contact_team = $module['infopage_id'];
					}
					if( !empty($this->page_id) ) { // Condition run for when click on tabed
						$packages_url = $this->url(array(), 'sitead_listpackage', true);
						if(!empty($module['package'])){ echo '<li><a href="'.$packages_url.'">'. $this->translate('Ad Packages'). '</a>'; }else {
						?><li><a href="<?php echo $this->url(array('page_id' => $module['infopage_id']), 'sitead_help', true);//echo $this->url( array('module' => 'sitead', 'controller' => 'display', 'action' => 'help-and-learnmore', 'page_id' => $module['infopage_id']), 'default', true ); ?>" class="<?php if( $this->page_id == $module['infopage_id'] ){ echo 'selected'; } ?>" id="help_learnmore_<?php echo $module['infopage_id'];?>"><?php  echo $this->translate($module['title']); ?></a></li><?php }
					}else { // Condition when come first time [ not clicked on tabed ]
					$packages_url = $this->url(array(), 'sitead_listpackage', true); 
				if(!empty($module['package'])){ echo '<li><a href="'.$packages_url.'">'. $this->translate('Ad Packages'). '</a>'; }else { ?>
				<li><a href="<?php echo $this->url(array('page_id' => $module['infopage_id']), 'sitead_help', true);//echo $this->url(array('module' => 'sitead', 'controller' => 'display', 'action' => 'help-and-learnmore', 'page_id' => $module['infopage_id']), 'default', true); ?>" class="<?php if( !empty($this->display_faq) ){  if($module['faq'] == $this->display_faq)  echo 'selected'; else '' ;  }else { if ( empty($count)  ) echo 'selected'; else '' ; }?>" id="help_learnmore_<?php echo $module['infopage_id'];?>"><?php  echo $this->translate($module['title']); ?></a></li>
		<?php } 
				}
			$count++;
			endforeach;
		?>
	</ul>
  
  <div class="clr">
    <?php
      $isModEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitecoupon');
      if(!empty($isModEnabled))
        echo $this->content()->renderWidget("sitecoupon.show-coupons", array('print' => '0', 'interval' =>'1', 'usagesshow' => '1', 'package_type' => 'package', 'title' => 'Discount Coupons for Advertising'));  
    ?>
  </div>
</div>