<?php

if ($link = @mysql_connect($_POST['bdd_host'], $_POST['bdd_login'], $_POST['bdd_pass'])) {
    switch ($_POST['action']) {
        case "conf":
            if (!isset($_POST['bdd_radio_choice']) || $_POST['bdd_radio_choice'] == "Créer une nouvelle base de données :") {
                $bdd_name = $_POST['bdd_name'];
            } else {
                $bdd_name = $_POST['bdd_radio_choice'];
            }
            $hi = fopen("../conf/config.session.php", "w");
            $text = '<?php' . "\n";
            $text .= '$mysqltype = \'mysql\';' . "\n";
            $text .= '$mysqlserveur = \'' . $_POST['bdd_host'] . '\';' . "\n";
            $text .= '$mysqllogin = \'' . $_POST['bdd_login'] . '\';' . "\n";
            $text .= '$mysqlpassword = \'' . $_POST['bdd_pass'] . '\';' . "\n";
            $text .= '$mysqlmaindb=\'' . $bdd_name . '\';' . "\n";
            $text .= '$mysqlprefix=\'' . $_POST['bdd_prefixe'] . '\';' . "\n";
            $text .= '$gt_version=\'2.0.7\';' . "\n";
            $text .= '?>';
            fwrite($hi, $text);
            fclose($hi);
            break;
        case "create_bdd":
            if ($_POST['bdd_choice'] != "Créer une nouvelle base de données :") {
                echo "var status = true;";
                echo "var bdd_select = '" . $_POST['bdd_choice'] . "';";
            } else {
                $query_db = "CREATE DATABASE `" . $_POST['bdd_name'] . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
                if (mysql_query($query_db)) {
                    echo "var status = true;\r\n";
                    echo "var bdd_select = '" . $_POST['bdd_name'] . "';";
                } else {
                    echo "var status = false;";
                }
            }
            break;
        case "actions":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_actions = "CREATE TABLE `" . $_POST['bdd_prefixe'] . "_action` (
			    `id_action` int(4) NOT NULL auto_increment,
			    `user` int(2) NOT NULL default '0',
			    `nom` varchar(200) NOT NULL default '',
			    `desc` text NOT NULL,
			    `date` varchar(50) NOT NULL default '',
			    `active` tinyint(1) NOT NULL default '0',
			    PRIMARY KEY  (`id_action`)
			    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            if (mysql_query($query_actions)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "projets":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_projets = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_projet` (
			    `id_projet` int(3) NOT NULL AUTO_INCREMENT,
			    `user` int(3) NOT NULL DEFAULT '0',
			    `nom` varchar(200) NOT NULL DEFAULT '',
			    `desc` varchar(255) NOT NULL DEFAULT '',
			    `date` varchar(50) NOT NULL DEFAULT '',
			    `active` tinyint(1) NOT NULL DEFAULT '0',
			    PRIMARY KEY (`id_projet`)
			    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            if (mysql_query($query_projets)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "inter":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_inter = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_inter` (
			    `id_inter` int(4) NOT NULL AUTO_INCREMENT,
			    `user` int(4) NOT NULL DEFAULT '0',
			    `date` varchar(50) NOT NULL DEFAULT '',
			    `heure_debut` varchar(25) NOT NULL DEFAULT '',
			    `heure_fin` varchar(25) NOT NULL DEFAULT '',
			    `pause` varchar(25) DEFAULT NULL,
			    `heure_total` varchar(25) NOT NULL DEFAULT '',
			    `projet` int(4) NOT NULL DEFAULT '0',
			    `action` int(4) NOT NULL DEFAULT '0',
			    `commentaire` varchar(255) DEFAULT NULL,
			    PRIMARY KEY (`id_inter`)
			    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            if (mysql_query($query_inter)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "users":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_user = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_user` (
			    `id_user` int(4) NOT NULL AUTO_INCREMENT,
			    `user` varchar(50) NOT NULL DEFAULT '',
			    `pass` varchar(50) NOT NULL DEFAULT '',
			    `droits` tinyint(2) NOT NULL DEFAULT '0',
			    `nom` varchar(50) NOT NULL DEFAULT '',
			    `prenom` varchar(50) NOT NULL DEFAULT '',
			    `service` varchar(50) NOT NULL DEFAULT '',
			    PRIMARY KEY (`id_user`)
			    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            if (mysql_query($query_user)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "join":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_join = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_join` (
			    `join_projet` int(255) NOT NULL,
			    `join_action` int(255) NOT NULL,
			    `join_order` int(255) NOT NULL
			    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            if (mysql_query($query_join)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "champ":
            $db_selected = mysql_select_db($_POST['bdd_current']);
            $query_info_user = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_info_user` (
			    `id_info` int(11) NOT NULL AUTO_INCREMENT,
			    `intitule` varchar(255) DEFAULT NULL,
			    `colname` varchar(255) NOT NULL,
			    `require` tinyint(1) NOT NULL,
			    PRIMARY KEY (`id_info`)
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            if (mysql_query($query_info_user)) {
                $query_info_join = "CREATE TABLE IF NOT EXISTS `" . $_POST['bdd_prefixe'] . "_info_join` (
			    `id_user` int(11) NOT NULL,
			    `id_info` int(11) NOT NULL,
			    `content` varchar(255) DEFAULT NULL
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                if (mysql_query($query_info_join)) {
                    echo "var status = true;";
                } else {
                    echo "var status = false;";
                }
            } else {
                echo "var status = false;";
            }
            break;
        case "add_admin":
            include ("../conf/config.session.php");
            $db_selected = mysql_select_db($mysqlmaindb);
            $query_admin = "INSERT INTO `" . $mysqlprefix . "_user` (`id_user`, `user`, `pass`, `droits`, `nom`, `prenom`, `service`) VALUES
			    ('', '" . $_POST['login'] . "', '" . md5($_POST['pass']) . "', 1, '', '', '')";
            if (mysql_query($query_admin)) {
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        case "list_bdd":
            echo "var list_bdd = new Array();";
            $i = 0;
            if ($res = mysql_query("SHOW DATABASES")) {
                while ($row = mysql_fetch_assoc($res)) {
                    echo 'list_bdd[' . $i . '] = "' . $row['Database'] . '";';
                    $i++;
                }
                echo 'list_bdd[' . $i . '] = "Créer une nouvelle base de données :";';
                echo "var status = true;";
            } else {
                echo "var status = false;";
            }
            break;
        default:
            break;
    }
} else {
    if ($_POST['action'] == "suppr_install") {
        /*unlink('etape2.php');
        unlink('finalisation.php');
        unlink('progress.php');
        unlink('index.php');
        rmdir('../install');*/
        echo "var status = true";
    } else {
        echo "var status = false;";
    }
}
?>
