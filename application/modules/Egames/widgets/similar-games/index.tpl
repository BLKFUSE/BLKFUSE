<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egames/externals/styles/styles.css'); ?>

<div class="sesbasic_bxs">
  <div class="egames_listing" id="egames_listing">
    <?php foreach($this->paginator as $game){ ?>
      <div class="egames_listing_item">
        <article>
          <div class="egames_listing_item_thumb">
            <a href="<?php echo $game->getHref(); ?>"><img src="<?php echo $game->getPhotoUrl(); ?>" alt="<?php echo $game->getTitle(); ?>" /></a>
          </div>
          <div class="egames_listing_item_info">
            <div class="_title">
              <a href="<?php echo $game->getHref(); ?>"><?php echo $game->getTitle(); ?></a>
            </div>
          </div>
        </article>
      </div>
    <?php } ?>
  </div>
</div>
