<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: editad.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<div class="global_form_popup">

<?php echo $this->form->setAttrib('class', '')->render($this) ?>

</div>
<script type="application/javascript">
  scriptJquery('.core_admin_main_monetization').parent().addClass('active');
  scriptJquery('.core_admin_main_ads').addClass('active');
</script>
