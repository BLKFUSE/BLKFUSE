<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>

<h2>
    <?php echo $this->translate('Advanced Photos & Albums Plugin'); ?>
</h2>
<div class="sesbasic_nav_btns">
    <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php if (engine_count($this->navigation)): ?>
<div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
</div>
<?php endif; ?>
<div class="sesbasic_support_links">
    <a href="https://help.socialenginesolutions.com" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/faq.png" />
        <h4>FAQs</h4>
        <p>Get answers to all your random queries about this plugin from our Help Center. Click to read the tutorials and FAQs.</p>
    </a>
    <a href="https://socialnetworking.solutions/support/" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/support.png" />
        <h4>Support</h4>
        <p>If you face any issues with the plugin or you do not find answer to your query in our Help Center, then file a support ticket from here.</p>
    </a>
    <a href="https://socialenginesolutions.us16.list-manage.com/subscribe?u=70b1c9baba63dcf30fec2c66d&id=3c72ee2249" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/info.png" />
        <h4>Subscribe Newsletter</h4>
        <p>Do you want know more about "Questions & Answers Plugin" and it's features? If Yes, then click here to subscribe to our newsletter and stay updated.</p>
    </a>
    <a href="http://www.socialenginesolutions.com/contact-us/" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/contact.png" />
        <h4>Contact Us</h4>
        <p>Do you have a feature request, feedback or anything to discuss with our expert professionals directly, just Contact us freely.</p>
    </a>
    <a href="http://www.socialenginesolutions.com/blog/" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/update.png" />
        <h4>Blog Updates</h4>
        <p>We regularly post blogs about new releases & announcements. To read the new updates, check out our Blog Posts.</p>
    </a>
    <a href="https://www.socialengine.com/experts/profile/socialenginesolutions" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/review.png" style="filter:invert(0);" />
        <h4>Write a Review</h4>
        <p>Do you like this theme and wish to leave your feedback or review? You just have to "Write a Review" for it.</p>
    </a>
</div>
