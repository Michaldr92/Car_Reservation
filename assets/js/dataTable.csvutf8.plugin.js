jQuery.fn.dataTable.Api.register('csvutf8()', function(filename) { // plus opcjonalny drugi parametr: tylko widoczne kolumny (true|false)
    var dt = this;
    var separator = ';'; //dla zgodnosci z excel 2010

    var all = true;
    if (arguments[1]) all = false;

    var c = 0;
    var regexp = new RegExp(/["]/g);

    var csv = dt.data().map(function(row) {
        c = row.length;
        var row2 = [];
        for (var j = 0; j < c; j++) {
            if (dt.column(j).visible() || all) {
                var v = row[j] + '';
                v = (v == 'undefined') ? '' : v;
                v = v.replace(regexp, "“");
                row2.push('"' + v + '"');
            }
        }
        return row2.join(separator);
    }).join('\n');

    var titles_tab = [];
    for (var i = 0; i < c; i++) {
        if (dt.column(i).visible() || all) {
            titles_tab.push('"' + dt.column(i).header().innerHTML.replace(regexp, "“") + '"');
        }
    }
    csv = titles_tab.join(separator) + '\n' + csv;
    var href = 'data:application/csv;charset=utf-8,\ufeff' + encodeURIComponent(csv);

    //to jedyny sposób aby ustawić nazwę pobieranego pliku
    var a = $('<a></a>').css('display', 'none').prop('download', filename).prop('href', href);
    $('body').append(a);
    a[0].click();
    a.remove();
});