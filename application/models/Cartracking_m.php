<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');


class Cartracking_m extends CI_Model {
    
    private $cartracking = 'cartracking';
    
    function __construct() {
        
    }
    
    // WYKORZYSTANIE ZEWNĘTRZNEJ BIBLIOTEKI DO ŁATWIEJSZEGO WYŚWIETLENIA DATATABLES - JS
    function get_infolist() {
        /* Array of table columns which should be read and sent back to DataTables. Use a space where
        * you want to insert a non-database field (for example a counter or static image)
        */
        $aColumns = array('driver_name', 'phone', 'destination', 'start_date', 'nameppl_travel', 'end_date',
        'nameppl_travel_back', 'car_pass', 'kro_pass', 'comments', 'edit_by', 'id');
        
        /* Indexed column (used for fast and accurate table cardinality) */
        //$sIndexColumn = "*";
        
        /* Total data set length */
        $sQuery = "SELECT COUNT(*) AS row_count FROM cartracking";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->row_count;
        
        /*
        * Paging
        */
        $sLimit = "";
        $iDisplayStart = $this->input->get_post('start', true);
        $iDisplayLength = $this->input->get_post('length', true);
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $sLimit = "LIMIT " . intval($iDisplayStart) . ", " .
            intval($iDisplayLength);
        }
        
        $uri_string = $_SERVER['QUERY_STRING'];
        $uri_string = preg_replace("/%5B/", '[', $uri_string);
        $uri_string = preg_replace("/%5D/", ']', $uri_string);
        
        $get_param_array = explode("&", $uri_string);
        $arr = array();
        foreach ($get_param_array as $value) {
            $v = $value;
            $explode = explode("=", $v);
            $arr[$explode[0]] = $explode[1];
        }
        
        $index_of_columns = strpos($uri_string, "columns", 1);
        $index_of_start = strpos($uri_string, "start");
        $uri_columns = substr($uri_string, 7, ($index_of_start - $index_of_columns - 1));
        $columns_array = explode("&", $uri_columns);
        $arr_columns = array();
        foreach ($columns_array as $value) {
            $v = $value;
            $explode = explode("=", $v);
            if (count($explode) == 2) {
                $arr_columns[$explode[0]] = $explode[1];
                } else {
                $arr_columns[$explode[0]] = '';
            }
        }
        
        /*
        * Ordering
        */
        $sOrder = "ORDER BY ";
        $sOrderIndex = $arr['order[0][column]'];
        $sOrderDir = $arr['order[0][dir]'];
        $bSortable_ = $arr_columns['columns[' . $sOrderIndex . '][orderable]'];
        if ($bSortable_ == "true") {
            $sOrder .= $aColumns[$sOrderIndex] .
            ($sOrderDir === 'asc' ? ' asc' : ' desc');
        }
        
        /*
        * Filtering
        */
        $sWhere = "";
        $sSearchVal = $arr['search[value]'];
        if (isset($sSearchVal) && $sSearchVal != '') {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        
        /* Individual column filtering */
        $sSearchReg = $arr['search[regex]'];
        for ($i = 0; $i < count($aColumns); $i++) {
            $bSearchable_ = $arr['columns[' . $i . '][searchable]'];
            if (isset($bSearchable_) && $bSearchable_ == "true" && $sSearchReg != 'false') {
                $search_val = $arr['columns[' . $i . '][search][value]'];
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                    } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->db->escape_like_str($search_val) . "%' ";
            }
        }
        
        /*
        * SQL queries
        * Get data to display
        */
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
        FROM $this->cartracking
        $sWhere
        $sOrder
        $sLimit
        ";
        $rResult = $this->db->query($sQuery);
        
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() AS length_count";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();
        $iFilteredTotal = $aResultFilterTotal->length_count;
        
        /*
        * Output
        */
        
