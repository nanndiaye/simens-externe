<?php

namespace Chururgie\Model;

class ClassePathologie{
    public $id_organe;
    public $LESORGANES;
    
    public function exchangeArray($data) {
        $this->id_organe = (! empty ( $data ['id_organe'] )) ? $data ['id_organe'] : null;
        $this->LESORGANES = (! empty ( $data ['LESORGANES'] )) ? $data ['LESORGANES'] : null;
      
}
}