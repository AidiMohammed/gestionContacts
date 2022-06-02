<?php

require_once('data_provider.php');
require_once('../../lib/library.php');

class Contact extends DataProvider
{

    //add new contact
    public function addNewContact($paylodContact)
    {
        //check if paylod is empty return no paylod no new contact ;)
        if(!$paylodContact)
            return false;
        else
        {
            $db = $this->connect();
            if($db == null) return false;
            else
            {
                $sql = "INSERT INTO contacts (user_id,first_name,last_name,email,phone_number,avatar,address1,address2,country,city,zip_code,birthday,sex,status) VALUES (:user_id,:first_name,:last_name,:email,:phone_number,:avatar,:address1,:address2,:country,:city,:zip_code,:birthday,:sex,:status)";
                $stm = $db->prepare($sql);
                $stm->execute([
                    ":user_id" => $paylodContact['userid'],
                    ":first_name" => $paylodContact['firstName'],
                    ":last_name" => $paylodContact['lastName'],
                    ":email" => $paylodContact['email'],
                    ":phone_number" => $paylodContact['phonenumber'],
                    ":avatar" => "",
                    ":address1" => $paylodContact['address1'],
                    ":address2" => $paylodContact['address2'],
                    ":country" => $paylodContact['country'],
                    ":city" => $paylodContact['city'],
                    ":zip_code" => $paylodContact['zipcode'],
                    ":birthday" => $paylodContact['birthday'],
                    ":sex" => "",
                    ":status" => "",
                ]);
                redirect('./account.php');
                $this->checkPhoneNumberIfExistUser($paylodContact['userid'],$paylodContact['phonenumber']);
            }
        }
    }
    public function checkPhoneNumberIfExistUser($userId,$phone_number)
    {
        $db = $this->connect();
        if($db == null) return;
        else
        {
            $sql = "SELECT * FROM contacts WHERE user_id = ? ";
            $stm = $db->prepare($sql);
            $stm->execute([$userId]);
            $contactes = $stm->fetch(PDO::FETCH_ASSOC);
            echo '<pre>';
            echo print_r($contactes);
            echo '</pre>';
            die();
            return $contactes;
        }
    }
    //edit contact
    public function editContact($idContact)
    {

    }

    //get all contacts
    public function getAllContacts($userId)
    {
        $db = $this->connect();
        if($db == null) return;

        $sql = "SELECT * FROM contacts WHERE user_id = ?";
        $stm = $db->prepare($sql);
        $stm->execute([$userId]);
        $contactes = $stm->fetchAll(PDO::FETCH_OBJ);

        if($contactes)
        {
            $db = null;
            $stm = null;

            return $contactes;
        }
        else
        {
            $db = null;
            $stm = null;

            return false;
        }
    }

    //get contact by id
    function getContactById($id)
    {

    }

    //get contact by email
    function getContactByEmail($email)
    {

    }

    //get contact by phone number
    public function getContactByPhoneNumber($phone)
    {

    }

    //get contact by index [PHONE-ID-EMAIL]
    public function getContactByIndex($index)
    {

    }
 
    //add a contact to list
    public function addAContactToList($paylodUser)
    {

    }

    //add a contacts to list
    public function addAContactsToli($paylodUsers)
    {

    }

    //delete contact
    public function deleteContact($id)
    {

    }

    //delete contacts
    public function deleteContactes($paylosUsers)
    {

    }
}
?>