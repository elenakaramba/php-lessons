<html>
<head>
    <title>Hello World</title>
</head>
<body>
<h1>Hello!</h1>
<form>
    <b>Введите размер</b>
    <input type="text" name="size">
    <button type="submit">Submit</button>

</form>
<?php
if (array_key_exists("size", $_GET)) {
  $size= (int)$_GET["size"];
  if ($size <=0) {
      $size = 10;
  }
} else {
    $size = 10;
}
echo "<h2>$size</h2>";
?>
<table border="1">
    <?php
    echo "<tr>";
    echo "<td> </td>";
    for ($i=1; $i<=$size;$i++) {
        echo "<td> <b>" ;
        echo $i;
        echo "</b> </td>";

    }
    echo "</tr>";
    for ($i=1; $i<=$size; $i++) {
        echo "<tr>";
        echo "<td><b>$i</b></td>" ;
        for ($j=1; $j<=$size; $j++) {
            echo "<td>";
            echo $i*$j;
            echo "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>

