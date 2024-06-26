<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php $randonNumber = $this->identity; ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jquery.min.js'); ?>
<?php $this->headScript()->appendFile($baseUrl . 'externals/ses-scripts/jquery.tagcanvas.min.js'); ?>
<div class="sestutorialc_sidebar_widget sestutorial_clearfix">
  <?php if($this->type == 'cloud'):?>
  <div id="myCanvasContainer_<?php echo $randonNumber ?>" style="height:<?php echo $this->height;  ?>px;">
   <canvas style="width:100%;height:100%;" id="myCanvas_<?php echo $randonNumber ?>">
    <p><?php echo $this->translate("Anything in here will be replaced on browsers that support the canvas element"); ?></p>
    <ul>
      <?php foreach($this->paginator as $valueTags):?>
	<?php if($valueTags['text'] == '' || empty($valueTags['text'] )):?>
	  <?php continue; ?>
	<?php endif;?>
	<li><a href="<?php echo $this->url(array('module' =>'sestutorial','controller' => 'index', 'action' => 'browse'),'sestutorial_general',true).'?tag_id='.$valueTags['tag_id'].'&tag_name='.$valueTags['text']  ;?>"><?php echo $valueTags['text'] ?></a></li>
      <?php endforeach;?>
    </ul>
   </canvas>
  </div>
  <?php else:?>
  <div class="sestutorial_tags_cloud_tutorial sestutorial_bxs ">
  	<ul class="sestutorial_tags_cloud_list">
      <?php foreach($this->paginator as $valueTags):?>
	<?php if($valueTags['text'] == '' || empty($valueTags['text'] )):?>
	  <?php continue; ?>
	<?php endif;?>
	<li><a href="<?php echo $this->url(array('module' =>'sestutorial','controller' => 'index', 'action' => 'browse'),'sestutorial_general',true).'?tag_id='.$valueTags['tag_id'].'&tag_name='.$valueTags['text']  ;?>"><?php echo $valueTags['text'] ?></a></li>
      <?php endforeach;?>
    </ul>
  </div>
  <?php endif;?>
  <a href="<?php echo $this->url(array('action' => 'tags'),'sestutorial_general',true);?>" class="sestutorial_more_link clear"><?php echo $this->translate("See All Tags");?> &raquo;</a>
</div>
<script type="text/javascript">
  scriptJquery(document).ready(function() {
    if( ! scriptJquery ('#myCanvas_<?php echo $randonNumber ?>').tagcanvas({
      textFont: 'Impact,"Arial Black",sans-serif',
      textColour: "#<?php echo $this->color; ?>",
      textHeight: "<?php echo $this->textHeight; ?>",
      maxSpeed : 0.03,
      depth : 0.75,
      shape : 'sphere',
      shuffleTags : true,
      reverse : false,
      initial :  [0.1,-0.0],
      minSpeed:.1
    })) {
      // TagCanvas failed to load
      scriptJquery ('#myCanvasContainer_<?php echo $randonNumber ?>').hide();
    }
  });
 </script>
