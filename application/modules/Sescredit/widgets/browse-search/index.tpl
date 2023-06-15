<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headTranslate(array('SesSun','SesMon','SesTue','SesWed','SesThu','SesFri','SesSat',"SesJan", "SesFeb", "SesMar", "SesApr", "SesMay", "SesJun", "SesJul", "SesAug", "SesSep", "SesOct", "SesNov", "SesDec"));?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/scripts/moment.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/scripts/daterangepicker.min.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/daterangepicker.css'); ?>


<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/styles.css'); ?>
<div class="sesbasic_clearfix sesbasic_bxs sescredit_browse_search sescredit_browse_search_horizontal">
  <?php echo $this->form->render($this) ?>
</div>
<?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget("sescredit.my-transactions",'widget',"sescredit_index_transaction"); ?>
<script type="application/javascript">
  function formSubmit<?php echo $identity; ?>(obj){
    if(scriptJquery('._mytransactionstable_content').length > 0){
      scriptJquery('#activity-transaction_<?php echo $identity; ?>').html('');
      scriptJquery('#sescredit_transaction_loading').show();
      scriptJquery('#sescredit_table_contaner_<?php echo $identity; ?>').hide();
      scriptJquery('#loading_image_<?php echo $identity; ?>').show();
      scriptJquery('#loadingimgsescontest-wrapper').show();
      is_search_<?php echo $identity; ?> = 1;
      if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
        isSearch = true;
        searchParams<?php echo $identity; ?> = scriptJquery(obj).serialize();
        paggingNumber<?php echo $identity; ?>(1);
      }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
        isSearch = true;
        searchParams<?php echo $identity; ?> = scriptJquery(obj).serialize();
        page<?php echo $identity; ?> = 1;
        viewMore_<?php echo $identity; ?>();
      }
    }
  }
  en4.core.runonce.add(function () {
    scriptJquery(document).on('submit','#filter_form',function(e){
      e.preventDefault();
      formSubmit<?php echo $identity; ?>(this);
      return true;
    });	
  });
  
  scriptJquery(function() {
    scriptJquery('input[name="show_date_field"]').daterangepicker({
      opens: 'left'
    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
  });
</script>
<style>
  .datepicker .footer button.apply:before{content:"Search";}
  .datepicker .footer button.cancel:before{content:"Cancel";}
</style>
