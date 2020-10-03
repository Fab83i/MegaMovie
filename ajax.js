$(document).ready(function(){

    $('#dailyTrendMovie').hide();
    $('#dailyTrendTv').hide();
    $('#dailyTrendActor').hide();
    $('#reseachResult').hide();
    $('#spinner').hide();

    var page = 2;
    var lastHeight;
    var category;
    var clickItem =true;



    // Detecte le bas de page et affiche plus d'élèments
    $(window).scroll(function(){
        if( ($(window).scrollTop() + $(window).height()) > ($(document).height() - 500) && clickItem){
            if ($(document).height() != lastHeight){
                lastHeight = $(document).height();
                $.get(
                    'requete.php',
                    {
                        pageSelected : page,
                        categorySelected : category
                    },
                    function(data){
                        $('#result').append(data);
                    },
                    'html'
                );
                page++;
            }
        }
    });



    // récupère les tendances du jour au lancement du site
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


    /*
    *  click sur le bouton 'Go' pour éxécuter la requête de recherche
    */
    $('#submit').click(function(){
        $('#spinner').show();

        $('div').remove('#dailyTrend');     // Supprime la div contenant le texte 'Tendance du jour' lors du click sur Go
        var researchParam = $('#search').val();
        var inputParam = $('#search').val().replaceAll(' ','/');

        $.post(
            'requete.php',
            {
                research : inputParam
            },
            function(data){
                $('#result').html(data);

                $('#reseachResult').show();
                $('#dailyTrendTv').hide();
                $('#dailyTrendActor').hide();
                $('#dailyTrendMovie').hide();
                $('#dailyTrendAll').hide();
                $('#spinner').hide();
                document.getElementById('researchEnter').innerHTML= researchParam;
            },
            'html'
        );
    });
    /*
    *  Utilisation de la touche entrée (code 13) pour valider l'input de recherche
    */
    $(document).on('keypress',function(e) {


        $('#spinner').show();
        if(e.which == 13) {
            var researchParam = $('#search').val();
            var inputParam = $('#search').val().replaceAll(' ','/');

            $.post(
                'requete.php',  // Fichier cible côté serveur
                {
                    research : inputParam   // Récupération de la valeur saisie dans le formulaire html
                },
                function(data){      // Fonction callback avec les données renvoyées par jQuery en paramètre
                    $('#result').html(data);     // Affiche le résultat de la fonction callBack dans la div #result
                    $('div').remove('#dailyTrend');  // Supprime la div contenant le texte 'Tendance du jour' lors keypress 13
                    $('#dailyTrendTv').hide();
                    $('#dailyTrendActor').hide();
                    $('#dailyTrendMovie').hide();
                    $('#dailyTrendAll').hide();
                    $('#reseachResult').show();
                    $('#search').val('');
                    $('#spinner').hide();
                    document.getElementById('researchEnter').innerHTML= researchParam;
                },
                'html'        // Format des données reçues
            );
        }
    });



    $('.nav').click(function(){

        $('#spinner').show();
        clickItem = true;
        category = $(this).attr('id');
        $.get(
            'requete.php',  // Fichier cible côté serveur
            {
                choose : category // Récupération de la valeur saisie dans le formulaire html
            },
            function(data){ // Fonction callback avec les données renvoyées par jQuery en paramètre
                $('#result').html(data);
                $('#spinner').hide();
            },
            'html' // Format des données reçues
        );

        $('#dailyTrendTv').hide();
        $('#dailyTrendMovie').hide();
        $('#dailyTrendActor').hide();
        $('#dailyTrendAll').hide();
        $('#reseachResult').hide();


        if (category == 'all'){
            $('#dailyTrendAll').show();
        }
        if(category == 'movie'){
            $('#dailyTrendMovie').show();
        }
        if(category == 'actor'){
            $('#dailyTrendActor').show();
        }
        if( category  == 'tv') {
            $('#dailyTrendTv').show();
        }
    });



    $(document).on('click', '.wrapProduct', function(){
        $('#spinner').show();
        clickItem = false;

        $.post(
            'requete.php',               // Fichier cible côté serveur
            {
                selectedId : $(this).attr('id'),         // Variable passée en param pour la selection dans requete.php
                selectedClass : $(this).attr('class'),
                resultId : $(this).children('p').attr('id'),
            },
            function (data){               // fonction callback à effectuer au retour de la requête
                $('#result').html(data);           // ajoute à la div #result l'html revenant de requete.php
                $('div').remove('#dailyTrend');         // Supprime la div contenant le texte 'Tendance du jour' lors keypress 13
                $('html,body').animate({scrollTop: 0}, 'slow');
                $('#dailyTrendTv').hide();
                $('#dailyTrendActor').hide();
                $('#dailyTrendMovie').hide();
                $('#dailyTrendAll').hide();
                $('#reseachResult').hide();
                $('#spinner').hide();
            },
            'html'
        );
    });




});
