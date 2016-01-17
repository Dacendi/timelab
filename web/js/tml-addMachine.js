/**
 * Created by dacendi on 05/01/16.
 */

function getMachineCode($, machineTitleField, firstWord){
    if( machineTitleField.value !== "" ) { //when the title isn't set, do nothing
        $.post(my_ajax_obj.ajax_url, {          //POST request
            _ajax_nonce: my_ajax_obj.nonce,     //nonce
            action: "machine_name_check",        //action
            title: machineTitleField.value,                 //data
            firstWordOnly:  firstWord

        }, function(data) {                     //callback
            document.getElementById("machineid").value = data ;              //insert server response
        });
    }
    else {
        document.getElementById("machineid").value = "" ;
    }
}

jQuery(document).ready(function($) {            //wrapper

    var fWordOnly = "off";

    $("#machineidfw").click(function()
    {
        var checkedBox = $(this);
        if (checkedBox.is(":checked"))
            fWordOnly = "on";
        else
            fWordOnly = "off";

        getMachineCode($, document.getElementById("machinetitle"), fWordOnly);
    });

    var codeforce = "off";
    $("#machineidfi").click(function()
    {
        checkBoxFc = $(this);
        if (checkBoxFc.is(':checked'))
        {
            document.getElementById("machineid").readOnly = false;
            codeforce = "on";
        }
        else
        {
            document.getElementById("machineid").readOnly = true;
            codeforce = "off";
            getMachineCode($, document.getElementById("machinetitle"), fWordOnly);
        }
    });

    $("#machinetitle").blur(function() {         //event
        if(codeforce === "off")
            getMachineCode($, this, fWordOnly);
    });
});