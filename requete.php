<?php

/*
 *
 * INFOS !
 *
 * requete.php réunit toutes les requêtes envoyées à l'API, ainsi que les retours.
 * Le lien avec le fichier ajax.js permet de retourner en ajax les résultats de chaque commande.
 *
 *
 * A FAIRE
 *
 * Rendre la barre de nav responsive
 *
 * Ajouter une section avis pour laisser des avis sur un item.
 * Afficher tous les avis laisser pour un item sur sa page.
 *
 *
 */



/*
 * Affichage des tendances du jour à l'arrivée sur le site Web. Tendance Movies et TV confondues.
 */

if (isset($_GET['launch'])){

    // récupère les tendances du jour via l'API
    $requestApi = 'https://api.themoviedb.org/3/trending/all/day?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $response = file_get_contents($requestApi);
    $responseJson = json_decode($response, true);

    // Affiche toutes les tendances à l'écran
    for($i=0; $i<count($responseJson['results']);$i++){
        if ($responseJson['results'][$i]['poster_path'] != null){
            $idMovie = $responseJson['results'][$i]['id'];
            echo '<div class="wrapProduct allTrend" id="'.$idMovie.'">';
            echo '<img class="poster allTrend" src="http://image.tmdb.org/t/p/w500'.($responseJson['results'][$i]['poster_path']).'" alt="test">';
            echo '<p class="movieTitle allTrend">'.$responseJson['results'][$i]['original_title'].'</p>';
            echo '<p class="movieTitle allTrend">'.$responseJson['results'][$i]['original_name'].'</p>';
            echo '</div>';
        }
    }
}

/*
 * Condition sur le clic d'une catégorie du menu. Afin d'afficher respectivement movies, TV ou Actors
 * Attention à la syntaxe, le nom d'un TV et d'un movie n'est pas exprimé sous le même attribut. original_title ou original_name.
 *
 *
 * UPDATE 02/10/2020
 * Séparation des quatre catégories pour plus de clarté. Vérification avec le $_GET pour choisir une catégorie.
 *
 * */

if (isset($_GET['choose'])) {

    // requête tendance avec paramètre de catégorie $_GET renvoie movie, actor ou Tv
    $requestApi = 'https://api.themoviedb.org/3/trending/' . $_GET['choose'] . '/day?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $response = file_get_contents($requestApi);
    $responseJson = json_decode($response, true);

    // Requête tendance pour les acteurs
    $requestApiActor = 'https://api.themoviedb.org/3/person/popular?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $responseActor = file_get_contents($requestApiActor);
    $responseJsonActor = json_decode($responseActor, true);


    // On regarde sur quelle catégorie à cliqué le user et on renvoie en conséquence
    if ($_GET['choose'] == 'actor') {

        for ($i = 0; $i < count($responseJsonActor['results']); $i++) {
            if ($responseJsonActor['results'][$i]['profile_path'] != null) {
                $idActor = $responseJsonActor['results'][$i]['id'];
                echo '<div class="wrapProduct actors" id="' . $idActor . '">';
                echo '<img class="poster actors" src="http://image.tmdb.org/t/p/w500' . ($responseJsonActor['results'][$i]['profile_path']) . '" alt="test">';
                echo '<p class="movieTitle actors" id="'.$i.'">' . $responseJsonActor['results'][$i]['name'] . '</p>';
                echo '</div>';
            }
        }
    }
    if ($_GET['choose'] == 'all') {

        for($i=0; $i<count($responseJson['results']);$i++){
            if ($responseJson['results'][$i]['poster_path'] != null){
                $idMovie = $responseJson['results'][$i]['id'];
                echo '<div class="wrapProduct allTrend" id="'.$idMovie.'">';
                echo '<img class="poster allTrend" src="http://image.tmdb.org/t/p/w500'.($responseJson['results'][$i]['poster_path']).'" alt="test">';
                echo '<p class="movieTitle allTrend">'.$responseJson['results'][$i]['original_title'].'</p>';
                echo '<p class="movieTitle allTrend">'.$responseJson['results'][$i]['original_name'].'</p>';
                echo '</div>';
            }
        }
    }
    if ($_GET['choose'] == 'tv') {

        for ($i = 0; $i < count($responseJson['results']); $i++) {
            if ($responseJson['results'][$i]['poster_path'] != null) {
                $idTv = $responseJson['results'][$i]['id'];
                echo '<div class="wrapProduct tv" id="' . $idTv . '">';
                echo '<img class="poster tv" src="http://image.tmdb.org/t/p/w500' . ($responseJson['results'][$i]['poster_path']) . '" alt="test">';
                echo '<p class="movieTitle tv">' . $responseJson['results'][$i]['original_name'] . '</p>';
                echo '</div>';
            }
        }
    }
    if ($_GET['choose'] == 'movie') {

        for ($i = 0; $i < count($responseJson['results']); $i++) {
            if ($responseJson['results'][$i]['poster_path'] != null) {
                $idMovie = $responseJson['results'][$i]['id'];
                echo '<div class="wrapProduct movie" id="' . $idMovie . '">';
                echo '<img class="poster movie" src="http://image.tmdb.org/t/p/w500' . ($responseJson['results'][$i]['poster_path']) . '" alt="test">';
                echo '<p class="movieTitle movie">' . $responseJson['results'][$i]['original_title'] . '</p>';
                echo '</div>';
            }
        }
    }
}

