<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Classified
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>


<?php
  // Render the admin js
  echo $this->render('_jsAdmin.tpl')
?>

<h2>Classifieds Plugin</h2>

<?php if( engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<p>
  <?php echo $this->translate('CLASSIFIED_FIELDS_VIEWS_SCRIPTS_ADMINFIELDS_DESCRIPTION') ?>
</p>

<br />

<div class="admin_fields_options">
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion">Add Question</a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addheading" style="display:none;">Add Heading</a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;">Save Order</a>
</div>

<br />


<ul class="admin_fields">
  <?php foreach( $this->topLevelMaps as $field ): ?>
    <?php echo $this->adminFieldMeta($field) ?>
  <?php endforeach; ?>
</ul>

<br />
<br />


