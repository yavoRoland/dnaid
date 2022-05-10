# dnaid
projet de gestion des assemblés de juste.


le projet DNAID est une application client-serveur qui ressencera les différentes communautés de justes en Cote d'Ivoire et au déla.

le serveur developer en PHP abritera une base de données MySql

un premier client developper en HTML & CSS et JavaScript permettra de  faire sera livrer avec le serveur à terme


# configuration de developpement
- Dans le dossier dnaid/assets/BDD vous trouverez le fichier de definition de la base de données

- Dans le fichier dnaid/technique/CONSTANTE.php vous devez definir les identifiant d'accès à votre SGBD MySql Serveur

- Dans le fichier dnaid/dao/dao.php il faudra definir le chemin d'accès absolu au projet et l'assigner à la variable $chemin_base
PS la variable est declarer deux fois
* une fois tout en haut du fichier
* une seconde fois au debut de la fonction spl_autoload_register


# Fonctionnement

Le projet etant developpé en achitecteur multi-couches, Nous avons:

- la couche dao qui comprend:
	* dnaid/dao/entites : les differentes class de gestion des tables de la base de données. elle sont chargées d'effectuer les requetes à la base de données

	* dnaid/dao/logs : les differents fichiers de logs

	* dnaid/dao/dao.php : l'interface d'accès à la couche dao pour les couches superieurs

- la couche metier:
	* dnaid/metier/metierSystem.php : le fichier principal de la couche metier
	PS plusieurs fichier metier différent pourront etre declarer en fonction des besoins de l'application à l'avenir

- la couche presentation (dnaid/API):
	* dnaid/API/ApiSystem : l'api principale du serveur

	* dnaid/API/ApiTest : fichier de test du serveur utiliser pendant la phase de developpement