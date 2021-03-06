<?php
    require_once('user.php');
    class Session{
        function __construct($ID, $patientID, $docID, $consultation_bill, $startdate, $paid){
            $this->ID =$ID;
            $this->patientID = $patientID;
            $this->docID = $docID;
            $this->consultation_bill = $consultation_bill;
            $this->startdate = $startdate;
            $this->paid = $paid;
        }
        static function getSessionFromDB($conn, $ID){
            $query = "SELECT * FROM tbl_sessions WHERE ID=$ID";
            $stmt = $conn->query($query);
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            return new Session($ID, $result["patientID"], $result["docID"], $result["consultation_bill"], $result['startdate'], $result["paid"]);
        }

        static function getSessionFromPatient($conn, $patientID){
            $query = "SELECT * FROM tbl_sessions WHERE patientID=$patientID";
            $stmt = $conn->query($query);
            $sessions = array();
            if($rows = $stmt->fetchall(PDO::FETCH_ASSOC)){
                foreach($rows as $result){
                    array_push($sessions, new Session($result['ID'], $result["patientID"], $result["docID"],$result["consultation_bill"], $result['startdate'], $result["paid"]));
                }
            }
            return $sessions;
        }

        static function getAllSessionsFromDB($conn){
            $stmt = $conn->query("SELECT * FROM tbl_sessions");
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            $array = array();
            foreach ($result as $row) {
                array_push($array, new Session($row["ID"], $row['patientID'], $result["docID"],$row["consultation_bill"], $result['startdate'],$result["paid"]));
            }
            return $array;

        }

        static function getAllSessionsFromPatient($conn, $patientID){
                    $stmt = $conn->query("SELECT * FROM tbl_sessions WHERE patientID=$patientID");
                    $result = $stmt->fetchall(PDO::FETCH_ASSOC);
                    $array = array();
                    foreach ($result as $row) {
                        array_push($array, new Session($row["ID"], $row['patientID'], $row["docID"],$row["consultation_bill"], $row['startdate'],$row   ["paid"]));
                    }
                    return $array;

                }
        static function getLastForPatient($conn, $patientID){
            $query = "SELECT * FROM tbl_sessions WHERE patientID=$patientID ORDER BY ID DESC";
            $stmt = $conn->query($query);
            $session;
            if($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                $session = new Session($result['ID'], $result["patientID"], $result["docID"],$result["consultation_bill"], $result['startdate'], $result["paid"]);
            }
            return $session;
        }

        function saveToDB($conn){
            $query = "INSERT INTO tbl_sessions(ID, patientID, docID, consultation_bill, startdate, paid) VALUES(null, '$this->patientID', '$this->docID', '$this->consultation_bill', '$this->startdate', '$this->paid')";
            $conn->exec($query);
        }

        function updatePaid($conn){
            $query = "UPDATE tbl_sessions SET paid='$this->paid' WHERE ID = $this->ID";
            $conn->exec($query);

        }

        function getDoctor($conn){
            return User::getUserWithID($conn, $this->docID);
        }

        function getDoctorName($conn){
            $doc = $this->getDoctor($conn);
            return "Dr. ".$doc->firstname." ".$doc->lastname;
        }

        function getTotalBill($conn){
            $query = "SELECT ID FROM tbl_diagnosis WHERE sessionID=$this->ID";
            $stmt = $conn->query($query);
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            $bill = 0;
            foreach ($result as $row) {
                $diagnosisID = $row["ID"];
                $query = "SELECT * FROM tbl_prescriptions WHERE diagnosisID=$diagnosisID";
                $stmt = $conn->query($query);
                $result2 = $stmt->fetchall(PDO::FETCH_ASSOC);
                $array = array();
                foreach ($result2 as $row2) {
                    $bill += $row2["bill"];
                }
            }
            return $bill+$this->consultation_bill;
            
        }
        function getDiagnosis($conn){
            $query = "SELECT * FROM tbl_diagnosis WHERE sessionID=$ID";
            $stmt = $conn->query($query);
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            $array = array();
            foreach ($result as $row) {
                array_push($array, new Diagnosis($row["ID"], $ID, $row["diagnosis"], $result["date"], $result["medication"]));
            }
            return $array;

        }
    }
?>