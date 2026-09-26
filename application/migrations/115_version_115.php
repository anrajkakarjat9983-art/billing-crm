<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_115 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("ALTER TABLE tbl_project
  ADD (hourly_rate varchar(200) NULL,fixed_rate varchar(8) NULL,with_tasks enum('yes','no') NOT NULL DEFAULT 'no')");
    }
}
