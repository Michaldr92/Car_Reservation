var dialog, form;

$(document).ready(function() {

    var col = 0;
    var td = 0;

    dialog = dialogInit(); // Inicjacja okna (osobny plik - funkcja)

    // Utworzenie datatables	- TABELA
    // Wykorzystanie serverSide, pobieranie danych z bazy, wyświetlenie kolumn, definicja.

    var d_table = $('#cartracking').DataTable({ // zapisanie w zmiennej d_table
        "processing": true,
        "serverSide": true,
        "ajax": base_url + 'Cartracking_c/get_infolist',
        "order": [
            [3, "desc"]
        ],
        "columnDefs": [{
            className: "last_column",
            "targets": [11]
        }],
        "drawCallback": function(settings) {
            var api = this.api();

            // Dodanie ikonki ołówka do ostatniej kolumny ->

            if (netid != '') {
                $("#cartracking td.last_column").each(function() {
                    var td = $(this);

                    var tr = td.parent();
                    var email_db = $('td:nth-child(11)', tr).text();

                    if (email == email_db || admin == 1) {
                        $("#cartracking td.last_column").removeClass("last_column");
                        $('td:nth-child(12)', tr).addClass("contextPlay");
                    }

                    var tekst = td.text();
                    td.data('id', tekst);
                    td.text('');
                });
				
                addContextMenu(); // Dodaj context menu	
            }



            d_table.data().map(function(row) {
                col = row.length;
            });


            if (netid != '') {
                $("#add_row").show();
            }


            if (netid == '') {
                var column = d_table.column(11);
                column.visible(false);
            }
        }

    });

    // EKSPORT DO CSV
    $("#eksport").click(function() {
        $('#cartracking').DataTable().csvutf8('request.csv', true);
    });


    // FUNKCJA DATE - wybór daty
    $(function() {
        $("#start_date, #end_date").datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: "HH:mm",
            stepHour: 1,
            stepMinute: 15,
            showOn: "button",
            buttonImage: base_url + "assets/css/images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date"
        });
    });


    // OŁÓWEK - po kliknięciu ołówka otwiera się context menu
    function addContextMenu() {
        $.contextMenu({
            selector: 'td.contextPlay ',
            trigger: 'left', // lpm
            callback: function(key, options) {
                var td = $(this);

                id = td.data('id');
                if (key == "edit") { // tryb edycji
                    edit_car(id);
                }

                if (key == "delete") { // tryb usunięcia
                    delete_car(id);
                }
            },
            items: { // po kliknięciu -> lista
                "edit": {
                    name: "Edit"
                },
                "sep1": "---------",
                "delete": {
                    name: "Delete"
                }
            }
        });
    }
});

function delete_car(id) { // usuwanie wpisu z bazy & AJAX

    if (confirm("Czy napewno chcesz usunąć ten rekord?")) {
        $.ajax({
            dataType: "json",
            data: {
                'id': id
            },
            method: 'POST',
            url: base_url + 'getedit/delete_car',
            success: function(data) {
                $('#cartracking').DataTable().ajax.reload();
            }
        });
    }
}

function edit_car(id) { // Edycja wpisu

    dialog.dialog("open"); // Otwarcie dialogu
    if (id > 0) {

        var cartracking_data = [];

        cartracking_data.push({
            'id': id
        });

        $.ajax({
            method: "GET", // GET
            dataType: "json", // JSON
            data: {
                'cartracking_data': cartracking_data
            }, // dane
            url: base_url + 'getdata/get_carinfo/' + id, // pobierz info -> model
            success: function(data) {
                data = data[0];

                $('#id').val(data['id']); //id
                $('#driver_name').val(data['driver_name']); // imie kierowcy
                $('#phone').val(data['phone']); // nr tel
                $('#destination').val(data['destination']); // kierunek dokąd jedzie
                $('#start_date').val(data['start_date']); // kiedy jedzie - start
                $('#nameppl_travel').val(data['nameppl_travel']); // Pasazerowie

                // Rozbicie na kilka pól formularzu, kilka pasażerów
                var ppl_travel_db = data['nameppl_travel'];
                var pplArray = ppl_travel_db.split('<br>');
                var licznik1 = 0;
                $('#ppl_start input').each(function() {
                    var inp1 = $(this);
                    inp1.val(pplArray[licznik1]);
                    licznik1++;
                });

                $('#end_date').val(data['end_date']); // Przyjazd - END
                $('#nameppl_travel_back').val(data['nameppl_travel_back']);

                var ppl_travel_db_back = data['nameppl_travel_back']; // Pasażerowie wracający
                var pplArray_back = ppl_travel_db_back.split('<br>');
                var licznik = 0;

                // Rozbicie na kilka pól formularzu, kilka pasażerów wracających
                $('#ppl_back input').each(function() {
                    var inp = $(this);
                    inp.val(pplArray_back[licznik]);
                    licznik++;
                });

                // Checkbox z przepustkami
                $('#car_pass').val(data['car_pass']);
                if (data['car_pass'] == 'Yes') $("#car_pass").prop('checked', true);
                else if (data['car_pass'] == 'Not yet') $("#car_pass").prop('checked', false);

                $('#kro_pass').val(data['kro_pass']);
                if (data['kro_pass'] == 'Yes') $("#kro_pass").prop('checked', true);
                else if (data['car_pass'] == 'Not yet') $("#kro_pass").prop('checked', false);

                $('#comments').val(data['comments']); // Komentarz
                $('#tryb').val('edit'); // Tryb -> edycja, nowy
            },
            error: function(data) {
                alert("Wystąpił błąd"); // Jeśli błąd to wyświetl
                console.log(data);
            }
        });
    }
}