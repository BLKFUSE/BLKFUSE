<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  $this->headLink()
  	->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitead/externals/styles/style_sitead.css');
?>
<?php
$this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'externals/calendar/calendar.compat.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitead/externals/scripts/bsn.DOM.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitead/externals/scripts/ajaxupload.js');

 ?>

<?php if (!empty($this->enableCountry)): ?>
  <script type="text/javascript">
    var maxRecipients = 100;

    function removeToValue(id, toValueArray){
      for (var i = 0; i < toValueArray.length; i++){
        if (toValueArray[i]==id) toValueIndex =i;
      }

      toValueArray.splice(toValueIndex, 1);
      $('toValues').value = toValueArray.join();
    }



    en4.core.runonce.add(function() {
     
      new Autocompleter.Request.JSON('country', '<?php echo $this->url(array('module' => 'sitead', 'controller' => 'index', 'action' => 'country-sugget'), 'default', true) ?>', {
        'postVar' : 'text',
        'minLength': 1,
        'delay' : 250,
        'selectMode': 'pick',
        'autocompleteType': 'message',
        'multiple': false,
        'className': 'message-autosuggest',
        'filterSubset' : true,
        'tokenFormat' : 'object',
        'tokenValueKey' : 'label',
        'injectChoice': function(token){

          var choice = new Element('li', {'class': 'autocompleter-choices', 'id':token.label});
          new Element('div', {'html': this.markQueryValue(token.label),'class': 'autocompleter-choice'}).inject(choice);
          choice.inputValue = token.label;
          this.addChoiceEvents(choice).inject(this.choices);
          choice.store('autocompleteChoice', token);

        },
        onPush : function(){
          if( $('toValues').value.split(',').length >= maxRecipients ){
            $('country').disabled = true;
          }
        }
      });

      <?php if( isset($this->toCountry)  ): ?>
      <?php foreach ($this->toCountry as $ck=>$cv):?>
      var toID = '<?php echo $this->toCountry[$ck]['key'] ?>';
      var name = '<?php echo $this->toCountry[$ck]['value'] ?>';
      var myElement = new Element("span");
      myElement.id = "tospan" + toID;
      myElement.setAttribute("class", "tag");
      myElement.innerHTML = name + " <a href='javascript:void(0);' onclick='this.parentNode.destroy();removeFromToValue(\""+toID+"\");'>x</a>";
      $('toValues-element').appendChild(myElement);
      $('toValues-wrapper').setStyle('height', 'auto');
      <?php endforeach;?>
      <?php endif; ?>

    });

  </script>
<?php endif; ?>

<script type="text/javascript">
 var showMarkerInDate="<?php echo $this->showMarkerInDate ?>";
   var cal_cads_start_date_onHideStart = function(){
     if(showMarkerInDate == 0) return;
    // check end date and make it the same date if it's too
    cal_cads_end_date.calendars[0].start = new Date( $('cads_start_date-date').value );
    // redraw calendar
    cal_cads_end_date.navigate(cal_cads_end_date.calendars[0], 'm', 1);
    cal_cads_end_date.navigate(cal_cads_end_date.calendars[0], 'm', -1);
  }
  var cal_cads_end_date_onHideStart = function(){
    if($('cads_end_date-date').value == "")
        $m("enable_end_date").checked=true;
    if(showMarkerInDate == 0) return;
    // check start date and make it the same date if it's too
    cal_cads_start_date.calendars[0].end = new Date( $('cads_end_date-date').value );
    // redraw calendar
    cal_cads_start_date.navigate(cal_cads_start_date.calendars[0], 'm', 1);
    cal_cads_start_date.navigate(cal_cads_start_date.calendars[0], 'm', -1);     
  }
  var cal_cads_end_date_onShowStart = function(){
    if($('validation_cads_end_date-element')){
        document.getElementById("cads_end_date-element").removeChild($('validation_cads_end_date-element'));
      }
       $m("enable_end_date").checked=false;
  }
  
  en4.core.runonce.add(function()
  {
    cal_cads_start_date_onHideStart();
    cal_cads_end_date_onHideStart();
    en4.core.runonce.add(function init()
    {
      monthList = [];
      myCal = new Calendar({ 'cads_start_date[date]': 'M d Y', 'cads_end_date[date]' : 'M d Y' }, {
        classes: ['event_calendar'],
        pad: 0,
        direction: 0
      });
    });
  });
  
  function enableEndDate() {
    var value=$m("enable_end_date").checked;
    if($('validation_cads_end_date-element')){
      document.getElementById("cads_end_date-element").removeChild($('validation_cads_end_date-element'));
    }
    if(value) {
      $('cads_end_date-wrapper').style.display = 'none';
    } else {
      $('cads_end_date-wrapper').style.display = 'block';
    }
  }

  var updateTextFields = function() {
    var adcampaign = document.getElementById("temp_campaign_id");
    var namewrapper = document.getElementById("temp_campaign_name-wrapper");
    var name = document.getElementById("temp_campaign_name");

    if (adcampaign.value == 0)
    {
      namewrapper.style.display = "block";        
    }
    else
    {
      namewrapper.style.display = "none";
      var camText='';
      for(var i=(adcampaign.options.length-1);i>=0;i--)
      {
        if(adcampaign.options[i].value==adcampaign.value){
          camText=adcampaign.options[i].text;
          break;
        }
      }

      name.value=camText;
    }
  }   
 en4.core.runonce.add(updateTextFields);

function profileFields(profile_id) {
  window.console.log(profile_id);
 <?php foreach ($this->profileField as $key => $profilefield) { ?>
          var div_id=  'group_<?php echo $key ?>';
           var element_id = document.getElementById(div_id);
           if(element_id)
           element_id.style.display = "none";
      <?php } ?>

          var div_id=  'group_' + profile_id;
          var element_id = document.getElementById(div_id);
          if(element_id)
            element_id.style.display = "block";
  }
</script>
<?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
         <?php if (count($this->navigation)): ?>
            <?php
             $this->navigation()->menu()->setContainer($this->navigation)->render();
          ?>
      <?php endif; ?>
<?php endif; ?> 
