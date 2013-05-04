<?php
/***************************************************************************
 *
 *   NewPoints plugin (/inc/languages/admin/newpoints.lang.php)
 *	 Author: Pirata Nervo
 *   Copyright: © 2009-2011 Pirata Nervo
 *
 *   Website: http://www.mybb-plugins.com
 *
 *   NewPoints plugin for MyBB - A complex but efficient points system for MyBB.
 *
 ***************************************************************************/

/****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

/****************************************************************************
	French translation by Christian Fillion
****************************************************************************/

$l['newpoints'] = "NewPoints";
$l['newpoints_submit_button'] = 'Soumettre';
$l['newpoints_reset_button'] = 'Réinitialiser';
$l['newpoints_error'] = 'Une erreur inconnue s\'est produite.';
$l['newpoints_continue_button'] = 'Continuer';
$l['newpoints_click_continue'] = 'Cliquez sur Continuer pour poursuivre.';
$l['newpoints_delete'] = 'Supprimer';
$l['newpoints_missing_fields'] = 'Il y a un ou plusieurs champs manquants.';
$l['newpoints_edit'] = 'Éditer';
$l['newpoints_task_ran'] = 'Backup NewPoints task ran';

///////////////// Plugins
$l['newpoints_plugins'] = 'Plugins';
$l['newpoints_plugins_description'] = 'Vous pouvez gérer ici les plugins NewPoints.';

///////////////// Settings
$l['newpoints_settings'] = 'Paramètres';
$l['newpoints_settings_description'] = 'Vous pouvez gérer ici les paramètres de NewPoints.';
$l['newpoints_settings_change'] = 'Modifier';
$l['newpoints_settings_change_description'] = 'Modifier le paramètre.';
$l['newpoints_settings_main'] = 'Paramètres principaux';
$l['newpoints_settings_main_description'] = 'Ces paramètres sont livrés avec NewPoints par défaut.';
$l['newpoints_settings_income'] = 'Paramètres de revenu';
$l['newpoints_settings_income_description'] = 'Ces paramètres sont livrés avec NewPoints par défaut et sont liés aux revenus générés par l\'affichage, le vote dans les sondages, etc...';
$l['newpoints_select_plugin'] = 'Vous devez sélectionner un groupe.';

///////////////// Log
$l['newpoints_log'] = 'Journal';
$l['newpoints_log_description'] = 'Gérer les entrées du journal.';
$l['newpoints_log_action'] = 'Action';
$l['newpoints_log_data'] = 'Données';
$l['newpoints_log_user'] = 'Utilisateur';
$l['newpoints_log_date'] = 'Date';
$l['newpoints_log_options'] = 'Options';
$l['newpoints_no_log_entries'] = 'Impossible de trouver les entrées du journal.';
$l['newpoints_log_entries'] = 'Entrées du journal';
$l['newpoints_log_notice'] = 'Remarque : certaines statistiques sont basés hors des entrées de journal.';
$l['newpoints_log_deleteconfirm'] = 'Etes-vous sûr de vouloir supprimer l\'entrée du journal sélectionné ?';
$l['newpoints_log_invalid'] = 'Entrée du journal invalide.';
$l['newpoints_log_deleted'] = 'L\'entrée du journal a été supprimée avec succès.';
$l['newpoints_log_prune'] = 'Purger les entrées du journal';
$l['newpoints_older_than'] = 'Plus vieux que';
$l['newpoints_older_than_desc'] = 'Purger les entrées de journal de plus vieilles que le nombre de jours que vous avez entré';
$l['newpoints_log_pruned'] = 'Les entrées du journal ont été purgés avec succès.';
$l['newpoints_log_pruneconfirm'] = 'Etes-vous sûr de vouloir purger les entrées du journal ?';

