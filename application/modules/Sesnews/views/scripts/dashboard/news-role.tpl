<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: news-role.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
<?php if(!$this->is_ajax):?> 
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
  <?php echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array('news' => $this->news));	?>
<div class="sesnews_dashboard_content sesnews_manage_role_form sesbm sesbasic_clearfix">
	<div class="sesnews_manage_role_form_top sesbasic_clearfix">
		<p class="heading_desc"><?php echo $this->translate('Below, you can add admins to your news who all will be able to do anything on your news as you do including editing, creating sub news, etc.');?></p>
		<?php endif; ?>
		<form id="news_admin_form" action="<?php echo $this->url(array('action' => 'save-news-admin', 'news_id' => $this->news->news_id), 'sesnews_dashboard', true) ?>" method="post">
			<div id="manage_admin_input">
				<div class="sesnews_manage_roles_item">
					<span class="show_img" id="show_default_img"></span>
					<input type='text' id="news_admin" name='news_admin' size='20' placeholder='<?php echo $this->translate('Type Member Name') ?>' />
					<input type="hidden" id="user_id" name="news_admins[]" value=""/>
				</div>
			</div>
			<a href="javascript:void(0);" onclick="addMore();"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->translate('Add Another Member');?></a>
			<button onclick="saveForm();return false;" id="save_button_admin" disabled><?php echo $this->translate("Save Admin"); ?></button>
	  </form>
<?php if(!$this->is_ajax){ ?>
  </div>
	<div class="sesnews_footer_contant">
		<b><?php echo $this->translate('Admins');?></b>
		<p><?php echo $this->translate('And so when Rihanna, in the middle of soaking up the sun on her swan float — a required activity for all celebrities — realized that she was losing her balance, her priorities were clear. RiRi gloriously emerged like Aphrodite from the water, wine glass in hand.');?></p>
		<div id="manage_admin">
			<?php foreach($this->paginator as $newsAdmin):?>
				<div class="admin_manage" id="admin_manage_<?php echo $newsAdmin->role_id;?>">
					<?php $user = Engine_Api::_()->getItem('user', $newsAdmin->user_id);?>
					<?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon', $user->getTitle())) ?>
          <?php echo $this->htmlLink($user->getHref(), $user->getTitle()) ?>
          <?php if($newsAdmin->user_id != $this->news->owner_id):?>
						<a class="remove_news" href="javascript:void(0);" onclick="removeUser('<?php echo $newsAdmin->news_id;?>','<?php echo $newsAdmin->role_id;?>');"><i class="fa fa-times"></i></a>
          <?php endif;?>
					
          <br />
				</div>
			<?php endforeach;?>
		</div>
	</div>
</div>
</div>
<?php  } ?>
<?php if($this->is_ajax) die; ?>
<script type="text/javascript">
en4.core.runonce.add(function() {
    AutocompleterRequestJSON('text_search', "<?php echo $this->url(array('module' => 'sesariana', 'controller' => 'index', 'action' => 'search'), 'default', true) ?>", function(selecteditem) {
      window.location.href = selecteditem.url;
    })
  });

	function showAutosuggest(newsAdmin, imageId) {
	  var contentAutocomplete1 =  'contentAutocomplete-'+newsAdmin
	  
	  AutocompleterRequestJSON(newsAdmin, "<?php echo $this->url(array('module' => 'sesnews', 'controller' => 'dashboard', 'action' => 'get-members', 'news_id' => $this->news->news_id), 'default', true) ?>", function(selecteditem) {
      if(document.getElementById('user_id').value != '')
        document.getElementById('user_id').value = document.getElementById('user_id').value+','+selecteditem.id;
			else
        document.getElementById('user_id').value = selecteditem.id;
      document.getElementById(imageId).innerHTML = selecteditem.photo;
			scriptJquery('#'+newsAdmin).attr('rel', selecteditem.id);
			scriptJquery('#save_button_admin').removeAttr('disabled');
    });
	}
	en4.core.runonce.add(function() {
	  showAutosuggest('news_admin','show_default_img');
	});
	
	function saveForm() {
	  var UserIds = document.getElementById('user_id').value;
		scriptJquery.ajax({
      dataType: 'html',
			url : en4.core.baseUrl + 'sesnews/dashboard/save-news-admin/news_id/' + <?php echo $this->news->news_id ?>,
			method: 'post',
			data : {
				format : 'html',
				data: UserIds,
				is_ajax: 1,
			},
			success: function(responseHTML) {
				document.getElementById('manage_admin').innerHTML = responseHTML;
			}
		});
	}
	
	scriptJquery(document).on('keyup', 'input[id^="news_admin"]', function(event) {
    var value = scriptJquery(this);
		if(!value.val()){
			var id = value.attr('rel');
			if(typeof id == 'undefined')
				return false;
			var str = document.getElementById('user_id').value;
			var res = str.replace(id, "");
			scriptJquery('#user_id').val(res);
			if(res == '' || res == ',')
				scriptJquery('#save_button_admin').attr('disabled', true);
			value.parent().find('.show_img').html('');				
		}
	});
	
	var count = 1;
	function addMore() {
		var itemCount = scriptJquery('#manage_admin_input').children().length - 1;
		var currentElem = scriptJquery('#manage_admin_input').children().eq(itemCount).find('input').first().val();
		if(!currentElem || !scriptJquery('#manage_admin_input').children().eq(itemCount).find('input').first().attr('rel'))
			return false;
	  var ColumnId = 'news_admin_'+count;
	  scriptJquery('#manage_admin_input').append('<div class="sesnews_manage_roles_item"><span class="show_img" id="show_default_img_'+count+'"'+'></span> <input type="text" placeholder="Type Member Name" size="20" name="'+ColumnId+'"' +'id="'+ColumnId+'"'+'autocomplete="off" rel="'+count+'"><a class="remove_icon" href="javascript:void(0);" onclick="removeInputForm('+"'"+ColumnId+"'"+');"><i class="fa fa-times" id="close_option_'+count+'"'+'></i></a></div>');
	  showAutosuggest('news_admin_'+count, 'show_default_img_'+count);
	  count = count+1;
	}
	
  function removeInputForm(id) {
    var explodedstr = id.split("_"); 
    var countNumber = explodedstr['2'];
    var str = document.getElementById('user_id').value;
    var res = str.replace(scriptJquery('#'+id).attr('rel'), "");
		var itemS = scriptJquery('#show_default_img_'+countNumber);
		itemS.parent().remove();
    scriptJquery('#user_id').val(res);
    if(res == '' || res == ',') {
			scriptJquery('#save_button_admin').attr('disabled', true)
    }
  }
  
  function removeUser(newsId, roleId) {
		scriptJquery.ajax({
      dataType: 'html',
			url : en4.core.baseUrl + 'sesnews/dashboard/delete-news-admin',
			method: 'post',
			data : {
				format : 'json',
				role_id: roleId,
				news_id: newsId,
				is_ajax: 1,
			},
			success: function(responseJSON) {
				scriptJquery('#admin_manage_'+roleId).remove();
			}
		});
  }
</script>
