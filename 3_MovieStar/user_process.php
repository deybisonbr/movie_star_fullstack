<?php


require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$user = new User();
$message = new Message($BASE_URL);
$userDAO = new userDAO($conn, $BASE_URL);

//resgata O TIPO DO FORMULARIO
$type = filter_input(INPUT_POST, "type");


//Atualizar usuário

if ($type === "update") {
    //Resgata Dados do usuário
    $userData = $userDAO->verifyToken();

    //recener dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    // Preencher os dados do usuário

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    //upload da imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypesAllowed = ["image/jpeg", "image/jpg", "image/png", "image/gif"];

        // Verificar se é uma imagem válida
        if (in_array($image["type"], $imageTypesAllowed)) {

            // Tentar criar a imagem a partir do arquivo temporário
            if ($image["type"] === "image/gif") {
                $imageFile = @imagecreatefromgif($image["tmp_name"]);
                $imageName = $user->imageGenerateName(".gif");
            } else {
                $imageFile = @imagecreatefromjpeg($image["tmp_name"]);
                $imageName = $user->imageGenerateName(".jpg");
            }

            if ($imageFile !== false) {

                // Salvar a imagem no diretório
                if ($image["type"] === "image/gif") {
                    if (imagegif($imageFile, "./img/users/" . $imageName)) {
                        $userData->image = $imageName;
                    } else {
                        $message->setMessage("Erro ao salvar a imagem", "error", "back");
                    }
                } else {
                    if (imagejpeg($imageFile, "./img/users/" . $imageName, 100)) {
                        $userData->image = $imageName;
                    } else {
                        $message->setMessage("Erro ao salvar a imagem", "error", "back");
                    }
                }
            } else {
                $message->setMessage("Erro ao processar a imagem", "error", "back");
            }
        } else {
            $message->setMessage("Tipo inválido de imagem, insira PNG, JPG ou GIF", "error", "back");
            exit;
        }
    }

    $userDAO->update($userData);
} else if ($type === "changepassword") {


    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //Regatar usuario
    $userData = $userDAO->verifyToken();

    $id = $userData->id;

    if ($password == $confirmpassword) {
        //Criar um novo objetode usuario
        $user = new User(); 

        $finalPassword = $user->generatePassword($password);

        $user->password = $finalPassword;
        $user->id = $id;


        $userDAO->changePassword($user);




    } else {
        $message->setMessage("As senhas não são iguais", "error", "back");
    }
} else {
    $message->setMessage("Informações invalidas!", "error", "index.php");
}
