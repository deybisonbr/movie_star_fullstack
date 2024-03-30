<?php
class ValidationMovies {


    public function empty($movies, $type){
        if(count($movies) === 0){
            return "Ainda não há filmes de $type cadastrados!";
        }
    
    }


}
