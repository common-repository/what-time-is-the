
window.onload = function(){
        for (var i=0;i<10;i++) {
            element = document.getElementById("wtitdate"+i);
            if (element!=null)
                element.textContent = set_right_date(element.textContent);
        }
}

function get_time_zone_offset( ) {
    var rightNow = new Date();
    var jan1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
    var temp = jan1.toGMTString();
    var jan2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
    var std_time_offset = (jan1 - jan2) / (1000 * 60 * 60);
    return std_time_offset;
}

function set_right_date(date) {
    var d = Date.parse(date);
    d.add(get_time_zone_offset()).hours();
    return d.format("mmm d, yyyy HH:MM");
}