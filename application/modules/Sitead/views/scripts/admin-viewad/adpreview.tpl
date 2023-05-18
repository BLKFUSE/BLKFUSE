<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: adreview.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<style type="text/css">
.cmaddis
{
	width:<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.block.width', 150); ?>px;
}
.admin_cmadpriview 
{
	background-color: #E9F4FA;
	overflow: hidden;
	padding: 10px;
}
.admin_cmadpriview > div {
	background: none repeat scroll 0 0 #FFFFFF;
	border: 1px solid #D7E8F1;
	overflow: hidden;
	padding: 10px;
}
#global_page_sitead-admin-viewad-adpreview .admin_cmadpriview 
{
	margin:20px;
	width:<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.block.width', 150)+40; ?>px;
}
#global_page_sitead-admin-viewad-editad .cmaddis_preview_wrapper button
{
	display:none;
}
</style>
<script type="text/javascript">
      function redirect_target(url) {
	window.parent.location.href=url;
	parent.Smoothbox.close();
      }
</script>

<div class="admin_cmadpriview">
	<div class="cmaddis_preview_wrapper">
		<h3><?php echo $this->translate("Ad: "). ucfirst($this->siteads_array['web_name']) //$this->row->cads_title; ?></h3>
		<b><?php echo $this->translate("Preview") ?></b>
		<div class="cadcp_preview">
	  <div class="cmaddis">
	  	<div class="cmad_addis">
				<!--tital code start here for both-->
				<div class="cmaddis_title">
					<?php // Title if has existence on site then "_blank" not work else work.
						
						echo '<a onclick="redirect_target(\''. $this->siteads_array['web_url'] .'\')"  href="javascript:void(0);" >' . ucfirst($this->siteads_array['web_name']) . "</a>";
					?>
				</div>
				<?php
					if ( !empty($this->siteads_array['resource_type']) && !empty($this->siteads_array['resource_id']) ) { ?>
						<div class="cmaddis_adinfo">
						<?php 
							$resource_url = Engine_Api::_()->sitead()->resourceUrl( $this->siteads_array['resource_type'], $this->siteads_array['resource_id'] );
								if( !empty($resource_url['status']) ) {
									echo '<a onclick="redirect_target(\''. $resource_url['link'] .'\')"  href="javascript:void(0);" >' . $resource_url['title'] . "</a>";
								}else {
									echo $resource_url['title'];
								}
						?>
						</div>
					<?php } else if( !empty($this->hideCustomUrl) ) {
						$ad_url = Engine_Api::_()->sitead()->adSubTitle( $this->siteads_array['web_url'] );
						echo '<div class="cmaddis_adinfo"><a title="'. $this->siteads_array['web_url'] .'" onclick="redirect_target(\''. $this->siteads_array['web_url'] .'\')"  href="javascript:void(0);" >' . $this->translate(Engine_Api::_()->sitead()->truncation($ad_url, 25)) . "</a></div>";
					}
					?>
					<!--image code start here for both-->
					<?php
					// Display image if 'Advertisment' is the content of the site then show the content image.
						$site_ad_image = $this->itemPhoto($this->siteads_array, '', '');
					?>
					<div class="cmaddis_image">
					<?php 
						echo '<a onclick="redirect_target(\''. $this->siteads_array['web_url'] .'\')"  href="javascript:void(0);" >' .  $site_ad_image . "</a>";
					?>
					</div>
					<!--image code end here for both-->
					
					<!--description code start here for both-->
					<div class="cmaddis_body">	
						<?php
							echo '<a onclick="redirect_target(\''. $this->siteads_array['web_url'] .'\')"  href="javascript:void(0);" >' . "</a>"; 
						?>
					</div>
					<!-- Like option only show in the case of if existence on the site -->
					<?php if ( !empty($this->siteads_array['resource_type']) && !empty($this->siteads_array['resource_id']) ) { ?>
						<div class="cmad_show_tooltip_wrapper">
							<?php echo '<div class="cmaddis_cont"><a href="javascript:void(0);" class="cmad_like_button"><i class="like_thumbup_icon"></i><span>Like</span></a><span class="cmad_like_un">&nbsp;&middot;&nbsp;<a href="javascript:void(0);">' . $this->get_title . '</a>' . $this->translate(' likes this.') . '</span></div>'; ?>
							<div class="cmad_show_tooltip">
								<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/tooltip_arrow.png" />
								<?php echo $this->translate("Viewers will be able to like this ad and its content. They will also be able to see how many people like this ad, and which friends like this ad.");?>
							</div>
						</div>
					<?php } ?>
					<!--description code end here for both-->
				</div>	
			</div>		
		</div>
      <button onclick='javascript:parent.Smoothbox.close()' style="float:left;clear:both;margin-top:10px;"><?php echo $this->translate('Close'); ?></button>

	</div>
</div>
	
<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>