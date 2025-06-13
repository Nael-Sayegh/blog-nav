var styles = [
    ["Blue Sky", "background:linear-gradient(to bottom right,#045fb4,#2ecefe);color:white;", "", "font-size:120%;"],
    ["Metal Kiwi", "background: linear-gradient(to bottom right,#81fe81,#81fe81,#81fe81,#badcba,#81fe81,#81fe81);padding-right:20px;", "color: #024202;", "color:black;"],
    ["Light Pacific", "background-color:paleturquoise;", "color:midnightblue;", ""],
    ["Water Melon", "background-color:#fecc81;", "color:#009d88;", ""],
    ["Breizh Gradient", "background:linear-gradient(to bottom right,#D3FEA7,aliceblue,#D3FEA7,#D3FEA7);", "", ""]
];

function setstyle(id)
{
    var cs = document.getElementById(id+"stylejs").value;
    var i = 0;
    while(i < styles.length)
    {//>
        if(styles[i][0] == cs)
        {
            document.getElementById(id+"style").value = styles[i][1];
            document.getElementById(id+"title_style").value = styles[i][2];
            document.getElementById(id+"contain_style").value = styles[i][3];
            i = styles.length;
        }
        i ++;
    }
}
