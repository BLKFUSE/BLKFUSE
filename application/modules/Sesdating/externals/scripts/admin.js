
scriptJquery(document).ready(function(e){
  scriptJquery('#name').removeAttr('onChange');
  scriptJquery('#name').change(function(e){
    var value = scriptJquery(this).val();
    if(value == "sesbasic_mini"){
      window.location.href = "admin/sesdating/menu";
    }else{
       scriptJquery(this).parent().submit(); 
    }
  })
});
