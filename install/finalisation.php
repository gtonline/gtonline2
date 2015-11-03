<script type="text/javascript">
    $(document).ready(function() {
        var bdd_host = $('#bdd_host').val();
        var bdd_login = $('#bdd_login').val();
        var bdd_pass = $('#bdd_pass').val();
        $('#submit_form').button();
        $('#dialog_login').dialog();
        $('#msg_final').hide();
        $('#button_final').button().hide();
        $('#submit_form').click(function() {
            $.ajax({
                type: "POST",
                url: "progress.php",
                data: {action: 'add_admin', login: $('#adminname').val(), pass: $('#adminpass').val(), bdd_host: bdd_host, bdd_login: bdd_login, bdd_pass: bdd_pass},
                dataType: "script",
                success: function() {
                    if (status == true || status == "true") {
                        $('#msg_final').show();
                        $('#button_final').show();
                        $('#dialog_login').dialog("close");
                    }
                }
            });
        });
        $('#button_final').click(function() {
            $.ajax({
                type: "POST",
                url: "progress.php",
                data: {action: 'suppr_install'},
                dataType: "script",
                success: function() {
                    if (status == true || status == "true") {
                        var uri = window.location.href;
                        var domaine = uri.substring(0, uri.indexOf("install", 7));
                        $(location).attr('href', domaine);
                    }
                }
            });
        });
    });
</script>
<div id="dialog_login" title="Création du compte administrateur">
    <form>
        <label for="adminname">Nom de l'administrateur</label><input type="text" name="username" id="adminname" placeholder="Nom de l'administrateur" />
        <label for="adminpass">Mot de passe</label><input type="password" name="userpass" id="adminpass" placeholder="Mot de passe" />
    </form>
    <button id="submit_form">Créer</button>
</div>
<div class="row">
    <div class="eleven columns centered" id="msg_final">
        <p>Merci d'avoir choisi GT Online.</p>
        <p>Vous venez de finaliser l'installation de GT Online.</p>
        <p>Vous allez pouvoir vous rendre, dès à présent, sur l'interface de GT Online en cliquant sur le bouton "suivant".</p>
        <p><strong>Commencez par modifier les paramètres de GT Online à votre convenance dans la partie "Administration".</strong></p>
        <p>Pour vous connectez utiliser le login et le mot de passe que vous venez de choisir.</p>
        <p>Pour des raisons de sécurité, les fichiers et dossiers d'installation vont être supprimés du serveur. Ces fichiers sont évidemment devenus inutiles.</p>
    </div>
</div>
<div class="row">
    <div class="two columns offset-by-ten"><button id="button_final">Suivant</button></div>
</div>