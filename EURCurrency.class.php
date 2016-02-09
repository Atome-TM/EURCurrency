<?php
class EURCurrency {

	protected $_urlXML = '';
	protected $_folder = '';

	private $_XMLfile = 'change.xml';
	public $currencies = [];

	public function __construct(
		$folder = "./",
		$urlXML = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml"
	){
		$this->_urlXML = $urlXML;
		$this->_folder = $folder;

		$this->checkXML();
		$this->readXML();
	}

	private function checkXML() {

		// On vérifie si le fichier local existe
		if(!is_file($this->_folder . $this->_XMLfile)) {

			// On vérifie si le chemin spécifié existe
			if(!is_dir($this->_folder)) {
				mkdir($this->_folder, 0777, true);
			}
			$this->saveXML();

		} else {
			$filetime = filemtime($this->_folder . $this->_XMLfile);
			$time = time();

			// On vérifie si le fichier n'a pas déjà été téléchargé aujourd'hui
			if($filetime AND $time AND date('Ymd', $time) != date('Ymd', $filetime)) {
				$this->saveXML();
			}
		}
	}

	private function saveXML() {
		// On récupère le contenu du fichier externe
		$content = file_get_contents($this->_urlXML);
		if($content AND strlen($content) > 0) {

			// On récupère le fichier pur l'enregistrer en local
			$fichier = fopen($this->_folder . $this->_XMLfile, 'w');
			if(fwrite($fichier, $content) === FALSE) {
				throw new Exception('Probleme lors de l\'enregistrement du fichier');
			}
		} else {
			throw new Exception('Il y a un problème avec le fichier externe');
		}
	}

	private function readXML() {
		$XML = simplexml_load_file($this->_folder . $this->_XMLfile);
		foreach($XML->Cube->Cube->Cube as $rate) {
			$this->currencies[$rate['currency']->__toString()] = (float)$rate['rate']->__toString();
		}
	}


	/**
	 * @param int $montant
	 * @param string $currency
	 * @return float
	 * @throws Exception
	 *
	 * Convertis des euros dans la monnaie choisie
	 */
	public function convertTo($montant = 1, $currency = "USD"){

		if(!isset($this->currencies[$currency])) {
			throw new Exception('Cette conversion n\'existe pas');
		}

		return (float)((float)$montant * (float)$this->currencies[$currency]);

	}

	/**
	 * @param int $montant
	 * @param string $currency
	 * @return float
	 * @throws Exception
	 *
	 * Convertis la monnaire choisie en euro
	 */
	public function convertFrom($montant = 1, $currency = "USD") {
		if(!isset($this->currencies[$currency])) {
			throw new Exception('Cette conversion n\'existe pas');
		}

		return (float)((float)$montant / $this->currencies[$currency]);
	}

}