/*
 * Utilisation de la barre de recherche par mots clés.
 * Effectue une recherche sur les films et les séries et retourne les 20 premiers résultats
 * Seulement si un poster est disponible. Sinon, l'item en question ne s'affiche pas et on passe au suivant
 */

if (isset($_POST['research'])){

    // requêtes pour les films en recherche
    $requestApi = 'https://api.themoviedb.org/3/search/multi?api_key=0d34be9b15e78c94a6d41f1872e62f86&query="'.$_POST['research'].'"';
    $response = file_get_contents($requestApi);
    $responseJson = json_decode($response, true);

    for($i=0; $i<count($responseJson['results']);$i++){
        if ($responseJson['results'][$i]['poster_path'] != null || $responseJson['results'][$i]['profile_path']  != null){
            $idItem = $responseJson['results'][$i]['id'];
            echo '<div class="wrapProduct" id="'.$idItem.'">';
            if($responseJson['results'][$i]['poster_path'] == null){ // On vérifie si l'affiche est nulle pour afficher le celle correspondante
                echo '<img class="poster actors" src="http://image.tmdb.org/t/p/w500'.($responseJson['results'][$i]['profile_path']).'" alt="test">';
            }
            else{
                echo '<img class="poster" src="http://image.tmdb.org/t/p/w500'.($responseJson['results'][$i]['poster_path']).'" alt="test">';
            }
            echo '<p class="movieTitle">'.$responseJson['results'][$i]['original_title'].'</p>';
            echo '<p class="movieTitle">'.$responseJson['results'][$i]['name'].'</p>';
            echo '</div>';
        }
    }
}



/*
 * Condition sur le click d'un ITEM.
 * Lancement des requêtes pour les film, les séries et les acteurs
 *
 * Comme les items n'ont pas un ID unique, il faut vérifier si l'ID est celui d'un film ou d'une série.
 * Pour cela on vérifie si il y a des épisodes dans l'item selected, si oui, c'est une série.
 * De même pour la vérification des acteurs, on vérifie si la class contient le mot "actors"
 *
 * */



