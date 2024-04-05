<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contact-information.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php if(!$this->is_ajax){ 
echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array(
	'news' => $this->news,
      ));	
?>
	<div class="sesnews_dashboard_content sesbm sesbasic_clearfix">
<?php } ?>
    	<div class="sesbasic_dashboard_form">
    		<?php echo $this->form->render() ?>
      </div>
    
<?php if(!$this->is_ajax){ ?>
    </div>
</div>
</div>
<?php  } ?>
<?php if($this->is_ajax) die; ?>
