<?php
require '../../../vendor/autoload.php';

use src\oop\app\src\Models\Movies;

// array of Movies objects
$movies = new Movies();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            display: flex;
        }

        .container .film {
            flex: 1;
            padding: 20px;
        }

        .container .film img {
            width: 200px;
            height: 290px;
        }

    </style>
</head>
<body>

<div class="container">
<?php foreach ($movies->getAllMovies() as $movie): ?>
    <div class="film">
        <h1><?= $movie->getTitle(); ?></h1>
        <img src="<?= $movie->getPoster(); ?>" alt="Poster"/>
        <p><?= $movie->getDescription(); ?></p>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>




