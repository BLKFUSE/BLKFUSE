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

<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()
->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_carousel.css') 
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.min.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.theme.default.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/style.css');
$this->headScript()
->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/jquery.min.js')
->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/owl.carousel.js'); 
?>
<?php $this->carouselClass = 'categorizedPackageCarousel'; ?>
<style type="text/css">
  .owl-carousel .owl-stage-outer {
  padding-bottom: 2%;
}
</style>

<?php
$package_view = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.view', 0);
$packageInfoArray = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.information',array('price', 'recurring', 'visiblity', 'allowad', 'autoapprove', 'format', 'type', 'featured', 'sponsored', 'targeting', 'youcanadvertise', 'description'));
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/seaomooscroll/SEAOMooHorizontalScrollBar.js');
$this->headLink()->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/style.css'); ?> 
<script type="text/javascript">
  window.addEvent('domready', function() {

  $$('.slct-formate').addEvent('click',function() {
    $$('.slct-formate .slect-circle').removeClass('selected');
    this.getElement('.slect-circle').addClass('selected');
});
  $$('.ad-type-wrapper').addEvent('click',function() {
    $$('.ad-type-wrapper .slect-circle').removeClass('selected');
    this.getElement('.slect-circle').addClass('selected');
});
});

	function preview_coupon(height, width, package_type) {
   var height_width = "width="+width+",height="+height;
   var child_window = window.open (en4.core.baseUrl + 'sitecoupon/index/previewcoupon/package_type/' + package_type,'mywindow','scrollbars=yes,width=600,height=600');
 }

</script>
<?php if(empty($this->is_ajax)): ?>
  <?php
  $this->headLink()
  ->prependStylesheet($this->layout()->staticBaseUrl .'application/modules/Sitead/externals/styles/style_sitead.css');
  ?>
  
     <?php //start coupon plugin work. ?>
     <?php if (!empty($this->modules_enabled) && in_array("package", $this->modules_enabled)) : ?>
     <h4 class="cmad_step fright"><a href="javascript:void(0);" class=" buttonlink item_icon_coupon"  onclick="javascript:preview_coupon('<?php echo '500' ?>', '<?php echo '500' ?>', '<?php echo 'package' ?>');"><?php echo $this->translate('Discount Coupons') ?></a></h4>
   <?php endif; ?>

   <div class='cmad_package_page'>
    <input type="hidden" id="type" name="type" value="website" />
    <div id="saad_select_type_wrp"> 
    <h3> <?php echo $this->translate('Select an Ad Type');?></h3>
    <?php foreach ($this->adTypes as $adType): ?> 
      <?php if($adType->type == 'boost' && $this->isAdvActivity != 1) 
         continue;
      ?> 
      <?php if($adType->type == 'page' && $this->isPageEnabled != 1) 
         continue;
      ?>  
      <div class="ad-type-wrapper" onclick="javascript:setAdType('<?php echo $adType->type ?>', 'type');" >
        <div class="ad-type-contner">
          <span class="ad-type-ico">
            <i style="background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/<?php echo $adType->type ?>-ad-image.png)"> </i>
          </span>
          <span class="ad-type-title" > <?php echo $this->translate($adType->title); ?></span>
          <div class="type-disc">
            <p><?php echo $this->translate($adType->desc); ?></p>
          </div>
        </div>
        <?php if($adType->type == 'website') : ?>
        <div class="slect-circle selected"></div>
        <?php else : ?>
        <div class="slect-circle"></div>
      <?php endif; ?>
      </div>
    <?php endforeach;?>
    <div id="slct_type_next">
      <button id="continue_format"><?php echo $this->translate('Next'); ?></button>
    </div>
  </div>

    <input type="hidden" id="format" name="format" value="image" />
    <div id="saad_select_format_wrp" style="display: none">
    <div id="format_wrapper">  
    <h3> <?php echo $this->translate('Select an Ad Format');?></h3>
    <?php foreach ($this->adFormats as $adFormat): ?>
      <div class="slct-formate" onclick="javascript:setAdFormat('<?php echo $adFormat ?>', 'format');">
        <div class="slct-wrapper">
             <?php if($adFormat == 'carousel') :?>
          <span class="formate-image">
            <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/carousel-image.png" />
          </span>
          <h4><?php echo 'Carousel Ad'; ?></h4>
          <?php elseif($adFormat == 'image') : ?>
            <span class="formate-image">
              <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/single-image.png" />
            </span>
          <h4><?php echo 'Image Ad'; ?></h4>
          <?php else: ?>
            <span class="formate-image">
              <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/video-image.png" />
          </span>
          <h4><?php echo 'Video Ad'; ?></h4>
          <?php endif; ?>
        </div>
       <?php if($adFormat == 'image') : ?>
        <div class="slect-circle selected"></div>
        <?php else : ?>
        <div class="slect-circle"></div>
      <?php endif; ?>
      </div>
    <?php endforeach;?>
  </div>
  <div id="slct_type_next">
    <button id="back_ad_type"><?php echo $this->translate('Back'); ?></button>
    <button id="continue_package"><?php echo $this->translate('Next'); ?></button>
  </div>
