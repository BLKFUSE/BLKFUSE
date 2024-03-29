<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: sanity.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php $this->headTitle($this->translate('Step %1$s', 1))->headTitle($this->translate('System Test')) ?>
<h1>
  <?php echo $this->translate('Step 1: Check Requirements') ?>
</h1>
<p>
  <?php echo $this->translate('Great! Next, let\'s make sure your server has everything it needs to support SocialEngine. If any of the requirements below are marked with red, you will need to address them before continuing. If items are marked with yellow, we recommend that you address them before installing, but you can continue if you wish.') ?>
</p>
<br />
<div class='sanity_wrapper'>
  <div>
    <ul class='sanity'>
      <?php foreach( $this->test->getTests() as $test ): ?>
        <li>
          <div>
            <?php echo $test->getName() ?>
          </div>
          <?php if( !$test->hasMessages() ): ?>
            <div class='sanity-ok'>
              <?php echo $test->getEmptyMessage(); ?>
          </div>
          <?php else: ?>
            <?php
              $errLevel = $test->getMaxErrorLevel();
              $errClass = ( $errLevel & 4 ? 'sanity-error' : ($errLevel & 3 ? 'sanity-notice' : 'sanity-ok' ));
            ?>
            <div class='<?php echo $errClass ?>'>
              <?php foreach( $test->getMessages() as $message ): ?>
								<?php if($test->getName() == 'mod_rewrite' && $errClass == 'sanity-notice') { ?>
									<?php echo $this->translate('Your server configuration is not running PHP as an Apache module so you\'ll need to manually verify that mod_rewrite is enabled in Apache. <br /> More info: <a href="https://stackoverflow.com/questions/18310183/how-to-check-for-mod-rewrite-on-php-cgi" target="_blank"> See KB article</a>.'); ?> <br />
								<?php } else { ?>
									<?php echo $message->toString() ?> <br />
                <?php } ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<br />
<?php if( $this->force ): ?>
  <p>
    <?php echo $this->translate('Force Install?') ?>
  </p>
  <div>
    <button onclick="window.location.href = '<?php echo $this->url(array('action' => 'db-info')) ?>';">
      <?php echo $this->translate('Force Install?...') ?>
    </button>
  </div>
<?php elseif( $this->maxOtherErrorLevel >= 4 ): ?>
  <p>
    <?php echo $this->translate('Please address all of the issues highlighted in red before continuing with the installation.') ?>
  </p>
  <br />
  <div>
    <button onclick="window.location.replace(window.location.href);">
      <?php echo $this->translate('Check Again') ?>
    </button>
  </div>
<?php elseif( $this->maxFileErrorLevel >= 4 ): ?>
  <p>
    <?php echo $this->translate('Please address all of the issues highlighted in red before continuing with the installation.'); ?>
  </p>
  <br />
  <p>
    <?php echo $this->translate('We noticed that some permissions have not been set correctly. To solve this, you can either attempt to %s (using your FTP information), or you can set the permissions manually by logging in with your FTP client and setting the necessary permissions, as shown above.',
      $this->htmlLink(array('action' => 'vfs', 'reset' => false), $this->translate('do it automatically'))) ?>
  </p>
  <br />
  <div>
    <button onclick="window.location.replace(window.location.href);">
      <?php echo $this->translate('Check Again') ?>
    </button>
  </div>
<?php else: ?>
  <div>
    <button onclick="window.location.href = '<?php echo $this->url(array('action' => 'db-info')) ?>';">
      <?php echo $this->translate('Continue...') ?>
    </button>
  </div>
<?php endif; ?>
