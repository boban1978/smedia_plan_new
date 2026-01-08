<?php
class CoreEngine {
	public static function getConnection() {
		return new CoreMySQLConnection();
	}
}