<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: landing-page-settings.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php  ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<h3><?php echo "Manage Landing Page Content"; ?></h3>
<p><?php echo "Here, you can configure the content to be shown in various blocks on landing page of your website. The content added below will display in their respective widgets. You can also place the widgets on other pages of your website."; ?> </p>
<br />
<div class='sesdating_lp_settings_wrapper'>
    <div class="sesdating_lp_settings_tabs">
      <ul class="nav_cnt">
       <li <?php if($this->param == 'banner'): ?> class="active" <?php endif; ?>><a href="javascript:;">Banner Image</a></li>
       <li <?php if($this->param == 'featuredwidget'): ?> class="active" <?php endif; ?>><a href="javascript:;">6 Content Circle</a></li>
       <li <?php if($this->param == 'membercloud'): ?> class="active" <?php endif; ?>><a href="javascript:;">Member Cloud</a></li>
      </ul>
    </div>
    
    <?php
//New File System Code
$banner_options = array('' => '');
$files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
foreach( $files as $file ) {
  $banner_options[$file->storage_path] = $file->name;
}
    $fileLink = $this->baseUrl() . '/admin/files/';
    ?>
    <?php $settings = Engine_Api::_()->getApi('settings', 'core'); ?>
    <div class="landing_page_widget_container sesdating_lp_settings_content sesbasic_admin_form">
      <div class="container" id="banner_widget">
        <form action="admin/sesdating/manage/landing-page-settings/param/banner" method="post">
        <h3><?php echo "Manage Banner Image Content"; ?></h3>
        <p><?php echo "In this section, you can manage the banner image content - upload the banner image, small image on banner, add title and description to be added on the banner image."; ?> </p>
        <br />
         <div class="settings">
          <div class="form-wrapper">
            <div class="form-label"><label>Banner Image</label></div>
            <div class="form-element">
            	<p class="description">Choose from below the banner image. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>.]</p>
            	<?php if(engine_count($banner_options)){ ?>
            		<select name="sesdating_banner_bgimage">
                  <?php foreach($banner_options as $key=>$banner_option){ ?>
                  <option value="<?php echo $key; ?>" <?php if($settings->getSetting('sesdating.banner.bgimage', '') == $key){ echo "selected"; } ?>><?php echo $banner_option; ?></option>
                	<?php } ?>
            		</select>
           		<?php } ?>
          	</div> 
          </div>
          <div class="form-wrapper">
            <div class="form-label"><label>Small Image on Banner</label></div>
            <div class="form-element">
            	<p class="description">Choose from below the small image which will show on banner image. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>.]</p>
            	<?php if(engine_count($banner_options)){ ?>
            		<select name="sesdating_banner_bannerupimage">
                  <?php foreach($banner_options as $key=>$banner_option){ ?>
                  <option value="<?php echo $key; ?>" <?php if($settings->getSetting('sesdating.banner.bannerupimage', 'public/admin/banner-img.png') == $key){ echo "selected"; } ?>><?php echo $banner_option; ?></option>
                	<?php } ?>
            		</select>
           		<?php } ?>
          	</div> 
          </div>
          <div class="form-wrapper">
            <div class="form-label"><label>Banner Caption</label></div>
            <div class="form-element">
              <p class="description">Enter the caption to be shown on the banner image.</p>
            	<input type="text" name="sesdating_staticcontent" value="<?php echo $settings->getSetting('sesdating.staticcontent', ''); ?>">
            </div>  
          </div>
          <div class="form-wrapper">
            <div class="form-label">
          		<label>Moving Descriptions</label>
            </div>
            <div class="form-element">
              <p class="description">Enter the moving descriptions to be shown on the banner image. Click on "<a href="javascript:;" class="add_banner_widget"><i class="fa fa-plus"></i>&nbsp;<b class="bold">Add</b></a>" link to add new description.</p>
              <div class="input_field_banner_txt" id="menu_list">
                <?php $sesdating_banner_content = $settings->getSetting('sesdating.banner.content', ''); ?>
                <?php if(!$sesdating_banner_content){ ?>
                  <div style="cursor:move" class="item_label">
                    <input type="text" name="sesdating_banner_content[]">
                    <span><a href="javascript:;" class="delete_input_field_banner_txt fa fa-trash"></a></span>
                  </div>
                <?php }else{ 
                  $sesdating_banner_contents = explode(',',$sesdating_banner_content);
                  foreach($sesdating_banner_contents as $sesdating_banner_content){
                ?>
                  <div style="cursor:move" class="item_label">
                    <input type="text" name="sesdating_banner_content[]" value="<?php echo $sesdating_banner_content; ?>">
                    <span title="Delete"><a href="javascript:;" class="delete_input_field_banner_txt fa fa-trash"></a></span>
                  </div>
                <?php } ?>
                <?php } ?>
              </div>
          	</div>
          </div>
          <div class="form-wrapper">
          	<button type="submit" name="">Save Changes</button>
          </div>
         </div>
        </form>
      </div>
      <div style="display:none;" id="featured_widget" class="container">
        <form action="admin/sesdating/manage/landing-page-settings/param/featuredwidget" method="post">
          <h3><?php echo "Manage 6 Content Circle"; ?></h3>
          <p>In this section, you can manage the content which forms a circle of content by adding title, description, URL, logo, etc.</p>
          <br />
          <div class="settings">

            <div class="form-wrapper">
              <div class="form-label"><label>Title</label></div>
              <div class="form-element">
                <p>Enter the title of this block.</p>
              	<input type="text" name="sesdating_feature_heading" value="<?php echo $settings->getSetting('sesdating.feature.heading', ''); ?>">
              </div>  
            </div>
            
            <div class="form-wrapper">
              <div class="form-label">
              	<label>Description</label>
              </div>
              <div class="form-element">
                <p>Enter the description of this block.</p>
              	<input type="text" name="sesdating_feature_caption" value="<?php echo $settings->getSetting('sesdating.feature.caption', ''); ?>">
              </div>
            </div>
            <div class="form-wrapper">
              <div class="form-label">
              	<label>Main background image</label>
              </div>
              <div class="form-element">
              <p class="form-description">Choose from below the main background image which will be shown in the center of this block. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>.</p>
                <?php if(engine_count($banner_options)){ ?>
                  <select name="sesdating_feature_bgimage">
                    <?php foreach($banner_options as $key=>$banner_option){ ?>
                      <option value="<?php echo $key; ?>" <?php if($settings->getSetting('sesdating.feature.bgimage', '') == $key){ echo "selected"; } ?>><?php echo $banner_option; ?></option>
                    <?php } ?>
                  </select>
                <?php } ?>
             	</div>
            </div>
            <div class="form-sub-heading form-wrapper">
             	<div class="form-label">Enter below the 6 content for this block.</div>
             	<div id="add_feature_content" class="form-element"><a href="javascript:;" class="add_feature_content"><i class="fa fa-plus"></i>&nbsp;<b class="bold">Add</b></a></div>
            </div>
             <div class="sesdating_feature_content_container" id="menu_list_feature">
               <?php $featureContent = $settings->getSetting('sesdating.feature.content', ''); 
                if(!$featureContent){
                 $featureContent = array();
                 $featureKey = 1;  
                }else{
                 $featureKey = max(array_keys($featureContent)) + 1;
                }                
               ?>
             <?php if(engine_count($featureContent)){ 
              foreach($featureContent as $key => $content){
             ?>
               <div style="cursor:move;" class="item_label_feature">
                <div class="form-wrapper">
                  <div class="form-label"><label>Title</label></div>
                   <div class="form-element">
                    <input type="text" name="sesdating_feature_content[<?php echo $key; ?>][caption]" value="<?php echo $content['caption']; ?>">
                   </div>
                 </div>
                 <div class="form-wrapper">
                  <div class="form-label"><label>Description</label></div>
                  <div class="form-element">
                    <input type="text" name="sesdating_feature_content[<?php echo $key; ?>][description]" value="<?php echo $content['description']; ?>">
                  </div>
                </div> 
								<div class="form-wrapper">
                	<div class="form-label"><label>URL</label></div>
                    <div class="form-element">
                    	<input type="text" name="sesdating_feature_content[<?php echo $key; ?>][url]" value="<?php echo $content['url']; ?>">
                    </div>
                 </div>
                 <div class="form-wrapper">
                  <div class="form-label"><label>Icon Image</label></div>
                 	<div class="form-element">
                  	<p class="description">Choose from below the image for icon of this content. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>.</p>
                  <?php if(engine_count($banner_options)){ ?>
                  <select name="sesdating_feature_content[<?php echo $key; ?>][iconimage]">
                    <?php foreach($banner_options as $key=>$banner_option){ ?>
                      <option value="<?php echo $key; ?>" <?php if($content['iconimage'] == $key){ echo "selected"; } ?>><?php echo $banner_option; ?></option>
                  <?php } ?>
                  </select>
                  <div style="padding-top:10px;">
                  	<a href="javascript:;" class="delete_feature_content fa fa-trash">&nbsp;Delete</a>     
                 	</div>  
                  </div>
                 <?php } ?>
   
               </div> 
               </div>  
             <?php 
                }
              }else{ ?> 
             	<div style="cursor:move;" class="item_label_feature">
             		<div class="form-wrapper">
                   <div class="form-label"><label>Title</label></div>
                   <div class="form-element">
                    <input type="text" name="sesdating_feature_content[0][caption]" value="">
                   </div>
                 </div> 
                 <div class="form-wrapper">
                  <div class="form-label">
                    <label>Description</label>
                  </div>
                  <div class="form-element">
                  	<input type="text" name="sesdating_feature_content[0][description]" value="">
                  </div>
                 </div>
                 <div class="form-wrapper">
                 	<div class="form-label">
                 		<label>URL</label>
                  </div>
                  <div class="form-element">  
                 		<input type="text" name="sesdating_feature_content[0][url]" value="">
                  </div>
                </div>
                <div class="form-wrapper">
                	<div class="form-label">    
                 <label>Icon Image</label>
                 </div>
                 <div class="form-element">
                 <p class="description">Choose from below the logo image for your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>. Leave the field blank if you do not want to show logo.]</p>
                  <?php if(engine_count($banner_options)){ ?>
                  <select name="sesdating_feature_content[0][iconimage]">
                    <?php foreach($banner_options as $key=>$banner_option){ ?>
                      <option value="<?php echo $key; ?>"><?php echo $banner_option; ?></option>
                  <?php } ?>
                  </select>
                 <?php } ?>
                 <div style="padding-top:10px;">
                  <a href="javascript:;" class="delete_feature_content fa fa-trash">&nbsp;Delete</a>     
                 </div>    
                 </div>
                 </div>
               </div>   
             <?php } ?>   
             </div>
             <div class="form-wrapper">
             	<button type="submit" name="">Submit</button>
             </div>
          </div>
        </form>
      </div>
      <div style="display:none;" id="membercloud_widget" class="container">
        <form action="admin/sesdating/manage/landing-page-settings/param/membercloud" method="post">
          <h3><?php echo "Manage Member Cloud Section"; ?></h3>
          <p><?php echo "In this section, you can manage the member cloud settings - choose height, width, caption, description, etc to be shown in this block."; ?> </p>
          <br />
          <div class="settings">
            <div class="form-wrapper">
              <div class="form-label"><label>Caption</label></div>
              <div class="form-element">
                <p><?php echo "Enter the caption to be shown on the member cloud block."; ?> </p>
              	<input type="text" name="sesdating_memeber_heading" value="<?php echo $settings->getSetting('sesdating.memeber.heading', ''); ?>">
              </div>  
            </div>
            <div class="form-wrapper">
            	<div class="form-label">
              	<label>Description</label>
              </div>
              <div class="form-element">
                <p><?php echo "Enter the descriptions to be shown on the member cloud block."; ?> </p>
              	<input type="text" name="sesdating_memeber_caption" value="<?php echo $settings->getSetting('sesdating.memeber.caption', ''); ?>">
              </div>  
            </div>
            <div class="form-wrapper">
            	<div class="form-label">
              	<label>Member Photo Height</label>
              </div>
              <div class="form-element">
                <p><?php echo "Enter the height of the member profile photos."; ?> </p>
              	<input type="text" name="sesdating_memeber_height" value="<?php echo $settings->getSetting('sesdating.memeber.height', ''); ?>">
              </div>  
            </div>
            <div class="form-wrapper">
            	<div class="form-label">
              	<label>Member Photo Height</label>
              </div>
              <div class="form-element">
                <p><?php echo "Enter the width of the member profile photos."; ?> </p>
              	<input type="text" name="sesdating_memeber_width" value="<?php echo $settings->getSetting('sesdating.memeber.width', ''); ?>">
                </div>
            </div>
            <div class="form-wrapper">
            	<div class="form-label">
              	<label>Enable Links to Profile</label>
              </div>
              <div class="form-element">
              	<p class="description">Do you want to enable the links on member profile, so that when users click on member photos, they redirect to their profiles?</p>  
              	<select name="sesdating_member_link">
                  <?php 
                  $designs = array('1'=>'Yes','2'=>'No');
                  foreach($designs as $key=>$link){ ?>
                    <option value="<?php echo $key; ?>" <?php if($settings->getSetting('sesdating.member.link', '') == $key){ echo "selected"; } ?>><?php echo $link; ?></option>
                  <?php } ?>
                </select>
              </div>  
            </div>
            <div class="form-wrapper">
            	<div class="form-label">
              	<label>Show Info Tooltip</label>
              </div>
              <div class="form-element">
              	<p class="description">Do you want to show the Info tooltip when users mouse over on Member Profile pictures?</p>  
              	<select name="sesdating_member_infotooltip">
                  <?php 
                  $designs = array('1'=>'Yes','2'=>'No');
                  foreach($designs as $key=>$link){ ?>
                    <option value="<?php echo $key; ?>" <?php if($settings->getSetting('sesdating.member.infotooltip', 1) == $key){ echo "selected"; } ?>><?php echo $link; ?></option>
                  <?php } ?>
                </select>
              </div>  
            </div>
            <div class="form-wrapper">
            <button type="submit" name="">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</div>