if (isset($_POST['selectedId'])){

    // Lancement des requêtes pour les films
    $requestApi = 'https://api.themoviedb.org/3/movie/'.$_POST['selectedId'].'?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $response = file_get_contents($requestApi);
    $responseJson = json_decode($response, true);

    // Lancement des requêtes pour les séries
    $requestApiTv = 'https://api.themoviedb.org/3/tv/'.$_POST['selectedId'].'?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $responseTv = file_get_contents($requestApiTv);
    $responseJsonTv = json_decode($responseTv, true);

    // Lancement des requêtes pour les acteurs populaires
    $requestApiActor = 'https://api.themoviedb.org/3/person/popular?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $responseActor = file_get_contents($requestApiActor);
    $responseJsonActor = json_decode($responseActor, true);

    // Lancement des requêtes pour les acteurs selectionnés dans les tendances, afin d'afficher leurs informations
    $requestApiSelectedActor = 'https://api.themoviedb.org/3/person/'.$_POST['selectedId'].'/?api_key=0d34be9b15e78c94a6d41f1872e62f86';
    $responseSelectedActor = file_get_contents($requestApiSelectedActor);
    $responseJsonSelectedActor = json_decode($responseSelectedActor, true);



    // affichage des infos movie
    if($responseJsonTv['episode_run_time'] == null && !isset($_POST['resultId'])){
        echo '<div id="chosenProduct">';
        echo '<p class="movieItem" id="chosenTitle">'.$responseJson['original_title'].'</p>';
        echo '<p class="movieItem" id="average_id"> <img id="star_id" src="https://perekchira.com/wp-content/uploads/2018/03/Etoile.png" alt="star">'.$responseJson['vote_average'].' / 10</p>';
        echo '<p class="movieItem" id="chosenDesc"><span style="font-style: italic; font-weight: bold; font-size: 20px">Synopsis : <br /><br /></span>'.$responseJson['overview'].'</p>';
        echo '<img class="movieItem" id="poster_path" src="http://image.tmdb.org/t/p/w500'.$responseJson['poster_path'].'" alt="image">';
        echo '</div>';
    }

    // affichage des infos actors
    if (isset($_POST['resultId'])){

        $content  = '<div style="display: flex; flex-wrap: wrap; justify-content: space-around; padding-bottom: 30px;margin-bottom: 30px; margin-top: 30px; border-bottom: 8px solid">';
        $content .= '<div id="divLeft">';
        $content .= '<img class="poster" src="http://image.tmdb.org/t/p/w500'.$responseJsonSelectedActor['profile_path'].'" alt="test">';
        $content .= '</div>';
        $content .= '<div id="divRight" style="width: 50%">';
        $content .= '<table style="margin: 20px 20px; border: 3px solid white; padding: 15px; font-size: 20px"><tr class="actor_content"><td style="width: 150px">Name : </td><td>' . $responseJsonSelectedActor['name'] . '</td></tr><tr class="actor_content"><td style="width: 150px">Birth date : </td><td>'.$responseJsonSelectedActor['birthday'].'</td></tr><tr class="actor_content"><td style="width: 150px">Biography : </td><td>'.$responseJsonSelectedActor['biography'].'</td></tr></table>';
        $content .= '</div>';
        $content .= '</div>';


        // Connu pour avoir jouer dans les films suivants
        for($i = 0 ; $i < count($responseJsonActor['results'][$_POST['resultId']]['known_for']) ; $i++){
            if ($responseJsonActor['results'][$_POST['resultId']]['known_for'][$i]['poster_path'] != null){
                $idMovie = $responseJsonActor['results'][$_POST['resultId']]['known_for'][$i]['id'];
                $content .= '<div class="wrapProduct" id="'.$idMovie.'">';
                $content .= '<img class="poster" src="http://image.tmdb.org/t/p/w500'.($responseJsonActor['results'][$_POST['resultId']]['known_for'][$i]['poster_path']).'" alt="test">';
                $content .= '<p class="movieTitle">'.$responseJsonActor['results'][$_POST['resultId']]['known_for'][$i]['original_title'].'</p>';
                $content .= '<p class="movieTitle">'.$responseJsonActor['results'][$_POST['resultId']]['known_for'][$i]['original_name'].'</p>';
                $content .= '</div>';
            }
        }
        echo $content;
    }
    // Affichage des infos séries
    if($_POST['resultId'] == null && $responseJsonTv['episode_run_time'] != null ){
        echo '<div id="chosenProduct">';
        echo '<p class="TvItem" id="chosenTitle">'.$responseJsonTv['original_name'].'</p>';
        echo '<p class="TvItem" id="average_id"> <img id="star_id" src="https://perekchira.com/wp-content/uploads/2018/03/Etoile.png" alt="star">'.$responseJsonTv['vote_average'].' / 10</p>';
        echo '<p class="TvItem" id="chosenDesc"><span style="font-style: italic; font-weight: bold; font-size: 20px">Synopsis : <br /><br /></span>'.$responseJsonTv['overview'].'</p>';
        echo '<img class="TvItem" id="poster_path" src="http://image.tmdb.org/t/p/w500'.$responseJsonTv['poster_path'].'" alt="image">';
        echo '</div>';
    }
}

