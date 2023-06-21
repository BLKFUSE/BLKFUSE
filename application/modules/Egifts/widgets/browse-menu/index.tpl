<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Ecoupon/externals/styles/styles.css'); ?> 
<div class="headline">
  <h2><?php echo $this->translate('Gifts'); ?></h2>
	<div class="tabs">
		<ul class="navigation">
			<?php foreach($this->navigation as $navigationMenu ): ?>
				<li <?php if ($navigationMenu->active): ?><?php echo "class='active'";?><?php endif; ?>>
					<?php if ($navigationMenu->action): ?>
						<a class= "<?php echo @$navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array('action' => $navigationMenu->action), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
					<?php else : ?>
						<a class= "<?php echo @$navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach;  ?>
		</ul>
	</div>
</div>
