<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: admin.tpl 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php echo $this->doctype()->__toString() ?>
<?php $locale = $this->locale()->getLocale()->__toString(); $orientation = ( $this->layout()->orientation == 'right-to-left' ? 'rtl' : 'ltr' ); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $locale ?>" lang="<?php echo $locale ?>" dir="<?php echo $orientation ?>">
<head>
  <base href="<?php echo rtrim('//' . $_SERVER['HTTP_HOST'] . $this->baseUrl(), '/'). '/' ?>" />

  <?php // ALLOW HOOKS INTO META ?>
  <?php echo $this->hooks('onRenderLayoutAdmin', $this) ?>

  <?php // TITLE/META ?>
  <?php
    $counter = (int) $this->layout()->counter;
    $staticBaseUrl = $this->layout()->staticBaseUrl;
    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $this->headTitle()
      ->setSeparator(' - ');
    $pageTitleKey = 'pagetitle-' . $request->getModuleName() . '-' . $request->getActionName()
        . '-' . $request->getControllerName();
    $pageTitle = $this->translate($pageTitleKey);
    if( $pageTitle && $pageTitle != $pageTitleKey ) {
      $this
        ->headTitle($pageTitle, Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
    }
    $this
      ->headTitle($this->translate("Control Panel"), Zend_View_Helper_Placeholder_Container_Abstract::PREPEND)
      ;
    $this->headMeta()
      ->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
      ->appendHttpEquiv('Content-Language', $this->locale()->getLocale()->__toString());
    if( $this->subject() && $this->subject()->getIdentity() ) {
      $this->headTitle($this->subject()->getTitle());
      $this->headMeta()->appendName('description', $this->subject()->getDescription());
      $this->headMeta()->appendName('keywords', $this->subject()->getKeywords());
    }

    // Get body identity
    if( isset($this->layout()->siteinfo['identity']) ) {
      $identity = $this->layout()->siteinfo['identity'];
    } else {
      $identity = $request->getModuleName() . '-' .
          $request->getControllerName() . '-' .
          $request->getActionName();
    }
  ?>
  <?php echo $this->headTitle()->toString()."\n" ?>
  <?php echo $this->headMeta()->toString()."\n" ?>

  <?php // LINK/STYLES ?>
  <?php
    $this->headLink(array(
      'rel' => 'favicon',
      'href' => ( isset($this->layout()->favicon)
        ? $staticBaseUrl . $this->layout()->favicon
        : '/favicon.ico' ),
      'type' => 'image/x-icon'),
      'PREPEND');
    if( APPLICATION_ENV != 'development' ) {
      $this->headLink()
        ->prependStylesheet($staticBaseUrl . 'application/css.php?request=application/modules/Core/externals/styles/admin/main.css');
    } else {
      $this->headLink()
        ->prependStylesheet(rtrim($this->baseUrl(), '/') . '/application/css.php?request=application/modules/Core/externals/styles/admin/main.css');    
    }
    // Process
    foreach( $this->headLink()->getContainer() as $dat ) {
      if( !empty($dat->href) ) {
        if( false === strpos($dat->href, '?') ) {
          $dat->href .= '?c=' . $counter;
        } else {
          $dat->href .= '&c=' . $counter;
        }
      }
    }
  ?>
  <?php echo $this->headLink()->toString()."\n" ?>
  <?php echo $this->headStyle()->toString()."\n" ?>

  <?php // TRANSLATE ?>
  <?php $this->headScript()->prependScript($this->headTranslate()->toString()) ?>

  <?php // SCRIPTS ?>
  <script type="text/javascript">
    <?php echo $this->headScript()->captureStart(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND) ?>

    Date.setServerOffset('<?php echo date('D, j M Y G:i:s O', time()) ?>');

    en4.orientation = '<?php echo $orientation ?>';
    en4.core.environment = '<?php echo APPLICATION_ENV ?>';
    en4.core.language.setLocale('<?php echo $this->locale()->getLocale()->__toString() ?>');
    en4.core.setBaseUrl('<?php echo $this->url(array(), 'default', true) ?>');
    en4.core.staticBaseUrl = '<?php echo $this->escape($staticBaseUrl) ?>';
    en4.core.loader = new Element('img', {src: en4.core.staticBaseUrl + 'application/modules/Core/externals/images/loading.gif'});

    <?php if( $this->subject() ): ?>
      en4.core.subject = {
        type : '<?php echo $this->subject()->getType(); ?>',
        id : <?php echo $this->subject()->getIdentity(); ?>,
        guid : '<?php echo $this->subject()->getGuid(); ?>'
      };
    <?php endif; ?>
    <?php if( $this->viewer()->getIdentity() ): ?>
      en4.user.viewer = {
        type : '<?php echo $this->viewer()->getType(); ?>',
        id : <?php echo $this->viewer()->getIdentity(); ?>,
        guid : '<?php echo $this->viewer()->getGuid(); ?>'
      };
    <?php endif; ?>
    if( <?php echo ( Zend_Controller_Front::getInstance()->getRequest()->getParam('ajax', false) ? 'true' : 'false' ) ?> ) {
      en4.core.dloader.attach();
    }
    <?php echo $this->headScript()->captureEnd(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND) ?>
  </script>
  <?php
    $this->headScript()
      ->prependFile($staticBaseUrl . 'externals/smoothbox/smoothbox4.js')
      ->prependFile($staticBaseUrl . 'application/modules/User/externals/scripts/core.js')
      ->prependFile($staticBaseUrl . 'application/modules/Core/externals/scripts/core.js')
      ->prependFile($staticBaseUrl . 'externals/chootools/chootools.js')
      ->prependFile($staticBaseUrl . 'externals/mootools/mootools-more-1.4.0.1-full-compat-' . (APPLICATION_ENV == 'development' ? 'nc' : 'yc') . '.js')
      ->prependFile($staticBaseUrl . 'externals/mootools/mootools-core-1.4.5-full-compat-' . (APPLICATION_ENV == 'development' ? 'nc' : 'yc') . '.js');
    // Process
    foreach( $this->headScript()->getContainer() as $dat ) {
      if( !empty($dat->attributes['src']) ) {
        if( false === strpos($dat->attributes['src'], '?') ) {
          $dat->attributes['src'] .= '?c=' . $counter;
        } else {
          $dat->attributes['src'] .= '&c=' . $counter;
        }
      }
    }
  ?>
  <?php echo $this->headScript()->toString()."\n" ?>

  <script type="text/javascript">
  //<![CDATA[
  var changeEnvironmentMode = function(mode, btn) {
    if( btn ) {
      btn.set('class', '');
    }
    if( $$('div.admin_home_environment button') ) {
      $$('div.admin_home_environment button').set('class', 'button_disabled');
    }
    if( $$('div.admin_home_environment_description') ) {
      $$('div.admin_home_environment_description').set('text', 'Changing mode - please wait...');
    }
    new Request.JSON({
      url: '<?php echo $this->url(array('action'=>'change-environment-mode'), 'admin_default', true) ?>',
      method: 'post',
      success: function(responseJSON){
        if ($type(responseJSON) == 'object') {
          if (responseJSON.success || !$type(responseJSON.error))
            window.location.href = window.location.href;
          else
            alert(responseJSON.error);
        } else
          alert('An unknown error occurred; changes have not been saved.');
      }
    }).send('format=json&environment_mode='+mode);
  }
  //]]>
  </script>
</head>
<body id="global_page_<?php echo $identity ?>">
  
  <?php if( 'development' == APPLICATION_ENV ): ?>
  <div class="development_mode_warning">
    Your site is currently in development mode (which may decrease performance). 
    When you've finished changing your settings, remember to
    <a href="javascript:void(0)" onclick="changeEnvironmentMode('production', this);this.blur();this.getParent('.development_mode_warning').set('html', '<img src=\'application/modules/Core/externals/images/loading.gif\'>')">return to production mode</a>.
  </div>
  <?php endif ?>
  
  <!-- TOP HEADER BAR -->
  <div id='global_header_wrapper'>
    <div id='global_header'>
      <?php echo $this->content()->renderWidget('core.admin-menu-mini') ?>
      <?php echo $this->content()->renderWidget('core.admin-menu-logo') ?>
      <?php echo $this->content()->renderWidget('core.admin-menu-main') ?>
    </div>
  </div>


<!--
  <div id='global_menu_wrapper'>
    <?php echo $this->content()->renderWidget('core.admin-menu-main') ?>
  </div>
-->
  

  <!-- BEGIN CONTENT -->
  <div id='global_content_wrapper'>
    <div id='global_content'>
      <?php echo $this->layout()->content ?>
    </div>
  </div>

</body>
</html>
