<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableMonitoringMutasiStok extends Migration
{
    public function up()
    {
        // Since forge doesn't support column renaming, we use raw SQL with CHANGE
        $this->db->query("
            ALTER TABLE `t_monitoringmutasistok` 
            CHANGE `STOKAWAL` `STOKFISIK` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKMUTASI` `STOKSOTERTAHAN` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKDITAHAN` `STOKBELUMKIRIM` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKTERSEDIA` `STOKBARANGREJECT` INT NOT NULL DEFAULT '0'
        ");

        $this->db->query("
            ALTER TABLE `t_monitoringmutasistok` 
            ADD `STOKMUTASIREGIONAL` INT NOT NULL DEFAULT '0' AFTER `STOKBARANGREJECT`, 
            ADD `STOKTERSEDIA` INT NOT NULL DEFAULT '0' AFTER `STOKMUTASIREGIONAL`
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE `t_monitoringmutasistok` 
            DROP COLUMN `STOKTERSEDIA`, 
            DROP COLUMN `STOKMUTASIREGIONAL`
        ");

        $this->db->query("
            ALTER TABLE `t_monitoringmutasistok` 
            CHANGE `STOKFISIK` `STOKAWAL` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKSOTERTAHAN` `STOKMUTASI` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKBELUMKIRIM` `STOKDITAHAN` INT NOT NULL DEFAULT '0', 
            CHANGE `STOKBARANGREJECT` `STOKTERSEDIA` INT NOT NULL DEFAULT '0'
        ");
    }
}
