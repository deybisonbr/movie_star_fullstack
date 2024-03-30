<?php



require_once("templates/header.php");
require_once("dao/MovieDAO.php");
require_once("validation/validationMovies.php");

// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDAO->getLatesMovies();

$actionMovies = $movieDAO->getMoviesByCategory("Ação");

$comedyMovies = $movieDAO->getMoviesByCategory("Comedia");

$dramaMovies = $movieDAO->getMoviesByCategory("Drama");
$romanceMovies = $movieDAO->getMoviesByCategory("Romance");

$validationMovies = new ValidationMovies();


?>


<div id="main-container" class="container-fluid">
    <h2 class="section-title">Filmes Novos</h2>
    <p class="section-description">Veja as criticas do ultimos filmes adicionados no MovieStar</p>

    <div class="movies-container">
        <?php foreach ($latestMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <p class="empty-list"><?php echo $validationMovies->empty($latestMovies, "novos"); ?></p>
    </div>

    <h2 class="section-title">Ação</h2>
    <p class="section-description">Veja os melhores filmes de ação</p>

    <div class="movies-container">
        <?php foreach ($actionMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <p class="empty-list"><?php echo $validationMovies->empty($actionMovies, "ação"); ?></p>


    </div>

    <h2 class="section-title">Comedia</h2>
    <p class="section-description">Veja os melhores filmes de comedia</p>

    <div class="movies-container">
        <?php foreach ($comedyMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <p class="empty-list"><?php echo $validationMovies->empty($comedyMovies, "comedia"); ?></p>

    </div>


    <h2 class="section-title">Drama</h2>
    <p class="section-description">Veja os melhores filmes de drama</p>

    <div class="movies-container">
        <?php foreach ($dramaMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <p class="empty-list"><?php echo $validationMovies->empty($dramaMovies, "drama"); ?></p>

    </div>

    <h2 class="section-title">Romance</h2>
    <p class="section-description">Veja os melhores filmes de romance</p>

    <div class="movies-container">
        <?php foreach ($romanceMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <p class="empty-list"><?php echo $validationMovies->empty($romanceMovies, "romance"); ?></p>

    </div>

</div>

<?php include_once("templates/footer.php") ?>