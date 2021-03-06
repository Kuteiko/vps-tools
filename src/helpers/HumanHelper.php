<?php
	namespace vps\tools\helpers;

	use Yii;

	class HumanHelper
	{
		/**
		 * Converts bitrate in b/s to readable format.
		 * @param int    $bitrate
		 * @param string $unit One of: b/s, kb/s, mb/s.
		 * @return string
		 */
		public static function bitrate ($bitrate, $unit = '')
		{
			if (is_numeric($bitrate) and $bitrate >= 0)
			{
				if (( !$unit && $bitrate >= 1000000 ) || $unit == 'mb/s')
					return round($bitrate / ( 1000000 )) . ' mb/s';
				elseif (( !$unit && $bitrate >= 1000 ) || $unit == 'kb/s')
					return round($bitrate / ( 1000 )) . ' kb/s';

				return round($bitrate) . ' b/s';
			}

			return null;
		}

		/**
		 * Convert duration in seconds (with ms) to readable format.
		 * @param string $duration
		 * @return string
		 */
		public static function duration ($duration)
		{
			if (is_numeric($duration))
			{
				$negative = ( $duration < 0 );
				if ($negative)
					$duration = -$duration;

				if (strpos($duration, '.') === false)
					list( $seconds, $tail ) = [ $duration, 0 ];
				else
					list( $seconds, $tail ) = explode('.', $duration);

				$dt = new \DateTime('@0');
				$dt2 = new \DateTime("@" . $seconds);

				return ( $negative ? '-' : '' ) . $dt->diff($dt2)->format('%H:%I:%S') . '.' . str_pad($tail, 3, '0', STR_PAD_RIGHT);
			}

			return null;
		}

		/**
		 * Finds maximum upload size based on PHP settings.
		 * @return null|int Size in bytes.
		 * @see size
		 */
		public static function maxBytesUpload ()
		{
			$values = [
				ini_get('post_max_size'),
				ini_get('upload_max_filesize'),
				ini_get('memory_limit'),
			];

			$min = false;
			foreach ($values as $v)
			{
				if ($v != -1)
				{
					$bytes = self::toBytes($v);
					if ($bytes != null)
						$min = $min ? min($min, $bytes) : $bytes;
				}
			}

			return $min ? $min : null;
		}

		/**
		 * Finds maximum upload size based on PHP settings.
		 * @param mixed $default Value to be returned in case upload size is unlimited.
		 * @return string|null
		 * @see size
		 */
		public static function maxUpload ($default = null)
		{
			$bytes = self::maxBytesUpload();

			return ( $bytes == null ) ? $default : self::size($bytes);
		}

		/**
		 * Converts size to human readable.
		 * @param int    $size Size in bytes.
		 * @param string $unit One of: KB, MB, GB, B.
		 * @return string
		 */
		public static function size ($size, $unit = '')
		{
			if (is_numeric($size) and $size >= 0)
			{
				if (( !$unit && $size >= 1 << 30 ) || $unit == 'GB')
					return round($size / ( 1 << 30 )) . ' GB';
				if (( !$unit && $size >= 1 << 20 ) || $unit == 'MB')
					return round($size / ( 1 << 20 )) . ' MB';
				if (( !$unit && $size >= 1 << 10 ) || $unit == 'KB')
					return round($size / ( 1 << 10 )) . ' KB';

				return round($size) . ' B';
			}

			return null;
		}

		/**
		 * Converts readable string with size to integer in bytes.
		 * @param string $string
		 * @return int|null
		 */
		public static function toBytes ($string)
		{
			$value = null;
			$string = strtoupper($string);

			preg_match('/(\d+)\s?([KMGT]?)/', $string, $match);

			if (isset( $match[ 2 ] ))
			{
				$digit = (int)$match[ 1 ];
				switch ($match[ 2 ])
				{
					case 'K':
						$value = $digit << 10;
						break;

					case 'M':
						$value = $digit << 20;
						break;

					case 'G':
						$value = $digit << 30;
						break;

					case 'T':
						$value = $digit << 40;
						break;

					default:
						$value = $digit;
						break;
				}
			}

			return $value;
		}
	}