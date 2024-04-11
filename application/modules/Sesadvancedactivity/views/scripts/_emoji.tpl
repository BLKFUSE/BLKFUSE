<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _emoji.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<div  class="emoji_contents">
  <?php 
    if($this->edit)
      $class="edit";
    else
      $class = '';
    $emojis = Engine_Api::_()->getApi('emoji','sesbasic')->getEmojisArray();?>
    <div class="sesbasic_custom_scroll">
    <ul class="_simemoji">
    <?php
    foreach($emojis as $key=>$emoji){ ?>   
      <li rel="<?php echo $key; ?>"><a href="javascript:;" class="select_emoji_adv<?php echo $class; ?>"><?php echo $emoji; ?></a></li>  
  <?php 
    } ?>
    </ul>
    </div>
    <?php if(!$this->edit){ ?>
    <script type="application/javascript">
    scriptJquery(document).on('click','.select_emoji_adv > img',function(e){
      var code = scriptJquery(this).parent().parent().attr('rel');
      var html = scriptJquery('.compose-content').html();
      if(html == '<br>')
        scriptJquery('.compose-content').html('');
      composeInstance.setContent(composeInstance.getContent()+' '+code);
    });
    </script>
    <?php } ?>
  </div>