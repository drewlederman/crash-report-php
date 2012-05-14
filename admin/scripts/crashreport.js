
function selectAll(chk) {
  $(".report_item .massaction_chk").each(function() {
    this.checked = chk.checked;
  });
}

function download(file) {
  var url = "./files/"+file;
  window.open(url, "_blank");
}

function deleteEntryWithConfirm(id) {
  if (confirm("Are you sure you want to delete this record?")) {
    deleteEntries(id);
  }
}

function deleteEntries(ids) {
  $.get("delete.php?entryids="+ids)
      .complete(function() { window.location.reload(); });
}

function massActionDownload() {
  $(".report_item .massaction_chk:checked").each(function() {
    download($(this).parents(".report_item").attr("dumpfile"));
  });
}

function massActionDelete() {
  if (confirm("Are you sure you want to delete the selected records?")) {
    var ids = "";
    var items = $(".report_item .massaction_chk:checked");
    
    if (items.size() > 0) {
      ids += $(items[0]).parents(".report_item").attr("entryid");
      for (i = 1; i < items.size(); i++) {
        ids += "," + $(items[i]).parents(".report_item").attr("entryid");
      }
      deleteEntries(ids);
    }  
  }
}

function onClickStackTrace(elem) {
  $(elem).toggleClass("expanded");
}