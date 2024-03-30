<?php
require_once("templates/header.php");


//Verifica se o usuario está logado
require_once("dao/MovieDAO.php");
require_once("dao/ReviewDAO.php");

//Pegar id do filme
$id = filter_input(INPUT_GET, "id");

$movie;

$movieDAO = new MovieDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

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




$userOnceMovie = false;

if (!empty($userData)) {
    if ($userData->id === $movie->users_id) {
        $userOnceMovie = true;
    }

    // Checar se o filme é do usuário
    $alreadyReviewd = $reviewDAO->hasAlreadyReviewd($id, $userData->id);
}


//Resgatar as reviews do filme

$movieReviews = $reviewDAO->getMovieReview($id);
?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 movie-container">
            <h1 class="page-title"><?php echo $movie->title; ?></h1>
            <p class="movie-datails">
                <span>Duração: <?php echo $movie->length; ?></span>
                <span class="pipe"></span>
                <span class="category-film"><?php echo $movie->category; ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> <?php echo $movie->rating; ?></span>
            </p>

            <iframe src="<?php echo $movie->trailer; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encryted-media; gyroscope; picture-is-picture" allowfullscreen width="560" height="315"></iframe>
            <p><?php echo $movie->description; ?></p>
        </div>
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url('<?php echo $BASE_URL; ?>img/movies/<?php echo $movie->image; ?>');"></div>
        </div>
        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações: </h3>
            <!-- Verifica se habilita para o usuario ou não -->
            <?php if (!empty($userData) && !$userOnceMovie && !$alreadyReviewd) : ?>
                <div class="col-md-12" id="review-form-container">
                    <h4>Enviar sua avaliação:</h4>
                    <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme</p>
                    <form action="<?php echo $BASE_URL; ?>review_process.php" id="review-form" method="POST">
                        <input type="hidden" name="type" value="create">
                        <input type="hidden" name="movies_id" value="<?php echo $movie->id; ?>">
                        <div class="form-group">
                            <label for="rating">Nota do filme: </label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="">Selecione</option>
                                <option value="10">10</option>
                                <option value="9">9</option>
                                <option value="8">8</option>
                                <option value="7">7</option>
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Enviar comentário">
                    </form>
                </div>
            <?php endif; ?>

            <!--Comentarios-->
            <?php foreach ($movieReviews as $review) : ?>
                <?php require("templates/user_review.php") ?>
            <?php endforeach ?>
            <?php if (count($movieReviews) == 0) : ?>
                <p class="empty-list">Não há comentarios para este filme ainda...</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once("templates/footer.php") ?>