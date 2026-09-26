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
        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) VALUES
(110, 'filemanager', 'admin/filemanager', 'fa fa-file-o', 0, 4, '2016-07-25 21:02:59', 1)");

        $this->db->query("ALTER TABLE `tbl_tickets` ADD (last_reply varchar(30) DEFAULT NULL)");
        $this->db->query("ALTER TABLE `tbl_users` ADD (media_path_slug text NULL)");
    }
}
