<?php/** * SocialEngineSolutions * * @category   Application_Sescontest * @package    Sescontest * @copyright  Copyright 2017-2018 SocialEngineSolutions * @license    http://www.socialenginesolutions.com/license/ * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $ * @author     SocialEngineSolutions */?><?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?><div class="sescontest_contest_photo_widget sesbasic_clearfix">  <img style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px':$this->params['height'] ?>;width:<?php echo is_numeric($this->params['width']) ? $this->params['width'].'px':$this->params['width'] ?>" src="<?php echo $this->subject->getPhotoUrl('thumb.main'); ?>" alt="">  <?php if($this->params['show_title']): ?>    <span><?php echo $this->subject->getTitle(); ?></span>  <?php endif; ?></div>