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

?>


<div id="main-container" class="container-fluid">

    <div class="offset-md-4 col-md-4 new-movie-container">
        <h1 class="page-title">Adicionar Filme</h1>
        <p class="page-description">Adicione sua critica e compartilhe com o mundo</p>
        <form action="<?php echo $BASE_URL; ?>movie_process.php" method="POST" id="add-movie-form" enctype="multipart/form-data">
            <input type="hidden" name="type" value="create">
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Digite o titulo do seu filme">
            </div>

            <div class="form-group">
                <label for="image">Imagem:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>

            <div class="form-group">
                <label for="length">Duração:</label>
                <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
            </div>

            <div class="form-group">
                <label for="category">Categoria:</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Selecione</option>
                    <?php foreach($categoriesMovies as $category): ?>
                    <option value="<?php echo $category ?>"><?php echo $category ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="trailer">Trailer:</label>
                <input type="text" class="form-control" id="trailer" name="trailer" placeholder="insira o link do trailer">
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" rows="5" class="form-control" placeholder="Descreva o filme"></textarea>
            </div>
            <input type="submit" class="btn card-btn" value="Adicionar Filme">
        </form>
    </div>

</div>

<?php include_once("templates/footer.php") ?>