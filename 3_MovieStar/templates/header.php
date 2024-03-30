<?php
require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");


$message = new Message($BASE_URL);

$flashMessage = $message->getMessage();

if (!empty($flashMessage["msg"])) {
    $message->clearMessage();
}


$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(false);

// print_r($userData);
// exit;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="shortcut icon" href="<?php echo $BASE_URL ?>img/moviestar.ico" type="image/x-icon">

    <!--FontAwesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Css Bootstrap -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.css" integrity="sha512-VcyUgkobcyhqQl74HS1TcTMnLEfdfX6BbjhH8ZBjFU9YTwHwtoRtWSGzhpDVEJqtMlvLM2z3JIixUOu63PNCYQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Css do Projeto -->
    <link rel="stylesheet" href="<?php echo $BASE_URL ?>css/styles.css">
</head>

<body>
    <header>
        <nav id="main-navbar" class="navbar navbar-expand-lg">
            <a href="<?php echo $BASE_URL ?>" class="navbar-brand">
                <img src="<?php echo $BASE_URL ?>img/logo.svg" alt="MovieStar" id="logo">
                <span id="moviestar-title">MovieStar</span>
            </a>

            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <form action="<?php echo $BASE_URL ?>search.php" method="GET" id="search-form" class="form-inline my-2 my-lg-0">
                <input type="search" name="q" id="search" class="form-control mr-sm-2" placeholder="Buscar Filmes" aria-label="search">
                <button type="submit" class="btn my-2 my-sm-0">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <?php if ($userData) : ?>
                        <li class="nav-item">
                            <a href="<?php echo $BASE_URL ?>newmovie.php" class="nav-link"><i class="far fa-plus-square"></i> Incluir filme</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $BASE_URL ?>editprofile.php" class="nav-link bold">
                                <?php echo $userData->name; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $BASE_URL ?>logout.php" class="nav-link">Sair</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="<?php echo $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <?php if (!empty($flashMessage["msg"])) : ?>
        <div class="msg-container">
            <p class="msg <?php echo $flashMessage["type"] ?>"><?php echo $flashMessage["msg"] ?></p>
        </div>
    <?php endif; ?>