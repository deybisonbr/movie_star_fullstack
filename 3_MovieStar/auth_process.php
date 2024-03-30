<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");


$message = new Message($BASE_URL);
$userDAO = new userDAO($conn, $BASE_URL);

//resgata O TIPO DO FORMULARIO
$type = filter_input(INPUT_POST, "type");

//Verificação do tipo de formulario

if ($type === "register") {
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //verificação de dados minimos

    if ($name && $lastname && $email && $password) {

        //verificar se as senhas batem
        if ($password === $confirmpassword) {

            //VERIFICAR SE O EMAIL JA EST CADASTRADO NO SISTEMA

            if ($userDAO->findByEmail($email) === false) {

                $user = new User();

                // Criação de token de senha
                $userToken = $user->generateToken();
                $finalPassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = true;


                $userDAO->create($user, $auth);
            } else {
                $message->setMessage("Email já cadastrado", "error", "back");
            }
        } else {
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }
    } else {

        //Enviar uma msg de erro, de dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
} else if ($type == "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");


    if ($email && $password) {
        //Teta authenticar usuário
        if ($userDAO->authenticateUser($email, $password)) {
            // Redireciona para perfil do usuário
            $message->setMessage("Seja bem Vindo", "success", "editprofile.php");
        } else {
            //redirecionar usuário caso não consiga authenticar
            $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
        }
    } else {
        $message->setMessage("Necessário preencher os campos para realizar o login.", "error", "back");
    }
} else {

    $message->setMessage("Informações invalidas!", "error", "index.php");
}
