<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: seo.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(!$this->is_ajax){ 
echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array(
	'contest' => $this->contest,
      ));	
?>
	<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php } 
	echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array(
	'contest' => $this->contest,
      ));	
?>
    	<div class="sesbasic_dashboard_form">
    		<?php echo $this->form->render() ?>
      </div>
    
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php  } ?>
<?php if($this->is_ajax) die; ?>