<?php

require ("./config.php");

$tableName = $_POST["tableName"];
$idColumn = $_POST["idColumn"];
$labelColumn = $_POST["labelColumn"];
$conditionalColumnName = $_POST["conditionalColumnName"];
$conditionalColumnValue = $_POST["conditionalColumnValue"];
// $defaultValue = $_POST["defaultValue"];

$finalObject = new \stdClass();
$finalObject->data = [];

$query = "";
if ($conditionalColumnName == "" && $conditionalColumnValue == "") {
    $query = "select $idColumn, $labelColumn from $tableName order by $labelColumn asc";

} else {
    $query = "select $idColumn, $labelColumn from $tableName where $conditionalColumnName = $conditionalColumnValue order by $labelColumn asc";
}

$result = $con->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tempObject = new \stdClass();
        $tempObject->id = $row[$idColumn];
        $tempObject->label = $row[$labelColumn];
        // $tempObject->selected = $row[$idColumn] == $defaultValue ? true : false;
        $finalObject->data[] = $tempObject;
    }
}

echo json_encode($finalObject);


?>