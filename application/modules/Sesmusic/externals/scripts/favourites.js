
function sesmusicFavourite(resource_id, resource_type) {

  if (document.getElementById(resource_type + '_favouritehidden_' + resource_id))
    var favourite_id = document.getElementById(resource_type + '_favouritehidden_' + resource_id).value

  en4.core.request.send(scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesmusic/favourite/index',
    data: {
      format: 'json',
      'resource_type': resource_type,
      'resource_id': resource_id,
      'favourite_id': favourite_id
    },
    success: function(responseJSON) {
      if (responseJSON.favourite_id) {
        if (document.getElementById(resource_type + '_unfavourite_' + resource_id))
          document.getElementById(resource_type + '_unfavourite_' + resource_id).style.display = 'inline-block';
        if (document.getElementById(resource_type + '_favouritehidden_' + resource_id))
          document.getElementById(resource_type + '_favouritehidden_' + resource_id).value = responseJSON.favourite_id;
        if (document.getElementById(resource_type + '_favourite_' + resource_id))
          document.getElementById(resource_type + '_favourite_' + resource_id).style.display = 'none';
      } else {
        if (document.getElementById(resource_type + '_favouritehidden_' + resource_id))
          document.getElementById(resource_type + '_favouritehidden_' + resource_id).value = 0;
        if (document.getElementById(resource_type + '_unfavourite_' + resource_id))
          document.getElementById(resource_type + '_unfavourite_' + resource_id).style.display = 'none';
        if (document.getElementById(resource_type + '_favourite_' + resource_id))
          document.getElementById(resource_type + '_favourite_' + resource_id).style.display = 'inline-block';

      }
    }
  }));
}
