<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Music/views/scripts/_adminHeader.tpl';?>
<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="text/javascript">
  //<![CDATA[
  scriptJquery('#level_id').on('change', function() {
    var url = '<?php echo $this->url(array('id' => null)) ?>';
    window.location.href = url + '/index/id/' + scriptJquery(this).val();
  });
  //]]>
</script>
