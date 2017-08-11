<?php

namespace Chururgie\Model;

class ClassePathologie{
    public $id_classe_pathologie;
    public $nom_classe_pathologie;
    
    public function exchangeArray($data) {
        $this->id_classe_pathologie = (! empty ( $data ['id_classe_pathologie'] )) ? $data ['id_classe_pathologie'] : null;
        $this->nom_classe_pathologie = (! empty ( $data ['nom_classe_pathologie'] )) ? $data ['nom_classe_pathologie'] : null;
      
}
}