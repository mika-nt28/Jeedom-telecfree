$("#table_cmd_telec").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
$("#table_cmd_racoucis").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
$("#table_cmd_chaine").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
function addCmdToTable(_cmd) {
	if (!isset(_cmd)) {
        var _cmd = {};
	_cmd.type = 'action';
	_cmd.subType = 'other';	
	_cmd.template.dashboard = 'core::telecfreeChaine';	
	_cmd.template.mobile = 'core::telecfreeChaine';			
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
	var tr =$('<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">');
  	tr.append($('<td>')
		.append($('<div>')
			.append($('<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove">')))
		.append($('<div>')
			.append($('<i class="fa fa-arrows-v pull-left cursor bt_sortable">'))));
	tr.append($('<td>')
		.append($('<div>')
			.append($('<input type="hidden" class="cmdAttr form-control input-sm" data-l1key="id">'))
			.append($('<input class="cmdAttr form-control input-sm" data-l1key="name" value="' + init(_cmd.name) + '" placeholder="{{Name}}" title="Name">'))));
	tr.append($('<td>')
		.append($('<input class="cmdAttr form-control input-sm" data-l1key="logicalId" placeholder="{{Numero de chaine}}">'))
		.append($('<input type="hidden" class="cmdAttr" data-l1key="type" />'))
		.append($('<input type="hidden" class="cmdAttr" data-l1key="subType" />')));	
	tr.append($('<td>')
		.append($('<span>')
			.append('{{Historiser}}')
			.append($('<input type="checkbox" class="cmdAttr" data-size="mini" data-label-text="{{Historiser}}" data-l1key="isHistorized"/>')))
		.append($('</br>'))
		.append($('<span>')
			.append('{{Afficher}}')
			.append($('<input type="checkbox" class="cmdAttr" data-size="mini" data-label-text="{{Afficher}}" data-l1key="isVisible" checked/>'))));  
	var parmetre=$('<td>');
	if (is_numeric(_cmd.id)) {
		parmetre.append($('<a class="btn btn-default btn-xs cmdAction" data-action="test">')
			.append($('<i class="fa fa-rss">')
				.text('{{Tester}}')));
	}
	parmetre.append($('<a class="btn btn-default btn-xs cmdAction" data-action="configure">')
		.append($('<i class="fa fa-cogs">')));
	tr.append(parmetre);
	switch(_cmd.logicalId){
		case 'programmes':
		case 'disques':
		case 'musiques':
		case 'radios':
		case 'videos':
		case 'replay':
		case 'tv':
			$('#table_cmd_racoucis tbody').append(tr);
			$('#table_cmd_racoucis tbody tr:last').setValues(_cmd, '.cmdAttr');		
		break;
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
		case 'power':
		case 'powerstat':			
		case 'chaine':
		case 'vol_inc':
		case 'vol_dec':
		case 'prgm_inc':
		case 'prgm_dec':
		case 'home':
		case 'mute':
		case 'rec':
		case 'next':
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
			$('#table_cmd_telec tbody').append(tr);
			$('#table_cmd_telec tbody tr:last').setValues(_cmd, '.cmdAttr');
		break;
		default:
			$('#table_cmd_chaine tbody').append(tr);
			$('#table_cmd_chaine tbody tr:last').setValues(_cmd, '.cmdAttr');
		break;
	}
}
