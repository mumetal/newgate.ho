<?php
class User {
    public $email, $firstname, $lastname, $phonenos, $role;
    function __construct($id, $email, $firstname, $lastname, $phonenos, $role){
        $this->id = $id;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phonenos = $phonenos;
        $this->role = $role;
    }

    function saveToDB($conn){
        $query = "INSERT INTO tbl_users(ID, email, firstname, lastname, phoneno, password) VALUES(null, '$this->email', '$this->firstname', '$this->lastname', '$this->phonenos', '$this->lastname')";
        if ($conn->exec($query)){
            $id = $conn->lastInsertId();
            $query = "INSERT INTO tbl_roles(userID, role) VALUES($id, :role)";  
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":role", $r);
            foreach ($this->role as $r){
                $stmt->execute();
            }
        }

    }

    static function getUser($conn, $stmt, $data){
        $stmt->execute($data);
        if( $result = $stmt->fetch(PDO::FETCH_ASSOC) ){        
            $id = $result["ID"];
            $stmt = $conn->query("SELECT role FROM tbl_roles WHERE userID = $id");
            $result2 = $stmt->fetchall(PDO::FETCH_ASSOC);
            $rolearray = array();
            foreach ($result2 as $row) {
                array_push($rolearray,$row["role"]);
            }
        }        
        return new User($id, $result["email"], $result["firstname"], $result["lastname"], $result["phoneno"], $rolearray);
    }

    static function getUserWithLD($conn, $email, $password){     
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email AND password = :password");
        $data = array("email"=> $email, "password" => sha1($password));
        return self::getUser($conn, $stmt, $data);
    }

    static function getUserWithID($conn, $id){
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE id = :id");
        $data = array("id"=> $id);
        return self::getUser($conn, $stmt, $data);
    }

    static function getAllUsers($conn){
        $users = array();
        $stmt = $conn->query("SELECT * FROM tbl_users");
        $results = $stmt->fetchall(PDO::FETCH_ASSOC);
        foreach($results as $result){        
            $id = $result["ID"];
            $stmt = $conn->query("SELECT role FROM tbl_roles WHERE userID = $id");
            $result2 = $stmt->fetchall(PDO::FETCH_ASSOC);
            $rolearray = array();
            foreach ($result2 as $row) {
                array_push($rolearray,$row["role"]);
            }
            $user = new User($id, $result["email"], $result["firstname"], $result["lastname"], $result["phoneno"], $rolearray);
            array_push($users, $user);
        }
        return $users; 

    }
    
    function saveToSession(){        
        session_start();
        $_SESSION['user'] = $this;
        
    }

    function isAdmin(){
        return in_array('ADMIN', $this->role);
    }

    function isDoctor(){
        return in_array('DOCTOR', $this->role);

    }

    function isSupport(){
        return in_array('SUPPORT', $this->role);
        
    }
}