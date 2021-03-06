<?php
require "genderCategories/DividedCategories.php";
require "genderCategories/MenCategories.php";
require "genderCategories/WomanCategories.php";

function getWebsiteLink($router){
    $urlHead = "https://www2.hm.com/nl_nl/";
    $urlTail = ".html?";
    $url = "";
    $amount = "sort=stock&image-size=small&image=model&offset=0&page-size=200";

    //Check the main category of the endpoint call
    if (strpos($router->endpoint,"heren") !== false) {
        $url = $urlHead . "heren" . "/shop-op-item/";
    }

    if(strpos($router->endpoint,"dames") !== false) {
        $url = $urlHead . "dames" . "/shop-by-product/";
    }

    if(strpos($router->endpoint,"divided") !== false) {
        $url = $urlHead . "divided" . "/shop-by-product/";
    }

    //Match the endpoint parameter with the categories
    $category = containsCategory($router->endpoint, $router->id);

    //Return if no category is found
    if (empty($category))
        return "";

    $url = $url . "/" . $category[1];

    $url = $url . $urlTail;
    $url = $url . $category[1];
    $url = $url . $amount;

    return $url;
}

function containsCategory($path, $parameter){
    $c = categoryStringFormatter($parameter);

    $categoryArr = [];

    //Check which array has to be used according to the maintopic
    if (strpos($path,"heren") !== false)
        $categoryArr = getMenCategory();

    if(strpos($path,"dames") !== false)
        $categoryArr = getWomanCategory();

    if(strpos($path,"divided") !== false)
        $categoryArr = getDividedCategory();

    //Find the category in that specific array
    foreach ($categoryArr as $category){
        $lowerCategory = strtolower($category[0]);

        if ($parameter == "" && $category[0] == "")
            return $category;

        if (strpos($lowerCategory, $c) !== false)
            return $category;
    }

    return [];
}

function categoryStringFormatter($category){
    //Format the string to make it match the pattern
    $c = strtolower($category);
    if (strpos($c, '%20') !== false)
        $c = str_replace("%20", " ", $c);
    return $c;
}