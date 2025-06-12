var rdisps = {};

function rdisp(id,aria_popup_id)
{
    var obj = document.getElementById(id);
    if(rdisps[id] == undefined)
    {
        if(obj.style.display == undefined)
            rdisps[id] = [true, ""];
        else if(obj.style.display == "none")
            rdisps[id] = [false, "block"];
        else
            rdisps[id] = [true, obj.style.display];
    }
    if(rdisps[id][0])
    {
        obj.style.display = "none";
        if(typeof(aria_popup_id) != undefined)
            document.getElementById(aria_popup_id).setAttribute("aria-expanded", "false");
    }
    else
    {
        obj.style.display = rdisps[id][1];
        if(typeof(aria_popup_id) != undefined)
            document.getElementById(aria_popup_id).setAttribute("aria-expanded", "true");
    }
    rdisps[id][0] = !rdisps[id][0];
}

function redirect(event,elm)
{
    if(event.keyCode == 13)
        window.location = elm.value;
}

function showjs(id)
{
    document.getElementById(id).style.display = "initial";
}

var close_confirm = {};

function init_close_confirm()
{
    var forms = document.querySelectorAll('form');
    for (var i = 0; i < forms.length; i++)
    {
        var form = forms[i];
        close_confirm[i] = false;
        var fields = form.querySelectorAll('input, select, textarea');
        for (var j = 0; j < fields.length; j++)
        {
            var field = fields[j];
            field.addEventListener('change', function(event)
            {
                var formIndex = Array.prototype.indexOf.call(forms, event.target.form);
                close_confirm[formIndex] = true;
            });
        }
        form.addEventListener('submit', function(event)
        {
            var formIndex = Array.prototype.indexOf.call(forms, event.target);
            close_confirm[formIndex] = false;
        });
    }
    window.onbeforeunload = function(e)
    {
        for (var i = 0; i < forms.length; i++)
        {
            if (close_confirm[i])
            {
                e = e || window.event;
                if (e) e.returnValue = "Sure?";
                    return "Sure?";
            }
        }
    };
}
