<?php
$gt_version = '2.0.7';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset=utf-8>
        <title>Installation de GT Online</title>

        <link type="text/css" href="../css/start/jquery-ui-1.10.2.custom.min.css" rel="Stylesheet" />
        <link type="text/css" href="../css/foundation.min.css" rel="Stylesheet" />
        <script charset="utf-8" src="../js/jquery-1.9.1.min.js"></script>
        <script charset="utf-8" src="../js/jquery-ui-1.10.2.custom.min.js"></script>
        <script>
            $(document).ready(function() {
                $('input').click(function() {
                    $(this).removeClass("ui-state-error");
                });
                $('#fieldset_bdd_name').hide();
                $('#fieldset_bdd_prefixe').hide();
                $('#widget_error').hide();
                $("#tabs").tabs({
                    disabled: [1, 2]
                });
                $('#next').button();
                $('#next').click(function() {
                    if ($('#bdd_host').val() == "") {
                        $('#bdd_host').addClass("ui-state-error");
                    }
                    if ($('#bdd_login').val() == "") {
                        $('#bdd_login').addClass("ui-state-error");
                    }
                    if ($('#fieldset_bdd_name').is(':hidden') && $('#bdd_host').val() != "" && $('#bdd_login').val() != "") {
                        $.ajax({
                            type: "POST",
                            url: "progress.php",
                            data: {action: 'list_bdd', bdd_host: $('#bdd_host').val(), bdd_login: $('#bdd_login').val(), bdd_pass: $('#bdd_pass').val()},
                            dataType: "script",
                            success: function() {
                                if (status == true || status == "true") {
                                    $.each(list_bdd, function(index, value) {
                                        var nbr_bdd = list_bdd.length;
                                        if (index == (nbr_bdd - 1)) {
                                            $('<input type="radio" name="bdd_choice" id="bdd_choice" value="' + value + '" checked="checked" />' + value + '<br />').appendTo('#bdd_radiobox');
                                        } else {
                                            $('<input type="radio" name="bdd_choice" id="bdd_choice" value="' + value + '" />' + value + '<br />').appendTo('#bdd_radiobox');
                                        }

                                    });
                                    $('#fieldset_bdd_name').show();
                                    $('#fieldset_bdd_prefixe').show();
                                } else {
                                    $('#widget_error').fadeIn("fast").delay("4000").fadeOut("fast");
                                }
                            }
                        });
                    } else if ($('#bdd_host').val() != "" && $('#bdd_login').val() != "") {
                        if ($(":checked").val() == "Créer une nouvelle base de données :" && $('#bdd_name').val() == "") {
                            $('#bdd_name').val("gtonline");
                        }
                        $("#tabs").tabs("enable", 1).tabs("option", "active", 1)
                    }
                });
                $('form').on('keyup', function(e) {
                    if (e.keyCode == 13) {
                        $(':button:first').click();
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="row">
            <div class="twelve columns">
                <h3>Installation de GT Online V<?php echo $gt_version ?> ...</h3>
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Etape1</a></li>
                        <li><a href="etape2.php">Etape2</a></li>
                        <li><a href="finalisation.php">Finalisation</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div class="row">
                            <div class="twelve columns"><h4>Connexion à votre base.</h4></div>
                        </div>
                        <div class="row">
                            <div class="twelve columns">
                                <p>Consultez les informations fournies par votre hébergeur : vous devez y trouver le serveur de base de données qu'il propose et vos identifiants personnels pour vous y connecter.</p>
                            </div>
                        </div>
                        <form>
                            <div class="row">
                                <div class="twelve columns">
                                    <fieldset>
                                        <legend>Adresse de la base de données</legend>
                                        <p>Souvent cette adresse correspond à celle de votre site, parfois elle correspond à la mention "localhost", parfois elle est laissée totalement vide.</p>
                                        <div class="row">
                                            <div class="twelve columns"><input type="text" placeholder="localhost" class="four" id="bdd_host" /></div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Le login de connexion</legend>
                                        <p>Correspond parfois à votre login d'accès au FTP.</p>
                                        <div class="row">
                                            <div class="twelve columns"><input type="text" placeholder="login" class="four" id="bdd_login" /></div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Le mot de passe de connexion</legend>
                                        <p>Correspond parfois à votre mot de passe pour le FTP; parfois laissé vide.</p>
                                        <div class="row">
                                            <div class="twelve columns"><input type="text" placeholder="password" class="four" id="bdd_pass" /></div>
                                        </div>
                                    </fieldset>
                                    <fieldset id="fieldset_bdd_name">
                                        <legend>Le nom de la base de données</legend>
                                        <p>Le nom de la base de données doit vous être communiqué par votre hébergeur. Sélectionnez la base dans la liste ci-dessous.<br />Vous pouvez aussi, si votre serveur SQL le permet, choisir de créer votre propre base de données. Choisissez alors l'option "Créer une nouvelle base de données" et indiquez le nom de cette base dans la boite de texte.</p>
                                        <div class="row">
                                            <div id="bdd_radiobox"></div>
                                            <br />
                                        </div>
                                        <div class="row">
                                            <div class="twelve columns"><input type="text" placeholder="gtonline" class="four" id="bdd_name" /></div>
                                        </div>
                                    </fieldset>
                                    <fieldset id="fieldset_bdd_prefixe">
                                        <legend>Le préfixe utilisé par les tables</legend>
                                        <p>Vous pouvez modifier le préfixe du nom des tables de données (ceci est indispensable lorsque l'on souhaite installer plusieurs sites dans la même base de données). Ce préfixe s'écrit en lettres minuscules, non accentuées, et sans espace. Il sera par défaut "gt".</p>
                                        <div class="row">
                                            <div class="twelve columns"><input type="text" placeholder="gt" class="four" id="bdd_prefixe" /></div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="ten columns">
                                <div class="ui-widget" id="widget_error">
                                    <div class="ui-state-error ui-corner-all" style="height:30px">
                                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                                            <strong>Alerte:</strong> Une erreur s'est produit. Veuillez vérifier vos informations de connexion à la base de données.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="two columns"><button id="next">Suivant</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
