<?php

namespace Chururgie\Model;

class TypePathologie{
    public $id_type_pathologie;
    public $nom_type_pathologie;
    
    public function exchangeArray($data) {
        $this->id_type_pathologie = (! empty ( $data ['id_type_pathologie'] )) ? $data ['id_type_pathologie'] : null;
        $this->nom_type_pathologie = (! empty ( $data ['nom_type_pathologie'] )) ? $data ['nom_type_pathologie'] : null;
      
}
}