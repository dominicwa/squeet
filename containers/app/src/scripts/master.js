var imkp, info, kb;
var ns, fov;
var t1, max_id, cc = 0;

var drawMessages = function() {

	if (info == null) {
		imkp = '<em>type + enter</em> to squeet <sup>| <a href="about.php">about &raquo;</a></sup>';
		info = new Element('div', {
			'id':		'info',
			'html':		imkp
		}).inject($('box'));
	}
	
}

var drawKeyBox = function() {

	if (!$('keybox')) {
		kb = new Element('input', {'id': 'keybox'});
		kb.inject($('box'));
	}
	
	kb.setStyle('top', window.getSize().y/2 - kb.getSize().y/2);
	kb.setStyle('left', window.getSize().x/2 - kb.getSize().x/2);

}

var drawSqueets = function() {

	var nx = window.getSize().x*1.2/48; nx = nx.round();
	var ny = window.getSize().y*1.2/48; ny = ny.round();
	
	ns = $$('.squeet').length;
	fov = nx*ny;
	
	for (var i = ns; i < fov; i++) {
		var rc = $RGB($random(0,255), $random(0,255), $random(0,255));
		new Element('div', {
			'id':		'squeet_' + i,
			'class':	'squeet',
			'styles': {
				'background-color': rc
			}
		}).inject($('box'));
	}
	
}

var startSqueeting = function(q) {
	new Request.JSON({
		url: 'search.php',
		onSuccess: function(o, txt) {
			console.log(o);
			var obj = o.statuses;
			if (max_id > 0) {
				var aOpenSqueets = [];
				$$('.squeet').each(function(item, index) {
					if (item.get('html') == '') { // :empty selectors don't seem to work :(
						var openId = item.get('id').replace('squeet_', '');
						if (parseInt(openId) < fov) {
							aOpenSqueets[aOpenSqueets.length] = item;
						}
					}
				});
				//console.log(aOpenSqueets);
				obj.each(function(item, index) {
					//console.log(item);
					if (aOpenSqueets.length > 0) {
						if (!item.user.default_profile_image) {
							var rn = Math.floor(Math.random() * aOpenSqueets.length);
							var rs = aOpenSqueets[rn];
							rs.empty();
							var a = new Element('a', {
								'href': 'https://twitter.com/' + item.user.screen_name + '/status/' + item.id_str,
								'target': '_blank',
								'title': '<span style="text-decoration: underline;">' + item.user.name + '</span>: ' + item.text.replace(new RegExp(q,'i'), '<strong>' + q + '</strong>')
							}).inject(rs);
							var i = new Element('img', {
								'src':		item.user.profile_image_url,
								'alt':		item.user.name + '\'s twitter profile image',
								'styles': {
									'opacity':	0
								}
							}).inject(a);
							var dfx = new Fx.Morph(i, {duration: 1000});
							dfx.start.delay(2000, dfx, {
								'opacity': [0, 1],
								'onComplete': function() {
									if (max_id > 0) {
										info.setStyle('visibility', 'hidden');
									}
								}
							});
							aOpenSqueets.erase(rs);
							new Tips(a);
						}
					} else {
						info.setStyle('visibility', 'visible');
						info.set('html', 'screen full');
					}
				});
				if (obj.length == 0) {
					$clear(t1);
					info.setStyle('visibility', 'visible');
					info.set('html', 'no more tweets available for "' + q + '"');
				} else {
					max_id = obj[obj.length-1].id_str - 1;
				}
			}
			cc++;
		}
	}).get({'q': q, 'p': max_id, 'c': cc});
}

window.addEvent('domready', function() {

	drawMessages();
	drawKeyBox();
	drawSqueets();
	
	document.addEvent('keydown', function(e) {
		switch (e.code) {
			case 27: // esc
				kb.blur();
				kb.setStyle('visibility', 'hidden');
				break;
			case 13: // enter
				if (t1 != null) {$clear(t1); cc = 0;}
				$$('.squeet').each(function(item, index) {
					item.empty();
				});
				if (kb.get('value') == '') {
					max_id = 0;
					info.set('html', imkp);
				} else {
					max_id = 1;
					info.set('html', 'searching twitter... please wait...');
					t1 = startSqueeting.periodical(5000, null, kb.get('value'));
					startSqueeting(kb.get('value'));					
				}
				info.setStyle('visibility', 'visible');
				kb.set('value', '');
				kb.setStyle('visibility', 'hidden');
				break;
			default:
				if (!e.control && !e.alt &&
					!(e.code >= 112 && e.code <= 123)) {
					kb.setStyle('visibility', 'visible');
					kb.focus();
					drawKeyBox();
				}
				break;
		}
	});

});

window.addEvent('resize', function() {
								   
	drawMessages();
	drawKeyBox();
	drawSqueets();
	
});