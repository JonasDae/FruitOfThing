function tableCreate() {
    var tablebody = document.getElementById("measurementTable");
    var thead = document.createElement('thead');
    var tbody = document.createElement('tbody');

    var datasets = [[12, 19, 3, 5, 2, 3],
				[11, 18, 5, 2, 12, 13],
                [1, 8, 15, 8, 5, 8]];
                
    for (var i = 0; i < 3; i++) {
        var tr = document.createElement('tr');
        for (var j = 0; j < 2; j++) {
            if (i == 2 && j == 1) {
            break
            } else {
            var td = document.createElement('td');
            td.appendChild(document.createTextNode('\u0020'))
            i == 1 && j == 1 ? td.setAttribute('rowSpan', '2') : null;
            tr.appendChild(td)
            }
        }
        tbdy.appendChild(tr);
    }
}