<?php
 
/**
 * @author Ravi Tamada
 * @link https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
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
    public function storeUser($email, $password, $name) { // add by omar
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
 
        $stmt = $this->conn->prepare("INSERT INTO `users`(name, email, encrypted_password, salt) VALUES(?, ?, ?, ?)"); 
        $stmt->bind_param("ssss", $name, $email, $encrypted_password, $salt); 
        $result = $stmt->execute();
        $stmt->close();

        //create default note when new account is registered
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $db = new DB_Functions();
        $db->addNoteByUserId($user["id"]);
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }
	
 
 
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stms = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stms->bind_param("s", $email);
 
        if ($stms->execute()) {
            $user = $stms->get_result()->fetch_assoc();
            $stms->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
		
    /**
     * Check user is existed or not when register
     */
    public function isUserExisted($email) {
        $stms = $this->conn->prepare("SELECT email from users WHERE email = ?");
        $stms->bind_param("s", $email);
        $stms->execute();
        $stms->store_result();
        if ($stms->num_rows > 0) {
            // user existed 
            $stms->close();
            return true;
        } else {
            // user not existed
            $stms->close();
            return false;
        }
    }
	
	/**
     * get notes to update list view note in Note.php
     * noteIDToBeActive == -1 activate first Note
     */
    public function updateNoteListView($userId, $noteIDToBeActive){
        $db = new DB_Functions();
        $dbNotes = $db->getNotesByUserID($userId);
        $notes = "";
        $array_notes = array();
        $counter = 1;
        foreach($dbNotes as $row => $innerArray){
            $array_notes[$innerArray["note_id"]] = $innerArray["note"];
            if($noteIDToBeActive == $innerArray["note_id"] ){
                $notes .= '<a href="#" class="list-group-item list-group-item-action active" id="'.$innerArray["note_id"].'" name="'.$innerArray["note"].'">'.'Note '.$counter.'</a>';
            }else if($noteIDToBeActive == -1 && $counter == 1){
                $notes .= '<a href="#" class="list-group-item list-group-item-action active" id="'.$innerArray["note_id"].'" name="'.$innerArray["note"].'">'.'Note '.$counter.'</a>';
            }else {
                $notes .= '<a href="#" class="list-group-item list-group-item-action" id="'.$innerArray["note_id"].'" name="'.$innerArray["note"].'">'.'Note '.$counter.'</a>';
            }
            $counter++;
        }
        return $notes;
    }
    
	/**
     * Get note and it's id by use id
     */
    public function getNotesByUserID($userId) {
        $stms = $this->conn->prepare("SELECT note, note_id FROM `notes` WHERE user_id = ?");
        $stms->bind_param("i", $userId);
        if ($stms->execute()) {
			$result = $stms->get_result();
			while ($data = $result->fetch_assoc()) {
				$notes[] = $data;
			}
            $stms->close(); 
			return $notes;
         } 
    }

    /**
     * update note in database by note id
     */
    public function updateNoteByNoteID($id, $newNote) {
        $stms = $this->conn->prepare("UPDATE `notes` SET `note`= ? WHERE `note_id` = ? LIMIT 1");
        $stms->bind_param("si", $newNote, $id);
        $stms->execute();
        $stms->close();
    }

    /**
     * delet note by note id
     */
    public function deleteNoteByNoteId($id) {
        $stms = $this->conn->prepare("DELETE FROM `notes` WHERE `note_id` = ? LIMIT 1");
        $stms->bind_param("i", $id);
        $stms->execute();
        $stms->close();
    }

    /**
     * add note in database by user id
     * and return note id by group all note by user id and sort it in descending oreder and select the first one
     */
    public function addNoteByUserId($userId) {
        //INSERT INTO `notes`(`user_id`, `note`) VALUES (3, "smnsknk")
        $stms = $this->conn->prepare("INSERT INTO `notes`(`user_id`, `note`) VALUES (?, 'new Note')");
        $stms->bind_param("i", $userId);
        $stms->execute();
        $stms->close();

        //SELECT `note_id` FROM `notes` WHERE `user_id` = 3 ORDER BY `note_id` DESC LIMIT 1
        $stmt = $this->conn->prepare("SELECT `note_id` FROM `notes` WHERE `user_id` = ? ORDER BY `note_id` DESC LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $note = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $note["note_id"];

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