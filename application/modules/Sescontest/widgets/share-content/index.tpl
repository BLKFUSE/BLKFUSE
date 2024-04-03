<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/share_content.css'); ?>
<?php if($this->contestTemplate == 1 && $this->contest){ ?>
<!--share content post one contest-->
<div class="share_content_post_one sebasic_clearfix sesbasic_bxs">
	<div class="share_content_post" id="sescontest-image" style="background-image:url(application/modules/Sescontest/externals/images/share_content/post_des_1.jpg">
  	<div class="share_content_post_section sesbasic_clearfix">
    <?php if($this->sponsored){ ?>
    	<div class="sopnsard_section">
      		<img src="application/modules/Sescontest/externals/images/share_content/top_logo_syn.png" />
          <span>Sponsard by</span>
      </div>
     <?php } ?>
     <?php if($this->contestType || $this->contestDate){ ?>
      <div class="contest_category_section">
      	<p>
          <?php if($this->contestType){ ?>
        	<b><span id="sescontest-media-type"><?php echo $this->type; ?></span> Contest</b>
          <?php } ?>
          <?php if($this->contestDate){ ?>
        	<span id="sescontest-date">on 29-11-2017</span>
           <?php } ?>
        </p>
      </div>
      <?php } ?>
      <?php if($this->description || $this->title || $this->logo){ ?>
      <div class="contest_pos_des_section">
        <?php if($this->title){ ?>
      	<h2 id="sescontest-title"></h2>
        <?php } ?>
        <?php if($this->description){ ?>
        <p id="sescontest-description"></p>
        <?php } ?>
        <?php if($this->logo){ ?>
        <span class="constent_site_logo"><img src="<?php echo $this->logoimage; ?>" /></span>
        <?php } ?>
      </div>
       <?php } ?>
    </div>
  </div>
</div>
<?php  } ?>
<?php if($this->contestTemplate == 2 && $this->contest){ ?>
<!--share content post two contest-->
<div class="share_content_post_two sebasic_clearfix sesbasic_bxs">
	<div class="share_content_post">
  		<div class="share_content_post_left">
      <?php if($this->logo){ ?>
      	<div class="constent_site_logo"><img src="<?php echo $this->logoimage; ?>" /></div>
      <?php } ?>
        <div class="contest_photo">
        	<span class="constent_img_one"><img src="application/modules/Sescontest/externals/images/share_content/post_des_2.jpg" /></span>
          <span class="constent_img_two"><img src="application/modules/Sescontest/externals/images/share_content/post_des_2.jpg" /></span>
        </div>
      </div>
      <div class="share_content_post_right">
      <?php if($this->contestType || $this->contestDate){ ?>
      	<div class="contest_category_section">
          <p>
            <?php if($this->contestType){ ?>
              <b><?php echo $this->type; ?> Contest</b>
            <?php } ?>
            <?php if($this->contestDate){ ?>
              <span>on 29-11-2017</span>
            <?php } ?>
          </p>
        </div>
      <?php } ?>
      <?php if($this->description || $this->title){ ?>
      <div class="contest_pos_des_section">
      <?php if($this->title){ ?>
      	<h2>Beautiful Hangings</h2>
        <?php } ?>
        <?php if($this->title){ ?>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if($this->sponsored){ ?>
      <div class="sopnsard_section">
      		<span>Sponsard by</span>
          <img src="application/modules/Sescontest/externals/images/share_content/top_logo_syn.png" />
      </div>
      <?php } ?>
      </div>
  </div>
</div>
<?php } ?>
<?php if($this->entryTemplate == 1 && $this->entry){ ?>
<!--share content post three entry-->
<div class="share_content_post_three sebasic_clearfix sesbasic_bxs">
	<div class="share_content_post">
  	<div class="share_content_post_header sesbasic_clearfix">
    <?php if($this->contestType){ ?>
    	<div class="contest_category_section">
      	<p>
        	<b><?php echo $this->type; ?> Contest</b>
        </p>
      </div>
      <?php } ?>
      <?php if($this->sponsored){ ?>
      <div class="sopnsard_section">
      		<span>Sponsard by</span>
          <img src="application/modules/Sescontest/externals/images/share_content/top_logo_syn.png" />
      </div>
      <?php } ?>
    </div>
    <div class="share_content_post_left">
    	<div class="share_content_photo_bg"></div>
      <div class="share_content_photo">
      	<img src="application/modules/Sescontest/externals/images/share_content/post_des_2.jpg" />
      </div>
    </div>
    <div class="share_content_post_right">
      <?php if($this->title){ ?>
    	<h2 class="tittle_name">Akia Deyavon</h2>
      <?php } ?>
      <?php if($this->contestDate){ ?>
      <p class="content_date">Voting Start & End Date<br /> Nov 15, 2017, 8:56 PM Oct 24, 2019, 9:56 PM <br />(US/Pacific)</p>
      <?php } ?>
      <p class="like_vote"> 
      	<img src="application/modules/Sescontest/externals/images/share_content/like.png" /> 
      	<span>Like To Vote</span>
      </p>
      <?php if($this->logo){ ?>
       <p class="company_logo"><img src="<?php echo $this->logoimage; ?>" /></p>
       <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
<?php if($this->entryTemplate == 2 && $this->entry){ ?>
<!--share content post four entry-->
<div class="share_content_post_four sebasic_clearfix sesbasic_bxs">
  	<div class="share_content_post_header sesbasic_clearfix">
    <?php if($this->sponsored){ ?>
      <div class="sopnsard_section">
      		<p><span>Sponsard by</span>
          <img src="application/modules/Sescontest/externals/images/share_content/top_logo_syn.png" /></p>
      </div>
      <?php } ?>
      <?php if($this->contestType ){ ?>
      <div class="contest_category_section">
      	<p>
        	<b><?php echo $this->type; ?> Contest</b>
        </p>
      </div>
      <?php } ?>
    </div>
    <div class="share_content_post_left">
    	<div class="share_content_photo">
      	<img src="application/modules/Sescontest/externals/images/share_content/post_des_2.jpg" />
      </div>
    </div>
     <div class="share_content_post_right">
     <?php if($this->contestDate){ ?>
        <p class="content_date">
        	Voting Start: Nov 15, 2017, 8:56 PM<br /> & <br/> End Date Oct 24, 2019, 9:56 PM <br />(US/Pacific)
        </p>
        <?php } ?>
        <div class="like_vote">
        	<p><span>Like</span>
          <img src="application/modules/Sescontest/externals/images/share_content/like_vote.png" />
          <span>Vote</span>
          </p>
        </div>
     </div>
     <div class="share_content_post_bottom">
      <?php if($this->title){ ?>
     	  <h2 class="tittle_name">Pile On the Protein</h2>
      <?php } ?>
      <?php if($this->logo){ ?>
       <p class="company_logo"><img src="<?php echo $this->logoimage; ?>" /></p>
       <?php } ?>
     </div>
</div>
<?php } ?>
<?php if($this->winner){ ?>
<!--share content post five winner-->
<div class="share_content_post_five sebasic_clearfix sesbasic_bxs">
	<div class="share_content_post_left">
  	<div class="winner_logo">
    	<img src="application/modules/Sescontest/externals/images/share_content/winner_icon.png" />
      <span>1<sup>st</sup> </span>
    </div>
    <div class="company_logo">
    	<img src="application/modules/Sescontest/externals/images/share_content/comany_logo_yellow.png" />
    </div>
  </div>
  <div class="share_content_post_right">
  <?php if($this->contestType){ ?>
  	  <div class="contest_category_section">
      	<p><?php echo $this->type; ?> Contest</p>
      </div>
      <?php } ?>
      <div class="share_content_photo">
      	<img src="application/modules/Sescontest/externals/images/share_content/post_des_2.jpg" />
        <?php if($this->title){ ?>
        <h2 class="photo_title">Beautiful Me</h2>
        <?php } ?>
      </div>
      <?php if($this->sponsored){ ?>
     <div class="sopnsard_section">
        <p><span>Sponsard by</span>
        <img src="application/modules/Sescontest/externals/images/share_content/top_logo_syn.png" /></p>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>