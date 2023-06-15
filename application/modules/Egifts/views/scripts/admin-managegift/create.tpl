<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
  <?php echo $this->form->render($this); ?>

  <script>
    function allowOnlyNumbers(evt)
    {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 37 && charCode != 39 && charCode > 31 && (charCode < 48 || charCode > 57)) // for only numbers and forward, backward arrows
      {
        return false;
      }
      return true;
    }
  </script>
