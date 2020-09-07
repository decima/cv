<?php

require_once __DIR__ . '/vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/');
$twig = new \Twig\Environment($loader, [
    //'cache' => __DIR__."/cache/",
]);
$twig->addFilter(new \Twig\TwigFilter("md5", "md5"));
$parsedown = new Parsedown();
$twig->addFilter(new \Twig\TwigFilter("md", [$parsedown, "text"]));
$value = \Symfony\Component\Yaml\Yaml::parseFile("app.yaml");
$details = \Symfony\Component\Yaml\Yaml::parseFile("details.yaml");

$template = $twig->load('index.html.twig');
echo $template->render(array_merge(["details" => $details], $value));
