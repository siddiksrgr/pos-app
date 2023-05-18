<?php
  function convert_date_time($value){
    return date('d F Y - H:i:s', strtotime($value));
  }

  function convert_date($value){
    return date('d F Y', strtotime($value));
  }
 
  function convert_rupiah($value){
	  return number_format($value,0,',','.');
  }

  function notifications(){
    $data = \App\Models\Product::where('stock', 0)->get();
    return json_decode(json_encode($data));
  }
?>