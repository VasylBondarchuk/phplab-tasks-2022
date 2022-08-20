<?php

use src\oop\app\src\ScrapperFactory;

$moviesUrls = [
    ['domain' => ScrapperFactory::FILMIX, 'url'=> 'https://filmix.ac/filmi/triller/151413-20022-ledyanoy-drayv-2021.html'],
    ['domain' => ScrapperFactory::KINOUKR,'url'=> 'https://kinoukr.com/4166-pravdyva-istoriya-bandy-kelli.html']
];

return $moviesUrls;