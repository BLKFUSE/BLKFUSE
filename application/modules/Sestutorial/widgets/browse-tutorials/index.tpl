<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>


<script type="text/javascript">
  function loadMore() {
  
    if (document.getElementById('view_more'))
      document.getElementById('view_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";

    if(document.getElementById('view_more'))
      document.getElementById('view_more').style.display = 'none';
    
    if(document.getElementById('loading_image'))
     document.getElementById('loading_image').style.display = '';

    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sestutorial/name/browse-tutorials',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1,
        params: '<?php echo json_encode($this->all_params); ?>',
        
      },
      success: function(responseHTML) {
        scriptJquery('#sestutorial_results').append(responseHTML);

        if(document.getElementById('view_more'))
          scriptJquery('#view_more').remove();
        
        if(document.getElementById('loading_image'))
         scriptJquery('#loading_image').remove();
               if(document.getElementById('loadmore_list'))
         scriptJquery('#loadmore_list').remove();
      }
    }));
    return false;
  }
  
  function showTutorial(id) {
  
    if(document.getElementById('sestutorial_question_answer_cont_'+id).style.display == 'block' || document.getElementById('sestutorial_question_answer_cont_'+id).style.display == '') {
      document.getElementById('sestutorial_question_answer_cont_'+id).style.display = 'none';
      document.getElementById('sestutorial_question_'+id).innerHTML = '<i class="far fa-plus-square"></i>';
    } else {
      document.getElementById('sestutorial_question_answer_cont_'+id).style.display = 'block';
      document.getElementById('sestutorial_question_'+id).innerHTML = '<i class="far fa-minus-square"></i>';
    }
  
  }
</script>


<?php if(is_countable($this->paginator) && engine_count($this->paginator) > 0): ?>
<!--question answer list view block-->
<?php if($this->viewtype == 'listview'): ?>
  <?php if (empty($this->viewmore)): ?>
    <div class="sestutorial_question_answer_list_content sestutorial_clearfix sestutorial_bxs" id="sestutorial_results">
  <?php endif;?>  
      <?php foreach($this->paginator as $tutorial): //print_r($tutorial->toarray());die; ?>
        <div class="sestutorial_question_answer_section _isexpcol" >
          <?php //if(engine_in_array('photo', $this->showinformation)): ?>
<!--            <div class="sestutorial_question_answer_img">
              <a href="<?php //echo $tutorial->getHref(); ?>"><img src="<?php //echo $tutorial->getPhotoUrl(); ?>" /></a>
            </div>-->
          <?php //endif; ?>
          <div class="sestutorial_question_answer_content_section">
            <div class="sestutorial_question_answer_title">
            	<?php if($this->showicons) { ?><a onclick="showTutorial('<?php echo $tutorial->getIdentity(); ?>')" class="sestutorial_question_answer_expcol_btn" id="sestutorial_question_<?php echo $tutorial->getIdentity(); ?>"><i  class="far fa-minus-square"></i></a><?php } ?>
              <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
            </div>
            <div class="sestutorial_question_answer_cont" id="sestutorial_question_answer_cont_<?php echo $tutorial->getIdentity(); ?>">
              <?php if(is_array($this->showinformation) && engine_in_array('description', $this->showinformation)): ?>
                <div class="sestutorial_question_answer_discription">
                  <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                </div>
              <?php endif; ?>
              <div class="sestutorial_question_answer_stats">
                <ul>
                  <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
                    <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                  <?php endif; ?>
                  <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
                    <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                  <?php endif; ?>
                  <?php if(engine_in_array('likecount', $this->showinformation)): ?>
                    <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                  <?php endif; ?>
                  <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
                    <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                  <?php endif; ?>
                </ul>
                <?php if(is_array($this->showinformation) && engine_in_array('readmorelink', $this->showinformation)): ?>
                  <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?><i class="fa fa-angle-right"></i></a></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
    <?php endforeach; ?>
  <?php if (empty($this->viewmore)): ?>
    </div>
  <?php endif;?>
