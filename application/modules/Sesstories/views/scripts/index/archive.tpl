<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: archive.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesstories/externals/scripts/core.js'); ?>
<?php if(!empty($this->archived)){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
        viewMoreHide_archived();
    });
    function viewMoreHide_archived() {
        if (document.getElementById('view_morearchived'))
            document.getElementById('view_morearchived').style.display = "<?php echo $this->archived['pagginator']['pagging']['total_page'] > $this->archived['pagginator']['pagging']['current_page'] ? 'block'  : 'none' ?>";
    }
    var requestViewMore_archived;
    function viewMore_archived (){
        document.getElementById('view_more_archived').style.display = 'none';
        document.getElementById('loading_image_archived').style.display = '';

        requestViewMore_archived = scriptJquery.ajax({
            method: 'post',
            'url': en4.core.baseUrl + "sesstories/index/archived-data",
            'data': {
                format: 'html',
                page: <?php echo $this->archivedPage; ?>,
        is_ajax : 1,
    },
        success: function(responseHTML) {
          scriptJquery('#sesstories_archived_data').append(responseHTML);
            document.getElementById('loading_image_archived').style.display = 'none';
        }
    });
        
        return false;
    }
</script>
<?php } ?>

<?php if(!empty($this->muted)){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
        viewMoreHide_muted();
    });
    function viewMoreHide_muted() {
        if (document.getElementById('view_moremuted'))
            document.getElementById('view_moremuted').style.display = "<?php echo $this->muted['pagginator']['pagging']['total_page'] > $this->muted['pagginator']['pagging']['current_page'] ? 'block'  : 'none' ?>";
    }
    var requestViewMore_muted;
    function viewMore_muted (){
        document.getElementById('view_more_muted').style.display = 'none';
        document.getElementById('loading_image_muted').style.display = '';

        requestViewMore_muted = scriptJquery.ajax({
            method: 'post',
            'url': en4.core.baseUrl + "sesstories/index/muted-data",
            'data': {
                format: 'html',
                page: <?php echo $this->mutedPage; ?>,
        is_ajax : 1,
    },
        success: function(responseHTML) {
          scriptJquery('#sesstories_muted_data').append(responseHTML);
            document.getElementById('loading_image_muted').style.display = 'none';
        }
    });
        
        return false;
    }
</script>
<?php } ?>

<?php if(!empty($this->is_ajax)){ ?>
    <?php if(!empty($this->archived)){ ?>
    <?php foreach($this->archived['result']['stories'][0]['story_content'] as $archived){ ?>
        <?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/index/archive-data.tpl'; ?>
    <?php } die; ?>
    <?php } ?>
    <?php if(!empty($this->muted)){ ?>
<?php foreach($this->muted['result'] as $muted){ ?>
        <?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/index/muted-data.tpl'; ?>
    <?php } die; ?>
    <?php } ?>


<?php } ?>


<div class="sesstories_archive_popup sesstories_bxs">
    <div class="sesstories_archive_popup_header">
        <ul id="sesstories_setting_cnt">
            <li><a href="javascript:;" class="_active"><?php echo $this->translate("Archive Stories"); ?></a></li>
            <li><a href="javascript:;"><?php echo $this->translate("SESSettings"); ?></a></li>
            <li><a href="javascript:;"><?php echo $this->translate("Stories You Muted"); ?></a></li>
        </ul>
    </div>
    <div class="sesstories_archive_popup_cont">
        <div class="sesstories_archive_stories" style="display:block">
            <ul id="sesstories_archived_data">
                <?php if($this->archived && engine_count($this->archived['result']['stories'][0]['story_content'])){ ?>
                <?php foreach($this->archived['result']['stories'][0]['story_content'] as $archived){ ?>
                        <?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/index/archive-data.tpl'; ?>
                <?php } ?>
                <?php }else{ ?>
                <li>
                	<div class="tip">
                		<span><?php echo $this->translate("There are no Archived stories yet."); ?></span>
                	</div>
                </li>
                <?php } ?>
            </ul>
            <div class="sesbasic_load_btn" style="display:none;" id="view_more_archived" onclick="viewMore_archived();"> <a style="display:none;" href="javascript:void(0);" id="feed_viewmore_link_archived" class="sesbasic_animation sesbasic_link_btn"><i class="fas fa-redo"></i><span>View More</span></a> </div>
            <div class="sesbasic_load_btn" id="loading_image_archived" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>
        </div>

        <div class="sesstories_stories_settings" style="display:none">
            <?php echo $this->form->setAttrib('class', 'sesstories_stories_settings_form')->render($this); ?>
        </div>
        <div class="sesstories_stories_muted" style="display: none;">
            <h3><?php echo $this->translate("Your Muted Stories"); ?></h3>
            <p><?php echo $this->translate("Here is a list of all users whose stories you previously muted."); ?></p>
            <ul id="sesstories_muted_data">
                <?php if($this->muted && engine_count($this->muted['result'])){ ?>
                <?php foreach($this->muted['result'] as $muted){ ?>
                <?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/index/muted-data.tpl'; ?>
                <?php } ?>
                <?php }else{ ?>
                <li>
                <div class="tip">
                	<span><?php echo $this->translate("You have not muted any user’s story yet."); ?></span>
                </div>
                </li>
                <?php } ?>

            </ul>
            <div class="sesbasic_load_btn" style="display:none;" id="view_more_muted" onclick="viewMore_muted();"> <a style="display:none;" href="javascript:void(0);" id="feed_viewmore_link_muted" class="sesbasic_animation sesbasic_link_btn"><i class="fas fa-redo"></i><span>View More</span></a> </div>
            <div class="sesbasic_load_btn" id="loading_image_muted" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
        </div>

    </div>
</div>

