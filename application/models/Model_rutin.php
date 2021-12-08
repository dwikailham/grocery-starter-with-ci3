<?php
class Model_rutin  extends CI_Model  {
	function __construct()
    {
        parent::__construct();
    }
    
    function inc_id($tabel,$id,$where){
    	if($where==""){
    		$dt=$this->db->query("select max(".$id.") as maks from ".$tabel);
    		$maks=1;
    		foreach($dt->result() as $r){
    			$maks=$maks+$r->maks;
    		}
    		return $maks;
    	}else{
    	
    	}
    }
    
    function popup($alamat,$judul,$row,$h){
	    echo '
	      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <!-- Modal -->
  <div class="modal fade" id="myModal'.$row.'" role="dialog">
    <div class="modal-dialog" style="width:80%;">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">'.$judul.'</h4>
        </div>
        <div class="modal-body">
          <iframe src="'.$alamat.'" name="iframe_a" height="'.$h.'%" width="100%" title="Iframe Example" frameBorder="0"></iframe>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
	    ';

    }

	function _modal($alamat,$value,$row){
		return '<a role="button" class="btn btn-info" style="padding:2px;font-size:12px;color:#fff;float:right;padding-left:10px;padding-right:10px;" data-toggle="modal" data-target="#myModal" onclick="coba'.$row.'()">'.$value.'</a>
		<script>
			function coba'.$row.'(){
				var el = document.getElementById("iframe_a");
				el.src = "'.$alamat.'"; // assign url to src property
			}
		</script>
		';
		

	}
	
	function disabled($a,$b){
		return	"<input type=hidden id='".$a."' name='".$a."' value='".$b."'>".$b;
	}

	function display0($dt){
		if($dt!=0){
			echo "style='border:none;'";
		}else{
			echo "style='border:none;color:blue;'";		
		}
	}

	function pilih($field,$nil){
		if($field==$nil){echo "selected";}
	}


	function display1($dt){
		if($dt!=''){
			echo "style='border:none;color:black;'";
		}else{
			echo "style='border:none;color:blue;'";		
		}
	}

	function display2($dt){
		if($dt!=0){
			echo "style='border-color:inherit;text-align:center;'";
		}else{
			echo "style='border:1;color:blue;text-align:center;'";		
		}
	}
	
}
