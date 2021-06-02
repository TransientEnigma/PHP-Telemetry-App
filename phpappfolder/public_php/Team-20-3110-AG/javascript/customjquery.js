/**
 * customjquery.js
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

//the switches are bootstrap checkbox controls and need extra jquery to toggle them
$(function (){
    $("#switchToggle1").click(function(){
        if($("input[name='switchControl1']").val()==="off"){
            $("input[name='switchControl1']").attr("value","on")
        }
        else if($("input[name='switchControl1']").val()==="on"){
            $("input[name='switchControl1']").attr("value","off")
        }
    });

});
$(function (){
    $("#switchToggle2").click(function(){
        if($("input[name='switchControl2']").val()==="off"){
            $("input[name='switchControl2']").attr("value","on")
        }
        else if($("input[name='switchControl2']").val()==="on"){
            $("input[name='switchControl2']").attr("value","off")
        }
    });
});

$(function (){
    $("#switchToggle3").click(function(){
        if($("input[name='switchControl3']").val()==="off"){
            $("input[name='switchControl3']").attr("value","on")
        }
        else if($("input[name='switchControl3']").val()==="on"){
            $("input[name='switchControl3']").attr("value","off")
        }
    });

});
$(function (){
    $("#switchToggle4").click(function(){
        if($("input[name='switchControl4']").val()==="off"){
            $("input[name='switchControl4']").attr("value","on")
        }
        else if($("input[name='switchControl4']").val()==="on"){
            $("input[name='switchControl4']").attr("value","off")
        }
    });
});

//Adjust the temperature up and down on Device
$(function (){

    $("#tempUp").click(function(){
        let tempString = $("input[name='tempValue']").val();
        let tempNumber = parseFloat(tempString);
        if(tempNumber <= 150.0){
            tempNumber = tempNumber + 0.1
            tempNumber = tempNumber.toFixed(1)
            $("input[name='tempValue']").attr("value", tempNumber);
        }


    });
});

$(function (){

    $("#tempDn").click(function(){
        let tempString = $("input[name='tempValue']").val();
        let tempNumber = parseFloat(tempString);
        if(tempNumber >= -150.0){
            tempNumber = tempNumber - 0.1
            tempNumber = tempNumber.toFixed(1)
            $("input[name='tempValue']").attr("value", tempNumber);
        }

    });
});
