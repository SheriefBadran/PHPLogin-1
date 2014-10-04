<?php

require_once(HelperPath.DS.'DatabaseAccessModel.php');
require_once(ModelPath.DS.'UserModel.php');

	class UserRepository extends DatabaseAccessModel {

		private static $tblName = 'user';
		private static $childTblName = 'cookie';
		private static $userId = 'userId';
		private static $uniqueId = 'uniqueId';
		private static $uniqueIdLength = 20;
		private static $username = 'username';
		private static $password = 'password';
		private static $expDate = 'rememberme';


		function authenticateUser ($username, $password) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT * FROM user WHERE " . self::$username . " = ? AND " . self::$password . " = ?";
				$params = array($username, $password);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result ? true : false;
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}
		}

		function makeUser ($uniqueId, $username, $password) {

			try {

				$user = new UserModel($uniqueId, $username, $password);
			}
			catch (Exception $e) {

				throw new \Exception('Username contains invalid characters!');
			}

			return $user;
		}

		function getUser ($username, $uniqueId = '') {

			if ($uniqueId !== '' && !is_string($uniqueId) && strlen($uniqueId) !== self::$uniqueIdLength) {
				
				throw new \Exception('Parameter has to be a unique id with type string and length' . 
					(string)(self::$uniqueIdLength));
			}

			if ($uniqueId !== '') {
				
				try {

					$db = $this->dbFactory->createInstance();

					$sql = "SELECT * FROM " . self::$tblName . " WHERE " . self::$username . " = ?";
					$sql .= " AND " . self::$uniqueId . " = ?";
					$params = array($username, $uniqueId);
					$query = $db->prepare($sql);
					$query->execute($params);
					$result = $query->fetch();

					if ($result) {
						
						$user = new UserModel($result[self::$uniqueId], $result[self::$username], $result[self::$password]);
						return $user;
					}
					else {

						// if there was no result, return null instead of user object.
						return null;
					}
				}
				catch (PDOException $e) {

					throw new \Exception('DB Error!');
				}
			}
			else {

				try {

					$db = $this->dbFactory->createInstance();

					$sql = "SELECT * FROM " . self::$tblName . " WHERE " . self::$username . " = ?";
					$params = array($username);
					$query = $db->prepare($sql);
					$query->execute($params);
					$result = $query->fetch();

					if ($result) {
						
						$user = new UserModel($result[self::$uniqueId], $result[self::$username], $result[self::$password]);
						return $user;
					}
					else {

						// if there was no result, return null instead of user object.
						return null;
					}
				}
				catch (PDOException $e) {

					throw ('DB Error!');
				}
			}
		}

		function userExist ($username) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$username . " FROM " . self::$tblName . " WHERE " . self::$username . " = ?";
				$params = array($username);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result ? true : false;
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}
		}

		function createUser (UserModel $user) {

			try {

				// I could write a SPROC instead, and assure that the uniqueId and username does not already exist in either
				// the user table or the cookie table prior to insert.

				$db = $this->dbFactory->createInstance();

				$sql = "INSERT INTO " . self::$tblName;
				$sql .= " (" . self::$uniqueId . ", " . self::$username . ", " . self::$password . ") VALUES (?, ?, ?)";
				$params = array($user->getUniqueId(), $user->getUsername(), $user->getPassword());
				$query = $db->prepare($sql);
				$query -> execute($params);				

				// Also create a cookie row belonging to the user (identified by uniqueId).
				// In a sproc I have to make sure that the first statement is executed successfully before the second is.
				// If the second statement is not executed successfully, make a rollback and throw an exception.
				$sql = "INSERT INTO " . self::$childTblName;
				$sql .= " (" . self::$uniqueId . ") VALUES (?)";
				$params = array($user->getUniqueId());
				$query = $db->prepare($sql);
				$query -> execute($params);

			}
			catch (Exeption $e) {

				throw ('Connection error!');
			}
		}

		function saveCookieExpTime ($uniqueId, $time) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "UPDATE " . self::$childTblName . " SET ";
				$sql .= self::$expDate . " = ?";
				$sql .= "WHERE " . self::$uniqueId . "= ?";
				$params = array($time, $uniqueId);
				$query = $db->prepare($sql);
				$query->execute($params);
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}	
		}

		function getCookieExpTime ($uniqueId) {

			if (!is_string($uniqueId) && strlen($uniqueId) !== self::$uniqueIdLength) {

				throw new \Exception('Parameter has to be a unique id with type string and length' . 
					(string)(self::$uniqueIdLength));
			}

			$expTime;

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$expDate . " FROM " . self::$childTblName;
				$sql .= " WHERE " . self::$uniqueId . " = ?";
				$params = array($uniqueId);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				if ($result) {

					$expTime = array_shift($result);
				}

				return $result ? $expTime : $result;
			}
			catch (PDOException $e) {

				throw new \Exception('DB Error!');
			}
		}

		// TODO: Maybe move this to the UserModel?
		function generateUniqueId () {

			// remember to declare $uniqueId as an array
		    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		    $uniqueId = array(); 

		    // put the length -1 in cache
		    $alphaLength = strlen($alphabet) - 1; 

		    for ($i = 0; $i < 20; $i++) {
		        $n = rand(0, $alphaLength);
		        $uniqueId[] = $alphabet[$n];
		    }

		    // turn the array into a string
		    return implode($uniqueId);
		}
	}