<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_111 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("CREATE TABLE `tbl_pinaction` (`pinaction_id` int(11) NOT NULL,`user_id` int(11) NOT NULL,`module_id` int(11) NOT NULL,`module_name` varchar(30) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `tbl_pinaction` ADD PRIMARY KEY (`pinaction_id`)");
        $this->db->query("ALTER TABLE `tbl_pinaction` MODIFY `pinaction_id` int(11) NOT NULL AUTO_INCREMENT;;");
        $this->db->query("ALTER TABLE `tbl_transactions` ADD (project_id int(11) NOT NULL);");
    }
}
