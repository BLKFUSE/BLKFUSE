var dataEliveStreamingUserId = '';
var dataEliveStreamingActionId = '';
var dataEliveStreamingStoryId = '';
var dataEliveStreamingHostId = '';
scriptJquery(document).on("click",'.elivestreaming_data_a',function (e) {
    e.preventDefault();
    dataEliveStreamingUserId = scriptJquery(this).data('user');
    dataEliveStreamingActionId = scriptJquery(this).data('action');
    dataEliveStreamingStoryId = scriptJquery(this).data('story');
    dataEliveStreamingHostId = scriptJquery(this).data('hostid');
    if(elLiveStreamingCheckContentData) {
        var iframeURL = elLiveStreamingCheckContentData.elivestreaming_linux_base_url;
        scriptJquery("body").append("<div class='elive_loading'><span></span></div>");
        scriptJquery("body").append('<iframe id="elivestreaming_host_popup_iframe" src="'+iframeURL+'" allow="camera;microphone" style="height: 100%;width: 100%;position:fixed;top:0;z-index: 100;left:0"></iframe>');
    }
});

if (window.addEventListener) {
    window.addEventListener("message", closeIframeelivestreaming);
    window.addEventListener("message", getEliveStreamingDefaultOptions);
} else {
    window.attachEvent("onmessage", closeIframeelivestreaming);
    window.attachEvent("onmessage", getEliveStreamingDefaultOptions);
}
function getEliveStreamingDefaultOptions(evt) {
    if (evt.data == "getLiveSteamingData") {
        var frame = scriptJquery("#elivestreaming_popup_iframe");
        if(frame.length)
            document.getElementById("elivestreaming_popup_iframe").contentWindow.postMessage({defaultVal:"livestreaming",value:elLiveStreamingContentData}, '*');
        else{
            var elLiveStreamingCheckContentDataJson = elLiveStreamingCheckContentData
            elLiveStreamingCheckContentDataJson['elivehost_id'] = dataEliveStreamingHostId;
            elLiveStreamingCheckContentDataJson['story_id'] = dataEliveStreamingStoryId;
            elLiveStreamingCheckContentDataJson['user_id'] = dataEliveStreamingUserId;
            elLiveStreamingCheckContentDataJson['activity_id'] = dataEliveStreamingActionId;
            document.getElementById("elivestreaming_host_popup_iframe").contentWindow.postMessage({defaultVal:"livestreaming",value:elLiveStreamingCheckContentData}, '*');
        }
    }
}
function closeIframeelivestreaming(evt) {
    scriptJquery(".elive_loading").remove();
    if (evt.data == "closePopup") {
        var frame = scriptJquery("#elivestreaming_popup_iframe");
        if(frame.length)
            scriptJquery('#elivestreaming_popup_iframe').remove();
        else{
            scriptJquery('#elivestreaming_host_popup_iframe').remove();
        }
    }
}
