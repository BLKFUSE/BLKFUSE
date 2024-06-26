<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: edit.tpl 9760 2012-08-13 23:35:28Z matthew $
 * @author     John
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_layout", 'parentMenuItemName' => 'core_admin_main_layout_language', 'lastMenuItemName' => 'Edit Phrases')); ?>
<?php
$db = Engine_Db_Table::getDefaultAdapter();
$translationAdapter = $db->select()
  ->from('engine4_core_settings', 'value')
  ->where('`name` = ?', 'core.translate.adapter')
  ->query()
  ->fetchColumn();   
 ?>
<div class="admin_common_top_section">
  <h2 class="page_heading">
    <?php echo $this->htmlLink(array('route' => 'admin_default', 'controller' => 'language', 'action' => 'index'), $this->translate('Language Manager')) ?>
    &#187; <?php echo $this->localeTranslation ?>
  </h2>
  <p> <?php echo $this->translate("CORE_VIEWS_SCRIPTS_ADMINLANGUAGE_EDIT_DESCRIPTION") ?></p>
</div>  
  <div class="admin_search">
  <div class="search">
    <?php echo $this->filterForm->render($this) ?>
  </div>
</div>
<?php
  $url = $this->url() . $this->query;
  if( $this->page ){
    if( !$this->query ){
      $url .=	'?';
    } else {
      $url .= '&';
    }
    $url .= "page=" . $this->page;
  }
?>
<form action="<?php echo $url ?>" method="post">
  <div>
    <div class="admin_language_editor">
      <div class="admin_language_editor_top">
        <div class="admin_language_editor_addphrase">
          <a class="admin_link_btn smoothbox" href="<?php echo $this->url(array('action' => 'add-phrase')) ?>">Add New Phrase</a>
        </div>
        <div class="admin_language_editor_pages">
          <?php $pageInfo = $this->paginator->getPages(); if ($pageInfo->totalItemCount):  ?>
          <?php echo $this->translate('Showing %1$s-%2$s of %3$s phrases', $pageInfo->firstItemNumber, $pageInfo->lastItemNumber, $pageInfo->totalItemCount) ?>
          <?php else: ?>
            <?php echo $this->translate('No phrases found.') ?>
          <?php endif; ?>
          <span>
            <?php if( !empty($pageInfo->previous) ): ?>
              <?php echo $this->htmlLink(array('reset' => false, 'QUERY' => array_merge(array('page' => $pageInfo->previous), $this->values)), $this->translate('Previous Page')) ?>
            <?php endif; ?>
            <?php if( !empty($pageInfo->previous) && !empty($pageInfo->next) ): ?>
               |
            <?php endif; ?>
            <?php if( !empty($pageInfo->next) ): ?>
              <?php echo $this->htmlLink(array('reset' => false, 'QUERY' => array_merge(array('page' => $pageInfo->next), $this->values)), 'Next Page') ?>
            <?php endif; ?>
          </span>
        </div>
      </div>
      <ul>
        <?php $tabIndex = 1; ?>
        <?php foreach( $this->paginator as $item ): ?>
          <?php if( !$item['plural'] ): ?>
            <li>
              <?php
                $height = ceil(max(Engine_String::strlen((string)$item['current']), Engine_String::strlen((string)$item['original']), 1) / 60) * 1.2;
                echo $this->formTextarea(sprintf('values[%d]', $item['uid']), $item['current'], array('cols' => 60, 'rows' => 1, 'style' => 'height: ' . $height . 'em', 'onkeypress' => 'checkModified(this, event);'));
                echo $this->formHidden(sprintf('keys[%d]', $item['uid']), $item['key']);
              ?>
              <span class="admin_language_original">
                <?php echo $this->escape($item['original']) ?>
              </span>
            </li>
          <?php else: ?>
            <?php for( $i = 0; $i < $this->pluralFormCount; $i++ ): ?>
              <li>
                <?php if(isset($this->pluralFormSample[$i])) { ?>
                <span class="admin_language_plural">
                  <?php echo $this->translate("This phrase is pluralized. Example values:") ?> <?php echo join(', ', (array) $this->pluralFormSample[$i]) ?>
                </span>
                <?php } ?>
                <?php
                  $height = ceil(max(Engine_String::strlen((string)@$item['current'][$i]), Engine_String::strlen((string)@$item['original'][0]), 1) / 60) * 1.2;
                  echo $this->formTextarea(sprintf('values[%d][%d]', $item['uid'], $i), @$item['current'][$i], array('cols' => 60, 'rows' => 2, 'style' => 'height: ' . $height . 'em', 'onkeypress' => 'checkModified(this, event);'));
                  echo $this->formHidden(sprintf('keys[%d][%d]', $item['uid'], $i), $item['key']);
                ?>
                <span class="admin_language_original">
                  <?php echo isset($item['original'][0]) ? $this->escape($item['original'][0]) : '' ?>
                </span>
              </li>
            <?php endfor; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <div class="admin_language_editor_submit">
        <?php if( $translationAdapter != 'array'): ?>
        <button type="submit"><?php echo $this->translate("Save Changes") ?></button>
        <?php else: ?>
        Please uncheck the "Translation Performance" box <a href="admin/core/settings/performance">here</a> before saving your changes.    
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</form>

<br />

<p>
   <?php echo $this->translate(
           "When you've finished editing this language pack, you can return to the %s.",
           $this->htmlLink(array('route'=>'admin_default','controller'=>'language'), 'Language Manager')) ?>
</p>
<br />
<p>
  Also after making changes in your Language Manager you can improve the load times of you pages by using PHP Arrays.<br />
  <a href="admin/core/settings/performance">Click Here</a> and check the "Translation Performance" performance box.  <br />
  Please note that the initial converstion may take longer that 30 seconds, but will improve future page loads.
</p>
<script type="application/javascript">
  scriptJquery('.core_admin_main_layout').parent().addClass('active');
  scriptJquery('.core_admin_main_layout_language').addClass('active');
</script>
