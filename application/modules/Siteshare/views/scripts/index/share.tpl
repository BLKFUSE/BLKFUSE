<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: share.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$this->headScript()
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>
<?php $this->headScript()
  ->appendFile($this->layout()->staticBaseUrl . '/application/modules/Siteshare/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . '/application/modules/Siteshare/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); ?>

<?php if( Engine_Api::_()->hasModuleBootstrap('sitehashtag') ): ?>
  <?php
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Advancedactivity/externals/scripts/composer.js');
  ?>
  <?php
  $composePartials = array(array('_composerHashtag.tpl', 'sitehashtag'));
  ?>
  <?php foreach( $composePartials as $partial ): ?>
    <?php echo $this->partial($partial[0], $partial[1], array("isAFFWIDGET" => 1)) ?>
  <?php endforeach; ?>
<?php endif; ?>
<div class="siteshare_share_popup sharelinksblock" >
  <h3><?php echo $this->translate('Share') ?></h3>
  <?php if( $this->social_navigation && count($this->social_navigation) ): ?>
  <div class="tabs_alt">
    <ul>
      <li  class="siteshare_share_tab active" data-tab="share">
        <a href="javascript:void(0)" >
          <?php $siteTitle = $this->settings('core_general_site_title', $this->translate('_SITE_TITLE')); ?>
          <?php echo $this->translate("Share on %s", $siteTitle) ?>
        </a>
      </li>
      <li  class="siteshare_share_tab" data-tab="social_share">
      <a href="javascript:void(0)" >
        <?php echo $this->translate('_SITESHARE_SOCIALSITES') ?>
      </a>
      </li>
      <a href="javascript:void()" class="fright cancelbtn" onclick="parent.Smoothbox.close()" ></a>
    </ul>
   </div>
    <div style="clear: both"></div>
  <?php endif; ?>
  <div class="siteshare_share_tab_content siteshare_share_tab_content_share ">
    <?php echo $this->form->setAttrib('class', 'global_form_popup')->render($this) ?>
      <div class="sitesharebox">
      <?php if($this->attachment->getType() === 'activity_action' && Engine_Api::_()->hasModuleBootstrap('advancedactivity')): ?>
        <ul class='feed feed_sections_left_round' id="activity-feed">
          <li class="activity-item">
        <?php echo $this->getRichContent($this->attachment);?>
          </li>
        </ul>
      <?php else: ?>
    <?php if( $this->attachment->getPhotoUrl() ): ?>
      <div class="sharebox_photo" style="background-image: url(<?php echo $this->attachment->getPhotoUrl()?>)">
      </div>
    <?php endif; ?>
    <div>
      <div class="sharebox_title">
        <?php echo $this->htmlLink($this->attachment->getHref(), $this->attachment->getTitle(), array('target' => '_parent')) ?>
      </div>
      <div class="sharebox_description">
        <?php echo $this->attachment->getDescription() ?>
      </div>
    </div>
  </div>
    <?php endif; ?>
  </div>
  <?php
  $divClass = $liClass = '';
  $buttonLayout = $this->settings('siteshare.share.socialbutton.layout', 'box_button');
  if( $buttonLayout == 'normol_button' ):
    $divClass = 'siteshare_buttons siteshare_list_buttons ss_icon_text_buttons';
    $liClass = 'ss_icon_text';
  endif;
  ?>
  <?php if( $this->social_navigation &&  count($this->social_navigation) ): ?>
    <div class="siteshare_share_tab_content siteshare_share_tab_content_social_share sitesharebox <?php echo $divClass?> dnone">
      <div>
        <ul class="navigation" >
        <?php foreach( $this->social_navigation as $link ): ?>
          <li class="<?php echo $liClass?>">
            <?php
            $label = '<span>'.$this->translate($link->getLabel()).'</span>';
            echo $this->htmlLink($link->getHref(), $label, array(
              'class' => ( $link->getClass() ? ' ' . $link->getClass() : '' ),
              'target' => $link->get('target'),
              'data-url' => $link->get('data-url'),
              'data-service' => $link->get('data-service'),
              'onclick' => 'en4.siteshare.socialService.clickHandler(this);'
            ))
            ?>
          </li>
        <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>
</div>

<script type="text/javascript">
//<![CDATA[
  var toggleFacebookShareCheckbox, toggleTwitterShareCheckbox;
  (function ($$) {
    toggleFacebookShareCheckbox = function () {
      $$('span.composer_facebook_toggle').toggleClass('composer_facebook_toggle_active');
      $$('input[name=post_to_facebook]').set('checked', $$('span.composer_facebook_toggle')[0].hasClass('composer_facebook_toggle_active'));
    }
    toggleTwitterShareCheckbox = function () {
      $$('span.composer_twitter_toggle').toggleClass('composer_twitter_toggle_active');
      $$('input[name=post_to_twitter]').set('checked', $$('span.composer_twitter_toggle')[0].hasClass('composer_twitter_toggle_active'));
    }
  })($$);
