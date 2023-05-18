<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
<script type="text/javascript">
var currentOrder = '<?php echo $this->order ?>';
var currentOrderDirection = '<?php echo $this->order_direction ?>';
var changeOrder = function(order, default_direction){
  // Just change direction
  if( order == currentOrder ) {
    document.getElementById('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
  } else {
    document.getElementById('order').value = order;
    document.getElementById('order_direction').value = default_direction;
  }
  document.getElementById('filter_form').submit();
}
function multiDelete(){
  return confirm("<?php echo $this->translate('Are you sure you want to delete selected Ads?');?>");
}
function selectAll() {
  var i;
  var multidelete_form = document.getElementById('multidelete_form');
  var inputs = multidelete_form.elements;
  for (i = 1; i < inputs.length; i++) {
    if (!inputs[i].disabled) {
      inputs[i].checked = inputs[0].checked;
    }
  }
}
</script>
<?php
  $sesblog = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog');
$article = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesarticle');
$text = "";
$allowShortCode = false;
if($sesblog || $article){
$allowShortCode = true;
$text = "Below you can also get the short code for ads which you can place in the description of Blogs/Article on your website, using the 'Get Short Code' link for each ad. Please note you will not get the short code for Boost Post type ads.";
}
?>
<h3><?php echo $this->translate("Manage Ads") ?></h3>
<p>All the Ads created by your members will get listed here. You can search any Ad by entering the criteria into the searching fields. You can also View, Edit and Delete any Ad from this section if you want. Here you will find all the details for the Ads.<?php echo $text; ?></p>
<br />
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this); ?>
</div>
<br />

<br />
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s ad found.', '%s ads found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <div class="admin_table_form">
      <table class='admin_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('sescommunityad_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'ASC');"><?php echo $this->translate("Owner") ?></a></th>
            <th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('is_approved', 'ASC');" title="Approved"><?php echo $this->translate("A") ?></a></th>
            <th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');" title="Featured"><?php echo $this->translate("F") ?></a></th>
            <th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('sponsored', 'ASC');" title="Sponsored"><?php echo $this->translate("S") ?></a></th>
            <th><?php echo $this->translate("Package"); ?></th>
            <th><?php echo $this->translate("Price"); ?></th>
            <th><?php echo $this->translate("Expiry"); ?></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Creation Date") ?></a></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->paginator as $item): ?>
          <tr>
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity();?>' value="<?php echo $item->getIdentity(); ?>" /></td>
            <td><?php echo $item->getIdentity() ?></td>
            <td><?php echo $this->htmlLink($this->url(array('action' => 'view', 'ad_id' => $item->getIdentity()),'sescommunityads_general',false), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getTitle(),16)), array('title' => $item->getTitle(), 'target' => '_blank')) ?></td>
            <td><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getOwner()->getTitle(),16)), array('title' => $this->translate($item->getOwner()->getTitle()), 'target' => '_blank')) ?></td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1):?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'approved', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unapprove')))) ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'approved', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Approve')))) ?>
              <?php endif; ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){ ?>
                <?php if($item->featured == 1):?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'featured', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Featured'))),array('class'=>'smoothbox')) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'featured', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Featured'))),array('class'=>'smoothbox')) ?>
                <?php endif; ?>
              <?php }else{ ?>
                  -
              <?php } ?> 
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){ ?>
                <?php if($item->sponsored == 1):?>
                 <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'sponsored', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Sponsored'))),array('class'=>'smoothbox')) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'sponsored', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Sponsored'))),array('class'=>'smoothbox')) ?>
                <?php endif; ?> 
              <?php }else{ ?>
                  -
              <?php } ?>
            </td>
            <?php $package = $item->getPackage();?>
            <td><a href="admin/sescommunityads/package/create/package_id/<?php echo $package->getIdentity(); ?>" target="_blank"><?php echo $package->getTitle(); ?></a></td>
            <td>
              <?php if($package->price < 1):?>
                <?php echo "FREE";?>
              <?php else:?>
                <?php $currentCurrency = Engine_Api::_()->sescommunityads()->getCurrentCurrency();?>
                <?php echo $package->getPackageDescription();?>
              <?php endif;?>
            </td>
            <td>
              <?php echo $this->partial('_expiry.tpl','sescommunityads',array('ad'=>$item)); ?>
            </td>
            <td><?php echo $item->creation_date ?></td>
            <td>
              <?php if($item->type != "boost_post_cnt" && $allowShortCode){ ?>
              <?php echo $this->htmlLink("javascript:;", $this->translate("Get Short Code"), array('class' => 'popup_preview','data-id'=>$item->getIdentity())); ?>
              |
              <?php } ?>
              <?php echo $this->htmlLink($this->url(array('action' => 'view', 'ad_id' => $item->getIdentity()),'sescommunityads_general',false), $this->translate("View"), array('target' => '_blank')); ?>
              |
              <?php echo $this->htmlLink($this->url(array('action' => 'edit-ad', 'sescommunityad_id' => $item->getIdentity()),'sescommunityads_general',false), $this->translate("Edit"), array('target' => '_blank')) ?>
              |
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-ads', 'action' => 'delete', 'id' => $item->getIdentity()), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    <br />
    <div class='buttons'>
      <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
    </div>
  </form>
  <br/>
  <div>
    <?php echo $this->paginationControl($this->paginator,null,null,$this->urlParams); ?>
  </div>
<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no ad created by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>
<script type="text/javascript">
    scriptJquery(document).on('click','.popup_preview',function(e){
        e.preventDefault();
        var id = scriptJquery(this).attr('data-id');
        en4.core.showError('<div class="sescmads_shortcode_popup"><h3>'+en4.core.language.translate("Get Short Code")+'</h3><div class="sescmads_shortcode_content"><div class="sescmads_shortcode_box">[SESCOMMUNITYADS_'+id+']</div><div class="sescmads_shortcode_button"><button onclick="Smoothbox.close()">'+en4.core.language.translate("Close")+'</button></div></div>');
        scriptJquery('.sesact_img_preview_popup').parent().parent().addClass('sesact_img_preview_popup_wrapper');
    });
  function showSubCategory(cat_id,selectedId) {
    var selected;
    if(selectedId != ''){
      var selected = selectedId;
    }
    var url = en4.core.baseUrl + 'sescommunityads/index/subcategory/category_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      url: url,
      data: {
        'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "inline-block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "none";
            document.getElementById('subcat_id').innerHTML = '';
          }
        }
      }
    }));
  }
function showSubSubCategory(cat_id,selectedId) {
    var selected;
    if(selectedId != ''){
      var selected = selectedId;
    }
    var url = en4.core.baseUrl + 'sescommunityads/index/subsubcategory/subcategory_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      url: url,
      data: {
        'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "inline-block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));
  }
  scriptJquery(document).ready(function() {
	<?php if(isset($this->category_id) && $this->category_id != 0){ ?>
	  <?php if(isset($this->subcat_id) && $this->subcat_id != 0){$catId = $this->subcat_id;}else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id ?>','<?php echo $catId; ?>');
    <?php  }else{?>
	  scriptJquery('#subcat_id-label').parent().hide();
	 <?php } ?>
	 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>');
	 <?php }else{?>
	 		 scriptJquery('#subsubcat_id-label').parent().hide();
	 <?php } ?>
  });
  scriptJquery( window ).load(function() {
      //document.getElementById('subcat_id').value = '13';
  });
</script>
