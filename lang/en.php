<?php
// Traduction des mois
$lng_month = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
$lng_day = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$lng_yes_no = array("no", "yes");
$lng_internal_error = "An internal error has occurred.";

// Accueil
$tabs_interventions = 'Interventions';
$tabs_search = "Search";
$tabs_informations = 'Informations';
$tabs_rapport = 'Balance sheet';
$tabs_administration = 'Administration';
$tabs_deconnexion = 'Logout';
$tabs_deconnexion_msg = 'You are now logout from GT Online.<br />Thank you and goodbye !!!';
$dialog_login = 'GT Online - Time Management';
$dialog_login_username = 'User name';
$dialog_login_userpass = 'Password';
$dialog_newuser_titre = "New user";
$dialog_newuser_username = "User name";
$dialog_newuser_userpass = "New password";
$dialog_newuser_verifpass = "Verification password";
$dialog_newuser_firstname = "Lastname";
$dialog_newuser_lastname = "Firstname";
$dialog_newuser_service = "Service";
$dialog_error_username = "This username is already in use.";
$dialog_error_surname = "This name and this firstname is already used.";
$error_username = 'Please provide your username.';
$error_nom = 'Please provide your name.';
$error_prenom = 'Please provide your lastname.';
$error_service = 'Please provide your service.';
$error_pass = 'Please provide your password.';
$error_passcheck = 'Please confirm the password.';
$error_passverif = 'Passwords do not match.';
$error_nouser = 'This user name does not exist !!!';
$error_passdb = 'Password do not match !!!';
$dialog_btn_connexion = 'Connection';
$dialog_btn_inscription = 'Registration';
$dialog_btn_create = 'Create';
$dialog_btn_cancel = "Cancel";
$link_lost_password = "Lost your password ?";
$dialog_lost_password = "Please ask the administrator to reset your password.";

// Interventions
$grid_inter_emptyrecords = "No intervention ...";
$grid_inter_username = "User";
$grid_inter_heure_debut = "Start";
$grid_inter_heure_fin = "End";
$grid_inter_pause = "Pause";
$grid_inter_projet = "Project";
$grid_inter_action = "Action";
$grid_inter_heure_total = "Total";
$grid_inter_caption = 'Interventions of ';
$grid_inter_duree = 'Duration';
$grid_inter_comment = 'Comment';
$grid_inter_cumul = 'Daily total :';

// Informations
$info_username = "User name";
$info_username_holder = "Enter your user name";
$info_newpass = "New password";
$info_newpass_holder = "Enter your new password";
$info_verifpass = "Verification password";
$info_verifpass_holder = "Enter the password again";
$info_firstname = "Lastname";
$info_firstname_holder = "Enter your lastname";
$info_lastname = "Firstname";
$info_lastname_holder = "Enter your firstname";
$info_service = "Service";
$info_service_holder = "Specify the service in which you work";
$info_comment = "Enter a new password to change it otherwise leave blank.";
$info_button = "Update";
$info_message_good = "Your information is updated.";
$info_message_wrong = "Passwords do not match.";

// Administration
$accordion_export = "Export/Backup/Delete";
$accordion_user = "Users management";
$accordion_projet = "Projects management";
$accordion_action = "Actions management";
$accordion_join = "Project/Actions association";
$accordion_hours = "Time management";
$accordion_parameters = "Parameters";
$accordion_about = "About";

// Gestion actions
$action_col_nom = "Action name";
$action_col_description = "Description";
$action_col_etat = "Status";
$action_emptyrecords = "No action ...";
$action_caption = "Action list";

// Gestion des projets
$projet_col_nom = "Project name";
$projet_col_description = "Description";
$projet_col_etat = "Status";
$projet_emptyrecords = "No project ...";
$projet_caption = "Project list";

