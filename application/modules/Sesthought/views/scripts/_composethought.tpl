<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _composethought.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php 
$request = Zend_Controller_Front::getInstance()->getRequest();
$requestParams = $request->getParams();
 
if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) { 
  return;
}
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/scripts/composer_thought.js'); ?>

<?php 
$categories = Engine_Api::_()->getDbtable('categories', 'sesthought')->getCategoriesAssoc();
$data = '';
if (engine_count($categories) > 0) {
  $categories = array('' => 'Choose Category') + $categories;
  foreach($categories as $key => $category) {
    $data .= '<option value="' . $key . '" >' . Zend_Registry::get('Zend_Translate')->_($category) . '</option>';
  }
}
?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    composeInstance.addPlugin(new Composer.Plugin.Thought({
      title: '<?php echo $this->string()->escapeJavascript($this->translate('Thought')) ?>',
      categoryOptionValues: '<?php echo $data; ?>',
    }));
  });
</script>
