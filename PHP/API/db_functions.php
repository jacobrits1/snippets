<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'db_connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {

    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($per_name, $per_email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $insert = "INSERT INTO person (per_name, per_email, per_password, per_salt, per_date_added) VALUES('".$per_name."', '".$per_email."', '".$encrypted_password."', '".$salt."', NOW())";

        $mysqli = new mysqli("localhost","medminco_demo","D3m0!234","medminco_digislip");//($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
          	/* check connection */
          	if (mysqli_connect_errno()) {
             		printf("Connect failed: %s\n", mysqli_connect_error());
             		exit();
          	}

        $mysqli->query("SET NAMES 'utf8'");
        $result=$mysqli->query($insert);
        while($e=mysqli_fetch_assoc($result)){
            $sql = "SELECT * FROM person WHERE email = '$per_email'";
            $result=$mysqli->query($sql);
            while($e=mysqli_fetch_assoc($result)){
                return $e;
            }
        }


        //$stmt = $this->conn->prepare($insert);
        //var_dump($insert);exit;
        //$stmt->bind_param("ssss", $per_name, $per_email, $per_password, $salt);
        //$result = $this->conn->execute();//"INSERT INTO person (per_name, per_email, per_password, per_salt, per_date_added) VALUES(".$per_name.", ".$per_email.", ".$per_password.", ".$salt.",NOW())");
        //$stmt->close();

        // check for successful store
        // if ($result) {
        //     $stmt = $this->conn->prepare("SELECT * FROM person WHERE email = ?");
        //     $stmt->bind_param("s", $email);
        //     $stmt->execute();
        //     $user = $stmt->get_result()->fetch_assoc();
        //     $stmt->close();
        //
        //     return $user;
        // } else {
            return false;
        //}
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($per_email, $password) {

      $select = "SELECT per_id,per_name,per_email,per_date_added,per_date_modified,per_salt,per_password FROM person WHERE per_email ='".$per_email."'";

      $mysqli = new mysqli("localhost","medminco_demo","D3m0!234","medminco_digislip");//($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
          /* check connection */
          if (mysqli_connect_errno()) {
              printf("Connect failed: %s\n", mysqli_connect_error());
              exit();
          }

      $mysqli->query("SET NAMES 'utf8'");
      $result=$mysqli->query($select);
      while($user=mysqli_fetch_assoc($result)){
        $salt = $user['per_salt'];
        $encrypted_password = $user['per_password'];
        $hash = $this->checkhashSSHA($salt, $password);
        // check for password equality
        if ($encrypted_password == $hash) {
            // user authentication details are correct
            return $user;
        }else {
            return NULL;
        }
      }


        //
        //
        // $stmt = $this->conn->prepare("SELECT * FROM person WHERE per_email = ?");
        //
        // $stmt->bind_param("s", $per_email);
        //
        // if ($stmt->execute()) {
        //     $user = $stmt->get_result()->fetch_assoc();
        //     $stmt->close();
        //
        //     // verifying user password
        //     $salt = $user['per_salt'];
        //     $encrypted_password = $user['per_password'];
        //     $hash = $this->checkhashSSHA($salt, $password);
        //     // check for password equality
        //     if ($encrypted_password == $hash) {
        //         // user authentication details are correct
        //         return $user;
        //     }
        // } else {
        //     return NULL;
        // }
    }

    /**
     * Check user is existed or not
     */
	 
	
	
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT per_email from person WHERE per_email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
