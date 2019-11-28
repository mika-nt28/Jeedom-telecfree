<?php
try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');
	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}
  switch(init('action')){
  case "getChaineLogo":
    $file="plugins/telecfree/core/template/images/chaines/".init('logicalId').".png";
				if(file_exists($file))
					$file
  break;
  default:
  	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
   break;
  }
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
