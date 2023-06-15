<button class="sesbasic_bxs sesbasic_share_btn user-go-live"><?php echo $this->translate("Go Live") ?></button>

<script>
<?php $dataLiveStream =  $this->permissions;
      $dataLiveStream["loggedinUserId"] = $this->viewer()->getIdentity();
      $dataLiveStream["type"] = "host";
      
 ?>
 var elLiveStreamingContentDataUser = <?php echo json_encode($dataLiveStream); ?>;
 scriptJquery(document).on("click",'.user-go-live',function(e){
    if (scriptJquery("#elivestreaming_popup_iframe").length) {
        return;
    }
    scriptJquery("body").append("<div class='elive_loading'><span></span></div>");
    scriptJquery("body").append('<iframe id="elivestreaming_popup_iframe" src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('elivestreaming_linux_base_url',''); ?>" allow="camera;microphone" style="height: 100%;width: 100%;position:fixed;top:0;z-index: 100;left:0"></iframe>');
 })
</script>