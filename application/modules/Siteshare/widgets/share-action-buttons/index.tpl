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

<?php //$this->headLink()->appendStylesheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); ?>

<div class="siteshare_buttons_wp siteshare_button_layout_<?php echo $this->params['layout'] ?>  siteshare_<?php echo $this->params['columns'] ?>col">
  <?php
  $counter = 1;
  $maxAlllow = $this->params['numberOfButtons'];
  ?>
  <ul>
    <?php foreach( $this->socialNavigation as $item ): ?>
      <li  class="ss_button_wp" ><?php
        $class = str_replace($item->get('data-class'), '', $item->class);
        $countContent = '<span class="siteshare_count">' . ($this->socialServiceStats($item->get('data-url'))->getServiceCount($item->get('data-service')) ?: 0 ) . '</span>';
        $icon = '<i class="fa ' . $item->get('data-class') . '"></i>';
        $label = $this->translate('Shares');
        $transAction = $this->translate('SITESHARE_' . strtoupper($item->get('data-service')) . '_SERVICE_ACTION_LABEL');
        if( $transAction != 'SITESHARE_' . strtoupper($item->get('data-service')) . '_SERVICE_ACTION_LABEL' ) {
          $label = $transAction;
        }
        echo $this->htmlLink($item->getHref(), $icon . '<span>' . $countContent . '<span>' . $label . '</span></span>', array_filter(array(
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
    <?php if( $this->socialNavigation && $this->socialNavigation->count() > $maxAlllow && $this->params['moreButton']): ?>
      <li class="ss_button_wp">
        <?php
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $url = $request->getRequestUri();
        $countContent = '<span class="siteshare_count">' . $this->socialServiceStats()->getTotalCount() . '</span>';
        ?>
        <?php
        $class = 'siteshare_social_link_more menu_siteshare_social_link';
        $icon = '<i class="fa fa-share-alt "></i>';
        echo $this->htmlLink('javascript:void(0);', $icon . '<span>' . $countContent . '<span>' . $this->translate('Total Shares') . '</span></span>', array('class' => $class, 'title' => 'Total Shares', 'style' => ''));
        ?>
        <div class="dnone" id="siteshare_share_stats_content_wapper">
          <?php
          echo $this->partial('_socialShare.tpl', 'siteshare', array(
            'contentTitle' => '',
            'contentUrl' => $url,
            'navigation' => $this->socialNavigation
          ));
          ?>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function () {
    $$('.siteshare_ss_pagetitle').set('html', $$('title').get('html'));
    $$('.menu_siteshare_social_link').each(function (el) {
      var classes = el.get('class').split(' ').filter(function (string) {
        return string.test('siteshare_social_link_');
      });
      el.getParent('li').addClass(classes[0] + '_wappper');
    });

     $$('.siteshare_social_link_more').addEvent('click', function () {
      SmoothboxSEAO.open({
        class: 'siteshare_socialshare_smoothbox',
        element: $('siteshare_share_stats_content_wapper').get('html')
      });
    });
  });
</script>