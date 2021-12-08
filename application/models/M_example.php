<?php
class M_example  extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function cb_mapel($id){
        $mapel=$this->db->query("SELECT * FROM mapel where id_mapel=?",array($id));
        $nama="";
        foreach($mapel->result() as $d){
            $nama=$d->nama_mapel;
        }
        return $nama;
    }

    function cb_guru($id){
        $guru=$this->db->query("SELECT * FROM guru where id_guru=?",array($id));
        $nama="";
        foreach($guru->result() as $d){
            $nama=$d->nama_guru;
        }
        return $nama;
    }

    function cb_kelas($id){
        $kelas=$this->db->query("SELECT * FROM kelas where id_kelas=?",array($id));
        $nama="";
        foreach($kelas->result() as $d){
            $nama=$d->nama_kelas;
        }
        return $nama;
    }

    function col_mapel()
    {
        $query = $this->db->query("SELECT * FROM mapel");
        return $query;
    }

    function col_guru(){
        $query = $this->db->query("SELECT * FROM guru");

        return $query;
    }


    function col_kelas(){
        $query = $this->db->query("SELECT * FROM kelas");

        return $query;
    }
}
