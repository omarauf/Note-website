<?php 
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    session_start();
    $userId = $_SESSION['id'];
    

    if (isset($_POST['newNote'])  &&   isset($_POST['noteId']) ){
        $newNote = $_POST['newNote'];
        $noteId = $_POST['noteId'];
        $db->updateNoteByNoteID($noteId, $newNote);
    } else if(isset($_POST['updateNoteListView'])) {
        $noteIDToBeActive = $_POST['updateNoteListView'];
        $notes =  $db->updateNoteListView($userId, $noteIDToBeActive);
        echo $notes;
    }else if(isset($_POST['deleteNote']) && isset($_POST['noteId'])) {
        $noteId = $_POST['noteId'];
        $db->deleteNoteByNoteId($noteId);
    }else if(isset($_POST['addNote'])) {
        $noteId = $db->addNoteByUserId($userId);
        echo $noteId;
    }

?>