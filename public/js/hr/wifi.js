function getXMLHttp_Wifi() {
    var xmlHttp

    try {
//Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch(e) {
//Internet Explorer
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                alert("Your browser does not support AJAX!")
                return false;
            }
        }
    }
    return xmlHttp;
}

function ajaxGet_Wifi(_uri) {
    var _username = document.getElementById('username').value;
    var _password = document.getElementById("password").value;
    var _verifypassword = document.getElementById('verifypassword').value;
    if (_username != '' & _password != ''){
        if (_password == _verifypassword){
            var xmlHttp = getXMLHttp_Wifi();
            var url = _uri + '/hr/wifi/chpassword';
            var pars = 'userName=' + _username + '&userPassword=' + _password;

            xmlHttp.onreadystatechange = function() {
                if(xmlHttp.readyState == 4) {
                    if ( xmlHttp.status == 200 ) {
                        return display_Wifi(HandleResponse_Wifi(xmlHttp.responseText));
                    } else {
                        return display_Wifi("Sending data error!");
                    }
                }
            }
            xmlHttp.open("POST", url, true);
            xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            xmlHttp.setRequestHeader("Content-length", pars.length);
            xmlHttp.setRequestHeader("Connection", "close");
            xmlHttp.send(pars);
        }
    }
}

function display_Wifi(_dispValue) {
    $('#back').show();
    switch (_dispValue){
        case 'Succeeded':
            document.getElementById('confirmMesg').innerHTML = '<h2>Congratulations!</h2>';
            $('#back').hide();
            break;

        default:
            document.getElementById('confirmMesg').innerHTML = '<h2>Failed!</h2>' + _dispValue;
            break;
    }
}



function HandleResponse_Wifi(response) {
    return(response);
}

var blnReturn = true;
