<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contact-participants.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(engine_count($this->participants)):?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/mdetect/mdetect' . ( APPLICATION_ENV != 'development' ? '.min' : '' ) . '.js')->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/composer.js');?>
<script type="text/javascript">
  var composeInstance;
  en4.core.runonce.add(function() {
    var tel = new Element('div', {
      'id' : 'compose-tray',
      'styles' : {
        'display' : 'none'
      }
    }).inject($('submit'), 'before');

    var mel = new Element('div', {
      'id' : 'compose-menu'
    }).inject($('submit'), 'after');

    // @todo integrate this into the composer
    if ( '<?php 
         $id = Engine_Api::_()->user()->getViewer()->level_id;
         echo Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('messages', $id, 'editor');
         ?>' == 'plaintext' ) {
      if( !Browser.Engine.trident && !DetectMobileQuick() && !DetectIpad() ) {
        composeInstance = new Composer('body', {
          overText : false,
          menuElement : mel,
          trayElement: tel,
          baseHref : '<?php echo $this->baseUrl() ?>',
          hideSubmitOnBlur : false,
          allowEmptyWithAttachment : false,
          submitElement: 'submit',
          type: 'message'
        });
      }
    }
  });
</script>
<?php endif;?>

<?php if(!$this->is_ajax):?>
  <?php echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array('contest' => $this->contest));?>
  <div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
    <div class="sescontest_join_loading sescontest_join_overlay" <?php if(isset($_SESSION['show_message'])):?>style="display:block;"<?php endif;?>>
    <?php unset($_SESSION['show_message']);?>
    <div class="sescontest_join_success" style="display: block"><div class="sescontest_join_overlay_cont"><i><img src="application/modules/Sescontest/externals/images/success.png" alt="" /></i><span class="_text"><?php echo $this->translate('Your message has been sent successfully.');?></span></div></div>
  </div>
<?php endif;?>
<?php  echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array('contest' => $this->contest));?>
<div class="sesbasic_dashboard_form sescontest_db_contact_form success">
  
  <?php if(engine_count($this->participants)):?>
    <?php foreach( $this->composePartials as $partial ): ?>
      <?php echo $this->partial($partial[0], $partial[1]) ?>
    <?php endforeach; ?>
    <?php echo $this->form->render() ?>
      <div id="sescontest-participants">
        <?php foreach($this->participants as $participant):?>
          <span class="tag"><?php echo $participant->getTitle();?></span>
        <?php endforeach;?>
      </div>
  <?php else:?>
    <?php echo $this->translate("No Member has joined this contest yet.");?>
  <?php endif;?>
</div>
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php } ?>
<?php if($this->is_ajax) die; ?>
<?php if(engine_count($this->participants)):?>
  <script type="text/javascript">
    scriptJquery('#to-element').html(scriptJquery('#sescontest-participants').html());
    scriptJquery('#sescontest-participants').html('');
  </script>
<?php endif;?>
<script type='text/javascript'>
  if(scriptJquery('.sescontest_join_loading').css('display') == 'block') {
   setInterval(function(){ scriptJquery('.sescontest_join_loading').hide(); }, 3000);
  }
</script>
