<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?> 
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php
?>

<?php
  if (APPLICATION_ENV == 'production')
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.min.js');
  else
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>

<script type="text/javascript">

  // Populate data
  var maxRecipients = <?php echo sprintf("%d", $this->maxRecipients) ?> || 10;
  var to = {
    id : false,
    type : false,
    guid : false,
    title : false
  };
  var isPopulated = false;

  <?php if( !empty($this->isPopulated) && !empty($this->toObject) ): ?>
    isPopulated = true;
    to = {
      id : <?php echo sprintf("%d", $this->toObject->getIdentity()) ?>,
      type : '<?php echo $this->toObject->getType() ?>',
      guid : '<?php echo $this->toObject->getGuid() ?>',
      title : '<?php echo $this->string()->escapeJavascript($this->toObject->getTitle()) ?>'
    };
  <?php endif; ?>
  
  function removeFromToValue(id) {
    // code to change the values in the hidden field to have updated values
    // when recipients are removed.
    var toValues = $('toValues').value;
    var toValueArray = toValues.split(",");
    var toValueIndex = "";

    var checkMulti = id.search(/,/);

    // check if we are removing multiple recipients
    if (checkMulti!=-1){
      var recipientsArray = id.split(",");
      for (var i = 0; i < recipientsArray.length; i++){
        removeToValue(recipientsArray[i], toValueArray);
      }
    }
    else{
      removeToValue(id, toValueArray);
    }

    // hide the wrapper for usernames if it is empty
    if ($('toValues').value==""){
      $('toValues-wrapper').setStyle('height', '0');
    }

    $('to').disabled = false;
  }

  function removeToValue(id, toValueArray){
    for (var i = 0; i < toValueArray.length; i++){
      if (toValueArray[i]==id) toValueIndex =i;
    }

    toValueArray.splice(toValueIndex, 1);
    $('toValues').value = toValueArray.join();
  }

  en4.core.runonce.add(function() {
    if( !isPopulated ) { // NOT POPULATED
      new Autocompleter.Request.JSON('to', '<?php echo $this->url(array('module' => 'user', 'controller' => 'friends', 'action' => 'suggest','message' => true), 'default', true) ?>', {
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
          if(token.type == 'user'){
            var choice = new Element('li', {
              'class': 'autocompleter-choices',
              'html': token.photo,
              'id':token.label
            });
            new Element('div', {
              'html': this.markQueryValue(token.label),
              'class': 'autocompleter-choice'
            }).inject(choice);
            this.addChoiceEvents(choice).inject(this.choices);
            choice.store('autocompleteChoice', token);
          }
          else {
            var choice = new Element('li', {
              'class': 'autocompleter-choices friendlist',
              'id':token.label
            });
            new Element('div', {
              'html': this.markQueryValue(token.label),
              'class': 'autocompleter-choice'
            }).inject(choice);
            this.addChoiceEvents(choice).inject(this.choices);
            choice.store('autocompleteChoice', token);
          }
            
        },
        onPush : function(){
          if( $('toValues').value.split(',').length >= maxRecipients ){
            $('to').disabled = true;
          }
        }
      });
      
      new Composer.OverText($('to'), {
        'textOverride' : '<?php echo $this->translate('Start typing...') ?>',
        'element' : 'label',
        'isPlainText' : true,
        'positionOptions' : {
          position: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
          edge: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
          offset: {
            x: ( en4.orientation == 'rtl' ? -4 : 4 ),
            y: 2
          }
        }
      });

    } else { // POPULATED

      var myElement = new Element("span", {
        'id' : 'tospan' + to.id,
        'class' : 'tag tag_' + to.type,
        'html' :  to.title /* + ' <a href="javascript:void(0);" ' +
                  'onclick="this.parentNode.destroy();removeFromToValue("' + toID + '");">x</a>"' */
      });
      $('to-element').appendChild(myElement);
      $('to-wrapper').setStyle('height', 'auto');

      // Hide to input?
      $('to').setStyle('display', 'none');
      $('toValues-wrapper').setStyle('display', 'none');
    }
  });
</script>
<?php
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/mdetect/mdetect' . ( APPLICATION_ENV != 'development' ? '.min' : '' ) . '.js');
?>
<div class="sesbsic_popup_form">
<?php echo $this->form->render($this) ?>
</div>
