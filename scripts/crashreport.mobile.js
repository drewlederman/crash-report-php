
$(document).bind("pageinit", function() {
  $(".crashreport > .header")
    .bind("swipeleft",  function() { onSwipeLeft($(this).parent()); })
    .bind("swiperight", function() { onSwipeRight($(this).parent()); });
}); 

function onSwipeLeft(elem) {
  if ($(elem).find(".delete").size() > 0) {
    return;
  }
  
  var $delete = $("<div>Delete</div>").attr({
      "class":"delete",
      "data-role":"button",
      "data-theme":"e",
      "data-mini":"true"
    });
    
  $delete.click(function() { onClickDelete(elem); });
  
  $(elem).append($delete).trigger("create");
}

function onSwipeRight(elem) {
  $(elem).find(".delete").remove();
}

function onClickDelete(elem) {
  var entryId = $(elem).attr("entryid");
  $.get("delete.php?entryids="+entryId, function() {
    $(elem).remove();
  });
}