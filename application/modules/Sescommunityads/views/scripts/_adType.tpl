<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: __adType.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="sescmads_select_ads_container sesbasic_bxs"> 
 <!--Selection Popup Start Here-->
 <div class="_head centerT"><?php echo $this->translate('How would you like to grow your business?'); ?></div>
 <div class="_des centerT"><?php echo $this->translate('Creating a promotion helps get you more of the business results you want.'); ?></div>
 <div class="sescmads_select_ads sesbasic_clearfix">
  <section>
    <?php //check item exists in package 
      $settings = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads_package_settings', 'a:16:{i:0;s:5:"price";i:1;s:12:"payment_type";i:2;s:16:"payment_duration";i:3;s:8:"ad_count";i:4;s:12:"auto_approve";i:5;s:9:"boos_post";i:6;s:12:"promote_page";i:7;s:15:"promote_content";i:8;s:15:"website_visitor";i:9;s:7:"carosel";i:10;s:5:"video";i:11;s:8:"featured";i:12;s:9:"sponsored";i:13;s:10:"targetting";i:14;s:11:"description";i:15;s:9:"advertise";}'));
    
    ?>
    <?php $boostPost = Engine_Api::_()->sescommunityads()->getPackageContent(array('name'=>'boost_post','package_id'=>$this->package_id)) && engine_in_array('boos_post',$settings);
          $promoteContent = Engine_Api::_()->sescommunityads()->getPackageContent(array('name'=>'promote_content','package_id'=>$this->package_id)) && engine_in_array('promote_content',$settings);
          $promotePage = Engine_Api::_()->sescommunityads()->getPackageContent(array('name'=>'promote_page','package_id'=>$this->package_id)) && engine_in_array('promote_page',$settings);
          $moreVisitor = Engine_Api::_()->sescommunityads()->getPackageContent(array('name'=>'website_visitor','package_id'=>$this->package_id)) && engine_in_array('website_visitor',$settings);
    ?>
    <input type="hidden" name="promote_cnt" value="" id="promote_cnt">
   <ul>
   <?php if($boostPost && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){ ?>
    <li class="sesbasic_bg"> 
     <a href="javascript:;" rel="boost_post_cnt" class="sescommunityads_promote_a">
       <div class="_thumb"> <img src="application/modules/Sescommunityads/externals/images/boost.png" alt="" align="center" /> </div>
       <div class="_cont">
        <div class="_title"><?php echo $this->translate('Boost A Post'); ?></div>
        <div class="_des"><?php echo $this->translate('Get more people to see and engage with your Page posts.'); ?></div>
       </div>
       <div class="_arrow sesbasic_text_light"><i class="fa fa-angle-right"></i></div>
     </a> 
   </li>
   <?php } ?>
   <?php if($promotePage && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sespage')){ ?>
    <li class="sesbasic_bg"> <a href="javascript:;"  rel="promote_page_cnt" class="sescommunityads_promote_a">
     <div class="_thumb"> <img src="application/modules/Sescommunityads/externals/images/promote_page.png" alt="" align="center" /> </div>
     <div class="_cont">
      <div class="_title"><?php echo $this->translate('Promote Your Page'); ?></div>
      <div class="_des"><?php echo $this->translate('Connect more people with your Page.'); ?></div>
     </div>
     <div class="_arrow sesbasic_text_light"><i class="fa fa-angle-right"></i></div>
     </a> 
    </li>
   <?php } ?>
    <?php if($promoteContent){ ?>
    <li class="sesbasic_bg"> <a href="javascript:;" rel="promote_content_cnt" class="sescommunityads_promote_a">
     <div class="_thumb"> <img src="application/modules/Sescommunityads/externals/images/promote_content.png" alt="" align="center" /> </div>
     <div class="_cont">
      <div class="_title"><?php echo $this->translate('Promote Your Content'); ?></div>
      <div class="_des"><?php echo $this->translate('Let more people see the content you post.'); ?></div>
     </div>
     <div class="_arrow sesbasic_text_light"><i class="fa fa-angle-right"></i></div>
     </a> 
   </li>
    <?php } ?>
    <?php if($moreVisitor){ ?>
    <li class="sesbasic_bg"> <a href="javascript:;"  rel="promote_website_cnt" class="sescommunityads_promote_a">
     <div class="_thumb"> <img src="application/modules/Sescommunityads/externals/images/visitors.png" alt="" align="center" /> </div>
     <div class="_cont">
      <div class="_title"><?php echo $this->translate('Get More Website Visitors'); ?></div>
      <div class="_des"><?php echo $this->translate('Advertise your website to a large audience'); ?></div>
     </div>
     <div class="_arrow sesbasic_text_light"><i class="fa fa-angle-right"></i></div>
     </a> 
   </li>
   <?php } ?>
   </ul>
  </section>
 </div>
 <!--Selection Popup End Here--> 
</div>
