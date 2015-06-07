<?php
/* 
 * Jonathan Gamble
 * 
 */

// include login information
require '../../../db.php';

class connect {

    private $db, $table, $query, $count;
    public $result, $id;

    public function __construct($table) {
        // set table
        $this->table = $table;

        // connect to database
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // handle connection error
        $connection_error = $this->db->connect_error;
        if ($connection_error) 
            die('Could not connect to MySQL: '.$connection_error);
    }
    public function __destruct() {
        // disconnect from database
        $this->db->close();
    }
    public function select_all() {
        // select all items in table
        $this->query = 'SELECT * FROM '.$this->table;
        return $this->get_result(); 
    }
    private function get_result() {
        // get all results, save to hash
        $result = $this->db->query($this->query);
        if (!$result)
            return $this->db->error;
        if ($result->num_rows) {
            $this->result = array();
            while ($row = $result->fetch_assoc())
                array_push($this->result, $row);
            $result->free();
        }
        return 0;
    }
    public function add($fields) {
        // add item
        $this->_get_insert_q($fields);
        return $this->run_query();
    }
    public function update($id, $fields) {
        // update item by id
        // prevent sql injection
        $id = $this->db->real_escape_string($id);
        $this->_get_update_q($id, $fields);
        return $this->run_query();
    }
    public function delete($id) {
        // delete item by id
        // prevent sql injection
        $id = $this->db->real_escape_string($id);
        $this->query = 'DELETE FROM '.$this->table.' WHERE id='.$id;
        return $this->run_query();
    }
    public function record_exists($fields) {
        // see if a $name=$value exists foreach field
        $this->_get_where_q($fields);
        return $this->get_result();
    }
    public function run_query() {
        // run the sql query
        $this->count = $this->db->query($this->query);
        $this->id = $this->db->insert_id;
        if ($this->db->error)
            return $this->db->error;
        return 0;
    }
    private function _get_where_q($fields) {
        // create multiple where query        
        $query = 'SELECT * FROM '.$this->table.' WHERE ';
        $i = 0;
        foreach($fields as $k => $v) {
            $query .= $this->db->real_escape_string($k) . " = '"
                   . $this->db->real_escape_string($v) . "'";
            // if not last item
            if (++$i !== count($fields)) {
                $query .= ' AND ';
            }
        }
        $this->query = $query;
    }
    private function _get_update_q($id, $fields) {
        // create update query
        $query = 'UPDATE '.$this->table.' SET ';
        $i = 0;
        foreach($fields as $k => $v) {
            $query .= $this->db->real_escape_string($k) . ' = "'
                   . $this->db->real_escape_string($v) . '"';
            // if not last item
            if (++$i !== count($fields)) {
                $query .= ',';
            }
            $query .= ' ';
        }
        $query .= ' WHERE id='.$id;
        $this->query = $query;
    }
    private function _get_insert_q($fields) {
        // create insert query
        $query = "INSERT into ".$this->table." (";
        $names = $values = "";
        $i = 0;
        foreach ($fields as $k => $v) {
            $names .= $this->db->real_escape_string($k);
            $values .= "'".$this->db->real_escape_string($v)."'";
            // if not last item
            if (++$i !== count($fields)) {
                $names .= ', ';
                $values .= ', ';
            }
        }
        $query .= $names.") VALUES (".$values.")";
        $this->query = $query;
    }
}