<?php

require_once(HelperPath.DS.'DatabaseAccessModel.php');
require_once(ModelPath.DS.'UserModel.php');

	class UserRepository extends DatabaseAccessModel {

		private static $tblName = 'user';
		private static $childTblName = 'cookie';
		private static $userId = 'userId';
		private static $uniqueId = 'uniqueId';
		private static $username = 'username';
		private static $password = 'password';
		private static $expDate = 'rememberme';
		private static $hashType = 'sha256';


		function authenticateUser ($username, $password) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT * FROM user WHERE " . self::$username . " = ? AND " . self::$password . " = ?";
				$params = array($username, hash(self::$hashType, $password));
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result ? true : false;
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}
		}

		function makeUser ($username) {

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

			// This works for insert!!

			// try {

			// 	$db = $this->dbFactory->createInstance();

			// 	$sql = "INSERT INTO user (uniqueId, username, password) VALUES (?, ?, ?)";
			// 	$params = array($user->getUnique(), $user->getUsername(), $user->getPassword());
			// 	$query = $db->prepare($sql);
			// 	$query -> execute($params);

			// Also create a cookie row belonging to the user (identified by uniqueId).

			// }
			// catch (Exeption $e) {

			// 	throw ('Connection error!');
			// }
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

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$expDate . " FROM " . self::$childTblName;
				$sql .= " WHERE " . self::$uniqueId . " = ?";
				$params = array($uniqueId);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result;
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}
		}

		function generateUniqueId () {

		    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		    $uniqueId = array(); //remember to declare $uniqueId as an array

		    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

		    for ($i = 0; $i < 20; $i++) {
		        $n = rand(0, $alphaLength);
		        $uniqueId[] = $alphabet[$n];
		    }

		    return implode($uniqueId); //turn the array into a string
		}
	}