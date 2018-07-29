<?php
class LayoutArtistModel extends CI_Model {

	function correct($data){

		$row = new \StdClass();


		
		$query = "SELECT job_layout_id
		FROM tbl_job_layout
		WHERE job_id = " . $data['job_id']
		;

		$row = $this->db->query($query)->row();


		if($row){

			$this->db->where('job_layout_id', $row->job_layout_id);

			if($this->db->update('tbl_job_layout',$data)){
				return "success";
			}else{
				return "failed";
			}
		}else{
			if($this->db->insert('tbl_job_layout',$data)){
				$result = "success";
			}else{
				$result = "failed";
			}
		}

		
		



		

	}

}
	?>