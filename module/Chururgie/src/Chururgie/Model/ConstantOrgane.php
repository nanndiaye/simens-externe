<?php

namespace Chururgie\Model;

class ConstantOrgane{
    public $id_consultant_organe;
    public $id_patient;
    
    public function exchangeArray($data) {
        $this->id_consultant_organe = (! empty ( $data ['id_consultant_organe'] )) ? $data ['id_consultant_organe'] : null;
        $this->id_patient = (! empty ( $data ['id_patient'] )) ? $data ['id_patient'] : null;
        $this->id_classe_pathologie = (! empty ( $data ['id_classe_pathologie'] )) ? $data ['id_classe_pathologie'] : null;

      
}
}