<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _composePhoto.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php
$request = Zend_Controller_Front::getInstance()->getRequest();
$requestParams = $request->getParams();

if(($requestParams['action'] == 'home' || $requestParams['action'] == 'index') && $requestParams['module'] == 'user' && ($requestParams['controller'] == 'index' || $requestParams['controller'] == 'profile') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<a style="display:none;" href="javacript:;"  class="sessmoothbox" id="sesalbum_act_a"></a>
<?php $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/scripts/composer_album.js'); ?>
<script type="text/javascript">
var isOpenPopup = 0;
scriptJquery(document).on('click','.sesalbum_popup_sesadv',function(){
  var hrefOpupSesalbum = 'sesalbum/index/create/params/anfwallalbum/ispopup/'+isOpenPopup;
  scriptJquery('#sesalbum_act_a').attr('href',hrefOpupSesalbum);
  scriptJquery('#sesalbum_act_a').trigger('click');
})


en4.core.runonce.add(function() {      
 composeInstance.addPlugin(new Composer.Plugin.Album({
      title: '<?php echo $this->string()->escapeJavascript($this->translate('Add Album')) ?>',
      lang : {
            'Add Album' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Album')) ?>',
            'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
      }
    }));
 });
</script>
<?php } ?>
