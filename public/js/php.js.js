/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function explode (delimiter, string, limit) {
// Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.
//
// version: 909.322
// discuss at: http://phpjs.org/functions/explode
// +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: kenneth
// +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: d3x
// +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// *     example 1: explode(' ', 'Kevin van Zonneveld');
// *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
// *     example 2: explode('=', 'a=bc=d', 2);
// *     returns 2: ['a', 'bc=d']

    var emptyArray = { 0: '' };

// third argument is not required
    if ( arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined' ) {
	return null;
    }

    if ( delimiter === '' || delimiter === false ||  delimiter === null )  {
	return false;
    }

    if ( typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object' ) {
	return emptyArray;
    }

    if ( delimiter === true ) {
	delimiter = '1';
    }

    if (!limit) {
	return string.toString().split(delimiter.toString());
    } else {
    // support for limit argument
	var splitted = string.toString().split(delimiter.toString());
	var partA = splitted.splice(0, limit - 1);
	var partB = splitted.join(delimiter.toString());
	partA.push(partB);
	return partA;
    }
}

function strpos (haystack, needle, offset) {
// Finds position of first occurrence of a string within another
//
// version: 909.322
// discuss at: http://phpjs.org/functions/strpos
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +   improved by: Onno Marsman
// +   bugfixed by: Daniel Esteban
// *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
// *     returns 1: 14
    var i = (haystack+'').indexOf(needle, (offset ? offset : 0));
    return i === -1 ? false : i;
}

function substr (f_string, f_start, f_length) {
// Returns part of a string
//
// version: 909.322
// discuss at: http://phpjs.org/functions/substr
// +     original by: Martijn Wieringa
// +     bugfixed by: T.Wild
// +      tweaked by: Onno Marsman
// *       example 1: substr('abcdef', 0, -1);
// *       returns 1: 'abcde'
// *       example 2: substr(2, 0, -6);
// *       returns 2: ''
    f_string += '';

    if (f_start < 0) {
	f_start += f_string.length;
    }

    if (f_length == undefined) {
	f_length = f_string.length;
    } else if (f_length < 0){
	f_length += f_string.length;
    } else {
	f_length += f_start;
    }

    if (f_length < f_start) {
	f_length = f_start;
    }

    return f_string.substring(f_start, f_length);
}

function split (delimiter, string) {
// Split string into array by regular expression
//
// version: 909.322
// discuss at: http://phpjs.org/functions/split
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// -    depends on: explode
// *     example 1: split(' ', 'Kevin van Zonneveld');
// *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    return this.explode( delimiter, string );
}

function chr (codePt) {
// Converts a codepoint number to a character
//
// version: 909.322
// discuss at: http://phpjs.org/functions/chr
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +   improved by: Brett Zamir (http://brett-zamir.me)
// *     example 1: chr(75);
// *     returns 1: 'K'
// *     example 1: chr(65536) === '\uD800\uDC00';
// *     returns 1: true
    if (codePt > 0xFFFF) { // Create a four-byte string (length 2) since this code point is high
    //   enough for the UTF-16 encoding (JavaScript internal use), to
    //   require representation with two surrogates (reserved non-characters
    //   used for building other characters; the first is "high" and the next "low")
        codePt -= 0x10000;
        return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
    } else {
        return String.fromCharCode(codePt);
}
}