///////////////// Maintenance
$l['newpoints_maintenance'] = 'Maintenance';
$l['newpoints_maintenance_description'] = 'Ici vous pouvez trouver différents outils de maintenance.';
$l['newpoints_recount'] = 'Recompter les points';
$l['newpoints_recount_per_page'] = 'Par page';
$l['newpoints_recount_per_page_desc'] = 'Entrez le nombre d\'utilisateurs que vous voulez recompter par page.<br />Le compte est est basé sur les paramètres de revenu.';
$l['newpoints_reset'] = 'Réinitialiser les points';
$l['newpoints_reset_per_page'] = 'Par page';
$l['newpoints_reset_per_page_desc'] = 'Entrez le nombre d\'utilisateurs que vous souhaitez réinitialiser par page.';
$l['newpoints_recounted'] = 'Vous avez recompté l\'argent des utilisateurs avec succès.';
$l['newpoints_reset_action'] = 'Vous avez réinitialisé l\'argent des utilisateurs avec succès.';
$l['newpoints_reset_done'] = 'Vous avez réinitialisé l\'argent des utilisateurs avec succès.';
$l['newpoints_recount_done'] = 'Les points ont étés recomptés';
$l['newpoints_recountconfirm'] = 'Êtes-vous sûr de vouloir recompter les points de tout le monde ?';
$l['newpoints_reset_points'] = 'Points';
$l['newpoints_reset_points_desc'] = 'Le nombre de points de tout le monde sera remis à cette valeur.';
$l['newpoints_edituser'] = 'Éditer l\'utilisateur';
$l['newpoints_edituser_uid'] = 'ID de l\'utilisateur';
$l['newpoints_edituser_uid_desc'] = 'Entrez l\'ID de l\'utilisateur que vous souhaitez éditer.';
$l['newpoints_reconstruct'] = 'Reconstruire les templates';
$l['newpoints_reconstruct_title'] = 'Reconstruire les templates';
$l['newpoints_reconstruct_desc'] = 'Les templates postbit, postbit_classic et member_profile seront édités afin d\'éviter les doublons.';
$l['newpoints_maintenance_edituser'] = 'Éditer l\'utilisateur';
$l['newpoints_maintenance_edituser_desc'] = 'Éditer les points d\'un utilisateur.';
$l['newpoints_invalid_user'] = 'Utilisateur invalide.';
$l['newpoints_edituser_points'] = 'Éditer les points';
$l['newpoints_edituser_points_desc'] = 'Entrez le nombre de points que l\'utilisateur sélectionné doit avoir.';
$l['newpoints_user_edited'] = 'L\'utilisateur sélectionné a été modifié avec succès.';
$l['newpoints_reconstruct_done'] = 'Les templates ont étés reconstruits';
$l['newpoints_reconstructed'] = 'Vous aveaz reconstruit les templates avec succès.';
$l['newpoints_reconstructconfirm'] = 'Êtes-vous sûr de vouloir reconstruire les templates ?';
$l['newpoints_resetconfirm'] = 'Êtes-vous sûr de vouloir réinitialiser l\'argent de tout le monde ?';

///////////////// Stats
$l['newpoints_stats'] = 'Statistiques';
$l['newpoints_stats_description'] = 'Voir les statistiques de votre forum.';
$l['newpoints_stats_lastdonations'] = 'Derniers dons';
$l['newpoints_error_gathering'] = 'Impossible d\'obtenir des données.';
$l['newpoints_stats_richest_users'] = 'Les utilisateur les plus riches';
$l['newpoints_stats_from'] = 'De la part de';
$l['newpoints_stats_to'] = 'À';
$l['newpoints_stats_date'] = 'Date';
$l['newpoints_stats_user'] = 'Utilisateur';
$l['newpoints_stats_points'] = 'Points';
$l['newpoints_stats_amount'] = 'Montant';

///////////////// Forum Rules
$l['newpoints_forumrules'] = 'Règles du forum';
$l['newpoints_forumrules_description'] = 'Gérer les règles du forum et des options.';
$l['newpoints_forumrules_add'] = 'Ajouter';
$l['newpoints_forumrules_add_description'] = 'Ajouter une nouvelle règle.';
$l['newpoints_forumrules_edit'] = 'Éditer';
$l['newpoints_forumrules_edit_description'] = 'Modifier une règle existante.';
$l['newpoints_forumrules_delete'] = 'Supprimer';
$l['newpoints_forumrules_title'] = 'Titre du forum';
$l['newpoints_forumrules_name'] = 'Nom de la règle';
$l['newpoints_forumrules_options'] = 'Options';
$l['newpoints_forumrules_none'] = 'Impossible de trouver des règles.';
$l['newpoints_forumrules_rules'] = 'Règles du forum';
$l['newpoints_forumrules_addrule'] = 'Ajouter une règle du forum';
$l['newpoints_forumrules_editrule'] = 'Éditer une règle du forum';
$l['newpoints_forumrules_forum'] = 'Forum';
$l['newpoints_forumrules_forum_desc'] = 'Sélectionnez le forum concerné par cette règle.';
$l['newpoints_forumrules_name_desc'] = 'Indiquez le nom de la règle.';
$l['newpoints_forumrules_desc'] = 'Description';
$l['newpoints_forumrules_desc_desc'] = 'Indiquez la description de la règle.';
$l['newpoints_forumrules_minview'] = 'Nombre minimum de points pour voir';
$l['newpoints_forumrules_minview_desc'] = 'Indiquez le minimum de points requis pour visualiser le forum sélectionné.';
$l['newpoints_forumrules_minpost'] = 'Nombre minimum de points pour poster';
$l['newpoints_forumrules_minpost_desc'] = 'Entrez le nombre minimum de points requis pour créer une nouvelle discussion ou une réponse dans le forum sélectionné.';
$l['newpoints_forumrules_rate'] = 'Taux de revenu';
$l['newpoints_forumrules_rate_desc'] = 'Indiquez le taux de revenu du forum sélectionné. La valeur par défaut est 1';
$l['newpoints_forumrules_added'] = 'Une nouvelle règle de forum a été ajouté avec succès.';
$l['newpoints_select_forum'] = 'Sélectionnez un forum';
$l['newpoints_forumrules_notice'] = 'Remarque : les forums sans règles ont un taux de revenu de 1 et n\'ont pas de points minimum requis pour voir ou poster.';
$l['newpoints_forumrules_invalid'] = 'Règle invalide.';
$l['newpoints_forumrules_edited'] = 'La règle sélectionnée a été modifiée avec succès';
$l['newpoints_forumrules_deleted'] = 'La règle sélectionnée a été supprimée avec succès';
$l['newpoints_forumrules_deleteconfirm'] = 'Etes-vous sûr de vouloir supprimer la règle sélectionnée ?';

