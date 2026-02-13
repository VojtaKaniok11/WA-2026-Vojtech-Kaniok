<?php

$name = "";
$message = "";
$age = 0;
$message2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["my_name"];
    $age = $_POST["my_age"];
    if ($name == "Vojta") {
        $message = "Ahoj, $name!";
        $age = $_POST["my_age"];
    } else {
        $message = "Neznám tě!";
    }
    
 

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
   <h1>Test PHP</h1>

    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Et rem, unde beatae, facere nesciunt consequuntur sed velit non voluptates quae fuga aut. Saepe veniam, voluptatem officia unde officiis nihil reiciendis?</p>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Et rem, unde beatae, facere nesciunt consequuntur sed velit non voluptates quae fuga aut. Saepe veniam, voluptatem officia unde officiis nihil reiciendis?</p>
    <form method="post">
        <input type="text" name="my_name" placeholder="Enter your name">
        <input type ="number" name="my_age" placeholder="Enter your age">
        <button type="submit">Odeslat</button>
        
    </form>

    <p>
        <?php echo $message; ?> 
        
    </p>
<p>
    <?php
      if ($age > 0) {
        echo "Tvůj věk je: $age";
      }
    ?>

</body>
</html>