        $sEcho = $this->input->get_post('draw', true);
        $output = array(
        "draw" => intval($sEcho),
        "recordsTotal" => $iTotal,
        "recordsFiltered" => $iFilteredTotal,
        "data" => array()
        );
        
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $output['data'][] = $row;
        }
        
        return $output;
    }

    
    // Pobranie wszystkich danych z bazy danych
    function get_carinfo($id){
        
        $q = 'SELECT  driver_name, phone, destination, start_date, nameppl_travel, end_date, nameppl_travel_back, car_pass, kro_pass, comments, edit_by, id FROM cartracking WHERE id = '.$id; // Pobranie danych z bazy danych cartracking
        
        $result = $this ->db -> query($q)->result_array(); // Wykonanie zapytania
        
        return $result;
        
    }
    
    
    function save_data($cartracking_data){ // Zapisanie danych w formularzu
        
        $session = $this->auth_m->getSession(); // Pobranie sesji
        
        if($session['netid'] != '' ) {     // Jeżeli netid nie jest puste...
            
            $error=$this->validate($cartracking_data); // Sprawdzenie pól formularzu - walidacja
            
            $tryb = $cartracking_data['tryb']; // Ustawienie zmiennej $tryb
            unset($cartracking_data['tryb']); // "usunięcie" ze zmiennej
            
            $tmp = '';             // zmienna pomocnicza
            $tmp1 = '';        // zmienna pomocnicza 1
            
            // ZAPISANIE do DATA danych wpisanych w formularzu
            
            if($cartracking_data['nameppl_travel1'] != '') $tmp = $cartracking_data['nameppl_travel1'];
            if($cartracking_data['nameppl_travel2'] != '') $tmp = $tmp.'<br>'.$cartracking_data['nameppl_travel2'];
            if($cartracking_data['nameppl_travel3'] != '') $tmp = $tmp.'<br>'.$cartracking_data['nameppl_travel3'];
            if($cartracking_data['nameppl_travel4'] != '') $tmp = $tmp.'<br>'.$cartracking_data['nameppl_travel4'];
            if($cartracking_data['nameppl_travel5'] != '') $tmp = $tmp.'<br>'.$cartracking_data['nameppl_travel5'];
            
            $cartracking_data['nameppl_travel'] = $tmp;
            
            if($cartracking_data['nameppl_travel_back1'] != '') $tmp1 = $cartracking_data['nameppl_travel_back1'];
            if($cartracking_data['nameppl_travel_back2'] != '') $tmp1 = $tmp1.'<br>'.$cartracking_data['nameppl_travel_back2'];
            if($cartracking_data['nameppl_travel_back3'] != '') $tmp1 = $tmp1.'<br>'.$cartracking_data['nameppl_travel_back3'];
            if($cartracking_data['nameppl_travel_back4'] != '') $tmp1 = $tmp1.'<br>'.$cartracking_data['nameppl_travel_back4'];
            if($cartracking_data['nameppl_travel_back5'] != '') $tmp1 = $tmp1.'<br>'.$cartracking_data['nameppl_travel_back5'];
            
            $cartracking_data['nameppl_travel_back'] = $tmp1;
            
            // SPRAWDZENIE CHECKBOXÓW
            
            if(isset($cartracking_data['car_pass'])) {
                $cartracking_data['car_pass'] = 'Yes';
                }    else{
                $cartracking_data['car_pass'] = 'Not yet';
            }
            
            if(isset($cartracking_data['kro_pass'])) {
                $cartracking_data['kro_pass'] = 'Yes';
                }    else{
                $cartracking_data['kro_pass'] = 'No';
            }
            
            // DODANIE EMAIL do edit_by
            $cartracking_data['edit_by']= $session['email'];
            
            //Dodanie danych w postaci tablicy do wysłania do bazy danych
            $cartracking_data_db = array(     'driver_name'            =>$cartracking_data['driver_name'],
            'phone'                      =>$cartracking_data['phone'],
            'destination'              =>$cartracking_data['destination'],
            'start_date'               =>$cartracking_data['start_date'],
            'nameppl_travel'        =>$cartracking_data['nameppl_travel'],
            'end_date'                =>$cartracking_data['end_date'],
            'nameppl_travel_back'=>$cartracking_data['nameppl_travel_back'],
            'car_pass'                =>$cartracking_data['car_pass'],
            'kro_pass'                =>$cartracking_data['kro_pass'],
            'comments'                =>$cartracking_data['comment'],
            'edit_by'                    =>$cartracking_data['edit_by'],
            'id'                            =>$cartracking_data['id']);
            
            // Jeżeli w walidacji nie będzie błedu to przejdź dalej..
            if ( $error == '' ){
                
                // Jeżeli tryb edycji:
                if ($tryb == 'edit'){
                    
                    $query = $this->db->get_where('cartracking', array('id'=>$cartracking_data_db['id'])); // Edytuj wpis w bazie
                    
                    foreach ($query->result() as $row)
                    {
                        $email = $row->edit_by; // Kto edytuje
                    }
                    
                    
                    if ($email == $session['email'] || $session['admin'] == 1){    // Jeżeli admin to zrób update w bazie
                        
                        unset($cartracking_data_db['edit_by']);
                        $this->db->where(array('id'=>$cartracking_data_db['id']));
                        $this->db->update('cartracking', $cartracking_data_db);
                    }
                    else{
                        header("Location: http://localhost/cartracking/ "); // Wróc do strony głównej
                    }
                }
                
                // Jeżeli tryb pusty, czyli nowy wpis to:
                elseif ($tryb == ''){
                    
                    if($cartracking_data_db['edit_by'] != ''){
                        unset($cartracking_data_db['id']);
                        $this->db->insert('cartracking', $cartracking_data_db);        // Dodaj wpis do bazy danych
                    }
                    else{
                        header("Location: http://localhost/cartracking/ "); // Wróc do strony głównej
                    }
                }
            }
        }
        
        return $error;
    }
    
    function delete_car($cartracking_data){ // Usunięcie wpisu z bazy danych
        
        $session = $this->auth_m->getSession();
        
        $query = $this->db->get_where('cartracking', array('id'=>$cartracking_data['id']));        // Pobierz dane z bazy
        foreach ($query->result() as $row)
        {
            $email = $row->edit_by; // kto usuwa/edytuje
        }
        
        if ($email == $session['email'] || $session['admin'] == 1) {        // Jesli admin
            $this->db->where(array('id'=>$cartracking_data['id']));
            $this->db->delete('cartracking');
        }
        else{
            header("Location: http://localhost/cartracking/ "); // Wróc do strony głównej
        }
    }
    
    
    // WALIDACJA FORMULARZY
    function validate($cartracking_data)
    {
        $error = "";
        
        if($cartracking_data['driver_name'] == ""){ // Jezeli puste pole
            $error .="Please write a drivern";
        }
        
        if($cartracking_data['destination'] == ""){ // Jezeli puste pole
            $error .="Please write a destinationn";
        }
        
        if($cartracking_data['start_date'] == ""){ // Jezeli puste pole
            $error .="Please choose start daten";
        }
        
        if($cartracking_data['end_date'] == ""){ // Jezeli puste pole
            $error .="Please choose end daten";
        }
        
        if($cartracking_data['nameppl_travel1'] == ""){ // Jezeli puste pole
            $error .="Please write start travel frist personn";
        }
        
        if($cartracking_data['nameppl_travel_back1'] == ""){ // Jezeli puste pole
            $error .="Please write end travel frist personn";
        }
        
        if((preg_match("/[^0-9]/", $cartracking_data['phone']))) // Sprawdź znaki w polu
        //if(strlen($cartracking_data['phone']) > 12)
        {
            $error.= "Invalid Number! Please write correct numbern";
        }
        
        
        return $error;
    }
    
}