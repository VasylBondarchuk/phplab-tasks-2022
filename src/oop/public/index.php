<?php

require '../../../vendor/autoload.php';

use src\oop\app\src\ScrapperFactory;
use src\oop\app\src\Parsers\FilmixParserStrategy;
use src\oop\app\src\Transporters\CurlStrategy;

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

<?php

$scrapperFactory = new ScrapperFactory();

//$filmixMovie = $scrapperFactory->create('filmix')->getMovie('https://filmix.ac/filmi/triller/151413-lost-ledyanoy-drayv-2021.html');
//$kinoukrMovie = $scrapperFactory->create('kinoukr')->getMovie('https://kinoukr.com/4166-pravdyva-istoriya-bandy-kelli.html');

$filmixStrategy = new FilmixParserStrategy();
$curlStrategy = new CurlStrategy();
$content = $filmixStrategy->parseContent($curlStrategy->getContent('https://github.com/'));
print_r($content);
?>

</body>
</html>





