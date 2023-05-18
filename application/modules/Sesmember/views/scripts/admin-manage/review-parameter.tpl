<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-parameter.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>
<style>
  .error {
    color:#FF0000;
  }
</style>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
      <h3><?php echo $this->translate("") ?> </h3>
      <p class="description"></p>
      <div class="sesbasic-categories-listing" style="width:100%">
      	<div id="error-message-category-delete"></div>
        <form id="multimodify_form" method="post" onsubmit="return multiModify();">
          <table class='admin_table' style="width: 100%;">
            <thead>
	      <tr>
		<th><?php echo $this->translate("Profile Type") ?></th>
		<th><?php echo $this->translate("Review Parameters") ?></th>
		<th><?php echo $this->translate("Options") ?></th>
	      </tr>
            </thead>
            <tbody>
              <?php foreach ($this->topLevelOptions as $key => $profileType):?>
		<tr id="profile_type-<?php echo $key; ?>" data-article-id="<?php echo $key; ?>">
		  <td>
		    <?php echo $profileType; ?>
		    <div class="hidden" style="display:none" id="inline_<?php echo $key; ?>">
		      <div class="parent">0</div>
		    </div>
		  </td>
		  <?php $reviewParameter = Engine_Api::_()->getDbtable('parameters', 'sesmember')->getParameterResult(array('profile_type'=>$key)); ?>
		  <td>
		    <?php $titleEAC = 'Add';?>
		    <?php if(engine_count($reviewParameter)):?>
		      <?php $titleEAC = 'Edit';?>
		      <ul class="sesmember_parameters_list">
			<?php foreach($reviewParameter as $val): ?>
			  <li><?php echo $val['title']; ?></li>
			<?php endforeach; ?>
		      </ul>
		    <?php else: ?>
		      -
		    <?php endif;?>
		  </td>
		  <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'add-parameter', 'id' => $key), $this->translate($titleEAC.' Parameter'), array('class'=> "smoothbox")); ?>
		</tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
