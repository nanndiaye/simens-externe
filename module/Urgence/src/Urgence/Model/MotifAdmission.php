<?php
namespace Urgence\Model;

class MotifAdmission{
	public $id_motif;
	public $id_admission_urgence;
	public $libelle_motif;

	public function exchangeArray($data) {
		$this->id_motif = (! empty ( $data ['id_motif'] )) ? $data ['id_motif'] : null;
		$this->id_admission_urgence = (! empty ( $data ['id_admission_urgence'] )) ? $data ['id_admission_urgence'] : null;
		$this->libelle_motif = (! empty ( $data ['libelle_motif'] )) ? $data ['libelle_motif'] : null;
	}
}