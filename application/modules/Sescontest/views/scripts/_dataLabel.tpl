<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _dataLabel.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($this->featuredLabelActive) && $contest->featured):?>
<span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
<?php endif;?>
<?php if(isset($this->sponsoredLabelActive) && $contest->sponsored):?>
<span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
<?php endif;?>
<?php if(isset($this->hotLabelActive) && $contest->hot):?>
<span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
<?php endif;?>