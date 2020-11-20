<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function telecfree_install(){
}
function telecfree_update(){
	log::add('telecfree','debug','Lancement du script de mise à jour'); 
	foreach(eqLogic::byType('telecfree') as $eqLogic){
		$eqLogic->save();
	}
	log::add('telecfree','debug','Fin du script de mise à jour');
}
function telecfree_remove(){
}
?>

