<?php
	if (!isConnect('admin')) {
		throw new Exception('{{401 - Accès non autorisé}}');
	}
	$plugin = plugin::byId('telecfree');
	sendVarToJS('eqType', $plugin->getId());
	$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">    
   	<div class="col-xs-12 eqLogicThumbnailDisplay">
  		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
      			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
      				<i class="fas fa-wrench"></i>
    				<br>
    				<span>{{Configuration}}</span>
  			</div>
  		</div>
  		<legend><i class="fas fa-table"></i> {{Mes templates}}</legend>
	   	<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
    		<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
		?>
		</div>
	</div>
 
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<!--a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure">
					<i class="fa fa-cogs"></i>
					 {{Configuration avancée}}
				</a>
				<a class="btn btn-default btn-sm eqLogicAction" data-action="copy">
					<i class="fas fa-copy"></i>
					 {{Dupliquer}}
				</a-->
				<a class="btn btn-sm btn-success eqLogicAction" data-action="save">
					<i class="fas fa-check-circle"></i>
					 {{Sauvegarder}}
				</a>
				<a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove">
					<i class="fas fa-minus-circle"></i>
					 {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
    			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left"></i>
				</a>
			</li>
    			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-tachometer"></i> 
					{{Equipement}}
				</a>
			</li>
    			<li role="presentation">
				<a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fa fa-list-alt"></i> 
					{{Commandes}}
				</a>
			</li>
  		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
      				<br/>
    				<form class="form-horizontal">
					<fieldset>
						<div class="form-group ">
                					<label class="col-sm-3 control-label">{{Nom}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                       						<input type="text" class="eqLogicAttr form-control" data-l1key="logicalId" style="display : none;" />
                    						<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
							</div>
						</div>
						<div class="form-group">
                					<label class="col-sm-3 control-label" >{{Objet parent}}</label>
							<div class="col-sm-3">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
										foreach (jeeObject::all() as $object) 
											echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
                					<label class="col-sm-3 control-label">{{Catégorie}}</label>
							<div class="col-sm-9">
								<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
										echo '</label>';
									}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<label class="checkbox-inline">
									<input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>
									{{Activer}}
								</label>
								<label class="checkbox-inline">
									<input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
									{{Visible}}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">{{Adresse IP du Freebox Player}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ipplayer" placeholder="{{Adresse IP du Freebox Player}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Code télécommande}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="codetelec" placeholder="{{Code telecommande}}"/>
							</div>
						</div>
						<div class="alert alert-info">
							{{Info : <br/>
							- Pour trouver votre code télécommande regardez dans les paramètres du Freebox Player<br/>
							- Depuis l'écran d'accueil --> Réglages --> Système --> Informations Freebox Player et Server --> Code télécommande réseau<br/>}}
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">	
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#telectab" aria-controls="home" role="tab" data-toggle="tab">
						<i class="fa fa-tachometer"></i> 
							{{Télécommande}}
						</a>
					</li>
					<li role="presentation">
						<a href="#racourcistab" aria-controls="profile" role="tab" data-toggle="tab">
							<i class="fa fa-list-alt"></i> 
							{{Racourcis}}
						</a>
					</li>
					<li role="presentation">
						<a href="#chainetab" aria-controls="profile" role="tab" data-toggle="tab">
							<i class="fa fa-list-alt"></i> 
							{{Chaine}}
						</a>
					</li>
				</ul>
				<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
					<div role="tabpanel" class="tab-pane active" id="telectab">
						<table id="table_cmd_telec" class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>{{Nom}}</th>
									<th>{{Action}}</th>
									<th>{{Parameter}}</th>
									<th>{{Avancée}}</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div role="tabpanel" class="tab-pane" id="racourcistab">	
						<table id="table_cmd_racoucis" class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>{{Nom}}</th>
									<th>{{Action}}</th>
									<th>{{Parameter}}</th>
									<th>{{Avancée}}</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div role="tabpanel" class="tab-pane" id="chainetab">	
						<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a><br/><br/>

						<br/><br/>
						<table id="table_cmd_chaine" class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>{{Nom}}</th>
									<th>{{Action}}</th>
									<th>{{Parameter}}</th>
									<th>{{Avancée}}</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<?php include_file('desktop', 'telecfree', 'js', 'telecfree'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