//]]>
</script>

<script type="text/javascript">
  var contentAutocomplete;
  var boxSize = 0;
  en4.core.runonce.add(function () {
    if (en4.user.viewer.id) {
      $("title-wrapper").style.display = 'none';
    }
    contentAutocomplete = new Autocompleter.Request.JSON('title', '<?php echo $this->url(array('module' => 'siteshare', 'controller' => 'index', 'action' => 'suggest-item'), 'default', true) ?>', {
      'postVar': 'text',
      'minLength': 1,
      'selectMode': 'pick',
      'autocompleteType': 'tag',
      'className': 'tag-autosuggest',
      'customChoices': true,
      'filterSubset': true,
      'multiple': false,
      'postData': {
        'type': 'timeline'
      },
      'injectChoice': function (token) {
        var choice = new Element('li', {'class': 'autocompleter-choices1', 'html': token.photo, 'id': token.label});
        new Element('div', {'html': this.markQueryValue(token.label), 'class': 'autocompleter-choice1'}).inject(choice);
        this.addChoiceEvents(choice).inject(this.choices);
        choice.store('autocompleteChoice', token);
      }
    });
    contentAutocomplete.addEvent('onSelection', function (element, selected, value, input) {
      var item = selected.retrieve('autocompleteChoice');
      document.getElementById('item_id').value = item.id;
      var myElement = new Element('span', {
        'id' : 'title_tag',
        'class' : 'tag',
        'html' :  item.label  + ' <a href="javascript:void(0);" ' +
                  'onclick="removeFromToValue();">x</a>'
      });
      $('title-wrapper').appendChild(myElement);
      $('title-wrapper').setStyle('height', 'auto');
      $('title-element').addClass('dnone');
      $('title').set('value', item.label);
      doAutoResize();
    });
    doAutoResize();
  });

  function removeFromToValue() {
    $('title').set('value', '');
    document.getElementById('item_id').value = 0;
    $('title-element').removeClass('dnone');
    if ($('title_tag')) {
      $('title_tag').destroy();
    }
  }

  function changeType(type) {
    var typeValue = type.get('value');
    contentAutocomplete.setOptions({
      'postData': {
        'type': typeValue
      }
    });
    removeFromToValue();
    $('title').required = true;
    if (typeValue != 'email') {
      $('title').placeholder = '<?php echo $this->string()->escapeJavascript($this->translate('Start typing...')) ?>';
    } else {
      $('title').placeholder = '<?php echo $this->string()->escapeJavascript($this->translate('Enter email address here')) ?>';
    }
    if (typeValue != 'timeline') {
      $("title-wrapper").style.display = 'block';
    } else {
      $("title-wrapper").style.display = 'none';
      $('title').required = false;
    }
    doAutoResize();
  }

  var doAutoResize = function () {
    parent.Smoothbox.instance.doAutoResize();
    var smoothbox = parent.Smoothbox.instance;
    var iframe = smoothbox.content;
    var element = Function.attempt(function () {
      return iframe.contentWindow.document.body.getChildren()[0];
    }, function () {
      return iframe.contentWindow.document.body;
    }, function () {
      return iframe.contentWindow.document.documentElement;
    });

    var size = Function.attempt(function () {
      return element.getScrollSize();
    }, function () {
      return element.getSize();
    }, function () {
      return {
        x: element.scrollWidth,
        y: element.scrollHeight
      }
    });

    var winSize = window.getSize();
    if (size.x - 50 > winSize.x)
      size.x = winSize.x - 50;
    if (size.y - 50 > winSize.y)
      size.y = winSize.y - 50;
    if (boxSize == 0) {
      boxSize = size.x + 20;
    }

    smoothbox.content.setStyles({
      'width': (boxSize - 20) + 'px',
      'height': (size.y + 20) + 'px'
    });

    smoothbox.options.width = (boxSize - 20);
    smoothbox.options.height = (size.y + 20);
    smoothbox.positionWindow();
  }

  en4.core.runonce.add(function () {
    $$('.siteshare_share_tab').addEvent('click', function (event) {
			
			var el =$(event.target).getParent();
      if (el.hasClass('active')) {
        return;
      }
      $$('.siteshare_share_tab_content').addClass('dnone');
      $$('.siteshare_share_tab').removeClass('active');
      $$('.siteshare_share_tab_content_' + el.get('data-tab')).removeClass('dnone');
      el.addClass('active');
      doAutoResize();
    });
  });

  en4.core.runonce.add(function(){
    $$('.menu_siteshare_social_link').each(function(el){
      var classes = el.get('class').split(' ').filter(function(string) {
        return string.test('siteshare_social_link_');
      });
      el.getParent('li').addClass(classes[0]+'_li');
    });
  });
</script>