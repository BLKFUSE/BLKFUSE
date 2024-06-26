<?php
  $headScript = new Zend_View_Helper_HeadScript();
  $headScript->appendFile($this->layout()->staticBaseUrl.'application/modules/Core/externals/scripts/create_edit_category.js');
?>
<script>
  var modulename = 'video';
  en4.core.runonce.add(function() {
    if(document.getElementById('category_id')) {
      <?php if(isset($_GET['category_id']) && $_GET['category_id'] != 0) { ?>
          showSubCategory('<?php echo $_GET['category_id']; ?>','<?php echo !empty($_GET['subcat_id']) ? $_GET['subcat_id'] : 0; ?>');
        <?php if(isset($_GET['subsubcat_id'])){ ?>
          showSubSubCategory("<?php echo !empty($_GET['subcat_id']) ? $_GET['subcat_id'] : 0; ?>","<?php echo $_GET['subsubcat_id']; ?>");
        <?php } else {?>
          if(document.getElementById('subsubcat_id-wrapper'))
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
        <?php } ?>
      <?php } else { ?>
        if(document.getElementById('subcat_id-wrapper'))
          document.getElementById('subcat_id-wrapper').style.display = "none";
        if(document.getElementById('subsubcat_id-wrapper'))
          document.getElementById('subsubcat_id-wrapper').style.display = "none";
      <?php } ?>
    }
  });
</script>
<?php if( $this->form ): ?>
  <?php echo $this->form->render($this) ?>
<?php endif ?>
