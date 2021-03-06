<?php

if(isset($_REQUEST['notFound']) && $_REQUEST['notFound'] == "true") {
    echo '<script type=\'text/javascript\'>
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "10000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
  };
  toastr["error"]("Il n\'existe aucune fiche pour le mois sélectionné", "Erreur");
  </script>';
} else {
    echo "";
}

echo '<div class="hidden alert alert-danger" id="danger-alert">
<button type="button" class="close" data-dismiss="alert">x</button>
<strong>Erreur! </strong>
Cette fiche n\'existe pas.
</div>';

$visiteurs = $pdo->getLesVisiteurs();
$listeMois = array(
    date('Y') . "01" => "Janvier",
    date('Y') . "02" => "Février",
    date('Y') . "03" => "Mars",
    date('Y') . "04" => "Avril",
    date('Y') . "05" => "Mai",
    date('Y') . "06" => "Juin",
    date('Y') . "07" => "Juillet",
    date('Y') . "08" => "Août",
    date('Y') . "09" => "Septembre",
    date('Y') . "10" => "Octobre",
    date('Y') . "11" => "Novembre",
    date('Y') . "12" => "Décembre",
    );

    ?>
    <fieldset>
        <legend>Choississez un visiteur puis un mois</legend>
        <div id="conteneurSelecteurs">
            <select class="selecteurVisiteur form-control"><?php
                foreach($visiteurs as $visiteur){
                    echo '<option value="'. $visiteur['id'] .'">'. $visiteur['nom'] . ' ' . $visiteur['prenom'] . '</option>';
                }
                echo '</select><br />';

                echo '<select class="hidden selecteurMois form-control">';
                foreach($listeMois as $idMois => $nomMois){
                    echo '<option value="'. $idMois .'">'. $nomMois .'</option>';
                }
                echo '</select></div>';


                if(isset($_REQUEST['requestedIdVisiteur']) && isset($_REQUEST['requestedMonth'])) {
                    if(empty($pdo->getLesFraisForfait($_REQUEST['requestedIdVisiteur'], $_REQUEST['requestedMonth']))){
                        ajouterErreur("Cette fiche n'existe pas");
                        echo '<script>';
                        echo '$("#danger-alert").removeClass("hidden");
                        $("#danger-alert").on("click", function() {
                            $("#danger-alert").fadeTo(1, 500).slideUp(500, function(){
                                $("#danger-alert").slideUp(500);
                            });
                        });';

                        echo '</script>';
                    } else {
                        include("v_consulterFiche.php");
                    }
                }
                ?>

                <script type="text/javascript">
                    $(".selecteurVisiteur").on("change", function() {
                        $('.selecteurMois').removeClass("hidden");
                    });
                    $('.selecteurMois').on("change", function() {
                        var requestedIdVisiteur = $(".selecteurVisiteur :selected").val();
                        var requestedMonth = $(".selecteurMois :selected").val();
                        $.ajax({
                            url: 'index.php?uc=gererFrais&action=consulterFrais',
                            type: 'get',
                            data: {requestedIdVisiteur: requestedIdVisiteur,
                                requestedMonth: requestedMonth,},
                                success: function() {
                                    window.location.href = "index.php?uc=gererFrais&action=consulterFrais&requestedIdVisiteur="+requestedIdVisiteur+"&requestedMonth="+requestedMonth;
                                },
                                error: function() {
                                    window.location.href = "index.php?uc=gererFrais&action=consulterFrais&requestedIdVisiteur="+requestedIdVisiteur+"&requestedMonth="+requestedMonth;
                                },
                                complete: function() {
                                    window.location.href = "index.php?uc=gererFrais&action=consulterFrais&requestedIdVisiteur="+requestedIdVisiteur+"&requestedMonth="+requestedMonth;
                                },
                            })
                    })
                </script>


