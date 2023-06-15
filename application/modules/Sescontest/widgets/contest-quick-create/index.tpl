<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="sescontest_sidebar_button">
  <a href="<?php echo $this->url(array('action' => 'create', 'ref' => $this->contest_id),'sescontest_general',true);?>" data-url="1" class="sesbasic_animation sesbasic_link_btn"><i class="fa fa-plus"></i><span><?php echo $this->translate("Post Similar Contest");?></span></a>
</div>