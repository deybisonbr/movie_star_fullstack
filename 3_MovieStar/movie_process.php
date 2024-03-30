<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/MovieDAO.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

//resgata O TIPO DO FORMULARIO
$type = filter_input(INPUT_POST, "type");

//Resgata Dados do usuário
$userData = $userDAO->verifyToken();



if ($type === "create") {

    //Receber on dados do imputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    //Validação minima de dados

    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->description = $description;
        $movie->length = $length;
        $movie->users_id = $userData->id;


        //upload de imagem do filme

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypesAllowed = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            //Checando tipo da imagem
            if (in_array($image["type"], $imageTypesAllowed)) {

                //checa se imagem e JPG
                if (in_array($image["type"], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }


                //nome imagem
                $imageName = $movie->imageGenerateName();

                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                $movie->image = $imageName;
            } else {

                $message->setMessage("Tipo inválido de imagem, insira PNG ou JPG", "error", "back");
                exit;
            }
        }

        $movieDao->create($movie);
    } else {
        $message->setMessage("Campos obrigatórios: título, descrição e categoria!", "error", "back");
    }
} else if ($type === "delete") {

    //Receber dados do formulario
    $id = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);

    if (!empty($movie)) {

        //verificar se o filme e do usuario
        if ($movie->users_id === $userData->id) {

            $movieDao->destroy($movie->id);
        } else {
            $message->setMessage("Informação invalida", "error", "index.php");
        }
    } else {
        $message->setMessage("Informação incorreta", "error", "index.php");
    }
} else if ($type === "update") {

    //Receber on dados do imputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    //verificar se encontrou o filme

    if ($movieData) {

        if ($movieData->users_id === $userData->id) {
            //verificação minima de dados
            if (!empty($title) && !empty($description) && !empty($category)) {
                //Edição do filme
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;

                if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                    $image = $_FILES["image"];
                    $imageTypesAllowed = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
                    $jpgArray = ["image/jpeg", "image/jpg"];
        
                    //Checando tipo da imagem
                    if (in_array($image["type"], $imageTypesAllowed)) {
        
                        //checa se imagem e JPG
                        if (in_array($image["type"], $jpgArray)) {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }
        
        
                        //nome imagem
                        $imageName = $movieData->imageGenerateName();
        
                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
        
                        $movieData->image = $imageName;
                    } else {
        
                        $message->setMessage("Tipo inválido de imagem, insira PNG ou JPG", "error", "back");
                        exit;
                    }
                }
                
                $movieDao->update($movieData);
            } else {
                $message->setMessage("Campos obrigatórios: título, descrição e categoria!", "error", "back");
            }
        } else {

            $message->setMessage("Informação invalida", "error", "index.php");
        }
    } else {
        $message->setMessage("Informação invalida ", "error", "index.php");
    }
} else {

    $message->setMessage("Informações invalidas!", "error", "index.php");
}
