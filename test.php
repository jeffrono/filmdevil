<?
require_once "dbFunctions.php";

$undo =  createUndo("fests", "ID", 1737, array("undoSQL"));
echo $undo;
//fd_query($undo);
echo "<p>done";

?>