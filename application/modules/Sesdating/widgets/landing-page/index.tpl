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
<?php 
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/lp.css');
  ?>
  <link href="//fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
<style>
.sesdating_landing_page{
   background-image:url(<?php echo Engine_Api::_()->sesdating()->getFileUrl($this->backgroundimage) ?>);
}
</style>
<div class="sesdating_lp">
  <div class="sesdating_lp_bg">
   <div class="sesdating_lp_inner">
     <div class="sesdating_lp_form">
       <div class="sesdating_lp_form_inner">
          <div class="sesdating_lp_logo">
            <?php echo $this->content()->renderWidget("sesdating.menu-logo")?>
          </div>
        <div class="sesdating_login">
        <h4><?php echo $this->translate("Find Dates Now!"); ?></h4>
        <div class="sesdating_home_search">
          <div class="form-group">
            <label><?php echo $this->translate("Name"); ?></label>
            <input type="text" name="displayname" id="displayname" value="">
          </div>
          <div class="form-group">
             <label><?php echo $this->translate("Gender"); ?></label>
             <select name="gender" id="gender" alias="gender">
                <option value=""></option>
                <option value="2"><?php echo $this->translate("Male"); ?></option>
                <option value="3"><?php echo $this->translate("Female"); ?></option>
             </select>
          </div>
          <?php for($i=13;$i<=100;$i++) { ?>
            <?php $option .= '<option value="'.$i.'">'.$i.'</option>'; ?>
          <?php } ?>
          <div class="form-group">
             <label><?php echo $this->translate("Age"); ?></label>
             <select name="min" id="min" alias="min">
                <option value=""></option>
                <?php echo $option; ?>
             </select>
             <span><?php echo $this->translate("to"); ?></span>
             <select name="max" id="max" alias="max">
                <option value=""></option>
                <?php echo $option; ?>
             </select>
          </div>
          <div class="form-group">
            <button onclick="searchMembers();" type="submit"><?php echo $this->translate("Next"); ?></button>
          </div>
        </div>
        <div class="sesdatinglp_login_signup">
          <a href="login"><?php echo $this->translate("Login"); ?></a>
          <a href="signup"><?php echo $this->translate("Sign Up"); ?></a>
        </div>
        </div>
       </div>
     </div>
     <div class="sesdating_lp_right">
        <div class="sesdating_lp_rightmenu">
          <div class="sesdating_lp_main_menu">
          <ul class="navigation">
    <?php foreach( $this->navigation as $navigationMenu ):
    $explodedString = explode(' ', $navigationMenu->class);
      ?>
      <?php if( $countMenu < $this->max ): ?>
        <?php $mainMenuIcon = Engine_Api::_()->sesdating()->getMenuIcon(end($explodedString));?>
        <li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?>>
          <?php if(end($explodedString)== 'core_main_invite'):?>
            <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo $this->url(array('module' => 'invite'), $navigationMenu->route, true) ?>'>
          <?php elseif(end($explodedString)== 'core_main_home' && ($this->viewer->getIdentity() != 0)):?>
            <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo $this->url(array('action' => 'home'), $navigationMenu->route, true) ?>'>
          <?php else:?>
            <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'>
          <?php endif;?>
            <?php if(!empty($mainMenuIcon)):?>
              <i class="menuicon" style="background-image:url(<?php echo $this->storage->get($mainMenuIcon, '')->getPhotoUrl(); ?>);"></i>
            <?php endif;?>
            <span><?php echo $this->translate($navigationMenu->label); ?></span>
          </a>
            <?php 
              
              $menuName = end($explodedString); 
              $moduleName = str_replace('core_main_', '', $menuName);
            ?>
           <?php $subMenus = Engine_Api::_()->getApi('menus', 'core')->getNavigation($moduleName.'_main'); 
              $menuSubArray = $subMenus->toArray();
           ?>
          <?php if(engine_count($subMenus) > 0 && $this->submenu): ?>
            <ul class="main_menu_submenu">
              <?php 
              $counter = 0; 
              foreach( $subMenus as $subMenu): 
             	$active = isset($menuSubArray[$counter]['active']) ? $menuSubArray[$counter]['active'] : 0;
              ?>
                <li class="sesbasic_clearfix <?php echo ($active) ? 'selected_sub_main_menu' : '' ?>">
                    <a href="<?php echo $subMenu->getHref(); ?>" class="<?php echo $subMenu->getClass(); ?>">
                    <?php if($this->show_menu_icon):?><i class="fa fa-angle-right"></i><?php endif;?><span><?php echo $this->translate($subMenu->getLabel()); ?></span>
                  </a>
                </li>
              <?php 
              $counter++;
              endforeach; ?>
            </ul>
          <?php endif; ?>
          
        </li>
      <?php else:?>
        <?php break;?>
      <?php endif;?>
      <?php $countMenu++;?>
    <?php endforeach; ?>
    <?php if (engine_count($this->navigation) > $this->max):?>
    <?php $countMenu = 0; ?>  
    <li class="more_tab">
      <a class="menu_core_main core_menu_more" href="javascript:void(0);">
        <span><?php echo $this->translate($this->moretext);?> + </span>
      </a>
      <ul class="main_menu_submenu">
        <?php foreach( $this->navigation as  $navigationMenu ): 
        $explodedString = explode(' ', $navigationMenu->class);
        ?>
          <?php $mainMenuIcon = Engine_Api::_()->sesdating()->getMenuIcon(end($explodedString));?>
          <?php if ($countMenu >= $this->max): ?>
            <li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?> >
              <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'>
                <?php if(!empty($mainMenuIcon)):?>
                  <i class="menuicon" style="background-image:url(<?php echo $this->storage->get($mainMenuIcon, '')->getPhotoUrl(); ?>);"></i>
                <?php endif;?>
                <span><?php echo $this->translate($navigationMenu->label); ?></span>
              </a>
            </li>
          <?php endif;?>
          <?php $countMenu++;?>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php endif;?>
  </ul>
</div>
        </div>
        <div class="sesdating_lp_righttop">
        <h1><?php echo $this->translate($this->heading); ?></h1>
        <p><?php echo $this->translate($this->description); ?></p>
        <ul>
           <li><i class="fa fa-male"></i><?php echo $this->translate("Singles"); ?></li>
           <li><i class="fa fa-users"></i><?php echo $this->translate("Friends"); ?></li>
           <li><i class="fa fa-heart"></i><?php echo $this->translate("Dating"); ?></li>
        </ul>
       </div>
       <div class="sesdating_lp_members">
          <ul>
          
           <?php foreach( $this->paginator as $user ): ?>
            <li><a href="<?php echo $user->getHref(); ?>"><?php echo $this->itemPhoto($user, 'thumb.profile') ?></a></li>
           <?php endforeach; ?>
          </ul>
       </div>
     </div>
    </div>
  </div>
</div>
<script>
  function searchMembers() {
    var displayname = $('displayname').value;
    var gender = $('gender').value;
    var min = $('min').value;
    var max = $('max').value;
    window.location = "members?displayname="+displayname+"&1_1_5_alias_gender="+gender+"&1_1_6_alias_birthdate%5Bmin%5D="+min+"&1_1_6_alias_birthdate%5Bmax%5D="+max;
  }
</script>
