<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _verticalPackageInfo.tpl 2015-05-11 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
 <?php $item =  $this->totalItem;
 if($item >= 3)
 $item = 3; 
 ?>
<script>
    en4.core.runonce.add(function() {
  var owl = '';
  var j_q = jq.noConflict();
  j_q(document).ready(function () {
    owl = j_q('.categorizedPackageCarousel').owlCarousel({
    loop: true,
    autoplay: false,
    touchdrag: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
        nav: true
      },
      600: {
        items: <?php echo $item; ?>,
        nav: false
      },
      1000: {
        items: <?php echo $item; ?>,
        nav: true,
        loop: false,
        margin: 20,
      }
    },
    slideBy: 1,
    dots: true,
    navigation: true,
    })
 });
});
</script>
<?php
$request = Zend_Controller_Front::getInstance()->getRequest();
$controller = $request->getControllerName();
$action = $request->getActionName();
?>

<li class="seaocore_package_vertical">
    <div class="vertical-package-continer">
        <div class="owl-carousel owl-theme <?php echo $this->carouselClass ?>">
        <?php foreach ($this->paginator as $item): ?>
            <div class="vertical-package-wrp">
              <div class="vertical-plan-title">
                <h3><?php echo $this->translate(ucfirst($item->title));?></h3>
            </div>
            <div class="vertical-plan-price">
                <h3>
                    <?php if (in_array('price', $packageInfoArray)): ?>   
                      <?php if (!$item->isFree()):echo Engine_Api::_()->sitead()->getPriceWithCurrency($item->price); 
                        else: echo $this->translate('FREE');
                        endif; ?>
                    <?php endif;?>
                </h3>
            </div>
            <ul>
                <?php if (in_array('recurring', $packageInfoArray)): ?> 
                 <li>
                  <span class="label"><?php echo $this->translate("Payment Cycle"); ?></span>
                  <span class="value"> <?php echo $this->translate("One Time"); ?></i></span>
              </li>
          <?php endif; ?>
          <?php if (in_array('visiblity', $packageInfoArray)): ?> 
             <li>
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
          </li>
      <?php endif; ?>
      <?php if (in_array('targeting', $packageInfoArray)): ?>             
        <li>
           <span class="label"><?php echo $this->translate("Targeting"); ?></span>

           <?php if ($item->network == 1) : ?>
              <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
              <?php else : ?>
                 <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
             <?php endif;?>
         </li>
     <?php endif; ?>

     <?php if (in_array('featured', $packageInfoArray)): ?> 

        <li>
          <span class="label"><?php echo $this->translate("Featured"); ?></span>
          <?php if ($item->featured == 1) : ?>
             <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
             <?php else : ?>
               <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
           <?php endif;?>
       </li>
   <?php endif;?>

   <?php if (in_array('sponsored', $packageInfoArray)): ?> 

    <li>
      <span class="label"><?php echo $this->translate("Sponsored"); ?></span>
      <?php if ($item->sponsored == 1) : ?>
        <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
        <?php else : ?>
           <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
       <?php endif;?>
   </li>
<?php endif;?>

<?php if (in_array('allowad', $packageInfoArray)): ?> 

    <li>
      <span class="label"><?php echo $this->translate("Allow Ads"); ?></span>
      <span class="value"> 
        <?php if ($item->allow_ad == 0)
        echo $this->translate("Unlimited");
        else
          echo $item->allow_ad; ?>
  </span>
</li>
<?php endif;?>

<?php if (in_array('autoapprove', $packageInfoArray)): ?> 

    <li>
      <span class="label"><?php echo $this->translate("Auto Approved Ads"); ?></span>
      <?php if ($item->auto_aprove == 1): ?>
       <span class="value tick"> <i class="fa fa-check" aria-hidden="true"></i></span>
       <?php else : ?>
           <span class="value cross"> <i class="fa fa-times" aria-hidden="true"></i></span>
       <?php endif; ?> 
   </li>
<?php endif;?>

<?php if (in_array('format', $packageInfoArray)): ?> 

    <li>
      <span class="label"><?php echo $this->translate("Ads Format "); ?></span>
      <span class="value"> 
        <?php if ($item->carousel == 1)
        echo $this->translate("Carousel, ");
        if ($item->image == 1)
            echo $this->translate("Image, ");
        if ($item->video == 1)
            echo $this->translate("Video"); ?>
    </span> 
</li>
<?php endif;?>

<?php if (in_array('type', $packageInfoArray)): ?> 

    <li>
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
</li>
<?php endif;?>

<?php if (!empty($item->urloption)): ?>
<?php if (in_array('youcanadvertise', $packageInfoArray)): ?> 
    <li>
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
</li>
<?php endif;?>
<?php endif;?>

<?php if (in_array('description', $packageInfoArray)): ?>
    <li>
      <span class="label">
        <?php if($item->allow_ad != 0) {
        $totalPackageAd =  Engine_Api::_()->sitead()->getTotalAdOfPackage($this->viewer_id, $item->package_id);
        echo $this->translate('(' . ($item->allow_ad - $totalPackageAd). ' ads left)');
    } ?>
      </span>
      <span class="value cross">  <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $this->translate($item->desc); ?>')"  > <?php echo $this->translate("Read More");?><i class="fa fa-angle-right" aria-hidden="true"></i></a></span>
  </li>
<?php endif ?>

<li>
  <div class="choose-btns">
   
    <?php if (!empty($this->type_id) && !empty($this->type)) : ?>
    <a href='<?php echo $this->url(array('id' => $item->package_id, 'type' => $this->type, 'type_id' => $this->type_id), 'sitead_create', true) ?>' ><?php echo $this->translate("SITEAD_PACKAGE_CREATE_BUTTON_".strtoupper($item->type)). ' '; ?><i class="fa fa-angle-right" aria-hidden="true"></i></a>
    <?php else : ?>
      <a href='<?php echo $this->url(array('id' => $item->package_id, 'type' => $this->package_type), 'sitead_create', true) ?>' ><?php echo $this->translate("SITEAD_PACKAGE_CREATE_BUTTON_".strtoupper($item->type)). ' '; ?><i class="fa fa-angle-right" aria-hidden="true"></i></a>  
  <?php endif; ?> 
</div>
</li>
</ul>
</div>      
<?php endforeach;?>
</div>
</div>
</li>