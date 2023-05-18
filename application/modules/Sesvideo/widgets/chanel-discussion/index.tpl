<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<div class="layout_core_comments">
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => "sesvideo_chanel", "id" => $this->subject->getIdentity(),'is_ajax_load'=>true)); 
        }else{ echo $this->action("list", "comment", "core", array("type" => "sesvideo_chanel", "id" => $this->subject->getIdentity())); } ?>
</div>