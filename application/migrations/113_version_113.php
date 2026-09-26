<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_113 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("ALTER TABLE tbl_accounts ADD permission text;");
    }
}