<?php elseif($this->viewtype == 'onlytutorialview'): ?>
  <?php if (empty($this->viewmore)): ?>
  	<div class="sestutorial_category_question_section_list sestutorial_clearfix sestutorial_bxs">
    	<ul id="sestutorial_results">
    <?php endif;?>  
        <?php foreach($this->paginator as $tutorial): //print_r($tutorial->toarray());die; ?>
          <li class="sestutorial_question_answer_section" >
          	<a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><i class=" far fa-file-alt"></i><span><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></span></a>
          </li>
      <?php endforeach; ?>
    <?php if (empty($this->viewmore)): ?>
  		</ul>
    </div>
  <?php endif;?>
<?php elseif($this->viewtype == 'gridview'): ?>
  <!--question answer grid view block-->
  <?php if (empty($this->viewmore)): ?>
    <div class="sestutorial_question_answer_grid_content sestutorial_clearfix sestutorial_bxs" id="sestutorial_results">
  <?php endif;?>
  <div class="row">
    <?php foreach($this->paginator as $tutorial): //print_r($tutorial->toarray());die; ?>
      <div class="col-lg-<?php echo $this->gridblock; ?> col-md-12 col-sm-6 col-12">  
        <div class="sestutorial_question_answer_section">
          <div class="sestutorial_question_answer_inner">
            <div class="sestutorial_question_answer_title">
              <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></a>
            </div>
            <div class="sestutorial_question_answer_stats">
              <ul>
                <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
                  <li class="sestutorial_text_light"><i class="far fa-comment"></i> <?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?></li>
                <?php endif; ?>
                <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
                  <li class="sestutorial_text_light"><i class="fa fa-eye"></i> <?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?></li>
                <?php endif; ?>
                <?php if(engine_in_array('likecount', $this->showinformation)): ?>
                  <li class="sestutorial_text_light"><i class="far fa-thumbs-up"></i> <?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?></li>
                <?php endif; ?>
                <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
                  <li class="sestutorial_text_light"><i class="far fa-star"></i> <?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?></li>
                <?php endif; ?>
              </ul>
            </div>
            <?php if(is_array($this->showinformation) && engine_in_array('photo', $this->showinformation)): ?>
              <div class="sestutorial_question_answer_img">
                <a href="<?php echo $tutorial->getHref(); ?>"><img src="<?php echo $tutorial->getPhotoUrl(); ?>" /></a>
              </div>
            <?php endif; ?>
            <?php if(is_array($this->showinformation) && (engine_in_array('readmorelink', $this->showinformation) || engine_in_array('description', $this->showinformation))): ?>
              <div class="sestutorial_question_answer_discription">
                <?php if(is_array($this->showinformation) && engine_in_array('description', $this->showinformation)): ?>
                <p> <?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?></p>
                <?php endif; ?>
                <?php if(is_array($this->showinformation) && engine_in_array('readmorelink', $this->showinformation)): ?>
                  <p class="read_more"><a href="<?php echo $tutorial->getHref(); ?>"><?php echo $this->translate("Read More"); ?></a></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (empty($this->viewmore)): ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
<?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
  <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
    <div class="clr" id="loadmore_list"></div>
    <div class="sestutorial_load_more" id="view_more" onclick="loadMore();" style="display: block;">
      <a href="javascript:void(0);" id="feed_viewmore_link" class="sestutorial_load_more_btn"><?php echo $this->translate('View More'); ?></a>
    </div>
    <div class="sestutorial_load_more" id="loading_image" style="display: none;">
      <span class="sestutorial_loading_icon"><i class="fa fa-spinner fa-spin"></i></span>
    </div>
  <?php endif; ?>
 <?php endif; ?>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no Tutorials.") ?>
    </span>
  </div>
<?php endif; ?>

<?php if($this->paginationType == 1): ?>
  <script type="text/javascript">    
     //Take refrences from: http://mootools-users.660466.n2.nabble.com/Fixing-an-element-on-page-scroll-td1100601.html
    //Take refrences from: http://davidwalsh.name/mootools-scrollspy-load
    en4.core.runonce.add(function() {
      var paginatorCount = '<?php echo $this->paginator->count(); ?>';
      var paginatorCurrentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
      function ScrollLoader() { 
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(document.getElementById('loadmore_list')) {
          if (scrollTop > 40)
            loadMore();
        }
      }
      window.addEvent('scroll', function() {
        ScrollLoader(); 
      });
    });    
  </script>
<?php endif; ?>
