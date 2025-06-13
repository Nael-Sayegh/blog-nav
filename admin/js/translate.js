function trsfiles_add_tr(model)
{
    var key = document.getElementById("tr_trsfiles_edit_add").value.trim();
    if(!key.match(/^[a-zA-Z0-9*_-]+$/))
        return;
    document.getElementById("tr_trsfiles_edit_add").value = "";

    var html = '<tr>';
    if(model)
        html += '<td class="trform2"></td>';
    html += '<td class="trform';
    if(model)
        html += '2';
    else
        html += '1';
    html += '">\
        <input type="checkbox" id="tr_trsfiles_edit_e0_'+key+'" name="tr0_'+key+'" aria-label="Activer" checked autocomplete="off"/>\
        <label for="tr_trsfiles_edit_e_'+key+'"><em>'+key+'</em></label><br>\
        <textarea id="tr_trsfiles_edit_e_'+key+'" name="tr_'+key+'" autocomplete="off"></textarea></td>\
    </tr>';
    $("#tr_trsfiles_edit_t").append(html);
}
