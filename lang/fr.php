<?php
// Traduction des mois
$lng_month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre");
$lng_day = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
$lng_yes_no = array("non", "oui");
$lng_internal_error = "Une erreur interne est survenue.";

// Accueil
$tabs_interventions = "Interventions";
$tabs_search = "Recherche";
$tabs_informations = "Informations";
$tabs_rapport = "Bilan";
$tabs_administration = "Administration";
$tabs_deconnexion = "Déconnexion";
$tabs_deconnexion_msg = "Vous êtes maintenant déconnecté de GT Online.<br />Merci et à bientôt !!!";
$dialog_login = "GT Online - Gestion du temps";
$dialog_login_username = "Nom d'utilisateur";
$dialog_login_userpass = "Mot de passe";
$dialog_newuser_titre = "Nouvel utilisateur";
$dialog_newuser_username = "Nom d'utilisateur";
$dialog_newuser_userpass = "Nouveau mot de passe";
$dialog_newuser_verifpass = "Vérification du mot de passe";
$dialog_newuser_firstname = "Nom";
$dialog_newuser_lastname = "Prénom";
$dialog_newuser_service = "Service";
$dialog_error_username = "Ce nom d\'utilisateur est déjà utilisé.";
$dialog_error_surname = "Ce nom et ce prénom sont déjà utilisés.";
$error_username = 'Veuillez indiquer votre nom d\'utilisateur.';
$error_nom = 'Veuillez indiquer votre nom.';
$error_prenom = 'Veuillez indiquer votre prénom.';
$error_service = 'Veuillez indiquer votre service.';
$error_pass = 'Veuillez indiquer un mot de passe.';
$error_passcheck = 'Veuillez confirmer le mot de passe.';
$error_passverif = 'Les mots de passe ne correspondent pas.';
$error_nouser = 'Ce nom d\'utilisateur n\'existe pas !!!';
$error_passdb = 'Le mot de passe ne correspond pas !!!';
$dialog_btn_connexion = 'Connexion';
$dialog_btn_inscription = 'Inscription';
$dialog_btn_create = 'Créer';
$dialog_btn_cancel = "Annuler";
$link_lost_password = "Vous avez perdu votre mot de passe ?";
$dialog_lost_password = "Veuillez demander à l'administrateur de réinitialiser votre mot de passe.";

// Interventions
$grid_inter_emptyrecords = "Aucune intervention ...";
$grid_inter_username = "Utilisateur";
$grid_inter_heure_debut = "Début";
$grid_inter_heure_fin = "Fin";
$grid_inter_pause = "Pause";
$grid_inter_projet = "Projet";
$grid_inter_action = "Action";
$grid_inter_heure_total = "Total";
$grid_inter_caption = 'Interventions du ';
$grid_inter_duree = 'Durée';
$grid_inter_comment = 'Commentaire';
$grid_inter_cumul = 'Cumul du jour :';

// Informations
$info_username = "Nom d'utilisateur";
$info_username_holder = "Indiquez votre nom d'utilisateur";
$info_newpass = "Nouveau mot de passe";
$info_newpass_holder = "Indiquez votre nouveau mot de passe";
$info_verifpass = "Vérification du mot de passe";
$info_verifpass_holder = "Indiquez de nouveau le mot de passe";
$info_firstname = "Nom";
$info_firstname_holder = "Indiquez votre nom";
$info_lastname = "Prénom";
$info_lastname_holder = "Indiquez votre prénom";
$info_service = "Service";
$info_service_holder = "Indiquez le service dans lequel vous travaillez";
$info_comment = "Entrez un nouveau mot de passe pour le modifier sinon laissez vide.";
$info_button = "Mettre à jour";
$info_message_good = "Vos informations sont mises à jour.";
$info_message_wrong = "Les mots de passe ne correspondent pas.";

// Administration
$accordion_export = "Exportation/Sauvegarde/Purge";
$accordion_user = "Gestion des utilisateurs";
$accordion_projet = "Gestion des projets";
$accordion_action = "Gestion des actions";
$accordion_join = "Association Projet/Actions";
$accordion_hours = "Gestion des horaires";
$accordion_parameters = "Paramètres";
$accordion_about = "A Propos";

// Gestion actions
$action_col_nom = "Nom de l\'action";
$action_col_description = "Description";
$action_col_etat = "Etat";
$action_emptyrecords = "Aucune action ...";
$action_caption = "Liste des actions";

// Gestion des projets
$projet_col_nom = "Nom du projet";
$projet_col_description = "Description";
$projet_col_etat = "Etat";
$projet_emptyrecords = "Aucun projet ...";
$projet_caption = "Liste des projets";

