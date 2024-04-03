<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $widgetParams = $this->widgetParams;  ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>
<?php if($widgetParams['autosuggest']) { ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php } ?>
<?php if($widgetParams['template'] == 1) { ?>
<div class="sestutorial_search_banner_wrapper sestutorial_clearfix sestutorial_bxs">
	<div class="sestutorial_search_banner_container sestutorial_clearfix <?php if($widgetParams['showfullwidth'] == 'full'): ?> search_banner_bg_full <?php endif; ?>" style="height:<?php echo $widgetParams['height'];?>px;">
  	<div class="sestutorial_search_banner type1" style="height:<?php echo $widgetParams['height'];?>px;">
    	<div class="sestutorial_search_banner_inner" style="background-image:url(<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['backgroundimage']); ?>);">
        <div class="sestutorial_search_banner_content_left">
          <?php if($widgetParams['bannertext']) { ?>
            <div class="sestutorial_search_banner_heading">
              <h2><?php if(isset($widgetParams['logo']) && !empty($widgetParams['logo'])) { ?><img src="<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['logo']); ?>" /><?php } ?><span><?php echo $this->translate($widgetParams['bannertext']); ?></span></h2>
            </div>
          <?php } ?>
          <?php if($widgetParams['description']) { ?>
            <div class="sestutorial_search_banner_des">
              <p><?php echo $this->translate($widgetParams['description']); ?></p>
            </div>
          <?php } ?>
          <div class="sestutorial_search_banner_form">
            <input type="text" id="tutorial_title" placeholder="<?php echo $this->translate($widgetParams['textplaceholder']); ?>" />
            <button name="submit" id="submit" type="submit" onclick="searchTutorial()" class="fa fa-search"></button>
          </div>
          <?php if($widgetParams['limit'] > 0) { ?>
            <div class="sestutorial_search_banner_ques">
              <?php foreach($this->tutorials as $tutorial) { ?>
                <div class="sestutorial_sidebar_list_title">
                  <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), 90); ?></a>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } elseif($widgetParams['template'] == 2) { ?>
  <div class="sestutorial_search_banner_wrapper sestutorial_clearfix sestutorial_bxs">
    <div class="sestutorial_search_banner_container sestutorial_clearfix sestutorial_search_banner_bg <?php if($widgetParams['showfullwidth'] == 'full'): ?> search_banner_bg_full <?php endif; ?>" style="height:<?php echo $widgetParams['height'];?>px;">
      <div class="sestutorial_search_banner type2" style="height:<?php echo $widgetParams['height'];?>px;">
      	<div class="sestutorial_search_banner_inner" style="background-image:url(<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['backgroundimage']); ?>);">
          <div class="sestutorial_search_banner_content">
            <div>
              <?php if($widgetParams['bannertext']) { ?>
                <div class="sestutorial_search_banner_heading">
                  <h2><?php if(isset($widgetParams['logo']) && !empty($widgetParams['logo'])) { ?><img src="<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['logo']); ?>" /><?php } ?><span><?php echo $this->translate($widgetParams['bannertext']); ?></span></h2>
                </div>
              <?php } ?>
              <?php if($widgetParams['description']) { ?>
                <div class="sestutorial_search_banner_des">
                  <p><?php echo $this->translate($widgetParams['description']); ?></p>
                </div>
              <?php } ?>
              <div class="sestutorial_search_banner_form">
                <div>
                  <input type="text" id="tutorial_title" placeholder="<?php echo $this->translate($widgetParams['textplaceholder']); ?>" />
                  <button name="submit" id="submit" type="submit" onclick="searchTutorial()" class="fa fa-search"></button>
                </div>
              </div>
              <?php if($widgetParams['limit'] > 0) { ?>
                <div class="sestutorial_search_banner_ques">	 
                  <?php foreach($this->tutorials as $tutorial) { ?>
                    <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), 30); ?></a>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } elseif($widgetParams['template'] == 3) { ?>
  <div class="sestutorial_search_banner_wrapper sestutorial_clearfix sestutorial_bxs">
    <div class="sestutorial_search_banner_container sestutorial_clearfix <?php if($widgetParams['showfullwidth'] == 'full'): ?> search_banner_bg_full <?php endif; ?>" style="height:<?php echo $widgetParams['height'];?>px;">
      <div class="sestutorial_search_banner type3" style="height:<?php echo $widgetParams['height'];?>px;">
      	<div class="sestutorial_search_banner_inner" style="background-image:url(<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['backgroundimage']); ?>);">
          <div class="sestutorial_search_banner_content">
            <div>
              <?php if($widgetParams['bannertext']) {  ?>
              <div class="sestutorial_search_banner_heading">
                <h2><?php if(isset($widgetParams['logo']) && !empty($widgetParams['logo'])) { ?><img src="<?php echo Engine_Api::_()->sestutorial()->getFileUrl($widgetParams['logo']); ?>" /><?php } ?><span><?php echo $this->translate($widgetParams['bannertext']); ?></span></h2>
              </div>
              <?php } ?>
              <?php if($widgetParams['description']) { ?>
                <div class="sestutorial_search_banner_des">
                  <p><?php echo $this->translate($widgetParams['description']); ?></p>
                </div>
              <?php } ?>
              <div class="sestutorial_search_banner_form">
                <div>
                  <input type="text" id="tutorial_title" placeholder="<?php echo $this->translate($widgetParams['textplaceholder']); ?>" />
                  <button name="submit" id="submit" type="submit" onclick="searchTutorial()" class="fa fa-search"></button>
                </div>
              </div>
              <?php if($widgetParams['limit'] > 0) { ?>
                <div class="sestutorial_search_banner_ques">	 
                  <?php foreach($this->tutorials as $tutorial) { ?>
                    <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), 30); ?></a>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<script>

  function searchTutorial() {
    window.location.href= '<?php echo $this->url(array("controller" => "index", "action" => "browse"), "sestutorial_general", true); ?>' + "?title_name=" + document.getElementById('tutorial_title').value;
  }
  
  <?php if($widgetParams['autosuggest']) { ?>
    en4.core.runonce.add(function() {
      AutocompleterRequestJSON('tutorial_title', "<?php echo $this->url(array('module' => 'sestutorial', 'controller' => 'index', 'action' => 'search'), 'default', true) ?>", function(selecteditem) {
      window.location.href = selecteditem.url;
      })
    });
  <?php } ?>
  
  scriptJquery(document).ready(function() {
    scriptJquery('#tutorial_title').keydown(function(e) {
      if (e.which === 13) {
        searchTutorial();
      }
    });
  });
</script>
<?php if($widgetParams['showfullwidth'] == 'full'): ?>
  <script type="text/javascript">
    scriptJquery(function() {
      scriptJquery('body').addClass('sestutorial_search_banner_full');
    });
  </script>
<?php endif; ?>
