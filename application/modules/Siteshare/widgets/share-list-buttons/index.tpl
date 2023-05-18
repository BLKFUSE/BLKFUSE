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

<div class="siteshare_buttons siteshare_list_buttons siteshare_<?php echo $this->params['columns']; ?>col <?php if ($this->params['round']): ?> ss_round <?php endif; ?> <?php if ($this->params['buttonLabel'] === 'diff_label'): ?> ss_icon_text_buttons <?php endif; ?>">
  <ul class="navigation">
    <?php
    $counter = 1;
    $maxAlllow = $this->params['numberOfButtons'];
    $sub = !empty($this->viewer()->getIdentity()) && $this->subject() ? 1 : 0;
    ?>
    <?php foreach( $this->socialNavigation as $item ): ?>
      <li class="<?php echo empty($this->params['buttonLabel'])  ? ' icon_button' : ' ss_icon_text'  ?>" ><?php
        $class = 'fa ' . (!empty($item->class) ? $item->class : '' );
        $countContent = $this->params['statsCount'] == 2 ?'<span class="siteshare_count">'.$this->socialServiceStats($item->get('data-url'))->getServiceCount($item->get('data-service')).'</span>' : '';
        if( $this->params['buttonLabel'] ) : $class .= ' button_label';
        endif;
        echo $this->htmlLink($item->getHref(), '<span>'.$this->translate($item->getLabel()).$countContent.'</span>'.$countContent, array_filter(array(
          'title' => $this->translate($item->getLabel()),
          'class' => $class,
          'target' => (!empty($item->target) ? $item->target : null ),
          'data-url' => $item->get('data-url'),
          'data-service' => $item->get('data-service'),
          'onclick' => 'en4.siteshare.socialService.clickHandler(this);'
        )))
        ?></li>
      <?php
      if(strpos($item->class, 'siteshare_social_link_whatsapp') != false): continue; endif;
      if( ++$counter > ($maxAlllow - $sub)) : break;
      endif;
      ?>
    <?php endforeach; ?>
    <?php if( !empty($this->viewer()->getIdentity()) && $this->subject() ): ?>
      <li class="<?php echo empty($this->params['buttonLabel'])  ? ' icon_button' : ' ss_icon_text'  ?>">
        <?php
        $subject = $this->subject();
        $class = 'smoothbox fa fa-share siteshare_social_link_share menu_siteshare_social_link';
        if( $this->params['buttonLabel'] ) : $class .= ' button_label';
        endif;
        echo $this->htmlLink(
          array(
          'route' => 'default',
          'module' => 'siteshare',
          'controller' => 'index',
          'action' => 'share',
          'type' => $subject->getType(),
          'id' => $subject->getIdentity(),
          'format' => 'smoothbox',
          'advanced_share' => 1
          ), '<span>'.$this->translate('Share').'</span>', array('class' => $class, 'title' => 'Share', 'style' => ''));
        ?>
      </li>
      <?php endif; ?>

    <?php if( $this->socialNavigation && $this->params['moreButton'] && $this->socialNavigation->count() > $maxAlllow ): ?>
      <li class="<?php echo empty($this->params['buttonLabel'])  ? ' icon_button' : ' ss_icon_text'  ?>">
        <?php
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $url = $request->getRequestUri();
        $countContent = $this->params['statsCount'] ? '<span class="siteshare_count">'.$this->socialServiceStats()->getTotalCount().'</span>' : '';
        ?>
        <?php
        $class = 'fa fa-share-alt siteshare_social_link_more menu_siteshare_social_link';
        if( $this->params['buttonLabel'] ) : $class .= ' button_label';
        endif;
        echo $this->htmlLink('javascript:void(0);', '<span>' . $this->translate('More') . $countContent . '</span>' . $countContent, array('class' => $class, 'title' => 'More', 'style' => ''));
        ?>
        <div class="dnone" id="siteshare_share_list_content_wapper">
          <?php echo $this->partial('_socialShare.tpl', 'siteshare', array(
            'contentTitle' => '',
            'contentUrl' => $url,
            'navigation' => $this->socialNavigation
          )); ?>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function () {
    $$('.siteshare_ss_pagetitle').set('html', $$('title').get('html'));
    $$('.menu_siteshare_social_link').each(function(el){
      var classes = el.get('class').split(' ').filter(function(string) {
        return string.test('siteshare_social_link_');
      });
      el.getParent('li').addClass(classes[0]+'_li');
    });

    $$('.siteshare_social_link_more').addEvent('click', function () {
      SmoothboxSEAO.open({
        class: 'siteshare_socialshare_smoothbox',
        element: $('siteshare_share_list_content_wapper').get('html')
      });
    });
  });
</script>