<script type="text/javascript">
    var bdd_host = $('#bdd_host').val();
    var bdd_login = $('#bdd_login').val();
    var bdd_pass = $('#bdd_pass').val();
    var bdd_radio_choice = $(":checked").val();
    var bdd_name = $('#bdd_name').val();
    if ($('#bdd_prefixe').val() == "") {
        var bdd_prefixe = "gt";
    } else {
        var bdd_prefixe = $('#bdd_prefixe').val();
    }
    $("#progressbar").progressbar({
        value: 0
    });
    var inc_bar = 100 / 8;
    $(document).ready(function() {
        $('#alert_connexion').hide();
        $('#alert_create').hide();
        $('#alert_actions').hide();
        $('#alert_projets').hide();
        $('#alert_inter').hide();
        $('#alert_users').hide();
        $('#alert_join').hide();
        $('#alert_champ').hide();
        $('#msg_box').hide();
        $('#final').button().hide();
        $('#final').click(function() {
            $('#tabs').tabs("enable", 2).tabs("option", "active", 2);
        });
        $.ajax({// Création du fichier de configuration
            type: "POST",
            url: "progress.php",
            data: {action: 'conf', bdd_radio_choice: bdd_radio_choice, bdd_name: bdd_name, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
            dataType: "script",
            success: function() {
                if (status == true || status == "true") {
                    $('#alert_conf').removeClass("secondary").addClass("success");
                    $('#alert_create').show('fast');
                    var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                    $('#progressbar').progressbar("option", "value", new_value);
                    $.ajax({// Création de la base de donnée si necessaire
                        type: "POST",
                        url: "progress.php",
                        data: {action: 'create_bdd', bdd_choice: bdd_radio_choice, bdd_name: bdd_name, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                        dataType: "script",
                        async: false,
                        success: function() {
                            if (status == true || status == "true") {
                                $('#alert_create').removeClass("secondary").addClass("success");
                                $('#alert_actions').show('slow');
                                var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                $('#progressbar').progressbar("option", "value", new_value);
                                $.ajax({// Création de la table des actions
                                    type: "POST",
                                    url: "progress.php",
                                    data: {action: 'actions', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                    dataType: "script",
                                    async: false,
                                    success: function() {
                                        if (status == true || status == "true") {
                                            $('#alert_actions').removeClass("secondary").addClass("success");
                                            $('#alert_projets').show('slow');
                                            var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                            $('#progressbar').progressbar("option", "value", new_value);
                                            $.ajax({// Création de la table des projets
                                                type: "POST",
                                                url: "progress.php",
                                                data: {action: 'projets', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                                dataType: "script",
                                                async: false,
                                                success: function() {
                                                    if (status == true || status == "true") {
                                                        $('#alert_projets').removeClass("secondary").addClass("success");
                                                        $('#alert_inter').show('slow');
                                                        var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                                        $('#progressbar').progressbar("option", "value", new_value);
                                                        $.ajax({// Création de la table des interventions
                                                            type: "POST",
                                                            url: "progress.php",
                                                            data: {action: 'inter', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                                            dataType: "script",
                                                            async: false,
                                                            success: function() {
                                                                if (status == true || status == "true") {
                                                                    $('#alert_inter').removeClass("secondary").addClass("success");
                                                                    $('#alert_users').show('slow');
                                                                    var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                                                    $('#progressbar').progressbar("option", "value", new_value);
                                                                    $.ajax({// Création de la table des utilisateurs
                                                                        type: "POST",
                                                                        url: "progress.php",
                                                                        data: {action: 'users', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                                                        dataType: "script",
                                                                        async: false,
                                                                        success: function() {
                                                                            if (status == true || status == "true") {
                                                                                $('#alert_users').removeClass("secondary").addClass("success");
                                                                                $('#alert_join').show();
                                                                                var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                                                                $('#progressbar').progressbar("option", "value", new_value);
                                                                                $.ajax({// Création de la table des associations
                                                                                    type: "POST",
                                                                                    url: "progress.php",
                                                                                    data: {action: 'join', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                                                                    dataType: "script",
                                                                                    async: false,
                                                                                    success: function() {
                                                                                        if (status == true || status == "true") {
                                                                                            $('#alert_join').removeClass("secondary").addClass("success");
                                                                                            $('#alert_champ').show();
                                                                                            var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                                                                            $('#progressbar').progressbar("option", "value", new_value);
                                                                                            $.ajax({// Création de la table des associations
                                                                                                type: "POST",
                                                                                                url: "progress.php",
                                                                                                data: {action: 'champ', bdd_current: bdd_select, bdd_prefixe: bdd_prefixe, bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                                                                                                dataType: "script",
                                                                                                async: false,
                                                                                                success: function() {
                                                                                                    if (status == true || status == "true") {
                                                                                                        $('#alert_champ').removeClass("secondary").addClass("success");
                                                                                                        $('#final').show();
                                                                                                        var new_value = $('#progressbar').progressbar("option", "value") + inc_bar;
                                                                                                        $('#progressbar').progressbar("option", "value", new_value);
                                                                                                    } else {
                                                                                                        $('#alert_champ').removeClass("secondary").addClass("alert");
                                                                                                        $('#msg_box').fadeIn(500);
                                                                                                    }
                                                                                                }
                                                                                            });
                                                                                        } else {
                                                                                            $('#alert_join').removeClass("secondary").addClass("alert");
                                                                                            $('#msg_box').fadeIn(500);
                                                                                        }
                                                                                    }
                                                                                });
                                                                            } else {
                                                                                $('#alert_users').removeClass("secondary").addClass("alert");
                                                                                $('#msg_box').fadeIn(500);
                                                                            }
                                                                        }
                                                                    });
                                                                } else {
                                                                    $('#alert_inter').removeClass("secondary").addClass("alert");
                                                                    $('#msg_box').fadeIn(500);
                                                                }
                                                            }
                                                        });
                                                    } else {
                                                        $('#alert_projets').removeClass("secondary").addClass("alert");
                                                        $('#msg_box').fadeIn(500);
                                                    }
                                                }
                                            });
                                        } else {
                                            $('#alert_actions').removeClass("secondary").addClass("alert");
                                            $('#msg_box').fadeIn(500);
                                        }
                                    }
                                });
                            } else {
                                $('#alert_create').removeClass("secondary").addClass("alert");
                                $('#msg_box').fadeIn(500);
                            }
                        }
                    });
                } else {
                    $('#alert_conf').removeClass("secondary").addClass("alert");
                    $('#msg_box').fadeIn(500);
                }
            }
        });
    });
</script>
<div class="row">
    <div class="seven columns">
        <div class="alert-box secondary" id="alert_conf">
            Création du fichier de configuration.
        </div>
        <div class="alert-box secondary" id="alert_create">
            Création de la base de données.
        </div>
        <div class="alert-box secondary" id="alert_actions">
            Création de la table des actions.
        </div>
        <div class="alert-box secondary" id="alert_projets">
            Création de la table des projets.
        </div>
        <div class="alert-box secondary" id="alert_inter">
            Création de la table des interventions.
        </div>
        <div class="alert-box secondary" id="alert_users">
            Création de la table des utilisateurs.
        </div>
        <div class="alert-box secondary" id="alert_join">
            Création de la table des associations.
        </div>
        <div class="alert-box secondary" id="alert_champ">
            Création de la table des champs personnalisés.
        </div>
    </div>
    <div class="five columns">
        <div class="panel callout radius" id="msg_box">
            <h5>Une erreur s'est produite.</h5>
            <p>Nous sommes désolé mais apparement une erreur vient de se produire.<br />Merci de contacter <a href="mailto:contact@gt-online.fr">GT Online</a> en précisant le nom de l'étape posant problème (ligne rouge).</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="ten columns">
        <div id="progressbar" style="height: 20px"></div>
    </div>
    <div class="two columns"><button id="final">Suivant</button></div>
</div>