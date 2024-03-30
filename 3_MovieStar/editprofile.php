<?php
require_once("templates/header.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");

$user = new User();

$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(true);

$fullName = $user->getFullName($userData);


if ($userData->image == "") {
    $userData->image = "user.png";
}

?>


<div id="main-container" class="container-fluid edit-profile-page">
    <div class="col-md-12">
        <form action="<?php echo $BASE_URL ?>user_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="update">
            <div class="row">
                <div class="col-md-4">
                    <h1><?php echo $fullName ?></h1>
                    <p class="page-description">Altere seus dados no formulario abaixo:</p>
                    <div class="form-group edit-profile">
                        <label for="name">Nome:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Digite seu nome" value="<?php echo $userData->name; ?>">
                    </div>
                    <div class="form-group edit-profile">
                        <label for="lastename">Sobrenome:</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Digite seu sobrenome" class="form-control" value="<?php echo $userData->lastname; ?>">
                    </div>
                    <div class="form-group edit-profile">
                        <label for="email">Email:</label>
                        <input type="email" readonly name="email" id="email" class="form-control disabled" placeholder="Digite seu Email" value="<?php echo $userData->email; ?>">
                    </div>
                    <input type="submit" class="btn card-btn" value="Alterar">
                </div>
                <div class="col-md-4">
                    <div id="profile-image-container" style="background-image: url('<?php echo $BASE_URL ?>img/users/<?php echo $userData->image; ?>');"></div>
                    <div class="form-group edit-profile">
                        <label for="image">Foto:</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>
                    <div class="form-group edit-profile">
                        <label for="bio">Sobre você:</label>
                        <textarea class="form-control" type="text" name="bio" id="bio" rows="5" placeholder="Conte quem você é, o que faz e onde trabalha..."><?php echo $userData->bio; ?></textarea>
                    </div>

                </div>
            </div>
        </form>
        <div class="row" id="change-password-container edit-profile-page">
            <div class="col-md-4">
                <h2>Alterar Senha</h2>
                <p class="page-description">
                    Digite a nova senha e confirme, para alterar sua senha:
                <form action="<?php echo $BASE_URL ?>user_process.php" method="POST">
                    <input type="hidden" name="type" value="changepassword">
                    <div class="form-group edit-profile">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Digite sua nova senha">
                    </div>
                    <div class="form-group edit-profile">
                        <label for="confirmpassword">Confirmação Senha:</label>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" placeholder="Digite sua nova senha">
                    </div>
                    <input type="submit" class="btn card-btn" value="Alterar Senha">
                </form>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include_once("templates/footer.php") ?>