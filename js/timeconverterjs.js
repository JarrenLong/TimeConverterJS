function msBetween1970and2000() {
	return new Date( 2000, 1, 1, 0, 0, 0, 0 ).getTime() - new Date( 1970, 1, 1, 0, 0, 0, 0 ).getTime();
}
function dateToMilliSecondsSince1970( date ) {
	return date.getTime();
}
function milliSecondsSince1970ToDate( interval ) {
	return new Date( interval )
}
function dateToSecondsSince1970( date ) {
	return date.getTime() / 1000;
}
function secondsSince1970ToDate( interval ) {
	return new Date( interval * 1000 );
}
function dateToMilliSecondsSince2000( date ) {
	return date.getTime() - msBetween1970and2000();
}
function milliSecondsSince2000ToDate( interval ) {
	return new Date( interval + msBetween1970and2000() );
}
function dateToSecondsSince2000( date ) {
	return ( date.getTime() - msBetween1970and2000() ) / 1000;
}
function secondsSince2000ToDate( interval ) {
	return new Date( interval * 1000 + msBetween1970and2000() );
}

function isValidDate( date ) {
	return ( !( Object.prototype.toString.call(date) !== "[object Date]" ) && !isNaN( date.getTime() ) );
}
function replicate(strOrChar, times) {
	var buf = "";
	
	for(var i=0;i<times;i++)
		buf += strOrChar;
		
	return buf;
}

