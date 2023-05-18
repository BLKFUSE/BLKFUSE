<?php


?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmusic/views/scripts/dismiss_message.tpl';?>
<p>
  <?php echo $this->translate("This page contains utilities to help configure and troubleshoot the music plugin.") ?>
</p>
<br/>
<div class="settings">
  <form onsubmit="return false;">
    <h3><?php echo $this->translate("Ffmpeg Version") ?></h3>
    <?php echo $this->translate("This will display the current installed version of ffmpeg.") ?>
    <br /><br />
    <textarea style="width: 600px;"><?php echo $this->version;?></textarea>
  </form>
</div>
<div class="settings">
  <form onsubmit="return false;">
    <h3><?php echo $this->translate("Supported Music Formats") ?></h3>
    <?php echo $this->translate('This will run and show the output of "ffmpeg -formats". Please see this page for more info.') ?>
    <br /><br />
    <textarea style="width: 600px;"><?php echo $this->format;?></textarea>
  </form>
</div>
