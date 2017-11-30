<?php  

namespace Application\Attribute\Geocode;

defined('C5_EXECUTE') or die("Access Denied.");


use Database;


class Controller extends \Concrete\Core\Attribute\Controller {

	public $helpers = array('form');

	public function getValue() {

		$db = \Database::connection();
        $vals = $db->GetRow("select lat,lng from atGeocode where avID = ?", array($this->getAttributeValueID()));

        if(empty($vals)) return false;

        return $vals;

	}

	public function saveForm($data)
    {
    	
        $this->saveValue($data);
    }

	public function saveValue($data) {
		
		$db = \Database::connection();
		$db->Replace('atGeocode', array('avID' => $this->getAttributeValueID(), 'lat' => $data['lat'], 'lng' => $data['lng']), 'avID', true);
	}


	public function form() {

		$this->set('name', $this->field('value'));

		$ak = $this->getAttributeKey();
		$this->set('akid',$ak->getAttributeKeyID());

		$vals = $this->getValue();

		$lat = "";
		$lng = "";

		if(!empty($vals)) {

			$lat = $vals['lat'];
			$lng = $vals['lng'];

		}


		$this->set('lat', $lat);
		$this->set('lng', $lng);

	}
	
}