<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contest-winners.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
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

<?php if(!$this->is_ajax):?>
  <?php echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array('contest' => $this->contest));?>
  <div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
  <?php endif;?>
<?php  echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array('contest' => $this->contest));?>
<div class="sesbasic_dashboard_form sescontest_db_contact_form">
<?php if(engine_count($this->winners)):?>
<?php foreach( $this->composePartials as $partial ): ?>
  <?php echo $this->partial($partial[0], $partial[1]) ?>
<?php endforeach; ?>
  <?php echo $this->form->render() ?>
  <div id="sescontest-participants">
  	<div>
      <?php foreach($this->winners as $winner):?>
        <div>
          <input type="checkbox" name="winner[]" value="<?php echo $winner->user_id;?>"/>
          <label><?php echo $winner->getTitle();?></label>
        </div>
      <?php endforeach;?>
  	</div>
  </div>
  <?php else:?>
    <?php echo $this->translate("No winners have been declared yet.");?>
  <?php endif;?>
</div>
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php } ?>
<?php if($this->is_ajax) die; ?>

<script type="text/javascript">
scriptJquery('#to-element').html(scriptJquery('#sescontest-participants').html());
scriptJquery('#sescontest-participants').html('');
</script>
