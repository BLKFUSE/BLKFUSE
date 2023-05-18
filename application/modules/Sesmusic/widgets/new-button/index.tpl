<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<div class="quicklinks">
	<a href="<?php echo $this->url(array('action' => 'create','new'=>true), 'sesmusic_general', true); ?>" class="sesmusic_create_button"><?php echo $this->translate($this->title); ?></a>
</div>
