<?php

require_once __DIR__ . '/vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/');
$twig = new \Twig\Environment($loader, [
    //'cache' => __DIR__."/cache/",
]);
$locale = "en";
$translations_filename = "locale_" . $locale . ".json";
$translations = json_decode(@file_get_contents($translations_filename), true) ?? [];
$twig->addFilter(new \Twig\TwigFilter("md5", "md5"));
$twig->addFilter(new \Twig\TwigFilter("md", [new Parsedown(), "text"]));
$twig->addFilter(new \Twig\TwigFilter("t", function ($message, $arguments = []) use (&$translations) {
    if (!isset($translations[$message])) {
        $translations[$message] = "";
    }
    return sprintf($translations[$message], ...$arguments);


}));
$value = \Symfony\Component\Yaml\Yaml::parseFile("app.yaml");
$details = \Symfony\Component\Yaml\Yaml::parseFile("details.yaml");

$template = $twig->load('index.html.twig');
echo $template->render(array_merge(["details" => $details], $value));
file_put_contents($translations_filename, json_encode($translations ?? [], JSON_PRETTY_PRINT));