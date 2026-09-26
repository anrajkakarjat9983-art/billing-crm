<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_116 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("INSERT INTO `tbl_languages` (`code`, `name`, `icon`, `active`) VALUES
('ar', 'arabic', 'ae', 1),
('pl', 'polish', 'pl', 1),
('tr', 'turkish', 'cy', 1)");
    }
}