function formatDate(date, fmt) {
	var buf = "";
	var i = 0, c = 0;
	var skip = false;
	
	for( i; i < fmt.length; i++ ) {
		c = 0;
		// Peek ahead to find # consecutive letters matching this letter, if any
		while( fmt[i + c] == fmt[i] )
			c++;
		
		// TODO: Print escaped single quotes
		// Skip anything that's single-quoted and just print it (don't print the quotes unless escaped)
		if(skip) {			 
			if( fmt[i] == "'" && fmt[i - 1] != '\\' )
				skip = false;
			else
				buf += replicate(fmt[i], c);
			
			i += c - 1;
			continue;
		}
		
		switch( fmt[i] ) {
			case "\\": { // Print next character (escape)
				buf += fmt[i + 1]
				i++;
			} break;
			case "'": { // Skip anything that's single-quoted and just print it (don't print the quotes unless escaped)
				skip = ( fmt[i - 1] != '\\' );
				if(!skip)
					buf += fmt[i];
			} break;
			case 'y': { // Year
				if( c == 2 ) // yy = 2-digit year, leading 0 if year < 10
					buf += date.getFullYear().toString().substring( 2, 4 );
				else if ( c == 4 ) // yyyy = 4-digit year
					buf += date.getFullYear();
				else 
					buf += replicate(fmt[i], c);
			} break; 
			case 'm': { // Month
				switch(c) {
				case 1:// m = 2 digit month, no leading 0 (1-12)
					buf += date.getMonth() + 1;
				break;
				case 2:// mm = 2 digit month, leading 0
					var m = date.getMonth() + 1;
					if(m < 10) buf += "0";
						buf += m;
				break;
				case 3:// mmm = 3-letter abbr.
					buf += DateFormat.Months[date.getMonth()].substring(0, 3);
				break;
				case 4:// mmmm = Month full name
					buf += DateFormat.Months[date.getMonth()];
				break;
				default:
					buf += replicate(fmt[i], c);
				break;
				}
			} break;
			case 'd': { // Day
				switch(c) {
				case 1:// d = 2 digit day, no leading 0
					buf += date.getDate();
				break;
				case 2:// dd = 2 digit day, leading 0
					var m = date.getDate();
					if(m < 10) buf += "0";
						buf += m;
				break;
				case 3:// ddd = 3-letter abbr.
					buf += DateFormat.Days[date.getDay()].substring(0, 3);
				break;
				case 4:// dddd = Days full name
					buf += DateFormat.Days[date.getDay()];
				break;
				default:
					buf += replicate(fmt[i], c);
				break;
				}
			} break;
			case 'h': { // 12-Hours
				if( c == 1 ) // h = No leading 0
					buf += date.getHours() % 12 + 1;
				else if ( c == 2 ) { // hh = Leading 0
					var y = date.getHours() % 12 + 1;
					if(y < 10) buf += "0";
					buf += y;
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 'H': { // 24-Hours
				// H = No leading 0, HH = Leading 0
				if( c == 1 ) // H = No leading 0
					buf += date.getHours() + 1;
				else if ( c == 2 ) { // HH = Leading 0
					var y = date.getHours() + 1;
					if(y < 10) buf += "0";
					buf += y;
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 'M': { // Minutes
				// M = No leading 0, MM = Leading 0
				if( c == 1 ) // M = No leading 0
					buf += date.getMinutes();
				else if ( c == 2 ) { // MM = Leading 0
					var y = date.getMinutes();
					if(y < 10) buf += "0";
					buf += y;
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 's': { // Seconds
				if( c == 1 ) // s = No leading 0
					buf += date.getSeconds();
				else if ( c == 2 ) { // ss = Leading 0
					var y = date.getSeconds();
					if(y < 10) buf += "0";
					buf += y;
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 'l': { // Milliseconds, 3 digits
				var m = date.getMilliseconds();
				if(m < 10)
					buf += "00";
				else if(m < 100)
					buf += "0";
				buf += m;
			} break;
			case 'L': { // Milliseconds, 2 digits
				var m = date.getMilliseconds() / 10;
				if(m < 10)
					buf += "0";
				buf += m;
			} break;
			case 't': { // Lowercase time marker string (a, p, am, or pm)
				if( c == 1 ) // t = a | p
					if ( date.getHours() < 12 )
						buf += "a";
					else
						buf += "p";
				else if ( c == 2 ) { // tt = am | pm
					if ( date.getHours() < 12 )
						buf += "am";
					else
						buf += "pm";
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 'T': { // Uppercase time marker string (A, P, AM, or PM)
				if( c == 1 ) // T = A | P
					if ( date.getHours() < 12 )
						buf += "A";
					else
						buf += "P";
				else if ( c == 2 ) { // TT = AM | PM
					if ( date.getHours() < 12 )
						buf += "AM";
					else
						buf += "PM";
				} else 
					buf += replicate(fmt[i], c);
			} break;
			case 'o': { // GMT/UTC timezone offset (-0500, +0230, etc)
				var m = date.getTimezoneOffset();
				
				if(m < 0)
					buf += "-";
				else
					buf += "+";
				
				if (m > -10 && m < 10) buf += "000";
				else if (m > -100 && m < 100) buf += "00";
				else if (m > -1000 && m < 1000) buf += "0";
				
				buf += Math.abs(m);
			} break;
			case 'Z': // TODO: US Timezone abbr. (EST, PST, MDT, etc)
			default: { // Default: just print what we found
				buf += replicate(fmt[i], c);
			} break;
		}
		
		i += c - 1; // Skip over consecutive formatters, 1 - to account for loop counter
	}
	
	return buf;
}

var DateFormat = {
	standard:"ddd mmm d yyyy HH:MM:ss", // Sat Jun 9 2007 17:46:21
	shortDate:"m/d/yy", // 6/9/07
	mediumDate:"mmm d, yyyy", // Jun 9, 2007
	longDate:"mmmm d, yyyy", // June 9, 2007
	fullDate:"dddd, mmmm d, yyyy", // Saturday, June 9, 2007
	shortTime:"h:MM TT", // 5:46 PM
	mediumTime:"h:MM:ss TT", // 5:46:21 PM
	longTime:"h:MM:ss TT Z", // 5:46:21 PM EST
	isoDate:"yyyy-mm-dd", // 2007-06-09
	isoTime:"HH:MM:ss", // 17:46:21
	isoDateTime:"yyyy-mm-dd'T'HH:MM:ss", // 2007-06-09T17:46:21
	isoFullDateTime:"yyyy-mm-dd'T':HH:MM:ss.lo", // 2007-06-09T17:46:21.431-0500
	
	Days: [ "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" ],
	Months: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
	USATimezones: [ "Pacific", "Mountain", "Centran", "Eastern" ], // Standard Time
};
var dateToTimestamp = function( currentDateTime ) {
	if( currentDateTime ) {
	  $('#unix_seconds').text( dateToSecondsSince1970( currentDateTime ) );
	  $('#epoch_seconds').text( dateToSecondsSince2000( currentDateTime ) );
	  $('#unix_milliseconds').text( dateToMilliSecondsSince1970( currentDateTime ) );
	  $('#epoch_milliseconds').text( dateToMilliSecondsSince2000( currentDateTime ) );
	}
};
var timestampToDate = function() {
	var str = $( this ).val();
	if( !str || !str.length )
		str = "?";

	var interval = parseInt( str );
	var buf = new Date();
	var format = DateFormat.isoFullDateTime;
	
	$('#date_unix_seconds').text( !isValidDate( ( buf = secondsSince1970ToDate( interval ) ) ) ? "?" : formatDate( buf, format ) );
	$('#date_epoch_seconds').text( !isValidDate( ( buf = secondsSince2000ToDate( interval ) ) ) ? "?" : formatDate( buf, format ) );
	$('#date_unix_milliseconds').text( !isValidDate( ( buf = milliSecondsSince1970ToDate( interval ) ) ) ? "?" : formatDate( buf, format ) );
	$('#date_epoch_milliseconds').text( !isValidDate( ( buf = milliSecondsSince2000ToDate( interval ) ) ) ? "?" : formatDate( buf, format ) );
};

$(function() {
	$('#input_time').datetimepicker({
		onChangeDateTime:dateToTimestamp,
		onShow:dateToTimestamp,
		mask:'yyyy/mm/dd HH:MM',
		closeOnDateSelect: true,
		closeOnTimeSelect: true
	}).blur();
	$('#input_num').change( timestampToDate ).keyup( timestampToDate );
});
