$(document).ready(function(){

    $('#dailyTrendMovie').hide();
    $('#dailyTrendTv').hide();
    $('#dailyTrendActor').hide();
    $('#known_for').hide();

    $.get(
        'requete.php',
        {
            launch : 'pageAccueil'
        },
        function (data){
            $('#result').html(data);
        },
        'html'
    );



    $('#submit').click(function(){

        $('div').remove('#dailyTrend');                                                   // Supprime la div contenant le texte 'Tendance du jour' lors du click sur Go
        var inputParam = $('#search').val().replaceAll(' ','/');

        $.post(
            'requete.php',                                                                // Fichier cible côté serveur
            {
                research : inputParam                                                     // Récupération de la valeur saisie dans le formulaire html
            },
            function(data){                                                              // Fonction callback avec les données renvoyées par jQuery en paramètre
                $('#result').html(data);
            },
            'html'                                                                       // Format des données reçues
        );
    });



    $('.nav').click(function(){

        if($(this).attr('id') == 'movie'){
            $('#dailyTrendMovie').show();
            $('#dailyTrendTv').hide();
            $('#dailyTrendAll').hide();
            $('#dailyTrendActor').hide();
            $('#known_for').hide();
        }
        if ($(this).attr('id') == 'all'){
            $('#dailyTrendTv').hide();
            $('#dailyTrendMovie').hide();
            $('#dailyTrendActor').hide();
            $('#dailyTrendAll').show();
            $('#known_for').hide();
        }
        if($(this).attr('id') == 'actor'){
            $('#dailyTrendTv').hide();
            $('#dailyTrendMovie').hide();
            $('#dailyTrendAll').hide();
            $('#dailyTrendActor').show();
            $('#known_for').hide();
        }
        if($(this).attr('id') == 'tv'){
            $('#dailyTrendTv').show();
            $('#dailyTrendActor').hide();
            $('#dailyTrendMovie').hide();
            $('#dailyTrendAll').hide();
            $('#known_for').hide();
        }

        $.get(
            'requete.php',                                                               // Fichier cible côté serveur
            {
                choose : $(this).attr('id')                                              // Récupération de la valeur saisie dans le formulaire html
            },
            function(data){                                                             // Fonction callback avec les données renvoyées par jQuery en paramètre
                $('#result').html(data);
            },
            'html'                                                                      // Format des données reçues
        );
    });


    /*
    *  Utilisation de la touche entrée (code 13) pour valider l'input de recherche
     */

    $(document).on('keypress',function(e) {

        if(e.which == 13) {
            var inputParam = $('#search').val().replaceAll(' ','/');
            $.post(
                'requete.php',                                                          // Fichier cible côté serveur
                {
                    research : inputParam                                               // Récupération de la valeur saisie dans le formulaire html
                },
                function(data){                                                         // Fonction callback avec les données renvoyées par jQuery en paramètre
                    $('#result').html(data);                                            // Affiche le résultat de la fonction callBack dans la div #result
                    $('div').remove('#dailyTrend');                                     // Supprime la div contenant le texte 'Tendance du jour' lors keypress 13
                },
                'html'                                                                  // Format des données reçues
            );
        }
    });


    $(document).on('click', '.wrapProduct', function(){

        $.post(
            'requete.php',                                                              // Fichier cible côté serveur
            {
                selectedId : $(this).attr('id'),                                       // Variable passée en param pour la selection dans requete.php
                classActor : $(this).attr('class'),
                resultId : $(this).children('p').attr('id')
            },
            function (data){                                                            // fonction callback à effectuer au retour de la requête
                $('#result').html(data);                                                // ajoute à la div #result l'html revenant de requete.php
                $('div').remove('#dailyTrend');                                         // Supprime la div contenant le texte 'Tendance du jour' lors keypress 13
                $('html,body').animate({scrollTop: 0}, 'slow');

                $('#known_for').show();
                $('#dailyTrendTv').hide();
                $('#dailyTrendActor').hide();
                $('#dailyTrendMovie').hide();
                $('#dailyTrendAll').hide();
            },
            'html'
        );
    });

});
