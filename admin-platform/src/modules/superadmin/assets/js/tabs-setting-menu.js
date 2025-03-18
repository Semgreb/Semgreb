// the other tab group is "admin/settings?group=info"
var parent = $('a[href*="admin/settings?group=update"]').parent(".panel-body");
parent.removeClass("panel-body");
parent.children( "a, hr" ).hide();
