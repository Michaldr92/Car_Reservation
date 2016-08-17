function dialogInit() {

    var dialog, form;


    dialog = $("#dialog-form").dialog({ // Właściwości okna dialogowego

        autoOpen: false,
        height: 800,
        width: 700,
        modal: true,
        buttons: {
            "OK": save_data, // zapisz
            Cancel: function() {
                dialog.dialog("close"); // zamknij
            }
        },
        close: function() {
            form[0].reset();
        }
    });

    form = dialog.find("form").on("submit", function(event) {
        event.preventDefault();
        save_data();
    });

    $("#add_row").button().on("click", function() { // Po naciśnięciu przycisku add_row...
        dialog.dialog('open'); // Otwórz okno dialogowe
    });
    return dialog;
}


function save_data() { // Funkcja zapisująca & AJAX
    $.ajax({
        url: base_url + 'getedit/save_data', // Funkcja save_data odwołanie do modelu
        type: "GET", // GET
        data: $('#car_form').serialize(), // Serializacja #car_form (funkcja jquery)
        success: function(data) {
            if (data['error'] != '') {
                alert(data['error']); // Jeśli błąd to wyświetl
            } else {
                dialog.dialog("close"); // Zamykanie okna dialogowego
                $('#cartracking').DataTable().ajax.reload(); // Przeładuje tabele
            }
        },
        error: function(jXHR, textStatus, errorThrown) {
            alert(errorThrown); // wyświetl error
        }
    });
}