// Gestion des utilisateurs
$users_col_nom = "Nom";
$users_col_prenom = "Prénom";
$users_col_service = "Service";
$users_col_identifiant = "Identifiant";
$users_col_droits = "Droits";
$users_col_password = "Mot de passe";
$users_emptyrecords = "Aucun utilisateur ...";
$users_caption = "Liste des utilisateurs";
$users_title_delete = "Suppression";
$users_title_modif = "Modification du mot de passe";
$users_intro_modif = "Vous avez modifié le mot de passe d'un utilisateur.";
$users_text_modif = "Pensez à lui communiquer son nouveau mot de passe.";
$champ_caption = "Liste des champs supplémentaires";
$champ_col_intitule = "Intitulé";
$champ_col_name = "Nom de base";
$champ_col_require = "Requis";
$champ_emptyrecords = "Aucun champ supplémentaire";
$champ_error_msg = "Vous devez renseigner le champ ";

// Gestion des horaires
$hours_label_inter = "Méthode d'entrée";
$hours_tooltip_inter = "Indiquez la méthode d'entrée des horaires<br>que vous souhaitez utiliser pour les interventions.";
$hours_select_inter = array("Interval de temps", "Durée");
$hours_label_rapport = "Affichage du rapport";
$hours_tooltip_rapport = "Indiquez dans quel format vous souhaitez obtenir le rapport.";
$hours_select_rapport = array("Jour", "Heure");
$hours_label_users = "Horaires utilisateurs";
$hours_tooltip_users = "Indiquez les horaires de vos utilisateurs.<br>Laissez vide les jours où ils ne travaillent pas.";
$hours_placeholder_users = array("Début", "Fin", "Pause");

// Gestion des parametres
$param_titre_interface = "Paramètres de l'interface";
$param_label_langue = "Langue";
$param_tooltip_langue = "Selectionnez la langue de GT Online.";
$param_select_langue = array("Anglais", "Français");
$param_label_interface = "Interface";
$param_tooltip_interface = "Selectionnez le thème de GT Online.";
$param_titre_cal = "Paramètres du calendrier et horodatage";
$param_label_semaine = "Numéro des semaines";
$param_tooltip_semaine = "Affiche les numéros de semaine dans le calendrier.";
$param_select_truefalse = array("Afficher", "Masquer");
$param_label_firstday = "Premier jour de la semaine";
$param_tooltip_firstday = "Selectionnez le premier jour de la semaine.";
$param_label_affich_horo = "Affichage horodateur";
$param_tooltip_affich_horo = "Affiche ou masque l'horodateur.";
$param_label_ampm = "Affichage AM/PM";
$param_tooltip_ampm = "Affiche AM/PM dans l'horodateur.";
$param_label_now = "Affichage 'Maintenant'";
$param_tooltip_now = "Affiche le bouton 'Maintenant' dans l'horodateur.<br>Permet de cliquer directement sur le bouton pour indiquer l'heure actuelle.";
$param_titre_users = "Paramètres des utilisateurs";
$param_label_create = "Création de compte par l'utilisateur";
$param_tooltip_create = "Autoriser la création de compte par les utilisateurs ou limiter à l'administrateur.";
$param_label_modif = "Informations utilisateur";
$param_tooltip_modif = "Autoriser l'utilisateur à<br />modifier ses informations.";
$param_tilde = "Pour que les paramètres soient pris en compte vous devez vous deconnecter.";

// Rapports
$rapport_col_projet = "Projet";
$rapport_col_action = "Action";
$rapport_col_duree = "Durée";
$rapport_col_unite = array(" en jours", " en heures");
$rapport_caption = "Bilan du mois de ";

// Type d'utilisateur
$user_type_admin = "Administrateur";
$user_type_user = "Utilisateur";

// Exportation - sauvegarde - purge
$export_title = "Exportation";
$export_button = "Exporter";
$export_from = "Date de début";
$export_to = "Date de fin";
$export_message_progress = "Exportation en cours. Veuillez patienter !!!";
$export_message_wrong = "Vous devez indiquer une date";
$export_message_good = "Les interventions ont bien été exportées";
$export_emptyrecords = "Aucune exportation...";
$export_caption = "Liste des fichiers";
$export_date = "Date";
$export_start_hour = "Heure de début";
$export_end_hour = "Heure de fin";
$purge_title = "Purge";
$purge_button = "Purger";
$purge_from = "Date de début";
$purge_to = "Date de fin";
$purge_message_good = "Les interventions ont bien été supprimées";

// Association Projet/Actions
$association_actions = "Actions";
$association_tooltip = "<h4>Association d'une action</h4><p>Faites glisser l'action vers le projet avec lequel vous souhaitez l'associer.</p><h4>Supprimer une action d'un projet</h4><p>Ramener l'action du projet vers la boite des actions pour la supprimer du projet.</p><h4>Tri des actions</h4><p>Vous pouvez organiser l'ordre des actions de chaque projet afin de déterminer l'ordre d'affichage lors de l'insertion d'une intervention.</p>";
?>