// Gestion des utilisateurs
$users_col_nom = "Lastname";
$users_col_prenom = "Firstname";
$users_col_service = "Service";
$users_col_identifiant = "Username";
$users_col_droits = "Rights";
$users_col_password = "Password";
$users_emptyrecords = "No user ...";
$users_caption = "Users list";
$users_title_delete = "Deleting";
$users_title_modif = "Changing the password";
$users_intro_modif = "You have changed the user's password.";
$users_text_modif = "Think of it communicate his new password.";
$champ_caption = "List of additional fields";
$champ_col_intitule = "Field name";
$champ_col_name = "Database name";
$champ_col_require = "Require";
$champ_emptyrecords = "No additional field";
$champ_error_msg = "Please fill in the field ";

// Gestion des horaires
$hours_label_inter = "Calculation";
$hours_tooltip_inter = "Specify the input schedules method<br>you want to use for interventions.";
$hours_select_inter = array("Interval time", "Duration");
$hours_label_rapport = "Viewing report";
$hours_tooltip_rapport = "Specify what format you want the report.";
$hours_select_rapport = array("Day", "Hour");
$hours_label_users = "Schedules users";
$hours_tooltip_users = "Indicate the times of your users.<br>leave blank the day when they are not working.";
$hours_placeholder_users = array("Start", "End", "Pause");

// Gestion des parametres
$param_titre_interface = "Interface parameters";
$param_label_langue = "Language";
$param_tooltip_langue = "Enter the GT Online language.";
$param_select_langue = array("English", "French");
$param_label_interface = "Interface";
$param_tooltip_interface = "Select the GT Online theme.";
$param_titre_cal = "Calendar and timestamp parameters";
$param_label_semaine = "Weeks number";
$param_tooltip_semaine = "Displays week numbers in the calendar.";
$param_select_truefalse = array("Show", "Hide");
$param_label_firstday = "First day of the week";
$param_tooltip_firstday = "Select the first day of the week.";
$param_label_affich_horo = "Timestamp display";
$param_tooltip_affich_horo = "Display the timestamp.";
$param_label_ampm = "Display AM/PM";
$param_tooltip_ampm = "Display AM/PM in the timestamp.";
$param_label_now = "Display 'Now'";
$param_tooltip_now = "Display the 'Now' button in the timestamp.<br>Can directly click on the button to select the currently hour.";
$param_titre_users = "Users parameters";
$param_label_create = "Creating user account";
$param_tooltip_create = "Allow account creation by users<br />or restricted to administrator.";
$param_label_modif = "User informations";
$param_tooltip_modif = "Allow user to change<br />his information.";
$param_tilde = "For the settings to take effect you must logout.";

// Rapports
$rapport_col_projet = "Project";
$rapport_col_action = "Action";
$rapport_col_duree = "Duration";
$rapport_col_unite = array(" in days", " in hours");
$rapport_caption = "Balance sheet of ";

// Type d'utilisateur
$user_type_admin = "Administrator";
$user_type_user = "User";

// Exportation - Backup - Delete
$export_title = "Exportation";
$export_button = "Export";
$export_from = "From";
$export_to = "To";
$export_message_progress = "Export in progress. Please wait !!!";
$export_message_wrong = "You must specify a date";
$export_message_good = "Interventions have been exported";
$export_emptyrecords = "No file...";
$export_caption = "Exports file";
$export_date = "Date";
$export_start_hour = "Start time";
$export_end_hour = "End time";
$purge_title = "Delete";
$purge_button = "Delete";
$purge_from = "From";
$purge_to = "To";
$purge_message_good = "Inteventions have been deleted";

// Association Projet/Actions
$association_actions = "Actions";
$association_tooltip = "<h4>Association of action</h4><p>Drag the action to the project you want to associate.</p><h4>Remove an action from a project</h4><p>Bring the project to the action box actions to remove it from the project.</p><h4>Sorting actions</h4><p>You can arrange the order of actions of each project to determine the display order when inserting an intervention.</p>";
?>