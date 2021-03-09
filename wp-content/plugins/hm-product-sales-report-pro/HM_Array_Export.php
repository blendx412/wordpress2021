<?php
/**
 * Author: Potent Plugins
 * License: GNU General Public License version 2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */
if (!class_exists('HM_Array_Export')) {
	class HM_Array_Export {

		private $dataArray;
		
		public function putRow($data, $header=false, $footer=false) {
			$this->dataArray[] = $data;
		}
		
		public function getData() {
			return $this->dataArray;
		}

	}
}


?>