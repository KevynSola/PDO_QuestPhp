<?php

require './connec.php';
$pdo = new \PDO(DSN, USER, PASS);

$personn['firstname'] = '';
$personn['lastname'] = '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Nettoyage de donnée
    $personn = array_map('trim', $_POST);
    // Initialise la variable errors
    $errors = [];

    if (empty($personn['firstname'])){
        $errors[] = "Le prénom est obligatoire";
    }

    if (strlen($personn['firstname']) > 45){
        $errors[] = "Le prénom doit faire moins de 45 caractères";
    }

    if (empty($personn['lastname'])){
        $errors[] = "Le nom est obligatoire";
    }

    if (strlen($personn['lastname']) > 45){
        $errors[] = "Le nom doit faire moins de 45 caractères";
    }

    if (empty($errors)){

        $query = 'INSERT INTO friends (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);

        $statement->bindValue(':firstname', $personn['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $personn['lastname'], \PDO::PARAM_STR);

        $statement->execute();

        $query = "SELECT * FROM friends";
        $statement = $pdo->query($query);

        $friendsArray = $statement->fetchAll(PDO::FETCH_ASSOC);
        /* header('Location:index.php'); */
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form to PDO</title>
</head>
<body>
    <h1>Form to PDO Course</h1>
        <ul>
            <?php foreach($friendsArray as $friend): ?>
            <li>
                <?= $friend['firstname'] . ' ' . $friend['lastname']; ?>
            </li>
            <?php endforeach ?>
        </ul>
    <?php if(!empty($errors)) : ?>
        <ul>
            <?php foreach($errors as $error): ?>
            <li>
                <?= $error ?>
            </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <form action="" method="post">
        <label for="firstname">Firstname:</label>
        <input type="text" name="firstname" id="firstname" required value="<?= $personn['firstname']?>">

        <label for="lastname">Lastname:</label>
        <input type="text" name="lastname" id="lastname" required value="<?= $personn['lastname']?>">

        <button>Submit</button>

        <?php var_dump($personn); ?>
    </form>
</body>
</html>