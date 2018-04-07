<?php
$db = new PDO('mysql:host=127.0.0.1; dbname=exercises2', 'root');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$query = $db->prepare("SELECT * FROM `colors`");
$query->execute();

$result=$query->fetchall();

function makeDropDown($colors){
    $resultString = "";
    foreach ($colors as $color) {
        $resultString .= '<option value="' . $color['id'] . '">' . $color['color'] . '</option>';
    }
    echo $resultString;
}




?>
<form method="POST" action="color.php">
    <select name="inputColor">
        <?php echo makeDropDown($result) ?>
    </select>
    <input type="submit">
</form>
<ul>
    <?php
    if($_POST){

        $query2=$db->prepare("
        SELECT `pets`.`type`
        FROM `colors`
INNER JOIN `children`
ON `colors`.`id`
=`children`.`f_color`
INNER JOIN
`parent_of`
ON `children`.`id`
= `parent_of`.`children_id`
INNER JOIN
`pet_of`
ON `parent_of`.`adults_id`
=`pet_of`.`owner_id`
INNER JOIN
`pets`
ON `pet_of`.`pet_id`
=`pets`.`id`
WHERE `children`.`f_color` = :color;
");

        $query2->bindParam(':color',$_POST['inputColor']);
        $query2->execute();
        $results2=$query2->fetchall();

        $color=$_POST['inputColor'];

        $query3=$db->prepare("SELECT COLOR FROM `colors` WHERE `id` =$color; ");
        $query3->execute();
        $colorName=$query3->fetchall();

        $col=$colorName[0]['COLOR'];



        $color=$_POST['inputColor'];
            echo "The children that like color $col have the following pets:";
            foreach($results2 as $values) {
            echo "<li> $values[type] </li>";
        }
    }
    ?>
</ul>
