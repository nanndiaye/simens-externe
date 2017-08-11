<?php

namespace Chururgie\Model;

class ClasseOrganePathologie{
    public $id_Classe_Organe_pathologie;
    public $id_organe;
    public $id_classe_pathologie;
    
    public function exchangeArray($data) {
        $this->$id_organe = (! empty ( $data ['id_organe'] )) ? $data ['id_organe'] : null;
        $this->$id_Classe_Organe_Pathologie = (! empty ( $data ['id_Classe_Organe_Pathologie'] )) ? $data ['id_Classe_Organe_Pathologie'] : null;
        $this->$id_classe_pathologie = (! empty ( $data ['id_classe_pathologie'] )) ? $data ['id_classe_pathologie'] : null;

}
}