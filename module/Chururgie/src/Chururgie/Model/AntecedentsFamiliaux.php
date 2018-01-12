<?php
namespace Chururgie\Model;


class AntecedentsFamiliaux {
	public $id_personne;
	public $id_antecedent;
	public $libelle;
	
	public function exchangeArray($data) {
		$this->id_personne = (! empty ( $data ['ID_PERSONNE'] )) ? $data ['ID_PERSONNE'] : null;
		$this->id_antecedent = (! empty ( $data ['ID_ANTECEDENT'] )) ? $data ['ID_ANTECEDENT'] : null;
		$this->libelle = (! empty ( $data ['libelle'] )) ? $data ['libelle'] : null;
	}
}