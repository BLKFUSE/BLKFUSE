<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: compose-upload.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Sami
 */
?>

<script type="text/javascript">
  try{
    parent._composeMusicResponse = <?php echo $this->jsonInline($this->getVars()) ?>;
  } catch(err){ console.log(err); };
</script>
