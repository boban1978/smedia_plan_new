
Ext.ns('Util');

Util.Array = {};
Util.Date = {};

Util.Array.remove = function(array, from, to) {
	var rest = array.slice((to || from) + 1 || array.length);
	array.length = from < 0 ? array.length + from : from;
	return array.push.apply(array, rest);
};

//converts commercial datetime string "yyyy-mm-dd hh:mm:ss" to JS date object (without time)
Util.Date.toDateObject = function(datetimeString) {
	var datestring = datetimeString.split(' ')[0];
	return new Date(parseInt(datestring.split('-')[0]), parseInt(datestring.split('-')[1]) - 1, parseInt(datestring.split('-')[2]));
};

//converts commercial datetime string "yyyy-mm-dd hh:mm:ss" to JS date object (with time)
Util.Date.toDateTimeObject = function(datetimeString) {
	var datestring = datetimeString.split(' ')[0];
	var timestring = datetimeString.split(' ')[1];
	return new Date(parseInt(datestring.split('-')[0]), parseInt(datestring.split('-')[1]) - 1, parseInt(datestring.split('-')[2]), parseInt(timestring.split(':')[0]), parseInt(timestring.split(':')[1]), (Ext.isDefined(timestring.split(':')[2]) ? parseInt(timestring.split(':')[2]) : '00'));
};

//converts JS datetime object to commercial datetime string "yyyy-mm-dd hh:mm"
Util.Date.toDateTimeString = function(datetimeObject) {
	var year = datetimeObject.getFullYear();
	var month = datetimeObject.getMonth() + 1;
	var day = datetimeObject.getDate();
	var hour = datetimeObject.getHours();
	var min = datetimeObject.getMinutes();
	var sec = datetimeObject.getSeconds();

	return year + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day) + ' ' + (hour < 10 ? '0' + hour : hour) + ':' + (min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
};

Util.Date.GetDayName = function(dayNumber) {
	if (dayNumber === 0) return 'Nedelja';
	if (dayNumber === 1) return 'Ponedeljak';
	if (dayNumber === 2) return 'Utorak';
	if (dayNumber === 3) return 'Sreda';
	if (dayNumber === 4) return 'Četvrtak';
	if (dayNumber === 5) return 'Petak';
	if (dayNumber === 6) return 'Subota';
};
