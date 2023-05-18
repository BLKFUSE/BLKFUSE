<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="siteshare_buttons_popup social_<?php echo $this->params['columns'] ?>col  siteshare_buttons_popup_<?php echo $this->params['alignment'] ?>" >
  <a href="javascript:void(0);" class="siteshare_popup_icon_cancel" title="<?php echo $this->translate("Close") ?>"><i class='fa fa-close'></i></a>
  <div class="siteshare_popup_header">
    <div class="siteshare_popup_heading"><?php echo $this->params['heading'] ? $this->translate($this->params['heading']) : "" ?></div>
    <p class="siteshare_popup_message"><?php echo $this->params['message'] ? $this->translate($this->params['message']) : "" ?></p>
    <?php if( $this->params['totalStats'] && $this->socialServiceStats()->getTotalCount() ): ?>
      <p class="siteshare_popup_count"><?php echo $this->translate(array('%s share', '%s shares', $this->socialServiceStats()->getTotalCount()), '<b>' . $this->socialServiceStats()->getTotalCount() . '</b>'); ?></p>
    <?php endif; ?>
  </div>
  <div class="siteshare_buttons siteshare_list_buttons <?php if( $this->params['round'] ): ?> ss_round <?php endif; ?> <?php if( $this->params['buttonLabel'] === 'diff_label' ): ?> ss_icon_text_buttons <?php endif; ?>">
    <ul class="navigation">
      <?php
      $counter = 1;
      $maxAlllow = $this->params['numberOfButtons'];
      ?>
      <?php foreach( $this->socialNavigation as $item ): ?>
        <li class="<?php echo empty($this->params['buttonLabel']) ? ' icon_button' : ' ss_icon_text' ?>" ><?php
          $class = 'fa ' . (!empty($item->class) ? $item->class : '' );
          $countContent = $this->params['statsCount'] == 2 ? '<span class="siteshare_count">' . $this->socialServiceStats($item->get('data-url'))->getServiceCount($item->get('data-service')) . '</span>' : '';
          if( $this->params['buttonLabel'] ) : $class .= ' button_label';
          endif;
          echo $this->htmlLink($item->getHref(), '<span>' . $this->translate($item->getLabel()) . $countContent . '</span>' . $countContent, array_filter(array(
            'title' => $this->translate($item->getLabel()),
            'class' => $class,
            'target' => (!empty($item->target) ? $item->target : null ),
            'data-url' => $item->get('data-url'),
            'data-service' => $item->get('data-service'),
            'onclick' => 'en4.siteshare.socialService.clickHandler(this);'
          )))
          ?></li>
        <?php
        if( strpos($item->class, 'siteshare_social_link_whatsapp') != false ): continue;
        endif;
        if( ++$counter > ($maxAlllow) ) : break;
        endif;
        ?>
      <?php endforeach; ?>
      <?php if( $this->socialNavigation && $this->socialNavigation->count() > $maxAlllow && $this->params['moreButton'] ): ?>
        <li class="<?php echo empty($this->params['buttonLabel']) ? ' icon_button' : ' ss_icon_text' ?>">
          <?php
          $request = Zend_Controller_Front::getInstance()->getRequest();
          $url = $request->getRequestUri();

          $class = 'fa fa-share-alt siteshare_social_link_more menu_siteshare_social_link';
          $countContent = $this->params['statsCount'] ? '<span class="siteshare_count">' . $this->socialServiceStats()->getTotalCount() . '</span>' : '';
          if( $this->params['buttonLabel'] ) : $class .= ' button_label';
          endif;
          echo $this->htmlLink('javascript:void(0);', '<span>' . $this->translate('More') . $countContent . '</span>' . $countContent, array('class' => $class, 'title' => 'More', 'style' => ''));
          ?>
          <div class="dnone" id="siteshare_share_list_popup_content_wapper">
            <?php
            echo $this->partial('_socialShare.tpl', 'siteshare', array(
              'contentTitle' => '',
              'navigation' => $this->socialNavigation
            ));
            ?>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function () {
    $$('.siteshare_buttons_popup').inject(document.body);
    $$('.layout_siteshare_share_buttons_popup').destroy();
    $$('.siteshare_ss_pagetitle').set('html', $$('title').get('html'));
    $$('.menu_siteshare_social_link').each(function (el) {
      var classes = el.get('class').split(' ').filter(function (string) {
        return string.test('siteshare_social_link_');
      });
      el.getParent('li').addClass(classes[0] + '_li');
    });

    $$('.siteshare_social_link_more').addEvent('click', function () {
      SmoothboxSEAO.open({
        class: 'siteshare_socialshare_smoothbox',
        element: $('siteshare_share_list_popup_content_wapper').get('html')
      });
    });

    $$('.siteshare_popup_icon_cancel').addEvent('click', function () {
      $$('.siteshare_buttons_popup').removeClass('siteshare_buttons_popup_visible').addClass('siteshare_buttons_popup_hidden');
    });
    window.addEventListener("scroll", function (event) {
      var top = (window.getScrollTop() / (window.getScrollSize().y - window.getHeight())) * 100;
      if (top > 25) {
        $$('.siteshare_buttons_popup').addClass('siteshare_buttons_popup_visible');
      }
    });
  });
</script>