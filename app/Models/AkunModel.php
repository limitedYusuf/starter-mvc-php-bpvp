<?php

class AkunModel extends Model
{
   public function authenticate($username, $password)
   {
      $query = "SELECT * FROM users WHERE username = :username";
      $stmt = $this->prepare($query);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_OBJ);

      if ($user && password_verify($password, $user->password)) {
         if ($user->status === 'Y') {
            Helper::setSession('user_data', [
               'nama' => $user->nama,
               'id' => $user->id,
               'role' => $user->role,
            ]);

            return $user;
         } else {
            return 'disabled';
         }
      }

      return false;
   }
}