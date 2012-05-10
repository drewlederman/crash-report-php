
function selectAll(chk) {
  $(".massaction_chk").each(function() {
    this.checked = chk.checked;
  });
  onBoxChecked();
}

function onBoxChecked() {
  var count = $(".massaction_chk:checked").size();
  $(".massaction_panel").toggle(count > 0);
}

function sort(sortBy) {
  window.location = "index.php?sort="+sortBy;
}

function download(file) {
  var url = "http://crashreport.kayako.com/"+file;
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
  $(".massaction_chk:checked").each(function() {
    download($(this).attr("dumpfile"));
  });
}

function massActionDelete() {
  if (confirm("Are you sure you want to delete the selected records?")) {
    var ids = "";
    
    var items = $(".massaction_chk:checked");
    if (items.size() > 0) {
      ids += $(items[0]).attr("entryid");
      for (i = 1; i < items.size(); i++) {
        ids += "," + $(items[i]).attr("entryid");
      }
    }
        
    deleteEntries(ids);
  }
}