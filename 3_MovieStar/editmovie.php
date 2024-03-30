<?php
require_once("templates/header.php");


//Verifica se o usuario está logado
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$user = new User();

$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(true);


// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

$categoriesMovies = $movieDAO->getAllCategories();

$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
    $message->setMessage("O filme não foi encontrado", "error", "index.php");
} else {
    $movie = $movieDAO->findById($id);

    //Verificar se o filme existe

    if (!$movie) {
        $message->setMessage("O filme não foi encontrado", "error", "index.php");
    }
}

//checar se o filme possui imagem 
if ($movie->image == "") {
    $movie->image = "movie_cover.jpg";
}

// Checar se o filme é do usuário

$userOnceMovie = false;

if (!empty($userData)) {
    if ($userData->id === $movie->users_id) {
        $userOnceMovie = true;
    }
}

if(!empty($userData) && !$userOnceMovie){
    $message->setMessage("Informação inválida", "error", "index.php");
}

?>


<div id="main-container" class="container-fluid">
    <di class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?php echo $movie->title; ?></h1>
                <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
                <form action="<?php echo $BASE_URL ?>movie_process.php" id="edit-movie-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?php echo $movie->id; ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Digite o titulo do seu filme" value="<?php echo $movie->title; ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Imagem:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>

                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme" value="<?php echo $movie->length; ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" class="form-control">

                            <option value="">Selecione</option>
                            <?php foreach ($categoriesMovies as $category) : ?>
                                <option value="<?php echo $category ?>" <?php echo $movie->category === $category ? "selected" : "" ?>><?php echo $category ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="text" class="form-control" id="trailer" name="trailer" placeholder="insira o link do trailer" value="<?php echo $movie->trailer; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" rows="5" class="form-control" placeholder="Descreva o filme"><?php echo $movie->description; ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar Filme">
                </form>
            </div>
            <div class="col-md-3">
                <div class="movie-image-container" style="background-image: url('<?php echo $BASE_URL; ?>img/movies/<?php echo $movie->image; ?>');"></div>
            </div>
        </div>
    </di>

</div>

<?php include_once("templates/footer.php") ?>