if(isset ($_GET['pageSelected'])){

    // récupère les tendances du jour via l'API
    $requestApi = 'https://api.themoviedb.org/3/trending/all/day?api_key=0d34be9b15e78c94a6d41f1872e62f86&page='.$_GET['pageSelected'].'';
    $response = file_get_contents($requestApi);
    $responseJson = json_decode($response, true);

    // requête tendance avec paramètre de catégorie $_GET renvoie movie, actor ou Tv
    $requestApiMovieTv = 'https://api.themoviedb.org/3/trending/' . $_GET['categorySelected'] . '/day?api_key=0d34be9b15e78c94a6d41f1872e62f86&page='.$_GET['pageSelected'].'';
    $responseMovieTv = file_get_contents($requestApiMovieTv);
    $responseJsonMovieTv = json_decode($responseMovieTv, true);

    // Requête tendance pour les acteurs
    $requestApiActor = 'https://api.themoviedb.org/3/person/popular?api_key=0d34be9b15e78c94a6d41f1872e62f86&page='.$_GET['pageSelected'].'';
    $responseActor = file_get_contents($requestApiActor);
    $responseJsonActor = json_decode($responseActor, true);




    if($_GET[categorySelected] == 'movie'){
        // Affiche toutes les tendances à l'écran
        for($i=0; $i<count($responseJsonMovieTv['results']);$i++){
            if ($responseJsonMovieTv['results'][$i]['poster_path'] != null){
                $idMovie = $responseJsonMovieTv['results'][$i]['id'];
                echo '<!--nouvelle page-->';
                echo '<div class="wrapProduct movie" id="'.$idMovie.'">';
                echo '<img class="poster  movie" src="http://image.tmdb.org/t/p/w500'.($responseJsonMovieTv['results'][$i]['poster_path']).'" alt="test">';
                echo '<p class="movieTitle  movie">'.$responseJsonMovieTv['results'][$i]['original_title'].'</p>';
                echo '</div>';
            }
        }

    }
    if($_GET[categorySelected] == 'tv'){
        // Affiche toutes les tendances à l'écran
        for($i=0; $i<count($responseJsonMovieTv['results']);$i++){
            if ($responseJsonMovieTv['results'][$i]['poster_path'] != null){
                $idTv = $responseJsonMovieTv['results'][$i]['id'];
                echo '<div class="wrapProduct tv" id="'.$idTv.'">';
                echo '<img class="poster tv" src="http://image.tmdb.org/t/p/w500'.($responseJsonMovieTv['results'][$i]['poster_path']).'" alt="test">';
                echo '<p class="movieTitle tv">'.$responseJsonMovieTv['results'][$i]['original_name'].'</p>';
                echo '</div>';
            }
        }

    }
    if($_GET[categorySelected] == 'actor'){
        // Affiche toutes les tendances à l'écran
        for ($i = 0; $i < count($responseJsonActor['results']); $i++) {
            if ($responseJsonActor['results'][$i]['profile_path'] != null) {
                $idActor = $responseJsonActor['results'][$i]['id'];
                echo '<div class="wrapProduct actors" id="' . $idActor . '">';
                echo '<img class="poster actors" src="http://image.tmdb.org/t/p/w500' . ($responseJsonActor['results'][$i]['profile_path']) . '" alt="test">';
                echo '<p class="movieTitle actors" id="'.$i.'">' . $responseJsonActor['results'][$i]['name'] . '</p>';
                echo '</div>';
            }
        }

    }


}

