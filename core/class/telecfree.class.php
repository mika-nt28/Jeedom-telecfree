<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
class telecfree extends eqLogic {
	public static function deamon_info() {
		$return = array();
		$return['log'] = 'telecfree';
		$return['launchable'] = 'ok';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('telecfree', 'pull');
		if (!is_object($cron))	
			return $return;
		if (!$cron->running())	
			return $return;
		$return['state'] = 'ok';
		return $return;
	}
	public static function deamon_start($_debug = false) {
		log::remove('telecfree');
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') 
			return;
		if ($deamon_info['state'] == 'ok') 
			return;
		$cron =cron::byClassAndFunction('telecfree', 'pull');
		if (!is_object($cron)) {
			$cron = new cron();
			$cron->setClass('telecfree');
			$cron->setFunction('pull');
			$cron->setEnable(1);
			$cron->setDeamon(1);
			$cron->setTimeout('999999');
			$cron->setSchedule('* * * * * *');
			$cron->save();
		}
		$cron->start();
		$cron->run();
	}
	public static function deamon_stop() {	
		$cron = cron::byClassAndFunction('telecfree', 'pull');
		if(is_object($cron))	
			$cron->remove();
	}	
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'telecfree_update';
		$return['progress_file'] = '/tmp/compilation_telecfree_in_progress';
		if (exec('dpkg -s netcat | grep -c "Status: install"') ==1)
				$return['state'] = 'ok';
		else
			$return['state'] = 'nok';
		return $return;
	}
	public static function dependancy_install() {
		if (file_exists('/tmp/compilation_telecfree_in_progress')) {
			return;
		}
		log::remove('telecfree_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('telecfree_update') . ' 2>&1 &';
		exec($cmd);
	}
	public static function pull() {
		while(true){
			foreach(eqLogic::byType('telecfree') as $telecfree){
				if($telecfree->getIsEnable()){
					$result=exec('nc -zv '.$telecfree->getConfiguration('ipplayer').' 7000 2>&1 | grep -E "open|succeeded" | wc -l');
					$telecfree->checkAndUpdateCmd('powerstat',$result);
					log::add('telecfree','debug','Etat du player freebox '.$telecfree->getConfiguration('ipplayer').' : '.$result);
				}
			}
			sleep(config::byKey('DemonSleep','telecfree'));
		}
		self::deamon_stop();
	}
	public function preUpdate() {
		if ($this->getConfiguration('codetelec') == '') {
			throw new Exception(__('Le code télécommande doit être renseigné. Vous pouvez trouver le code dans les paramètres de votre Freebox Player.',__FILE__));
		}		
		if ($this->getConfiguration('ipplayer') == '') {
			throw new Exception(__('L`adresse IP du player doit être renseignée, par défaut 192.168.1.2.',__FILE__));
		}
	}	
	public function postSave() {	
		$ActionPower=$this->AddCommande('Allumer-Eteindre','power',"action",'other','telecfreeBase');
		$InfoPower=$this->AddCommande('Statut Power','powerstat',"info",'binary','telecfreeBase');
		$ActionPower->setValue($InfoPower->getId());
		$ActionPower->save();
		$this->AddCommande('Volume +','vol_inc',"action",'other','telecfreeBase');
		$this->AddCommande('Volume -','vol_dec',"action",'other','telecfreeBase');
		$this->AddCommande('Chaine','chaine',"action",'slider');
		$this->AddCommande('Programme +','prgm_inc',"action",'other','telecfreeBase');
		$this->AddCommande('Programme -','prgm_dec',"action",'other','telecfreeBase');
		$this->AddCommande('Home','home',"action",'other','telecfreeBase');
		$this->AddCommande('Mute','mute',"action",'other','telecfreeBase');
		$this->AddCommande('Enregister','rec',"action",'other','telecfreeBase');
		$this->AddCommande('1','1',"action",'other','telecfreeBase');
		$this->AddCommande('2','2',"action",'other','telecfreeBase');
		$this->AddCommande('3','3',"action",'other','telecfreeBase');
		$this->AddCommande('4','4',"action",'other','telecfreeBase');
		$this->AddCommande('5','5',"action",'other','telecfreeBase');
		$this->AddCommande('6','6',"action",'other','telecfreeBase');
		$this->AddCommande('7','7',"action",'other','telecfreeBase');
		$this->AddCommande('8','8',"action",'other','telecfreeBase');
		$this->AddCommande('9','9',"action",'other','telecfreeBase');
		$this->AddCommande('0','0',"action",'other','telecfreeBase');
		$this->AddCommande('Precedent','prev',"action",'other','telecfreeBase');
		$this->AddCommande('Lecture','play',"action",'other','telecfreeBase');
		$this->AddCommande('Suivant','next',"action",'other','telecfreeBase');
		$this->AddCommande('Rouge','red',"action",'other','telecfreeBase');
		$this->AddCommande('Vert','green',"action",'other','telecfreeBase');
		$this->AddCommande('Bleu','blue',"action",'other','telecfreeBase');
		$this->AddCommande('Jaune','yellow',"action",'other','telecfreeBase');
		$this->AddCommande('Ok','ok',"action",'other','telecfreeBase');
		$this->AddCommande('Haut','up',"action",'other','telecfreeBase');
		$this->AddCommande('Bas','down',"action",'other','telecfreeBase');
		$this->AddCommande('Gauche','left',"action",'other','telecfreeBase');
		$this->AddCommande('Droite','right',"action",'other','telecfreeBase');
		
		$this->AddCommande('Télévision','tv',"action",'other','telecfreeRaccourci');
		$this->AddCommande('Replay','replay',"action",'other','telecfreeRaccourci');
		$this->AddCommande('Videos','videos',"action",'other','telecfreeRaccourci');
		$this->AddCommande('Radio','radios',"action",'other','telecfreeRaccourci');		
		$this->AddCommande('Musiques','musiques',"action",'other','telecfreeRaccourci');
		$this->AddCommande('Disques','disques',"action",'other','telecfreeRaccourci');
		$this->AddCommande('Programmes','programmes',"action",'other','telecfreeRaccourci');
	}
	public function AddCommande($Name,$_logicalId,$Type="info", $SubType='binary', $Template='default') {
		$Commande = $this->getCmd(null,$_logicalId);
		if (!is_object($Commande))
		{
			$VerifName=$Name;
			$Commande = new telecfreeCmd();
			$Commande->setId(null);
			$Commande->setLogicalId($_logicalId);
			$Commande->setEqLogic_id($this->getId());
			$Commande->setName($VerifName);
			$Commande->setType($Type);
			$Commande->setSubType($SubType);
		}
			$Commande->setTemplate('dashboard',$Template);
			$Commande->setTemplate('mobile', $Template);
			$Commande->save();
		return $Commande;
	}	
	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		$replace['#Chaines#']='';
		foreach ($this->getCmd(null, null, true) as $cmd) {
			if (isset($replace['#refresh_id#']) && $cmd->getId() == $replace['#refresh_id#']) 
				continue;
			$cmd_html = '';
			if ($br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1)
				$cmd_html .= '<br/>';
			$cmd_html .= $cmd->toHtml($_version);
			$br_before = 0;
			if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
				$cmd_html .= '<br/>';
				$br_before = 1;
			}
			switch($cmd->getLogicalId()){
				case 'chaine':
				break;
				case 'home':
				case 'power':
				case 'red':
				case 'up':
				case 'blue':
				case '1':
				case '2':
				case '3':
				case '4':
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				case '0':
				case 'vol_inc':
				case 'vol_dec':
				case 'prgm_inc':
				case 'prgm_dec':
				case 'mute':
				case 'left':
				case 'right':
				case 'ok':
				case 'green':
				case 'down':
				case 'yellow':
				case 'play':
				case 'prev':
				case 'next':
				case 'rec':
				case 'programmes':
				case 'disques':
				case 'musiques':
				case 'radios':
				case 'videos':
				case 'replay':
				case 'tv':
					$replace['#'.$cmd->getLogicalId().'#'] = $cmd_html;
				break;
				default:
					$replace['#Chaines#'] .= $cmd_html;
				break;
			}
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', jeedom::versionAlias($version), 'eqLogic', 'telecfree')));
	}
	public static function event() {
		$cmd =  telecfreeCmd::byId(init('id'));
	   
		if (!is_object($cmd)) {
			throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
		}
		$value = init('value');
       
		if ($cmd->getEqLogic()->getEqType_name() != 'telecfree') {
			throw new Exception(__('La cible de la commande telecfree n\'est pas un équipement de type telecfree', __FILE__));
		}
		$cmd->event($value);
		$cmd->setConfiguration('valeur', $value);
		log::add('telecfree','debug','set:'.$cmd->getName().' to '. $value);
		$cmd->save();
	}
}
class telecfreeCmd extends cmd {
	private function has_next(array $_array){
		return next($_array) !== false ?: key($_array) !== null;
	}
	private function SynchroMenu(){
		$this->sendAndWait('home',4000000);
		$this->sendAndWait('home',500000);
	}
	private function multiSend($Liste){
		foreach($Liste as $Cmd){
			if($this->has_next($Liste))
				$wait = 500000;
			else
				$wait = 0;
			$this->sendAndWait($Cmd,$wait);
		}
	}
	private function sendAndWait($Cmd,$wait=0){
		$url = 'http://'.$this->getEqLogic()->getConfiguration('ipplayer').'/pub/remote_control?code='.$this->getEqLogic()->getConfiguration('codetelec').'&key='.$Cmd;
		$result = file_get_contents($url);
		log::add('telecfree','debug',$this->getHumanName().' Send :'.$url . ' => '.$result);
		if($wait != 0)
			usleep($wait);	
	}
	public function execute($_options = null) {
		switch($this->getLogicalId()){
			case 'programmes':// => 987
				$this->SynchroMenu();
				$this->multiSend(array('green','down','ok','down','ok'));
			break;
			case 'disques':// => 986
				$this->SynchroMenu();
				$this->multiSend(array('right','right','right','right','ok'));
			break;
			case 'musiques':// => 985
				$this->SynchroMenu();
				$this->multiSend(array('right','right','ok'));
			break;
			case 'radios':// => 984
				$this->SynchroMenu();
				$this->multiSend(array('right','right','down','down','ok'));
			break;
			case 'videos':// => 983
				$this->SynchroMenu();
				$this->multiSend(array('right','ok'));
			break;
			case 'replay':// => 982
				$this->SynchroMenu();
				$this->multiSend(array('right','down','down','ok'));
			break;
			case 'tv':// => 981
				$this->SynchroMenu();
				$this->multiSend(array('ok'));
			break;
			case 'power':
				$this->sendAndWait('power',500000);
				$result=exec('nc -zv '.$this->getEqLogic()->getConfiguration('ipplayer').' 7000 2>&1 | grep -E "open|succeeded" | wc -l');
				$this->getEqLogic()->checkAndUpdateCmd('powerstat',$result);
			break;
			case 'chaine':
				$this->multiSend(str_split($_options['slider']));
			break;
			case 'vol_inc':
			case 'vol_dec':
			case 'prgm_inc':
			case 'prgm_dec':
			case 'home':
			case 'mute':
			case 'rec':
			case 'prev':
			case 'play':
			case 'red':
			case 'green':
			case 'blue':
			case 'yellow':
			case 'ok':
			case 'up':
			case 'down':
			case 'left':
			case 'right':
				$this->sendAndWait($this->getLogicalId());
			break;
			default:
				$this->multiSend(str_split($this->getLogicalId()));
			break;
		}
    	}
}
?>
