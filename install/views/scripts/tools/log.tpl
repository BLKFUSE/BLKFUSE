<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: log.tpl 7379 2010-09-14 07:13:04Z john $
 * @author     John
 */
?>

<h3>
  <?php echo $this->translate("Log Browser") ?>
</h3>
<p>System logs are helpful for troubleshooting and debugging. Select the log you would like to view below.</p>
<p>More info: <a href="http://support.socialengine.com/questions/216/Admin-Panel-Stats-Log-Browser" target="_blank">See KB article</a>.</p>	
<br />

<script type="text/javascript">
  scriptJquery(document).ready(function() {
    var el = scriptJquery('.admin_logs')[0];
//     if( el ) {
//       el.scrollTo(0, el.getScrollSize().y);
//     }
    scriptJquery(document).on('click','#clear',function(event){
      if( scriptJquery('#file').val() == '' ) {
        return;
      }
      var url = '<?php echo $this->url() ?>?clear=1';
      url += '&file=' + encodeURI(scriptJquery('#file').val());
      scriptJquery('#filter_form')
        .attr('action', url)
        .attr('method', 'POST')
        .submit();
        ;
    });
    scriptJquery(document).on('click','#download',function(event){
      if( scriptJquery('#file').val() == '' ) {
        return;
      }
      var url = '<?php echo $this->url(array('action' => 'log-download')) ?>';
      url += '?file=' + encodeURI(scriptJquery('#file').val());
      (new IFrame({
        src : url,

        styles : {
          display : 'none'
        }
      })).inject(document.body);
    });
  });
</script>

<?php if( !empty($this->formFilter) ): ?>
  <div class="admin_search">
    <div class="search">
      <?php echo $this->formFilter->render($this) ?>
    </div>
  </div>

  <br />
<?php endif; ?>

<?php if( $this->error ): ?>
  <ul class="form-notices">
    <li>
      <?php echo $this->error ?>
    </li>
  </ul>
<?php endif; ?>


<?php if( !empty($this->logText) ): ?>

  <div class="admin_logs_container">

    <div class="admin_logs_info">
      <?php echo $this->translate(
        'Showing the last %1$s lines, %2$s bytes from the end. The file\'s size is %3$s bytes.',
        $this->locale()->toNumber($this->logLength),
        $this->locale()->toNumber($this->logSize - $this->logOffset),
        $this->locale()->toNumber($this->logSize)
      ) ?>
    </div>
    <br />

    <div class="admin_logs_nav">
      <span class="admin_logs_nav_next">
        <?php if( $this->logEndOffset > 0 ): ?>
          <a href="<?php echo $this->url() ?>?<?php echo http_build_query(array(
            'file' => $this->logFile,
            'length' => $this->logLength,
            'offset' => $this->logEndOffset,
          )) ?>">
            Next
          </a>
        <?php endif; ?>
      </span>
      <?php if( $this->logOffset < $this->logSize ): ?>
        <span class="admin_logs_nav_previous">
          <a href="<?php echo $this->url() ?>?<?php echo http_build_query(array(
            'file' => $this->logFile,
          )) ?>">
            Back
          </a>
        </span>
      <?php endif; ?>
    </div>

    <div class="admin_logs">
      <pre><?php echo $this->logText ?></pre>
    </div>

  </div>
<?php endif; ?>
