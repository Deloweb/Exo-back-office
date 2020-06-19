<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
    ]);
    
    // chargement de l'extension Twig_Extension_Debug
    $twig->addExtension(new \Twig\Extension\DebugExtension());

require __DIR__.'/articles-lib.php';

$greeting = 'Ici vous pouvez modifier un article';
$formData = [
    'name' => $_GET['name'],
    'description' => $_GET['description'],
    'price' => $_GET['price'], 
    'quantity' => $_GET['quantity'],
    'number' => 0,
];

$errors = [];
$messages = [];

if($_POST) {

    if (isset($_POST['name'])) {
        $formData['name'] = $_POST['name'];
    }
    if (isset($_POST['description'])) {
        $formData['description'] = $_POST['description'];
    }
    if (isset($_POST['price'])) {
        $formData['price'] = $_POST['price'];
    }
    if (isset($_POST['quantity'])) {
        $formData['quantity'] = $_POST['quantity'];
    }

    if(!isset($_POST['name']) || empty($_POST['name'])) {
    $errors['name'] = true;
    $messages['name'] = "Le champ ne doit pas être vide";
    } elseif (strlen($_POST['name']) < 2 || strlen($_POST['name']) > 100) {
        $errors['name'] = true;
        $messages['name'] = "Le champ name doit être compris entre 2 et 100 caractères";
    }

    if (isset($_POST['description'])) {
        if (
            strpos($_POST['description'], '<')
            || strpos($_POST['description'], '>')
        ) {
            $errors['description']=true;
            $messages['description']="La description contient un caractère interdit < ou >";
        }
    }

    if (!isset($_POST['price']) || empty($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Le champ ne doit pas être vide";
    } elseif (!is_numeric($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Ce champ doit contenir un nombre uniquement";
    }

    if (!isset($_POST['quantity']) || empty($_POST['quantity']) ) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Ce champ ne doit pas être vide";
    } elseif ((!is_int(0 + $_POST['quantity']) || !is_numeric($_POST['quantity']))) {
        $errors['quantity'] = true;
        $messages['quantity'] = "La quantité doit être un nombre entier";
    } elseif ($_POST['quantity'] < 0 ) {
        $errors['quantity'] = true;
        $messages['quantity'] = "La quantité doit être supérieur ou égale à 0";
    }

    if (!$errors) {
        $url = 'articles.php';
    header("Location: {$url}", true, 302);
    exit();
    }
}



// affichage du rendu d'un template
echo $twig->render('article-edit.html.twig', [
    // transmission de données au template
    'greeting' => $greeting,
    'articles' => $articles,
    "formData" => $formData,
    'errors' => $errors,
    'messages' => $messages,
    'get' => $_GET,
]);