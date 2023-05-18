<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedgif
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _gif.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
$show = 1; //Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablestickers', 1);
$enablesearch = 1; //Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablesearch', 1);
$enableattachement = array('stickers'); //unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enableattachement', ''));
$giphyApi = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesfeedgif.giphyapi', '');
$limit = 15;
?>
<?php if(!$this->edit && !empty($show)) { ?>
  <!-- Sickers Search Box -->
<?php if(empty($giphyApi)){ ?>
    <div class="tip">
      <span>
        Gify API key not set!
      </span>
    </div>
<?php }else{ ?>
    <div class="ses_emoji_search_container sesbasic_clearfix gif_content">
      <div class="ses_emoji_search_bar">
        <div class="ses_emoji_search_input fa fa-search sesbasic_text_light">
          <input type="text" id="emessages_gif_search" onkeyup="displaygiflist(this.value);" placeholder="<?php echo $this->translate("Search GIF");?>" autocomplete="off">
        </div>	
      </div>
      
      <div class="ses_emoji_search_content sesbasic_custom_scroll sesbasic_clearfix main_search_category_srn">
        <ul class="" id="gify_append"></ul>
      </div>

      <div style="display:none;position:relative;height:255px;" class="main_search_cnt_srn" id="main_search_cnt_srn">
        <div class="sesgifsearch sesbasic_loading_container" style="height:100%;"></div>
      </div>
    </div>
  <?php } ?>

  <?php if(!$this->edit) { ?>
    <script type="application/javascript">
      displaygifseach();
      function displaygifseach() {
        scriptJquery("#gify_append").empty();
        scriptJquery.ajax({
          url: 'https://api.giphy.com/v1/gifs/trending?api_key=<?php echo $giphyApi; ?>&limit=<?php echo $limit; ?>&rating=G',
          method: "GET",
          enctype: 'multipart/form-data',
          data: {},
          success: function (html) {
            if (html['data'].length > 1) {
              scriptJquery("#gify_append").empty();
              for (var i = 0; i < html['data'].length; i++) {
                if (typeof (html['data'][i]['images']['downsized']['url']) != "undefined" && (html['data'][i]['images']['downsized']['url']) != null)
                  scriptJquery("#gify_append").append(
                    '<li rel="' +html['data'][i]['images']['downsized']['url'] + '">'+
                    '<a href="javascript:;" class="_sesadvgif_gif">'+
                    '<img src="' +html['data'][i]['images']['downsized']['url'] + '" alt="">'+
                    '</a>'+
                    '</li>');
              }
            }
          }
        });
      }
      function displaygiflist(text) {
        if(text.length<=0){ displaygifseach(); return false;  }
        scriptJquery("#main_search_cnt_srn").show();
        scriptJquery(".main_search_category_srn").hide();
        scriptJquery("#gify_append").empty();
        scriptJquery.ajax({
          url: 'https://api.giphy.com/v1/gifs/search?api_key=<?php echo $giphyApi; ?>&q='+text+'&limit=<?php echo $limit; ?>&offset=0&rating=G&lang=en',
          method: "GET",
          enctype: 'multipart/form-data',
          data: {},
          success: function (html) {
            if (html['data'].length > 1) {
              for (var i = 0; i < html['data'].length; i++) {
                if (typeof (html['data'][i]['images']['downsized']['url']) != "undefined" && (html['data'][i]['images']['downsized']['url']) != null)
                  scriptJquery("#gify_append").append(
                    '<li rel="' +html['data'][i]['images']['downsized']['url'] + '">'+
                    '<a href="javascript:;" class="_sesadvgif_gif">'+
                    '<img src="' +html['data'][i]['images']['downsized']['url'] + '" alt="">'+
                    '</a>'+
                    '</li>');            }
                scriptJquery("#main_search_cnt_srn").hide();
                scriptJquery(".main_search_category_srn").show();
              }
            }
          });
      }
    </script>
  <?php } ?>

  <?php if(!$this->edit && 0){ ?>
  <?php } ?>
<?php } ?>
