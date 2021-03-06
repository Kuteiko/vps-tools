<?php
	namespace vps\tools\helpers;

	class Url extends \yii\helpers\Url
	{
		/**
		 * Immediate redirect to given URL.
		 * @param string $url
		 */
		public static function redirect ($url)
		{
			header('Location: ' . $url);
			exit();
		}
	}
