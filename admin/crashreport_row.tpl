<tr>
  <td class='massaction'>
    <input type='checkbox' class='massaction_chk' entryid='[@entryid]' dumpfile='[@dumpfile]' onclick='javascript:onBoxChecked();'></input>
  </td>
  <td>
    <span class='application icon [@iconclass]'></span>
    <a href='index.php?productid=[@productid]'>[@productid]</a>
  </td>
  <td>
    <a href='index.php?productid=[@productid]&version=[@version]'>[@version]</a>
  </td>
  <td>
    <a href='index.php?ip=[@ip]'>[@ipstr]</a>
  </td>
  <td>
    [@timestamp]
  </td>
  <td class='info'>
    <span class='description icon' title='[@description]'></span>
    <span class='stacktrace icon' title='[@stacktrace]'></span>
  </td>
  <td class='icons'>
    <span title='Download' class='download icon' onclick='javascript:download("[@dumpfile]");'></span>
    <span title='Delete' class='delete icon' onclick='javascript:deleteEntryWithConfirm([@entryid]);'></span>
  </td>
</tr>