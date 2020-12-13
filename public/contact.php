<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new Environment($loader);

// traitement des données
$formData = [
    'email' => '@popschool.fr',
    'sujet' => '',
    'message' => '',
];



$errors = [];
if ($_POST) {
    foreach ($formData as $key => $value) {
        if (isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }
    if (empty($_POST['email'])){
        $errors['email'] = 'Merci de renseigner ce champ';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'merci de renseigner un email valide';
        
    //- email expéditeur : input email, max 190 caractères inclus
    } elseif (strlen($_POST['email']) >= 190){
        $errors['email'] = "mettre un longueur valide";
    
    } 

    if (empty($_POST['sujet'])){
        $errors['sujet'] = 'Donnez nous votre sujet';
    //min 3 max 190 caractères inclus, blocage du code html
    } elseif (strlen($_POST['sujet']) < 3 || strlen($_POST['sujet']) > 190){
        $errors['sujet'] = 'Entrez un sujet de la bonne longueur';
    } elseif (preg_match("/<[^>]*>/", $_POST['message']) !== 0) {
        $errors['message'] = 'merci de ne pas mettre de code html';
    }

    if (empty($_POST['message'])){
        $errors['message'] = 'Entrez votre message';
    //min 3 caractères max 1000 caractères inclus, blocage du code html
    } elseif (strlen($_POST['message']) < 3 || strlen($_POST['message']) > 1001){
        $errors['message'] = 'Entrez un sujet de la bonne longueur'; 
    } elseif (preg_match("/<[^>]*>/", $_POST['message']) !== 0) {
        $errors['message'] = 'merci de ne pas mettre de code html';
    }
   dump($_POST); 
}
// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'formData' => $formData,
]);


