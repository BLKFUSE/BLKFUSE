<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$serviceColors = array(
  'facebook' => '#3D5998',
  'twitter' => '#4EA4DD',
  'sms' => '#FFBD00',
  'whatsapp' => '#25D366',
  'linkedin' => '#3271B8',
  'myspace' => '#003399',
  'friend_feed' => '#87A9D9',
  'newsvine' => '#03652C',
  'reddit' => '#F54B00',
  'technorati' => '#3AAE01',
  'delicious' => '#0076E8',
  'digg' => '#101010',
  'googleplus' => '#DB4437',
  'stumbleupon' => '#EB4B23',
  'pinterest' => '#BD081C',
  'skype' => '#00AFF0',
  'mailgoogle' => '#D80403',
  'bookmark' => '#FFA520',
  'flipboard' => '#BE1A19',
  'rediff' => '#EC2127',
  'vk' => '#4c6c91',
  'yahoomail' => '#9F07D4',
  'mail' => '#7D7D7D',
);
?>
<h2>
  <?php echo $this->translate('Advanced Share Plugin') ?>
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<h3>
  Social Sites Share Stats
</h3>
<p>
  Here, you can view the statistics of links shared from your website on other social sites. 
  The search bar below allows you to filter various metrics and their change over different time durations.  
</p>
<br />

<div class='admin_search siteshare_admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<br />
<?php
$data = $slices = array();
$data[] = array('Social Networking Service', 'Share');
?>
<div class='siteshare_social_services_wapper'>
  <div class="siteshare_social_services_statistics">
    <div class="total_heading">Total Share Count: <?php echo $this->serviceStatistics['total'] ?></div>

    <?php if( $this->serviceStatistics['total'] ): ?>
      <div class="siteshare_social_services_statistics_graph_c">
        <div class="siteshare_social_services_statistics_graph" id="piechart">
        </div>
      </div>
    <?php endif; ?>
    <?php if( $this->serviceStatistics['total'] ): ?>
      <div>
        <ul>
          <?php foreach( $this->serviceNames as $service => $serviceName ): ?>
            <?php
            $color = $serviceColors[$service];
            if( empty($this->serviceStatistics['data'][$service]) ) {
              continue;
            }
            $count = $this->serviceStatistics['data'][$service]
            ?>
            <li class="siteshare_social_service_<?php echo $service ?>" style="border-color: <?php echo $color ?>">
              <span></span>
              <span><?php echo $serviceName ?></span>
              <span style="color: <?php echo $color ?>"><?php echo $count ?></span>
              <?php $data[] = array($serviceName, $count) ?>
              <?php $slices[] = array('color' => $color); ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php if( !empty($slices) ): ?>
  <?php $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Siteshare/externals/scripts/charts/loader.js')
  ?> 
  <!--  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $this->jsonInline($data) ?>);
        var options = {
          title: '',
          slices: <?php echo $this->jsonInline($slices) ?>,
          // is3D: true,
          // pieHole: 0.1,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    });
  </script>
<?php endif; ?>