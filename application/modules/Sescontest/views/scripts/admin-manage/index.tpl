<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
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
    scriptJquery('#filter_form').trigger('submit');
  }

  function multiDelete() {
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected contests?');?>");
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
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Manage Contests") ?></h3>
<p><?php echo $this->translate('This page lists all of the contests your users have created. You can use this page to monitor these contests and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific contest. Leaving the filter fields blank will show all the contests on your social network. <br /> Below, you can also choose any number of contests as Contests of the Day, Featured, Sponsored, Verified and Hot. You can also Approve and Disapprove contests.'); ?></p>
<br />
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<br />
<?php $isEnablePackage = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0); ?>
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s contest found.', '%s contests found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <div class="admin_table_form">
      <table class='admin_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('contest_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'ASC');"><?php echo $this->translate("Owner") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('is_approved', 'ASC');" title="Approved"><?php echo $this->translate("A") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');" title="Featured"><?php echo $this->translate("F") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('sponsored', 'ASC');" title="Sponsored"><?php echo $this->translate("S") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('hot', 'ASC');" title="Hot"><?php echo $this->translate("H") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('verified', 'ASC');" title="Verified"><?php echo $this->translate("V") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('offtheday', 'ASC');" title="Of the Day"><?php echo $this->translate("OTD") ?></a></th>
            <?php if($isEnablePackage){ ?>
              <th><?php echo $this->translate("Package"); ?></th>
              <th><?php echo $this->translate("Price"); ?></th>
              <th><?php echo $this->translate("Package Expiration Date"); ?></th>
            <?php } ?>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Creation Date") ?></a></th>
            <?php if($isEnablePackage):?>
              <th><?php echo $this->translate("Status") ?></th>
            <?php endif;?>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->paginator as $item): ?>
          <tr>
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->contest_id;?>' value="<?php echo $item->contest_id; ?>" /></td>
            <td><?php echo $item->contest_id ?></td>
            <td><?php echo $this->htmlLink($item->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getTitle(),16)), array('title' => $item->getTitle(), 'target' => '_blank')) ?></td>
            <td><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getOwner()->getTitle(),16)), array('title' => $this->translate($item->getOwner()->getTitle()), 'target' => '_blank')) ?></td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1):?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'approved', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unapprove')))) ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'approved', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Approve')))) ?>
              <?php endif; ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){?>
                <?php if($item->featured == 1):?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'featured', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Featured')))) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'featured', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Featured')))) ?>
                <?php endif; ?>
              <?php }else{ ?>
                  -
              <?php } ?>  
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){?>
                <?php if($item->sponsored == 1):?>
                 <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'sponsored', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Sponsored')))) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'sponsored', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Sponsored')))) ?>
                <?php endif; ?> 
              <?php }else{ ?>
                  -
              <?php } ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){?>
                <?php if($item->hot == 1):?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'hot', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Hot')))) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'hot', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Hot')))) ?>
                <?php endif; ?> 
              <?php }else{ ?>
                  -
              <?php } ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){?>
                <?php if($item->verified == 1): ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'verify', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Verified')))) ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'verify', 'id' => $item->contest_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Verified')))) ?>
                <?php endif; ?> 
              <?php }else{ ?>
                  -
              <?php } ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->is_approved == 1){?>
                <?php if(strtotime($item->enddate) < strtotime(date('Y-m-d')) && $item->offtheday == 1):?>
                  <?php Engine_Api::_()->getDbtable('contests', 'sescontest')->update(array('offtheday' => 0,'starttime' =>'',
                            'endtime' =>''), array("contest_id = ?" => $item->contest_id));?>
                  <?php $itemofftheday = 0;?>
                <?php else:?>
                  <?php $itemofftheday = $item->offtheday; ?>
                <?php endif;?>
                <?php if($itemofftheday == 1):?>  
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->contest_id, 'type' => 'contest', 'param' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Edit Contest of the Day'))), array('class' => 'smoothbox')); ?>
                <?php else: ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->contest_id, 'type' => 'contest', 'param' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Make Contest of the Day'))), array('class' => 'smoothbox')) ?>
                <?php endif; ?>
              <?php }else{ ?>
                  -
              <?php } ?>
            </td>
            <?php if($isEnablePackage){ 
       		$package = $item->getPackage();
       ?>
       <td><a href="javascript:;"><?php echo $package->getTitle(); ?></a></td>
       <td>
          <?php if($package->price < 1){
          echo "FREE";
        }else{ 
          $currentCurrency = Engine_Api::_()->sescontestpackage()->getCurrentCurrency();
          echo $package->getPackageDescription();
        } ?>
       </td>
        <td>
        	<?php if($package->price < 1){
          	echo "Never Expires";
          }else{ 
          	$transaction = $item->getTransaction();
            if(!$transaction){
            	if($item->orderspackage_id){
              	$table = Engine_Api::_()->getDbTable('transactions','sescontestpackage');
                $tableName = $table->info('name');
				$select = $table->select()->from($tableName)->where('orderspackage_id =?',$item->orderspackage_id)->where('gateway_profile_id !=?','')->where('state = "pending" || state = "complete" || state = "okay" || state = "active"')->limit(1);
				$transaction =  $table->fetchRow($select);
                if($transaction){
                  echo date('Y-m-d H:i:s');
                }else{
                  echo "Unlimited";
                }
             	}else{
            	 echo "Unlimited";	
              }
            }else{
            	echo date('Y-m-d H:i:s');
            }
          }
          ?>
        </td>
       <?php } ?>
       <td><?php echo $item->creation_date ?></td>
       <?php if($isEnablePackage):?>
        <td>
          <?php $currentTime = time();?>
          <?php if(strtotime($item->starttime) > $currentTime):?>
            <?php $status = 'notStarted';?>
          <?php elseif(strtotime($item->endtime) < $currentTime):?>
            <?php $status = 'expire';?>
          <?php else:?>
            <?php $status = 'onGoing';?>	
          <?php endif;?>
          <?php if($status == 'notStarted'){ ?>
            <?php echo $this->translate('Coming Soon');?>
          <?php }else if($status == 'expire'){ ?>
            <?php echo $this->translate('Expired');?>
          <?php }else{ ?>
            <?php echo $this->translate('Active');?>
          <?php } ?>
        </td>
      <?php endif;?>
            <td>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'view', 'id' => $item->contest_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
              |
              <?php echo $this->htmlLink($item->getHref(), $this->translate("View"), array('target' => '_blank')); ?>
              |
              <?php echo $this->htmlLink(array('route' => 'sescontest_dashboard', 'contest_id' => $item->custom_url), $this->translate('Edit'), array('target'=> "_blank")) ?>
              |
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'delete', 'type' => 'contest', 'id' => $item->contest_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
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
      <?php echo $this->translate("There are no contest created by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>
<script type="text/javascript">
  function showSubCategory(cat_id,selectedId) {
    var selected;
    if(selectedId != ''){
      var selected = selectedId;
    }
    var url = en4.core.baseUrl + 'sescontest/ajax/subcategory/category_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
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
    var url = en4.core.baseUrl + 'sescontest/ajax/subsubcategory/subcategory_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
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
	  document.getElementById('subcat_id-label').parentNode.style.display = "none";
	 <?php } ?>
	 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>');
	 <?php }else{?>
	 		 document.getElementById('subsubcat_id-label').parentNode.style.display = "none";
	 <?php } ?>
  });
  scriptJquery( window ).load(function() {
  document.getElementById('subcat_id').value = '13';
       });
</script>
