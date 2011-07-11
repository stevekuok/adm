function findPos(obj) {
    var curleft = curtop = 0;

    if (obj.offsetParent) {
        do {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);
    }
    return [curleft,curtop];
}

function getXMLHttp_Events() {
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

function ajaxGet_Events(_uri, _lnkCode, _action) {
    var lnkCode = substr(_lnkCode, strpos(_lnkCode, "_") + 1, _lnkCode.legnth);
    if (_uri != '' & _lnkCode != ''){
        switch (_action) {
            case "getEvent":
                var url = _uri + '/hr/events/getevents';
                var pars = 'lnkCode=' + lnkCode;
                break;

            case "getAction":
                alert(_lnkCode);
                break;

            case "getApproved":
                var url = _uri + '/hr/events/getapproved';
                var pars = 'lnkCode=' + lnkCode;
                break;
        }

        var xmlHttp = getXMLHttp_Events();
        xmlHttp.onreadystatechange = function() {
            if(xmlHttp.readyState == 4) {
                if ( xmlHttp.status == 200 ) {
                    var obj = document.getElementById(_lnkCode);

                    return display_Events(HandleResponse_Events(xmlHttp.responseText), findPos(obj), _action);
                } else {
                    return display_Events("Sending data error!");
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

function display_Events(_dispValue, _posXY, _action) {
//alert(_dispValue);
    if (substr(_dispValue,0, 21) == '<!DOCTYPE HTML PUBLIC') {
        var url = '/adm/hr/auth/logout';
        window.location = url;
    } else {
        switch (_action) {
            case "getEvent":
                var posX = _posXY[0];
                var posY = _posXY[1];
                if ( $('#approveddetails').is(':visible')) {
                    $('#approveddetails').hide("slow");
                }
                document.getElementById('appdetails').innerHTML = _dispValue;

                //var detailWidth = document.getElementById('appdetails').clientWidth;
                //var detailHeight = document.getElementById('appdetails').clientHeight - 55;
//alert(detailHeight);
                document.getElementById('appdetails').style.marginLeft = (posX - 430) + "px";
                document.getElementById('appdetails').style.marginTop = (posY - 148) + "px";
                $('#appdetails').show("slow");
                $('#eventsclose').click(function() {
                    $('#appdetails').hide("slow");
                });
                break;

            case "getAction":

                break;

            case "getApproved":
                var posX = _posXY[0];
                var posY = _posXY[1];
                if ( $('#appdetails').is(':visible')) {
                    $('#appdetails').hide("slow");
                }
                document.getElementById('approveddetails').innerHTML = _dispValue;

                //var detailWidth = document.getElementById('approveddetails').clientWidth;
                //var detailHeight = document.getElementById('approveddetails').clientHeight - 115;
//alert(detailWidth);
                document.getElementById('approveddetails').style.marginLeft = (posX - 430) + "px";
                document.getElementById('approveddetails').style.marginTop = (posY - 160) + "px";
                $('#approveddetails').show("slow");
                $('#approvedclose').click(function() {
                    $('#approveddetails').hide("slow");
                });
                break;
        }
    }
}

function HandleResponse_Events(response) {
    return(response);
}

function getAction(_actId) {
    //var dispValue;

    //dispValue = "<div id='action'><input type='checkbox'></div>";
    //document.getElementById(_actId).innerHTML = dispValue;
    //document.getElementById(substr(_actId,4, _actId.length)).innerHTML += "<div >";
}

var blnReturn = true;
