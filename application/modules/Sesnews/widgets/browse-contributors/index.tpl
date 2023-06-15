<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
  <?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
  <?php $randonNumber = $this->identity;?> 
<?php endif;?>

<?php if(!$this->is_ajax): ?>
  <div class="ses_member_img_view" id="scrollHeightDivSes_<?php echo $randonNumber; ?>">
    <ul id="news_contributors_<?php echo $randonNumber; ?>" style="position:relative;">
<?php endif;?>
    <?php foreach($this->paginator as $item):?>
      <?php $user = Engine_Api::_()->getItem('user', $item->user_id);?>
      <li class="contributors" style="width:<?php echo is_numeric($this->photo_width) ? $this->photo_width.'px' : $this->photo_width;?>;">
        <div class="ses_member_grid_thumd">
          <a href="<?php echo $user->getHref();?>"><span style="background-image:url(<?php echo $user->getPhotoUrl('thumb.main');?>); width:100%; height:<?php echo is_numeric($this->photo_height) ? $this->photo_height.'px' : $this->photo_height;?>;"></span></a>
          <div class="ses_membre_gird_title">
            <?php if(strlen($user->getTitle()) > $this->title_truncation_list):?>
              <?php $title = mb_substr($user->getTitle(),0,$this->title_truncation_list).'...';?>
              <?php echo $this->htmlLink($user->getHref(),$title,array('title'=>$user->getTitle()));?>
            <?php else: ?>
              <?php echo $this->htmlLink($user->getHref(),$user->getTitle(),array('title'=>$user->getTitle())  ) ?>
            <?php endif;?>
          </div>
        </div>
      </li>
    <?php endforeach;?> 
    <?php if($this->loadOptionData == 'pagging'): ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
    <?php endif;?>
<?php if(!$this->is_ajax): ?>
  </ul>
</div>
<?php endif;?>

<?php if(!$this->is_ajax){ ?>
  <?php if($this->loadOptionData != 'pagging'):?>
    <div class="sesbasic_view_more" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'buttonlink icon_viewmore')); ?> </div>
    <div class="sesbasic_view_more_loading sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>
  <?php endif;?>
  <script type="text/javascript">
    <?php if($this->loadOptionData == 'auto_load'){ ?>
      scriptJquery( window ).load(function() {
        scriptJquery(window).scroll( function() {
          var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
          var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
          if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
            document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
          }
        });
      });
    <?php } ?>
  </script>
<?php } ?>

<script type="text/javascript">
  var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
  var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
  var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
  var searchParams<?php echo $randonNumber; ?> ;
  var is_search_<?php echo $randonNumber;?> = 0;
  <?php if($this->loadOptionData != 'pagging'){ ?>
    viewMoreHide_<?php echo $randonNumber; ?>();	
    function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
      requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>",
        'data': {
          format: 'html',
          page: page<?php echo $randonNumber; ?>,    
          params : params<?php echo $randonNumber; ?>, 
          is_ajax : 1,
          is_search:is_search_<?php echo $randonNumber;?>,
          view_more:1,
          searchParams:searchParams<?php echo $randonNumber; ?> ,
          identity : '<?php echo $randonNumber; ?>',
          identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
          scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if(document.getElementById('loadingimgsesnews-wrapper'))
          scriptJquery('#loadingimgsesnews-wrapper').hide();
          
          scriptJquery('#news_contributors_<?php echo $randonNumber; ?>').append(responseHTML);
          
          document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        }
      });
      
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
      requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>",
        'data': {
          format: 'html',
          page: pageNum,    
          params :params<?php echo $randonNumber; ?> , 
          is_ajax : 1,
          searchParams:searchParams<?php echo $randonNumber; ?>  ,
          identity : identity<?php echo $randonNumber; ?>,
          type:'<?php echo $this->view_type; ?>'
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
          scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if(document.getElementById('loadingimgsesnews-wrapper'))
          scriptJquery('#loadingimgsesnews-wrapper').hide();
          scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
          document.getElementById('news_contributors_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
        }
      }));
      
      return false;
    }
  <?php } ?>
</script>

<style type="text/css">
  .ses_member_img_view ul{
    clear:both;
    overflow:hidden;
    padding:0px;
    margin:0px;
  }
  .ses_member_img_view ul li.contributors{
    list-style:none;
    float:left;
    margin:10px;
    position:relative;
      
  }
  .ses_member_img_view ul li .ses_member_grid_thumd a{
    display: inline-block;
    vertical-align: bottom;
    width: 100%;
  }
  .ses_member_img_view ul li .ses_member_grid_thumd a span{
    background-position: center 50%;
    background-color: #444;
    background-repeat: no-repeat;
    background-size: cover;
    display: block;
    transition: all 0.4s ease-in 0s;
    transform: rotate(0deg);
  }

  .ses_member_img_view ul li:hover .ses_member_grid_thumd a span {
    transform: scale(1.1) translate(0px, -4px) rotate(0.02deg);
    -webkit-transform: scale(1.1) translate(0px, -4px) rotate(0.02deg);
    filter: grayscale(100%);
    -webkit-filter: grayscale(100%);
    filter: gray;
  }
  .ses_member_img_view ul li .ses_member_grid_thumd{
    border-radius: 5px 5px 0 0;
    float: left;
    display: block;
    position: relative;
    width: 100%;
    text-align: center;
    margin: 10px 8px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  .ses_member_img_view ul li .ses_member_grid_thumd .ses_membre_gird_title{
    background: -webkit-linear-gradient(top, rgba(22, 24, 27, 0), rgba(22, 24, 27, 0.3) 30px, rgba(22, 24, 27, 0.8));
    background: -moz-linear-gradient(top, rgba(22, 24, 27, 0), rgba(22, 24, 27, 0.3) 30px, rgba(22, 24, 27, 0.8));
    background: -o-linear-gradient(top, rgba(22, 24, 27, 0), rgba(22, 24, 27, 0.3) 30px, rgba(22, 24, 27, 0.8));
    background: -ms-linear-gradient(top, rgba(22, 24, 27, 0), rgba(22, 24, 27, 0.3) 30px, rgba(22, 24, 27, 0.8));
    background: linear-gradient(top, rgba(22, 24, 27, 0), rgba(22, 24, 27, 0.3) 30px, rgba(22, 24, 27, 0.8));
    bottom: 0px;
    position: absolute;
    padding: 30px 7px 7px;
    font-weight: bold;
    left: 0;
    right: 0;
    z-index: 1;
    text-align:left;
  }
  .ses_member_img_view ul li .ses_member_grid_thumd .ses_membre_gird_title a{
    font-size:15px;
    color:#fff;
    text-shadow:2px 0 4px #000000;
    text-decoration:none;
  }
</style>
