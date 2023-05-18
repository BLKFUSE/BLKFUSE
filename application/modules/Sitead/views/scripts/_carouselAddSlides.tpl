<style type="text/css">

  .form-elements #ad_heading-wrapper {
    display: inline-block;
    vertical-align: middle;
  }
  .slides-count {
    text-align: left;
    vertical-align: middle;
  }
  .slides-count > span{
    margin-right: 10px;
    float: left;
    text-align: right;
    width: 150px;
  }
  .slides-count .slides_action_buttons {
    text-align: right;
    margin: 0 10px;
  }

  .slides-count .slides_action_buttons span a {
    border: 1px solid #ccc;
    padding: 0px 5px;
    cursor: pointer;
    margin: 2px;
    display: inline-block;
  }
  .slides-count .slides_action_buttons #slides_counters {
    margin: 0 15px;
  }
  .slides-count .slides_action_buttons a.slides_counter_button.active{
    font-weight: 700;
  }
</style>

<div class="sitead_crate">
  <div class="slides-count">
    <span id="slides_field"><?php echo $this->translate("Slides") ?></span>
    <div class="slides_action_buttons">
      <span id="slides_counters">
        <?php for( $i = 1; $i <= 9; $i++ ): ?>
        <a class="slides_counter_button <?php if($i ==1 ): ?> active <?php endif; ?>" id="slides_counter_button_<?php echo $i ?>" onclick="showCarouselSlides(<?php echo $i ?>)" <?php if($i >2 ): ?>style="display:none"<?php endif; ?>>
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
      </span>
      <span>
        <a id="button_add" onclick="valuechange(1, 'add');"><i class="fa fa-plus" aria-hidden="true" style="display: inline-block"></i></a>
        <a id="button_minus" onclick="valuechange(-1, 'remove');" style="display: none;"><i class="fa fa-minus" aria-hidden="true"></i></a>
      </span>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('slides_field').getParent('.sitead_crate').inject($('ad_heading_design-element'));
</script>