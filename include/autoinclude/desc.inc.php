<?php
$GLOBALS['DESC']= array();
$GLOBALS['DESC']['users'] = array(
	'login' => array(
		'lib'=>'Login',
		'type'=>'text',
		),
	'pass' => array(
		'lib'=>'Mot de passe',
		'type'=>'password',
		),
	'name' => array(
		'main'=>true,
		'lib'=>'Nom complet',
		'type'=>'text',
		'help'=>"Nom de la personne qui utilisera ce login",
		),
	'admin' => array(
		'lib'=>'Est administrateur',
		'type'=>'checkbox',
		'value'=>1,
		'default'=>0,
		'help'=>"Ce login est un administrateur (permet d'acc&egrave;der au backoffice')",
		),
	'specs' => array(
		'lib'=>'Admins',
		'lib_new'=>'Ajouter un nouvel admin',
		'lib_list'=>'#name (#login)',
		'help'=>"Administrateurs du site",
		'sort'=>array('login'=>'Login','name'=>'Nom complet'),
		),
);


$GLOBALS['DESC']['pages'] = array(
	'pub' => array(
		'lib'=>'Publier ce projet',
		'type'=>'checkbox',
		'value'=>'1',
		'default'=>'0',
		'help'=>"Si cette case n'est pas coch&eacute;e, le projet et son contenu n'est pas visible sur le site"
	),
	'titre' => array(
		'lib'=>'Titre',
		'type'=>'text',
		'help'=>'Titre de la page',
		),
	'uri' => array(
		'lib'=>'Uri',
		'type'=>'text',
		'help'=>'Uri de la page (exemple : ma-page.html)',
		),

	'texte' => array(
		'lib'=>'Ordrer',
		'type'=>'textarea',
		),


	'specs' => array(
		'docs'=>'true',
		'lib'=>'Pages',
		'lib_new'=>'Ajouter une nouvelle page',
		'lib_list'=>'#pub #titre</a> [<small><a href="/#uri">#uri</a></small>]',
		'help'=>"Pages du site : création, modification",
		'sort'=>array('titre'=>'Titre'),
		),
);



$GLOBALS['DESC']['rubriques'] = array(
	'pub' => array(
		'lib'=>'Publier cette rubrique',
		'type'=>'checkbox',
		'value'=>'1',
		'default'=>'0',
		'help'=>"Si cette case n'est pas coch&eacute;e, la rubrique et son contenu n'est pas visible sur le site"
	),
	'titre' => array(
		'lib'=>'Titre',
		'type'=>'text',
		),
	'ordre' => array(
		'lib'=>'Ordrer',
		'type'=>'text',
		),
	'specs' => array(
		'lib_field'=>'titre',
		'lib'=>'Rubriques',
		'lib_new'=>'Ajouter une nouvelle rubrique',
		'lib_list'=>'#pub #titre',
		'help'=>"Rubriques affichées dans la colonne de gauche",
		'ordre'=>'ordre ASC, id DESC',
		),
);




$GLOBALS['DESC']['projects'] = array(
	'pub' => array(
		'lib'=>'Publier ce projet',
		'type'=>'checkbox',
		'value'=>'1',
		'default'=>'0',
		'help'=>"Si cette case n'est pas coch&eacute;e, le projet et son contenu n'est pas visible sur le site"
	),
	'date' => array(
		'lib'=>'Date de publication',
		'type'=>'date',
		'default'=>time(),
		'help'=>"Permet de trier les projets sur la date"
		),
	'titre' => array(
		'lib'=>'Titre du projet',
		'type'=>'text',
		),
	'rubriques' => array(
		'lib'=>'Rubrique du projet',
		'type'=>'select(rubriques)',
		'options'=>array(
			'multiple'=>true,
			'links'=>true,
		),
		'default'=>-1,
		'help'=>"Rubrique dont d&eacute;pend le projet",
		),
	'accroche' => array(
		'lib'=>'Accroche',
		'type'=>'smalltextarea',
		'help'=>"texte court (3 lignes) destin&eacute; &agrave; &ecirc;tre affich&eacute; sur la page des projets"
		),
	'texte' => array(
		'lib'=>'Description',
		'type'=>'textarea',
		'help'=>"texte destin&eacute; &agrave; &ecirc;tre affich&eacute; dans la page d'un projet"
		),
	'lien' => array(
		'lib'=>'Lien externe (facultatif)',
		'type'=>'text',
		'help'=>"Lien externe qui sera affich&eacute; en bas &agrave; droite du projet",
		),
	'ordre' => array(
		'lib'=>'Ordre d\'affichage',
		'type'=>'text',
		'help'=>"Valeur numérique utilisée pour trier les projects",
		),

	'sticky' => array(
		'lib'=>'Projet mis en avant',
		'type'=>'checkbox',
		'value'=>'1',
		'default'=>'0',
		'help'=>"Si cette case est coch&eacute;e, le projet est en haut de la liste des projets"
	),
	'specs' => array(
		'docs'=>'true',
		'lib_field'=>'titre',
		'lib'=>'Projets',
		'lib_edit'=>'Editer ce projet',
		'lib_new'=>'Ajouter un nouveau projet',
		'lib_list'=>'#pub #titre',
		'help'=>"Administration des projets",
		'help2'=>"Uploader 2 PDF Maximun",
		'order'=>'ordre ASC, id DESC',
		'where'=>array('pub'=>1),
		),
);

$GLOBALS['DESC']['settings'] = array(

	'chemin_site' => array(
		'lib'=>'Chemin du site',
		'type'=>'text',
		'help'=>"Chemin coplet vers le répertoire www du site sur le serveur"
		),
	'url_site' => array(
		'lib'=>'Url du site',
		'type'=>'text',
		'help'=>"URL du site sur le serveur"
		),
	'menu' => array(
		'lib'=>'Menu du site',
		'type'=>'rawtextarea',
		'help'=>"Items composants le menu du site. Formzat : Libellé du lien => url "
		),
	'nom_site' => array(
		'lib'=>'Nom du site',
		'type'=>'text',
		'help'=>"Texte qui sera visible dans la barre de titre"
		),
	'desc_site' => array(
		'lib'=>'Description du site',
		'type'=>'rawtextarea',
		'help'=>"Texte qui sera visible dans la page des résultats de google"
		),
	'keywords' => array(
		'lib'=>'Mots clés du site',
		'type'=>'text',
		'help'=>"Mots clé qui permettent aux gens de trouver le site"
		),
	'admin_links' => array(
		'lib'=>'Afficher les liens d\'administration',
		'type'=>'checkbox',
		'help'=>"Afficher les liens d\'administration sur le site quand un administrateur est connecté"
		),
		
	'specs' => array(
		'lib_list'=>'#id',
		'lib_edit'=>'Editer les options',
		'lib'=>'Options',
		'unique'=>$GLOBALS['CUR_SETTINGS'],
		'help'=>"Permet de g&ecirc;rer divers param&ecirc;tres du site",
		),
);


function getSpec($w,$field) {
	if(isset($GLOBALS['DESC'][$w][$field])) {
		return $GLOBALS['DESC'][$w][$field];
	} else 
	if(isset($GLOBALS['DESC'][$w]['specs'][$field])) {
		return $GLOBALS['DESC'][$w]['specs'][$field];
	} else 	return false;
}
