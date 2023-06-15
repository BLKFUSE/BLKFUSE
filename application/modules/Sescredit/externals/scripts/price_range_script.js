scriptJquery(document).ready(function(){
  scriptJquery('#price-range-submit').hide();
  scriptJquery("#min_price,#max_price").on('change', function () {
    scriptJquery('#price-range-submit').show();
    var min_price_range = parseInt(scriptJquery("#min_price").val());
    var max_price_range = parseInt(scriptJquery("#max_price").val());
    if (min_price_range > max_price_range) {
      scriptJquery('#max_price').val(min_price_range);
    }
    scriptJquery("#slider-range").slider({
      values: [min_price_range, max_price_range]
    });
  });
  scriptJquery("#min_price,#max_price").on("blur", function () {   
    var min_price_range = parseInt(scriptJquery("#min_price").val());
    var max_price_range = parseInt(scriptJquery("#max_price").val());
    if(min_price_range == max_price_range){
      max_price_range = min_price_range + 100;
      scriptJquery("#min_price").val(min_price_range);		
      scriptJquery("#max_price").val(max_price_range);
    }
    scriptJquery("#slider-range").slider({
      values: [min_price_range, max_price_range]
    });
  });
  scriptJquery("#min_price,#max_price").on("paste keyup", function () {                                        
    scriptJquery('#price-range-submit').show();
    var min_price_range = parseInt(scriptJquery("#min_price").val());
    var max_price_range = parseInt(scriptJquery("#max_price").val());
    scriptJquery("#slider-range").slider({
      values: [min_price_range, max_price_range]
    });
  });
  scriptJquery(function () {
    scriptJquery("#slider-range").slider({
      range: true,
      orientation: "horizontal",
      min: 0,
      max: 10000,
      values: [0, 10000],
      step: 1,
      slide: function (event, ui) {
        if (ui.values[0] == ui.values[1]) {
            return false;
        }
        scriptJquery("#min_price").val(ui.values[0]);
        scriptJquery("#max_price").val(ui.values[1]);
      }
    });
    var min =scriptJquery("#min_price").val();
    var max = scriptJquery("#max_price").val();
    scriptJquery("#slider-range").slider({
      values: [min, max]
    });
  });
  scriptJquery("#slider-range,#price-range-submit").click(function () {
    var min_price = scriptJquery('#min_price').val();
    var max_price = scriptJquery('#max_price').val();
    scriptJquery("#searchResults").text("Here List of products will be shown which are cost between " + min_price  +" "+ "and" + " "+ max_price + ".");
  });
});
