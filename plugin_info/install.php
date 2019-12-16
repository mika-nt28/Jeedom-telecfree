<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function telecfree_install(){
}
function telecfree_update(){
	log::add('telecfree','debug','Lancement du script de mise à jour'); 
	foreach(eqLogic::byType('telecfree') as $eqLogic){
		foreach($eqLogic->getCmd(null,null,null,true) as $cmd){
			if($cmd->getName() == 'Chaine')
				$cmd->setLogicalId('chaine');
			if($cmd->getConfiguration('parameters','') != '' && $cmd->getConfiguration('parameters') != $cmd->getLogicalId())
				$cmd->setLogicalId($cmd->getConfiguration('parameters'));
			if($cmd->getLogicalId() == '987')
				$cmd->setLogicalId('programmes');
			if($cmd->getLogicalId() == '986')
				$cmd->setLogicalId('disques');
			if($cmd->getLogicalId() == '985')
				$cmd->setLogicalId('musiques');
			if($cmd->getLogicalId() == '984')
				$cmd->setLogicalId('radios');
			if($cmd->getLogicalId() == '983')
				$cmd->setLogicalId('videos');
			if($cmd->getLogicalId() == '982')
				$cmd->setLogicalId('replay');
			if($cmd->getLogicalId() == '981')
				$cmd->setLogicalId('tv');
			$cmd->save();
		}
		$eqLogic->save();
	}
	log::add('telecfree','debug','Fin du script de mise à jour');
}
function telecfree_remove(){
}
?>

