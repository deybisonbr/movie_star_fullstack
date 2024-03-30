<?php



require_once("templates/header.php");
require_once("dao/MovieDAO.php");
require_once("validation/validationMovies.php");

// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

$q = filter_input(INPUT_GET, "q");;

$movies = $movieDAO->findByTitle($q);;

?>


<div id="main-container" class="container-fluid">
    <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?php echo $q ?></span></h2>
    <p class="section-description">Resultado de buscas retornados com base na sua pesquisa.</p>

    <div class="movies-container">
        <?php foreach ($movies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
    </div>

    <?php if (count($movies) == 0) : ?>
        <p class="empty-list">Não há filmes para essa busca, <a href="<?php echo $BASE_URL ?>" class="back-link">voltar.</a></p>
    <?php endif; ?>
</div>

<?php include_once("templates/footer.php") ?>