<script type="application/javascript">

  var landingpageparam = '<?php echo $this->param; ?>';
  if(landingpageparam == 'banner') {
    document.getElementById('banner_widget').style.display = 'block';
    document.getElementById('featured_widget').style.display = 'none';
    document.getElementById('membercloud_widget').style.display = 'none';
  
  } else if(landingpageparam == 'featuredwidget') {
    document.getElementById('banner_widget').style.display = 'none';
    document.getElementById('featured_widget').style.display = 'block';
    document.getElementById('membercloud_widget').style.display = 'none';
  } else if(landingpageparam == 'membercloud') {
    document.getElementById('banner_widget').style.display = 'none';
    document.getElementById('featured_widget').style.display = 'none';
    document.getElementById('membercloud_widget').style.display = 'block';
  }

  var elementCounter = <?php echo $featureKey; ?>;
  scriptJquery(document).on('click','.delete_feature_content',function(){
   scriptJquery(this).parent().parent().parent().parent().remove();
   elementCounter--;
    if(elementCounter < 6){
      if(document.getElementById('add_feature_content'))
        document.getElementById('add_feature_content').style.display = 'block';
    }
  });
  
  var childElement = scriptJquery("#menu_list_feature > div > div > div > div").children().length;

  scriptJquery('.add_feature_content').click(function(){

    var html = '<div style="cursor:move;" class="item_label_feature"><div class="form-wrapper"><div class="form-label"><label>Title</label></div><div class="form-element"><input type="text" name="sesdating_feature_content['+elementCounter+'][caption]" value=""></div></div><div class="form-wrapper"><div class="form-label"><label>Description</label></div><div class="form-element"><input type="text" name="sesdating_feature_content['+elementCounter+'][description]" value=""></div></div><div class="form-wrapper"><div class="form-label"><label>URL</label></div><div class="form-element"><input type="text" name="sesdating_feature_content['+elementCounter+'][url]" value=""></div></div><div class="form-wrapper"><div class="form-label"><label>Icon Image</label></div><div class="form-element"><p class="description">Choose from below the logo image for your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="<?php echo $fileLink ?>" target="_blank">File & Media Manager</a>. Leave the field blank if you do not want to show logo.]</p><?php if(engine_count($banner_options)){ ?><select name="sesdating_feature_content['+elementCounter+'][iconimage]"><?php foreach($banner_options as $key=>$banner_option){ ?><option value="<?php echo $key; ?>"><?php echo $banner_option; ?></option><?php } ?></select><?php } ?><div style="padding-top:10px;"><a href="javascript:;" class="delete_feature_content fa fa-trash">&nbsp;Delete</a></div></div></div></div>';
    elementCounter++;
    scriptJquery('.sesdating_feature_content_container').append(html);
    SortablesInstance = new SortablesSes('menu_list_feature', {
      clone: true,
      constrain: false,
      handle: '.item_label_feature',
    });
    if(elementCounter == 6){
      if(document.getElementById('add_feature_content'))
        document.getElementById('add_feature_content').style.display = 'none';
    }
  });
 
  scriptJquery('.add_banner_widget').click(function(){
    var html = '<div style="cursor:move" class="item_label"><input type="text" name="sesdating_banner_content[]">&nbsp;<span><a href="javascript:;" class="delete_input_field_banner_txt fa fa-trash"></a></span></div>';
    scriptJquery('.input_field_banner_txt').append(html);
    SortablesInstance = new SortablesSes('menu_list', {
      clone: true,
      constrain: false,
      handle: '.item_label',
      onComplete: function(e) {
      }
    });
  });
  scriptJquery(document).on('click','.delete_input_field_banner_txt',function(){
    scriptJquery(this).parent().parent().remove();
  });  
  scriptJquery(document).on('click','ul.nav_cnt li a',function(){
    scriptJquery('ul.nav_cnt').find('li').removeClass('active');
    scriptJquery(this).parent().addClass('active');
    scriptJquery('.landing_page_widget_container').find('div.container').hide();
    scriptJquery('.landing_page_widget_container').children().eq(scriptJquery(this).parent().index()).show();
  });
