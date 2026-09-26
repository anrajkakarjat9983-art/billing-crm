<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_114 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("ALTER TABLE tbl_transactions ADD permission text;");
        $this->db->query("ALTER TABLE tbl_transfer ADD permission text;");
    }
}
