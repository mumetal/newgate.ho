<?php
require_once('session.php');
require_once('user.php');
class Patient {
    public $ID, $email, $firstname, $lastname, $phone_num, $dob, $height, $weight;
    function __construct($ID, $email, $firstname, $lastname, $phone_num, $dob, $height, $weight, $sessbill=null, $docID=null){
        $this->ID = $ID;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone_num = $phone_num;
        $this->dob = $dob;
        $this->height = $height;
        $this->weight = $weight;
        $this->sessbill = $sessbill;
        $this->docID=$docID;
    }


    function saveToDB($conn){
        $query = "INSERT INTO tbl_patients(ID, email, firstname, lastname, phone_num, dob, height, weight) VALUES(null, '$this->email', '$this->firstname', '$this->lastname', '$this->phone_num', '$this->dob', '$this->height', '$this->weight')";
        if($conn->exec($query)){
            if(isset($this->sessbill)){
                $id = $conn->lastInsertId();
                $now = date('Y-m-d');
                $session = new Session(null, $id, $this->docID, $this->sessbill, $now, 0);
                $session->saveToDB($conn);
                return TRUE;
            }
            return TRUE;
        }
    }

    function updateDB($conn){
        $stmt = $conn->prepare("UPDATE tbl_patients SET email=:email, firstname=:firstname, lastname=:lastname, phone_num=:phone_num, dob=:dob, height=:height, weight=:weight WHERE ID=:ID");
        $data = array('ID' => $this->ID, 'email' => $this->email, 'firstname' => $this->firstname, 'lastname' =>  $this->lastname, 'phone_num' => $this->phone_num, 'dob' => $this->dob, 'height' => $this->height, 'weight' => $this->weight);
        $stmt->execute($data);
    }

    static function getPatient($conn, $stmt, $data){
        $stmt->execute($data);
        if( $result = $stmt->fetch(PDO::FETCH_ASSOC) ){        
            $id = $result["ID"];
            return new Patient($id, $result["email"], $result["firstname"], $result["lastname"], $result["phone_num"], $result['dob'], $result['height'], $result['weight']);
        }
        return null;     
    }

    static function getPatientByID($conn, $id){
        $stmt = $conn->prepare("SELECT * FROM tbl_patients WHERE id = :id");
        $data = array("id"=> $id);
        return self::getPatient($conn, $stmt, $data);
    }

    static function getPatientsByName($conn, $name){
        $stmt = $conn->query("SELECT * FROM tbl_patients WHERE firstname LIKE '%".$name."%'  OR lastname LIKE '%".$name."'%");
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    static function getPatientsByNameAndEmail($conn, $name, $email){
        $cname = ($name)? "firstname LIKE '%".$name."%'  OR lastname LIKE '%".$name."%'"  : "FALSE" ;
        $cemail = ($email)? "email LIKE '%".$email."%'"  : "FALSE" ;
        if($cname=="FALSE" && $cemail=="FALSE"){
            $cname = "TRUE";
        }
        $stmt = $conn->query("SELECT * FROM tbl_patients WHERE $cname OR $cemail");
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    static function getAllPatients($conn){
        $patients = array();
        $stmt = $conn->query("SELECT * FROM tbl_patients");
        $results = $stmt->fetchall(PDO::FETCH_ASSOC);
        foreach($results as $result){        
            $patient = new Patient($result['ID'], $result["email"], $result["firstname"], $result["lastname"], $result["phone_num"], $result['dob'], $result['height'], $result['weight']);
            array_push($patients, $patient);
        }
        return $patients; 

    }

    function getCurrentSession($conn){
        #get session with latest start date
        return 1;
    }

}