///////////////// Group Rules
$l['newpoints_grouprules'] = 'Règles de groupe';
$l['newpoints_grouprules_description'] = 'Gérer les règles et les options de groupe.';
$l['newpoints_grouprules_add'] = 'Ajouter';
$l['newpoints_grouprules_add_description'] = 'Ajouter une nouvelle règle.';
$l['newpoints_grouprules_edit'] = 'Éditer';
$l['newpoints_grouprules_edit_description'] = 'Modifier une règle existante.';
$l['newpoints_grouprules_delete'] = 'Supprimer';
$l['newpoints_grouprules_title'] = 'Titre du groupe';
$l['newpoints_grouprules_name'] = 'Nom de la règle';
$l['newpoints_grouprules_options'] = 'Options';
$l['newpoints_grouprules_none'] = 'Impossible de trouver des règles.';
$l['newpoints_grouprules_rules'] = 'Règles de groupe';
$l['newpoints_grouprules_addrule'] = 'Ajouter une règles de groupe';
$l['newpoints_grouprules_editrule'] = 'Éditer une règles de groupe';
$l['newpoints_grouprules_group'] = 'Groupe';
$l['newpoints_grouprules_group_desc'] = 'Sélectionnez le groupe affecté par cette règle.';
$l['newpoints_grouprules_name_desc'] = 'Indiquez le nom de la règle.';
$l['newpoints_grouprules_desc'] = 'Description';
$l['newpoints_grouprules_desc_desc'] = 'Entrez la description de la règle.';
$l['newpoints_grouprules_rate'] = 'Taux de revenu';
$l['newpoints_grouprules_rate_desc'] = 'Entrez le taux de revenu pour le groupe sélectionné. La valeur par défaut est 1';
$l['newpoints_grouprules_added'] = 'Une nouvelle règle de groupe a été ajouté avec succès.';
$l['newpoints_select_group'] = 'Sélectionnez un groupe';
$l['newpoints_grouprules_notice'] = 'Remarque : les groupes sans règles ont un taux de revenu de 1 et ont n\'ont pas de paiements d\'automatiques.';
$l['newpoints_grouprules_invalid'] = 'Règle invalide.';
$l['newpoints_grouprules_edited'] = 'La règle sélectionnée a été modifiée avec succès';
$l['newpoints_grouprules_deleted'] = 'La règle sélectionnée a été supprimée avec succès';
$l['newpoints_grouprules_deleteconfirm'] = 'Etes-vous sûr de vouloir supprimer la règle sélectionnée ?';
$l['newpoints_grouprules_pointsearn'] = 'Points à verser';
$l['newpoints_grouprules_pointsearn_desc'] = 'Points accordés à ce groupe chaque X secondes (le nombre de secondes est défini dans l\'option ci-dessous).';
$l['newpoints_grouprules_period'] = 'À quelle fréquence ce groupe est-t\'il payé';
$l['newpoints_grouprules_period_desc'] = 'Nombre de secondes entre chaque paiement donné à tous les utilisateurs dont le groupe <strong>principal</strong> est celui-ci.';

///////////////// Upgrades
$l['newpoints_upgrades'] = 'Mises à jour';
$l['newpoints_upgrades_description'] = 'Mettez à jour NewPoints ici.';
$l['newpoints_upgrades_name'] = 'Nom';
$l['newpoints_upgrades_run'] = 'Lancer';
$l['newpoints_upgrades_confirm_run'] = 'tes-vous sûr de vouloir exécuter le fichier de mise à niveau sélectionné ?';
$l['newpoints_run'] = 'Lancer';
$l['newpoints_no_upgrades'] = 'Aucune mise à jour trouvée.';
$l['newpoints_upgrades_notice'] = 'Vous devriez sauvegarder votre base de données avant d\'exécuter un script de mise à jour.<br /><small>Exécutez seulement les fichiers de mise à jour que si vous êtes sûr de ce que vous faites.</small>';
$l['newpoints_upgrades_ran'] = 'Le script de mise à jour a été exécuté avec succès.';
$l['newpoints_upgrades_newversion'] = 'Nouvelle version';