</script>
<script type="text/javascript"> 
  scriptJquery(document).ready(function() {
    var elementCounter = <?php echo $featureKey; ?>;
    if(elementCounter == 6){
      if(document.getElementById('add_feature_content'))
        document.getElementById('add_feature_content').style.display = 'none';
    }
    
     new SortablesSes('menu_list', {
      clone: true,
      constrain: false,
      handle: '.item_label',
      onComplete: function(e) {
      }
    });
     new SortablesSes('menu_list_feature', {
      clone: true,
      constrain: false,
      handle: '.item_label_feature',
      onStart: function(e) {
      }
    });
  });
</script>
<script type="application/javascript">

var SortablesSes = new Class({

	Implements: [Events, Options],

	options: {/*
		onSort: function(element, clone){},
		onStart: function(element, clone){},
		onComplete: function(element){},*/
		opacity: 1,
		clone: false,
		revert: false,
		handle: false,
		dragOptions: {}/*<1.2compat>*/,
		snap: 4,
		constrain: false,
		preventDefault: false
		/*</1.2compat>*/
	},

	initialize: function(lists, options){
		this.setOptions(options);

		this.elements = [];
		this.lists = [];
		this.idle = true;

		this.addLists($$(document.id(lists) || lists));

		if (!this.options.clone) this.options.revert = false;
		if (this.options.revert) this.effect = new Fx.Morph(null, Object.merge({
			duration: 250,
			link: 'cancel'
		}, this.options.revert));
	},

	attach: function(){
		this.addLists(this.lists);
		return this;
	},

	detach: function(){
		this.lists = this.removeLists(this.lists);
		return this;
	},

	addItems: function(){
		Array.flatten(arguments).each(function(element){
			this.elements.push(element);
			var start = element.retrieve('sortables:start', function(event){
				this.start.call(this, event, element);
			}.bind(this));
			(this.options.handle ? element.getElement(this.options.handle) || element : element).addEvent('mousedown', start);
		}, this);
		return this;
	},

	addLists: function(){
		Array.flatten(arguments).each(function(list){
			this.lists.include(list);
			this.addItems(list.getChildren());
		}, this);
		return this;
	},

	removeItems: function(){
		return $$(Array.flatten(arguments).map(function(element){
			this.elements.erase(element);
			var start = element.retrieve('sortables:start');
			(this.options.handle ? element.getElement(this.options.handle) || element : element).removeEvent('mousedown', start);

			return element;
		}, this));
	},

	removeLists: function(){
		return $$(Array.flatten(arguments).map(function(list){
			this.lists.erase(list);
			this.removeItems(list.getChildren());

			return list;
		}, this));
	},

	getClone: function(event, element){
		if (!this.options.clone) return new Element(element.tagName).inject(document.body);
		if (typeOf(this.options.clone) == 'function') return this.options.clone.call(this, event, element, this.list);
		var clone = element.clone(true).setStyles({
			margin: 0,
			position: 'absolute',
			visibility: 'hidden',
			width: element.getStyle('width')
		}).addEvent('mousedown', function(event){
			element.fireEvent('mousedown', event);
		});
		//prevent the duplicated radio inputs from unchecking the real one
		if (clone.get('html').test('radio')){
			clone.getElements('input[type=radio]').each(function(input, i){
				input.set('name', 'clone_' + i);
				if (input.get('checked')) element.getElements('input[type=radio]')[i].set('checked', true);
			});
		}

		return clone.inject(this.list).setPosition(element.getPosition(element.getOffsetParent()));
	},

	getDroppables: function(){
		var droppables = this.list.getChildren().erase(this.clone).erase(this.element);
		if (!this.options.constrain) droppables.append(this.lists).erase(this.list);
		return droppables;
	},

	insert: function(dragging, element){
		var where = 'inside';
		if (this.lists.contains(element)){
			this.list = element;
			this.drag.droppables = this.getDroppables();
		} else {
			where = this.element.getAllPrevious().contains(element) ? 'before' : 'after';
		}
		this.element.inject(element, where);
		this.fireEvent('sort', [this.element, this.clone]);
	},

	start: function(event, element){
		if (
			!this.idle ||
			event.rightClick ||
			['button', 'input', 'a', 'textarea', 'select'].contains(event.target.get('tag'))
		) return;

		this.idle = false;
		this.element = element;
		this.opacity = element.getStyle('opacity');
		this.list = element.getParent();
		this.clone = this.getClone(event, element);

		this.drag = new Drag.Move(this.clone, Object.merge({
			/*<1.2compat>*/
			preventDefault: this.options.preventDefault,
			snap: this.options.snap,
			container: this.options.constrain && this.element.getParent(),
			/*</1.2compat>*/
			droppables: this.getDroppables()
		}, this.options.dragOptions)).addEvents({
			onSnap: function(){
				event.stop();
				this.clone.setStyle('visibility', 'visible');
				this.element.setStyle('opacity', this.options.opacity || 0);
				this.fireEvent('start', [this.element, this.clone]);
			}.bind(this),
			onEnter: this.insert.bind(this),
			onCancel: this.end.bind(this),
			onComplete: this.end.bind(this)
		});

		this.clone.inject(this.element, 'before');
		this.drag.start(event);
	},

	end: function(){
		this.drag.detach();
		this.element.setStyle('opacity', this.opacity);
		if (this.effect){
			var dim = this.element.getStyles('width', 'height'),
				clone = this.clone,
				pos = clone.computePosition(this.element.getPosition(this.clone.getOffsetParent()));

			var destroy = function(){
				this.removeEvent('cancel', destroy);
				clone.destroy();
			};

			this.effect.element = clone;
			this.effect.start({
				top: pos.top,
				left: pos.left,
				width: dim.width,
				height: dim.height,
				opacity: 0.25
			}).addEvent('cancel', destroy).chain(destroy);
		} else {
			this.clone.destroy();
		}
		this.reset();
	},

	reset: function(){
		this.idle = true;
		this.fireEvent('complete', this.element);
	},

	serialize: function(){
		var params = Array.link(arguments, {
			modifier: Type.isFunction,
			index: function(obj){
				return obj != null;
			}
		});
		var serial = this.lists.map(function(list){
			return list.getChildren().map(params.modifier || function(element){
				return element.get('id');
			}, this);
		}, this);

		var index = params.index;
		if (this.lists.length == 1) index = 0;
		return (index || index === 0) && index >= 0 && index < this.lists.length ? serial[index] : serial;
	}

});</script>
