/**
 * telemetry.js
 *
 * Container for all the js needed
 * for m2m data functions
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */


function doAuto() {

    startScrollTimer()

    document.onkeyup = getKey;

    let min = document.getElementById('min');
    let max = document.getElementById('max');
    let int = document.getElementById('interval');

    let valid = false

    // if (parseFloat(max.value) > parseFloat(min.value)) {
    //     alert()
    // }
    // alert(min.value)
    // alert(max.value)
    // alert(int.value)
    if (min.value >= -99 && min.value <= 500 ) {
        if (max.value >= -99 && max.value <= 500) {
            if (parseFloat(max.value) > parseFloat(min.value)) {
                if (int.value >= 5 && int.value <= 100) {
                    valid = true
                }
            }
        }
    }

    if (valid === false){
        document.getElementById('message').innerHTML = "Please complete all entries correctly - there are errors"
        return
    }


    let oOutput = document.getElementById("content");
    let oHeading = document.getElementById("pagetop");
    int.value = int.value * 1000;


    function getKey(e) {
        // If the users hits 'esc', stop loopcode from running.
        if (e.keyCode === 27) {
            clearInterval(ajax);
            //window.clearInterval(interval);
            stopScrollTimer();
        }

    }

    let ajax = setInterval(function () {
        doAjax();

    }, int.value);

    if (int.value !== 0) {
    oHeading.innerHTML = "<br><h1>Simulation Started - Press Esc to stop</h1><br>";
    oOutput.innerHTML = "";

}
    doAjax();
    function doAjax() {

        let url = window.location.host + window.location.pathname;

        url  = url.substring(url.indexOf('/'), url.lastIndexOf('/'));

        let oData = new FormData();
        oData.append('min_temp', min.value);
        oData.append('max_temp', max.value);
        oData.append('device_id', 'AG1');

        let oReq = new XMLHttpRequest();
        oReq.onload = function () {
            if (oReq.status === 200 && oReq.readyState === 4) {
                if (int.value !== 0) {

                    oOutput.innerHTML = oOutput.innerHTML + oReq.responseText + "<br>";
                }
            } else {
                oOutput.innerHTML = "Error " + oReq.status + " occurred . <br>";
            }
        };

        oReq.open("POST", url + "/processsenddata", true);
        oReq.send(oData);
    }

} // end doAuto

let elem = document.getElementById('content'); //YOUR DIV ELEMENT WHICH YOU WANT TO SCROLL
let scroll = 0; //SETTING INITIAL SCROLL TO 0
let timeout = 0;

function myTimer(){

    if(elem.scrollTop > scroll)
        scroll = elem.scrollTop;

    elem.scrollTo({ top: scroll, behavior: 'smooth' }) //SCROLL THE ELEMENT TO THE SCROLL VALUE WITH A SMOOTH BEHAVIOR
    scroll += 1; //AUTOINCREMENT SCROLL
}

function startScrollTimer(){

    window.setInterval(myTimer, 50)
}

function stopScrollTimer(){

    clearInterval(myTimer)

}


