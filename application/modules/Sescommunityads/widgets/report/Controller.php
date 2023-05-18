<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Widget_ReportController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
   
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $this->view->form = $form = new Sescommunityads_Form_Report();
    
    //get ad mininum year
    $table = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads');
    $tableName = $table->info('name');
    $minyear = $table->select()->from($tableName, array( 'MIN(approved_date) as min'))
            ->where('user_id = ?', $viewer->getIdentity())
            ->group('user_id')
            ->limit(1);
    $minyear = $table->fetchRow($minyear);
    $minyear = $minyear->min;
    if($minyear){
      $minyear = current(explode('-',$minyear));
      
      $currentYear = date('Y');
      $currentYears[$currentYear] = $currentYear;
      
      while ($currentYear != $minyear) {
        $currentYear--;
        $currentYears[$currentYear] = $currentYear;
      }
      $form->year_start->setMultiOptions($currentYears);
      $form->year_end->setMultiOptions($currentYears);
    }else{
      $this->view->noads = true;  
    }
    //user campaign
    
    $table = Engine_Api::_()->getDbTable('campaigns','sescommunityads');
    $tableName = $table->info('name');
    $campaign = $table->select()->from($tableName, '*')
            ->where('user_id = ?', $viewer->getIdentity());
    $campaigns = $table->fetchAll($campaign);
    
    $campaignArray = array();
    foreach($campaigns as $cmp){
      $campaignArray[$cmp->getIdentity()] = $cmp->getTitle();  
    }
    $form->campaign->setMultiOptions($campaignArray);
    //user ads
    $table = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads');
    $tableName = $table->info('name');
    $ad = $table->select()->from($tableName, '*')
            ->where('user_id = ?', $viewer->getIdentity());
    $ads = $table->fetchAll($ad);
    $adsArray = array();
    foreach($ads as $cmp){
      $adsArray[$cmp->getIdentity()] = $cmp->getTitle();  
    }
    $form->ads->setMultiOptions($adsArray);
    if (!engine_count($_POST))
      return;
    if (!$form->isValid($_POST))
      return;
    
    $values = $form->getValues();
    //echo "<prE>";var_dump($values);die;
    
		if(isset($_POST['format_name'])){
			$value['download'] = $_POST['format_name'];
    }
    $form->populate($value);
    $type = $values['type'];
    if($type == "day"){
      $starttime = date('Y-m-d',strtotime($values['start']));
      $endtime = date('Y-m-d',strtotime($values['end']));  
    }else{
      $starttime = $values['year_start']."-".$values['month_start']."-01";
      $endtime = $values['year_end']."-".$values['month_end']."-01";
    }
    if($values['format_type'] == "campaign"){
      if(!engine_count($values['campaign'])){
        $form->addError("Please choose Campaign to generate report");
        return;  
      }
      $campaignsSelected = $values['campaign'];
      $table = Engine_Api::_()->getDbTable('campaigns','sescommunityads');
      $tableName = $table->info('name');
      $tableStats = Engine_Api::_()->getDbTable('campaignstats','sescommunityads');
      $tableStatsName = $tableStats->info('name');
      $campaign = $table->select()->from($tableName, 'title')
              ->where($tableName.'.user_id = ?', $viewer->getIdentity())
              ->setIntegrityCheck(false)
              ->joinLeft($tableStatsName,$tableStatsName.'.campaign_id ='.$tableName.'.campaign_id',array('creation_date','view_count'=>new Zend_Db_Expr('COUNT(IF(view=1,1,NULL))'),'click_count'=>new Zend_Db_Expr('COUNT(IF(click=1,1,NULL))')))
              ->where($tableName.'.campaign_id IN(?)',$campaignsSelected)
              ->where($tableStatsName.'.campaign_id IN(?)',$campaignsSelected)
              ->where("DATE_FORMAT(".$tableStatsName.'.creation_date,"%Y-%m-%d") between "'.$starttime.'" AND "'.$endtime.'" ')
              ->group($tableStatsName.'.campaign_id')
              ->group("DATE_FORMAT(".$tableStatsName.'.creation_date,"%Y-%m-%d")');
      $campaignsResult = $table->fetchAll($campaign);
    }else{
      if(!engine_count($values['ads'])){
        $form->addError("Please choose Ads to generate report.");
        return;  
      }
      $adsSelected = $values['ads'];
      
      $table = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads');
      $tableName = $table->info('name');
      $tableStats = Engine_Api::_()->getDbTable('clickstats','sescommunityads');
      $tableStatsName = $tableStats->info('name');
      
      $tableViewStats = Engine_Api::_()->getDbTable('viewstats','sescommunityads');
      $tableViewStatsName = $tableViewStats->info('name');
      
      
      $selectView = $tableViewStats->select()->from($tableViewStatsName,array('sescommunityad_id','click_count'=>new Zend_Db_Expr(0),'creation_date as creation_date','view_count'=>new Zend_Db_Expr('COUNT(*)')))
      ->setIntegrityCheck(false)
      ->joinLeft($tableName,$tableName.'.sescommunityad_id ='.$tableViewStatsName.'.sescommunityad_id',array('title'))
      ->where($tableViewStatsName.'.sescommunityad_id IN(?)',$adsSelected)
      ->where("DATE_FORMAT(".$tableViewStatsName.'.creation_date,"%Y-%m-%d") between "'.$starttime.'" AND "'.$endtime.'" ')
      ->where($tableName.'.sescommunityad_id IS NOT NULL')
      ->group($tableViewStatsName.'.sescommunityad_id')
      ->group("DATE_FORMAT(".$tableViewStatsName.'.creation_date,"%Y-%m-%d")');
    
      $selectClick = $tableViewStats->select()->from($tableStatsName,array('sescommunityad_id','click_count'=>new Zend_Db_Expr('COUNT(*)'),'creation_date as creation_date','view_count'=>new Zend_Db_Expr(0)))
      ->setIntegrityCheck(false)
      ->joinLeft($tableName,$tableName.'.sescommunityad_id ='.$tableStatsName.'.sescommunityad_id',array('title'))
      ->where($tableStatsName.'.sescommunityad_id IN(?)',$adsSelected)
      ->where("DATE_FORMAT(".$tableStatsName.'.creation_date,"%Y-%m-%d") between "'.$starttime.'" AND "'.$endtime.'" ')
      ->where($tableName.'.sescommunityad_id IS NOT NULL')
      ->group($tableStatsName.'.sescommunityad_id')
      ->group("DATE_FORMAT(".$tableStatsName.'.creation_date,"%Y-%m-%d")');
            
      $select = "SELECT title, SUM(view_count) as view_count,SUM(click_count) as click_count,creation_date FROM (".$selectClick.' UNION '.$selectView." ) as t group by sescommunityad_id,DATE_FORMAT(creation_date,'%Y-%m-%d')";
      $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
      $adsResult = $dbGetInsert->query($select)->fetchAll();      
    }
		if(isset($value['download'])){
			$name = time();
			switch($value["download"])
    {
			case "excel" :
        $filename = $name . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        if(!empty($campaignsResult))
          $this->exportCampaignFile($campaignsResult);
        else
          $this->exportFile($adsResult);
        exit();
			case "csv" :
        $filename = $name . ".csv";
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Expires: 0");
        if(!empty($campaignsResult))
          $this->exportCampaignCSVFile($campaignsResult);
        else
          $this->exportCSVFile($adsResult);
				exit();
			default :
			break;
			}
		}
  }
  protected function exportCampaignCSVFile($records) {
	// create a file pointer connected to the output stream
	$fh = fopen( 'php://output', 'w' );
	$heading = false;
	$counter = 1;
		if(!empty($records))
		  foreach($records as $row) {
        if(!empty($row['view_count']))
          $ctl = number_format(round(($row['click_count']/$row['view_count'])*100, 4), 4);
        else 
          $ctl = number_format("0", 4);
			$valueVal['Date'] = date('Y-m-d',strtotime($row->creation_date));
			$valueVal['Campaign Name'] = $row['title'];
			$valueVal['Views'] = number_format($row['view_count']);
			$valueVal['Clicks'] = number_format($row['click_count']);
			$valueVal['CTR (%)'] = $ctl;
			$counter++;
			if(!$heading) {
			  // output the column headings
			  fputcsv($fh, array_keys($valueVal));
			  $heading = true;
			}
			// loop over the rows, outputting them
			 fputcsv($fh, array_values($valueVal));

		  }
		  fclose($fh);
  }
  
  protected function exportCampaignFile($records) {
    $heading = false;
    $counter = 1;
    if(!empty($records))
      foreach($records as $row) {
        if(!empty($row['view_count']))
          $ctl = number_format(round(($row['click_count']/$row['view_count'])*100, 4), 4);
        else 
          $ctl = number_format("0", 4);
			$valueVal['Date'] = date('Y-m-d',strtotime($row->creation_date));
			$valueVal['Campaign Name'] = $row['title'];
			$valueVal['Views'] = number_format($row['view_count']);
			$valueVal['Clicks'] = number_format($row['click_count']);
			$valueVal['CTR (%)'] = $ctl;
			$counter++;
      if(!$heading) {
        // display field/column names as a first row
        echo implode("\t", array_keys($valueVal)) . "\n";
        $heading = true;
      }
      echo implode("\t", array_values($valueVal)) . "\n";
      }
    exit;
  }
  
	protected function exportCSVFile($records) {
	// create a file pointer connected to the output stream
	$fh = fopen( 'php://output', 'w' );
	$heading = false;
	$counter = 1;
		if(!empty($records))
		  foreach($records as $row) {
        if(!empty($row['view_count']))
          $ctl = number_format(round(($row['click_count']/$row['view_count'])*100, 4), 4);
        else 
          $ctl = number_format("0", 4);
			$valueVal['Date'] = date('Y-m-d',strtotime($row['creation_date']));
			$valueVal['Ad Title'] = $row['title'];
			$valueVal['Views'] = number_format($row['view_count']);
			$valueVal['Clicks'] = number_format($row['click_count']);
			$valueVal['CTR (%)'] = $ctl;
			$counter++;
			if(!$heading) {
			  // output the column headings
			  fputcsv($fh, array_keys($valueVal));
			  $heading = true;
			}
			// loop over the rows, outputting them
			 fputcsv($fh, array_values($valueVal));

		  }
		  fclose($fh);
  }
  
  protected function exportFile($records) {
    $heading = false;
    $counter = 1;
    if(!empty($records))
      foreach($records as $row) {
        if(!empty($row['view_count']))
          $ctl = number_format(round(($row['click_count']/$row['view_count'])*100, 4), 4);
        else 
          $ctl = number_format("0", 4);
        $valueVal['Date'] = date('Y-m-d',strtotime($row['creation_date']));
        $valueVal['Ad Title'] = $row['title'];
        $valueVal['Views'] = number_format($row['view_count']);
        $valueVal['Clicks'] = number_format($row['click_count']);
        $valueVal['CTR (%)'] = $ctl;
        $counter++;
      if(!$heading) {
        // display field/column names as a first row
        echo implode("\t", array_keys($valueVal)) . "\n";
        $heading = true;
      }
      echo implode("\t", array_values($valueVal)) . "\n";
      }
    exit;
  }
}