</div>
    <div class="cmad_package_list" id="package_decription" style="border-bottom:none;display: <?php echo count($this->adTypes) > 0 ? 'none':'' ?>;">
         <h3> <?php echo $this->translate('Select a package ');?></h3>
         <div id="package_back_button" style="display: none">
      <button id="back_ad_format"> Back</button>
   </div>
   </div>
   <div class="sitead-packages-container">
   <ul class="sitead-packages-wrp" id="package_list" style="display: <?php echo count($this->adTypes) > 0 ? 'none':'' ?>;">
     <?php endif; ?>
   <?php
   if(!empty($this->is_ajax)):   ?> 
    <?php if( count($this->paginator) ):   ?>						
     <?php if (empty($package_view)): ?>
      <?php foreach ($this->paginator as $item): ?>
        <li>      
          <div class="plan-top-sec">
            <span class="plan-title"><?php echo $this->translate(ucfirst($item->title));?></span>
            <span class="plan-price"> 
              <?php if (in_array('price', $packageInfoArray)): ?>   
              <?php if (!$item->isFree()):echo Engine_Api::_()->sitead()->getPriceWithCurrency($item->price); 
                else: echo $this->translate('FREE');
                endif; ?>
              <?php endif;?>
              </span>
            </div>

            <div class="plan-mid-sec">
              <div class="plan-features">

                 <?php if (in_array('recurring', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Payment Cycle"); ?></span>
                          <span class="value"> 
                          <?php echo $this->translate("One Time"); ?>
                          </span>
                        </div>
                      </div>
                    <?php endif;?>

                <?php if (in_array('visiblity', $packageInfoArray)): ?> 
                  <div class="plan-content">
                    <div>
                      <span class="label"><?php echo $this->translate("Ad Visiblity"); ?></span>
                      <span class="value"> <?php
                      switch ($item->price_model):
                        case "Pay/view":
                        if ($item->model_detail != -1): echo $this->translate(array('%s View', '%s Views', $item->model_detail), $this->locale()->toNumber($item->model_detail));
                          else: echo $this->translate('UNLIMITED Views');
                          endif;

                          break;

                          case "Pay/click":
                          if ($item->model_detail != -1): echo $this->translate(array('%s Click', '%s Clicks', $item->model_detail), $this->locale()->toNumber($item->model_detail));
                            else: echo $this->translate('UNLIMITED Clicks');
                            endif;

                            break;

                            case "Pay/period":
                            if ($item->model_detail != -1): echo $this->translate(array('%s Day', '%s Days', $item->model_detail), $this->locale()->toNumber($item->model_detail));
                              else: echo $this->translate('UNLIMITED  Days');
                              endif;
                              break;
                            endswitch; ?> 
                          </span>
                        </div>
                      </div>
                    <?php endif;?>

                    <?php if (in_array('targeting', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Targeting"); ?></span>
                          
                            <?php if ($item->network == 1) : ?>
                              <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php else : ?>
                               <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
                               <?php endif;?>
                        </div>
                      </div>
                    <?php endif;?>

                    <?php if (in_array('featured', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Featured"); ?></span> 
                            <?php if ($item->featured == 1) : ?>
                           <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php else : ?>
                             <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
                             <?php endif;?>
                        </div>
                      </div>
                    <?php endif;?>

                    <?php if (in_array('sponsored', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Sponsored"); ?></span>
                            <?php if ($item->sponsored == 1) : ?>
                            <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php else : ?>
                             <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
                             <?php endif;?>
                        </div>
                      </div>
                    <?php endif;?>

                    <?php if (in_array('allowad', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Allow Ads"); ?></span>
                          <span class="value"> 
                            <?php if ($item->allow_ad == 0)
                            echo $this->translate("Unlimited");
                            else
                              echo $item->allow_ad; ?>
                          </span>
                        </div>
                      </div>
                    <?php endif;?>

                     <?php if (in_array('autoapprove', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Auto Approved Ads"); ?></span>
                            <?php if ($item->auto_aprove == 1): ?>
                             <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php else : ?>
                             <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
                           <?php endif; ?>  
                        </div>
                      </div>
                    <?php endif;?>

                    <?php if (in_array('format', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Ads Format "); ?></span>
                            <span class="value"> 
                            <?php if ($item->carousel == 1)
                            echo $this->translate("Carousel, ");
                            if ($item->image == 1)
                            echo $this->translate("Image, ");
                            if ($item->video == 1)
                            echo $this->translate("Video"); ?>
                          </span> 
                        </div>
                      </div>
                    <?php endif;?>

                     <?php if (in_array('type', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("Ads Type "); ?></span>
                            <span class="value"> 
                            <?php $categories = explode(",", $item->add_categories);                       
                            foreach ($categories as $key => $value): 
                              if($value == 'boost') {
                                echo $this->translate("Boost A Post, ");
                              }
                              if($value == 'content') {
                                echo $this->translate("Promote Your Content, ");
                              }
                              if($value == 'page') {
                                echo $this->translate("Promote Your Page, ");
                              }
                              if($value == 'website') {
                                echo $this->translate("Get More Website Visitor ");
                              }
                            endforeach; ?>
                          </span> 
                        </div>
                      </div>
                    <?php endif;?>
                   <?php if (!empty($item->urloption)): ?> 
                    <?php if (in_array('youcanadvertise', $packageInfoArray)): ?> 
                      <div class="plan-content">
                        <div>
                          <span class="label"><?php echo $this->translate("You can advertise"); ?></span>
                          <span class="value"> 
                            <?php
                            $canAdvertise = explode(",", $item->urloption);                       
                            foreach ($canAdvertise as $key => $value):               
                              if( strstr($value, "sitereview") ){
                                $isReviewPluginEnabled = Engine_Api::_()->getDbtable('modules', 'sitead')->getModuleInfo("sitereview");
                                if( !empty($isReviewPluginEnabled) ){
                                  $sitereviewExplode = explode("_", $value);
                                  $getAdsMod = Engine_Api::_()->getItem("sitead_module", $sitereviewExplode[1]);
                                  $modTemTitle = strtolower($getAdsMod->module_title);
                                  $modTemTitle = ucfirst($modTemTitle);
                                  $canAdvertise[$key] = $modTemTitle;
                                }else {
                                  unset($canAdvertise[$key]);
                                }
                              }else {                
                                if ($value != 'Custom Ad') {                  
                                  $getInfo = Engine_Api::_()->getDbtable('modules', 'sitead')->getModuleInfo($value);
                                  if (!empty($getInfo)) {
                                    $canAdvertise[$key] = $this->translate($getInfo['module_title']);
                                  }else {
                                    unset($canAdvertise[$key]);
                                  }
                                }else {
                                  $canAdvertise[$key] = ucfirst($value);
                                }
                              }
                            endforeach;

                            $canAdStr = implode(", ", $canAdvertise);
                            echo $canAdStr;
                            ?>
                          </span>
                        </div>
                      </div>
                    <?php endif;?>
                  <?php endif;?>
                  </div>
                </div>

                <div class="choos-wrp">
                  <?php if (in_array('description', $packageInfoArray)): ?>
                  <div class="cmad_list_details">
                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $this->translate($item->desc); ?>')"  > <?php echo $this->translate("Read More");?><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  </div>
                <?php endif;?>
                  <div class="choose-btns">
                    <?php 
                     if($item->allow_ad != 0) {
                    $totalPackageAd =  Engine_Api::_()->sitead()->getTotalAdOfPackage($this->viewer_id, $item->package_id);
                      echo $this->translate('(' . ($item->allow_ad - $totalPackageAd). ' left)');
                    }
                    ?>
                    <?php if (!empty($this->type_id) && !empty($this->type)) : ?>
                    <a href='<?php echo $this->url(array('id' => $item->package_id, 'type' => $this->type, 'type_id' => $this->type_id), 'sitead_create', true) ?>' ><?php echo $this->translate("SITEAD_PACKAGE_CREATE_BUTTON_".strtoupper($item->type)). ' '; ?><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    <?php else : ?>
                      <a href='<?php echo $this->url(array('id' => $item->package_id, 'type' => $this->package_type), 'sitead_create', true) ?>' ><?php echo $this->translate("SITEAD_PACKAGE_CREATE_BUTTON_".strtoupper($item->type)). ' '; ?><i class="fa fa-angle-right" aria-hidden="true"></i></a>  
                    <?php endif; ?>
                  </div>
                </div>   
              </li>
            <?php endforeach; ?>       

              <div class="" id="view_more" onclick="getPackageList()" style="margin-top: 5px; text-align:center; display:<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>" >   <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(           
                'class' => 'buttonlink icon_viewmore'
              )) ?>
            </div>
            <div class="cmad_package_loading" id="loding_image" style="display:none;margin:5px 0;text-align:center;">                   
              <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin-right: 5px;' />
              <?php echo $this->translate("Loading ...") ?>
            </div>
            <?php else:?>
              <?php include APPLICATION_PATH . '/application/modules/Sitead/views/scripts/index/_verticalPackageInfo.tpl'; ?>
            <?php endif;?>
            <?php else: ?>   
             <div class="tip">
              <span>
                <?php
                echo $this->translate("There are no ad packages right now for creating your ad. Please click %1shere%2s to contact sales team for advertising.", "<a href= '". $this->url(array('page_id' => 4), 'sitead_help', true)."' >", "</a>");
                ?>
              </span>
            </div>    
          <?php endif; ?>
        <?php endif; ?>      
        <?php if(empty($this->is_ajax)): ?>     
        </ul> 
      </div>
      </div>
<?php endif; ?>
<script type="text/javascript">

  function setAdType(val) {
    $('type').value = val;
  }

  $('continue_format').addEvent('click', function() {
     if($('type').value != '') {
      if($('type').value == 'boost') {
        $('format').value = 'image';
        getPackageList();
      } else {
        $('saad_select_format_wrp').style.display = 'block';
      } 
      $('saad_select_type_wrp').style.display = 'none';
    }
  });

  function setAdFormat(val) {
     $('format').value = val;
  }

  $('continue_package').addEvent('click', function() {
    if($('type').value != '' && $('format').value != '')
      getPackageList();
  });

   $('back_ad_type').addEvent('click', function() {
    $('saad_select_format_wrp').style.display = 'none';
    $('saad_select_type_wrp').style.display = 'block';
  });

  $('back_ad_format').addEvent('click', function() {
    if($('type').value == 'boost') {
      $('saad_select_type_wrp').style.display = 'block';
    } else {
       $('saad_select_format_wrp').style.display = 'block';
    }
    $('package_list').style.display = 'none';
    $('package_list').empty();
    $('package_back_button').style.display = 'none';
    $('package_decription').style.display = 'none';
    
  });
 
  function getNextPage(){
   <?php if ($this->is_ajax || count($this->adTypes) == 0) : ?>
    return <?php echo $this->paginator->getCurrentPageNumber() + 1 ?>;
  <?php endif; ?>
}

var getPackageList;
var ad_type_temp='<?php echo $this->ad_type ?>';
var ad_format_temp='<?php echo $this->ad_format ?>';


en4.core.runonce.add(function() {
 getPackageList = function() {
  if($('format').value != '') {
  // if( $('type').value == ad_type_temp && $('format').value == ad_format_temp) {
  //   return;
  // }
  $('package_list').style.display='';
  $('package_list').empty();
  $("package_decription").style.display='none';
  $('package_back_button').style.display = 'none';
  var loadingHTML= '<li id="package_type_loading" style="display: none;" class="cmad_package_loading" >'
  +'<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif" alt="Loading" />'
  +'</li>';
  Elements.from(loadingHTML).inject($('package_list'));
  $('package_type_loading').style.display = '';
  var nextPage=1;
  
  ad_type_temp = $('type').value;
  ad_format_temp = $('type').value;
  var request = new Request.HTML({
    url : '<?php echo $this->url(array(), 'sitead_listpackage', true); ?>',
    data : {
      format : 'html',
          'ad_type' : $('type').value,//$('type').value,//type, //$('type').value,   
          'ad_format': $('format').value,//$('format').value,//format,    
          'page':nextPage,
          'is_ajax':1,
          'type_id':'<?php echo  $this->type_id?>',
          'type':'<?php echo $this->type ?>'
        },
        evalScripts : true,
        onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
          $("package_decription").style.display='';
          $('package_back_button').style.display = 'block';
           $('saad_select_format_wrp').style.display = 'none';
          if($('package_type_loading'))
            $('package_type_loading').destroy(); 
          if(nextPage ==1){
            $('package_list').empty();
          }      
          if($('loding_image'))
            $('loding_image').destroy(); 
          Elements.from(responseHTML).inject($('package_list'));        
          en4.core.runonce.trigger();
          Smoothbox.bind($('package_list'));
        }
      });
  request.send();
}
}
});
</script>


