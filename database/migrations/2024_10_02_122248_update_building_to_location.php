<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDatabaseStructure extends Migration
{
    public function up()
    {
        // เปลี่ยนชื่อตาราง buildings เป็น locations
        Schema::rename('buildings', 'locations');

        // แก้ไขตาราง locations
        Schema::table('locations', function (Blueprint $table) {
            $table->id()->change();
        });

        // แก้ไขตาราง ehcm_guests
        $this->updateTableStructure('ehcm_guests');

        // แก้ไขตาราง ehcm_nets
        $this->updateTableStructure('ehcm_nets');

        // แก้ไขตาราง ehcm_switches
        $this->updateTableStructure('ehcm_switches');

        // แก้ไขตาราง tlcmn_guests
        $this->updateTableStructure('tlcmn_guests');

        // แก้ไขตาราง tlcmn_nets
        $this->updateTableStructure('tlcmn_nets');

        // แก้ไขตาราง tlcmn_switches
        $this->updateTableStructure('tlcmn_switches');

        // แก้ไขตาราง uncm_guests
        $this->updateTableStructure('uncm_guests');

        // แก้ไขตาราง uncm_nets
        $this->updateTableStructure('uncm_nets');

        // แก้ไขตาราง uncm_switches
        $this->updateTableStructure('uncm_switches');
    }

    private function updateTableStructure($tableName)
    {
        Schema::table($tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->after('id');
            $table->dropColumn('location');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /*     public function down()
    {
        // คำสั่งสำหรับ rollback การเปลี่ยนแปลง
        Schema::rename('locations', 'buildings');

        Schema::table('buildings', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        $tables = [
            'ehcm_guests', 'ehcm_nets', 'ehcm_switches',
            'tlcmn_guests', 'tlcmn_nets', 'tlcmn_switches',
            'uncm_guests', 'uncm_nets', 'uncm_switches'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
                $table->string('location');
            });
        }
    } */
}
