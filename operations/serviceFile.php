<?php

  class ServiceFile{

    private $FileHandler;
    private $Utilities;
    private $directory;

    public function __construct($ruta = "./"){
      $this->directory = "{$ruta}data";
      $this->filename = "transacciones";
      $this->jsonHandler = new JsonFileHandler($this->directory,$this->filename);
      $this->txtHandler = new SerializationFileHandler($this->directory,$this->filename);
      $this->Utilities = new Utilities();
    }

    public function GetList(){
      $transacciones = $this->FileHandler->ReadFile();
      if($transacciones == null){
        $transacciones = array();
      }
      return (array)$transacciones;
    }

    public function GetByID($id){
      $transacciones = $this->GetList();
      $transaccion = $this->Utilities->SearchProperty($transacciones, "ID", $id);
      return ((count($transaccion) == 0) ? null : $transaccion[0]);
    }

    public function Add($item){
      $transacciones = $this->GetList();
      do {
        $not_in_list = true;
        $id = uniqid('',true);
        foreach ($transacciones as $transaccion) {
          if($id == $transaccion->ID){
            $not_in_list = false;
          }
        }
      } while ($not_in_list == false);

      $item->ID = $id;
      array_push($transacciones,$item);
      $this->FileHandler->SaveFile($transacciones);
      $this->txtHandler->SaveFile($transacciones);
    }

    public function Edit($item){
      $transacciones = $this->GetList();
      $index = $this->Utilities->GetIndexElement($transacciones, "ID", $item->ID);
      if($index !== null){
        $transacciones[$index] = $item;
        $this->FileHandler->SaveFile($transacciones);
        $this->txtHandler->SaveFile($transacciones);
      }
    }

    public function Delete($id){
      $transacciones = $this->GetList();
      $index = $this->Utilities->getIndexElement($transacciones,"ID",$id);
      if($index !== null){
        unset($transacciones[$index]);
        $this->FileHandler->SaveFile($transacciones);
        $this->txtHandler->SaveFile($transacciones);
      }
    }
  }

?>