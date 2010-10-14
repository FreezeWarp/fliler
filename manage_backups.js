function processFile(file,i) {
  var data = http("manage_backups.php?action=new3&file=" + file,["status"]);
  document.getElementById('processed').innerHTML = i;
  if (data["status"] != 1) {
    document.getElementById('bad').innerHTML += file + "<br />";
  }
}