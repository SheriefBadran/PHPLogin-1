<?php

require_once(HelperPath.DS.'DatabaseAccessModel.php');

	class UserRepository extends DatabaseAccessModel {


		function authenticateUser ($username, $password) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT * FROM user WHERE username = ? AND password = ?";
				$params = array($username, hash('sha256', $password));
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result ? true : false;
			}
			catch (Exeption $e) {

				throw ('Connection error!');
			}
		}

		function userExist ($username) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT username FROM user WHERE username = ?";
				$params = array($username);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result ? true : false;
			}
			catch (Exception $e) {

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


			// }
			// catch (Exeption $e) {

			// 	throw ('Connection error!');
			// }
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