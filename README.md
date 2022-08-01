# dnaid
projet de gestion des assemblés de juste.


le projet DNAID est une application client-serveur qui ressencera les différentes communautés de justes en Cote d'Ivoire et au déla.

le serveur developer en PHP abritera une base de données MySql

un premier client developper en HTML & CSS et JavaScript permettra de faire des requête. il sera livrer avec le serveur à terme


# configuration de developpement

COTÉ SERVEUR
_____________________________________________________________________________________________________________________
- Dans le dossier dnaid/assets/BDD vous trouverez le fichier de definition de la base de données


- Dans le fichier dnaid/technique/CONSTANTE.php vous devez definir les identifiant d'accès à votre SGBD MySql Serveur et les assigner aux constantes "BDD", "USER" et "MDP"


- Dans le fichier dnaid/dao/dao.php il faudra definir le chemin d'accès absolu au projet et l'assigner à la variable "$chemin_base"
PS la variable est declarer deux fois
* une fois tout en haut du fichier
* une seconde fois au debut de la fonction spl_autoload_register




COTÉ CLIENT 
_____________________________________________________________________________________________________________________

- Dans le fichier dnaid/CMS/assets/parts/menu-part.html il faudra definir l'url correct d'accès du projet selon la configuration de votre serveur sur les liens Assemblee, Groupe, Juste et Service


- Dans le fichier dnaid/CMS/assets/scripts/shared/session-manager.js il faudra definir l'url correct d'accès au projet selon la configuration de votre serveur et l'assigner à la constante "server"




# Fonctionnement et organisation

COTÉ SERVEUR
_____________________________________________________________________________________________________________________
Le projet etant developpé en achitecteur multi-couches, Nous avons:

- la couche dao qui comprend (dnaid/dao):
	* dnaid/dao/entites : les differentes class de gestion des tables de la base de données. elle sont chargées d'effectuer les requetes à la base de données

	* dnaid/dao/logs : les differents fichiers de logs

	* dnaid/dao/dao.php : l'interface d'accès à la couche dao pour les couches superieurs

- la couche metier (dnaid/metier):
	* dnaid/metier/metierSystem.php : le fichier principal de la couche metier
	PS plusieurs fichier metier différent pourront etre declarer en fonction des besoins de l'application à l'avenir

- la couche presentation (dnaid/API):
	* dnaid/API/ApiSystem : l'api principale du serveur

	* dnaid/API/ApiTest : fichier de test du serveur utiliser pendant la phase de developpement

- la couche technique (dnaid/technique):
	* dnaid/technique/CONSTANTE.php : regroupe toutes les constantes qui seront utilisées sur l'ensembles des trois autres couches du serveur

	* dnaid/technique/JwtAuth.php : gère les tokens d'authentifications au serveur lorsqu'un client distant commence à faire des requête après s'être identifié via son login et son mot de passe

	* dnaid/technique/utilitaire.php: regroupe un ensemble de fonctions utilitaires utilisées dans l'ensembles des trois autre couches du serveur

- les fichiers additifs (dnaid/assets)
	* dnaid/assets/BDD/dnaid.sql: fichier de definition de la base de données

	* dnaid/assets/scripts: des fichiers bash permettant de simuler un comportement asynchrone au niveau de notre serveur php


COTÉ CLIENT
_____________________________________________________________________________________________________________________

Les vues (page) du projet se trouve dans le dossier dnaid/CMS. c'est un client distant entierement autonome vis à vis des autres couches du projet.
il est developpé en HTML, JavaScript et SASS (CSS).
Chaque vue est donc divisée en trois parties.

dans le dossier dnaid/CMS nous avons:

- dnaid/CMS/connexion.html : la page d'authenfication du client avant de pouvoir emettre des requêtes au serveurs
	
- dnaid/CMS/accueil.html : la page d'accueil après authentification

- dnaid/CMS/template.html : l'ossature de toutes les pages du projets. ce n'est pas une page en tant que telle. toute les autres pages à l'exception de connexion.html recopient cette page puis on y apporte les fonctionnalités specifiques à chaque page

- dnaid/pages: contient les differentes pages (vues) du client repartie selon les tables definies depuis la base de données

	* dnaid/pages/assemblee : les vues liers à la gestion des assemblées (ajout, modification, suppression, listing ...)
	* dnaid/pages/groupe : les vues liers à la gestion des groupes (ajout, modification, suppression, listing ...)
	* dnaid/pages/juste : les vues liers à la gestion des justes (ajout, modification, suppression, listing ...)
	* dnaid/pages/service : les vues liers à la gestion des services (ajout, modification, suppression, listing ...)

- dnaid/assets : ensembles de fichiers necessaires aux vues

	* dnaid/assets/data : fichier de données au format JSON 

	* dnaid/assets/fonts: fichier de polices d'ecriture

	* dnaid/assets/parts: fichier html contenant les parties qui se repetent sur chaque page (refactorisation du code html)

	* dnaid/assets/scripts: fichier de scripts JavaScript

	* dnaid/assets/styles: fichier CSS et SASS 







