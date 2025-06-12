var activeslide = 1;
var slideint;
var slidepause = false;

document.getElementById("slidershow").addEventListener("focusin", function()
{
    slidepause = false;
    clickpause();
});

document.getElementById("slidershow").addEventListener("focusout", function()
{
    slidepause = true;
    clickpause();
});

function slide(k = 1)
{
    $("#slide" + activeslide).attr("class", "slide noslide");
    activeslide += k;
    if (activeslide > slides) activeslide = 1;
    else if (activeslide < 1) activeslide = slides;
    $("#slide" + activeslide).attr("class", "slide activeslide");
}

function clickprev()
{
    if (!slidepause)
    {
        clearInterval(slideint);
        slideint = setInterval(slide, 13000);
    }
    slide(-1);
    $("#slide" + activeslide).focus();
}

function clicknext()
{
    if (!slidepause)
    {
        clearInterval(slideint);
        slideint = setInterval(slide, 13000);
    }
    slide();
    $("#slide" + activeslide).focus();
}

function clickpause()
{
    if (slidepause)
    {
        slideint = setInterval(slide, 13000);
        $("#slidepause").removeClass("slidepaused");
    }
    else
    {
        clearInterval(slideint);
        $("#slidepause").addClass("slidepaused");
    }
    slidepause = !slidepause;
}

$(function()
{
    $("#slider").attr("style", "");
    slideint = setInterval(slide, 13000);
    var minh = 0;
    var i = 1;
    while (i <= slides)
    {
        var h = $("#slide" + i).outerHeight();
        if (h > minh) minh = h;
            i++;
    }
    var i = 1;
    while (i <= slides)
    {
        $("#slide" + i).attr(
            "style",
            $("#slide" + i).attr("style") + "min-height:" + minh + "px;"
        );
        i++;
    }
});
