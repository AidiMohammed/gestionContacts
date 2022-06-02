<?php
    require_once('data_provider.php');

    class UserProfil extends DataProvider
    {

        /*methos 
            Update profile first anme
            Update profile last name
            Update profile addresse1
            Update profile addresse2
            Update profile phone number
            Update profile picture
            Update profile country
            Update profile country
            Update profile city
            Update profile zip code
            Update profile birthday
            Update profile sex
            Update profile status
        */
        public function updateProfile ($user_id,$paylodProfile)
        {        
            $db = $this->connect();
            if($db == null) return false;
            $sql = "UPDATE users_profile SET first_name = ?,last_name = ?,address1 = ?,address2 = ?,phone_number = ?,picture = ?,country = ?,city = ?,zip_code = ?, birthday = ?,sex = ' ' , status = ' ' WHERE user_id = ?";
            $db->prepare($sql)->execute(
                [$paylodProfile->firstName,
                $paylodProfile->lastName,
                $paylodProfile->address1,
                $paylodProfile->address2,
                $paylodProfile->phoneNumber,
                $paylodProfile->picture,
                $paylodProfile->country,
                $paylodProfile->city,
                $paylodProfile->zipCode,
                $paylodProfile->birthday,
                $user_id]);

            $db = null;
        }
        public function getProfileByUserId($user_id)
        {
            $db = $this->connect();
            if($db == null)return false;

            $sql = "SELECT * FROM users_profile WHERE user_id = ?";
            $stm = $db->prepare($sql);
            $stm->execute([$user_id]);

            $profile = $stm->fetch(PDO::FETCH_OBJ);
            if($profile)
            {
                $db = null;
                $stm = null;

                return $profile;
            }
            else{
                $db = null;
                $stm = null;

                return false;
            }
        }
        public function updateFirstName($user_id,$firstName)
        {
            $db = $this->connect();
            if($db == null)return false;

            $sql = "UPDATE users_profile SET first_name = ? WHERE user_id = ?";
            $db->prepare($sql)->execute([$firstName,$user_id]);

            $db = null;
            
        }
    }
?>