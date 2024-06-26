<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
  </div>
<h3><?php echo $this->translate("Manage Packages") ?></h3>
<p><?php echo $this->translate('This page lists all the packages that you have created for allowing contest creation on your website. A package can be deleted until someone has not created any contest under that package. In a package, only the fields which do not affect a user regarding the usage of the package subscribed like Description, Member Levels, Custom Fields, Highlight & Show in Upgrade, can be edited even after contests creation. If you want to make changes in any other field, then you will have to create a new package by disabling the current package.
'); ?></p>
<?php if( !empty($this->error) ): ?>
  <ul class="form-errors"><li><?php echo $this->error ?></li></ul>
<?php /*return; */ endif; ?>
<div class="sesbasic_search_result">
  <?php echo $this->htmlLink(array('action' => 'create', 'reset' => false), $this->translate('Create New Package'), array('class' => 'buttonlink sesbasic_icon_add',)) ?>
</div>
<script type="text/javascript">
  var currentOrder = '<?php echo $this->filterValues['order'] ?>';
  var currentOrderDirection = '<?php echo $this->filterValues['direction'] ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
    if( order == currentOrder ) {
      $('direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      document.getElementById('order').value = order;
      $('direction').value = default_direction;
    }
    scriptJquery('#filter_form').trigger('submit');
  }
</script>
<div class='admin_search'><?php echo $this->formFilter->render($this) ?></div>
<div class='sesbasic_search_result'>
  <?php $count = $this->paginator->getTotalItemCount() ?>
  <?php echo $this->translate(array("%s Package Found", "%s Packages   Found", $count), $count) ?>  
</div>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
   <div class="sesbasic_manage_table">
   <?php $class = ( $this->order == 'package_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
          	<div class="sesbasic_manage_table_head <?php echo $class ?>" style="width:100%;">
              <div style="width:5%" class="admin_table_centered">
              <a href="javascript:void(0);" onclick="javascript:changeOrder('package_id', 'DESC');">
                <?php echo "Id";?>
               </a>
              </div>
              <?php $class = ( $this->order == 'title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
              <div style="width:15%" class="<?php echo $class ?>">
               <a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');">
               	<?php echo $this->translate("Title") ?>
               </a>
              </div>
              <?php $class = ( $this->order == 'price' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
              <div style="width:10%" class="<?php echo $class ?>">
              	<a href="javascript:void(0);" onclick="javascript:changeOrder('price', 'DESC');">
              	 <?php echo $this->translate("Price") ?>
                </a>
              </div>
              <div style="width:15%">
               <?php echo $this->translate("Billing Cycle") ?>
              </div>
               <?php $class = ( $this->order == 'enabled' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
              <div style="width:8%"  class="admin_table_centered">
              <a href="javascript:void(0);" onclick="javascript:changeOrder('enabled', 'DESC');">
               <?php echo $this->translate("Enabled") ?>
               </a>
              </div> 
              
               <?php $class = ( $this->order == 'enabled' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
              <div style="width:8%"  class="admin_table_centered">
              <a href="javascript:void(0);" onclick="javascript:changeOrder('highlight', 'DESC');">
               <?php echo $this->translate("Highlighted") ?>
               </a>
              </div>
              
               <?php $class = ( $this->order == 'enabled' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
              <div style="width:8%"  class="admin_table_centered">
              <a href="javascript:void(0);" onclick="javascript:changeOrder('show_upgrade', 'DESC');">
               <?php echo $this->translate("In Upgrade") ?>
               </a>
              </div>
                           
              <div style="width:10%" class="admin_table_centered">
               <?php echo $this->translate("Total Contests") ?>
              </div>
              <div style="width:21%">
               <?php echo $this->translate("Options"); ?>
              </div>  
            </div>
          	<ul class="sesbasic_manage_table_list" id='menu_list' style="width:100%;">
            <?php foreach ($this->paginator as $item) : ?>
              <li class="item_label" id="package_<?php echo $item->package_id ?>">
                <div style="width:5%;" class="admin_table_centered">
                  <?php echo $item->package_id ?>
                </div>
                <div style="width:15%;">
                  <?php echo $item->title ?>
                </div>
                <div style="width:10%;">
                	<?php echo $this->locale()->toNumber($item->price) ? Engine_Api::_()->payment()->getCurrencyPrice($item->price,'','',true) : 'FREE' ?>
                </div>
                <div style="width:15%;">
                	<?php echo $item->getPackageDescription() ?>
                </div>
                <div style="width:8%;" class="admin_table_centered">
                <?php if($item->default != 1){ ?>
                 	<?php if($item->enabled == 1):?>
               <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'approved', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disabled')))) ?>
	  <?php else: ?>
	    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'approved', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enabled')))) ?>
	  <?php endif; ?>
                 <?php }else{ ?>
                 	-
                 <?php  } ?>
                </div>
                
                <div style="width:8%;" class="admin_table_centered">
                <?php if($item->default != 1){ ?>
                 	<?php if($item->highlight == 1):?>
               <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'highlight', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unhighlight')))) ?>
	  <?php else: ?>
	    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'highlight', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Highlight')))) ?>
	  <?php endif; ?>
                 <?php }else{ ?>
                 	-
                 <?php  } ?>
                </div>
                
                <div style="width:8%;" class="admin_table_centered">
                <?php if($item->default != 1){ ?>
                 	<?php if($item->show_upgrade == 1):?>
               <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'show-upgrade', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Hide from upgrade section')))) ?>
	  <?php else: ?>
	    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontestpackage', 'controller' => 'package', 'action' => 'show-upgrade', 'package_id' => $item->package_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Show in upgrade section')))) ?>
	  <?php endif; ?>
                 <?php }else{ ?>
                 	-
                 <?php  } ?>
                </div>
                
                <div style="width:10%;" class="admin_table_centered">
                  <?php echo $this->locale()->toNumber(@$this->contestCounts[$item->package_id]) ?>
                </div>                   
                  
                <div style="width:21%;">          
                 <a href='<?php echo $this->url(array('action' => 'edit', 'package_id' => $item->package_id)) ?>'>
                    <?php echo $this->translate("edit") ?>
                  </a>
                  <?php if(empty($this->contestCounts[$item->package_id]) && $item->default != 1){ ?>
                    |
                    <a href='<?php echo $this->url(array('action' => 'delete', 'package_id' => $item->package_id,'format'=>'smoothbox')) ?>' class="smoothbox">
                    <?php echo $this->translate("delete") ?>
                  </a>
            		<?php } ?>
                    |
                    <a href="<?php echo $this->url(array('module' => 'sescontest', 'controller' => 'manage'),'admin_default',true).'?package_id='.$item->package_id ?>">
                    <?php echo $this->translate("View Contests") ?>
                  </a>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
          </div>
     <?php else:?>
    <div class="tip">
      <span>
        <?php echo "No Package create yet.";?>
      </span>
    </div>
<?php endif; ?>
<div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'query' => $this->filterValues,
      'pageAsQuery' => true,
    )); ?>
  </div>
<script type="text/javascript"> 
  
  scriptJquery(document).ready(function() {
    scriptJquery('#menu_list').addClass('sortable');
    var SortablesInstance = scriptJquery('#menu_list').sortable({
      stop: function( event, ui ) {
        var ids = [];
        scriptJquery('#menu_list > li').each(function(e) {
          var el = scriptJquery(this);
          ids.push(el.attr('id'));
        });
        // Send request
        var url = '<?php echo $this->url(array('action' => 'order')) ?>';
        scriptJquery.ajax({
            url : url,
            dataType : 'json',
            data : {
                format : 'json',
                order : ids
            }
        });
      }
    });
  });
</script>
