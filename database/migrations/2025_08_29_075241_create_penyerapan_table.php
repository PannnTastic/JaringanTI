<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyerapan', function (Blueprint $table) {
            $table->id('id_penyerapan');
            $table->string('no_surat_survey_penyerapan');
            $table->foreignId('substation_id')->constrained('substations', 'substation_id');
            $table->string('lokasi_pekerjaan')->nullable();
            $table->bigInteger('rab_penyerapan')->nullable();
            $table->date('tanggal_nota_dinas')->nullable();//man Infra
            $table->string('diproses')->nullable();// SM UB INFRA
            $table->enum('kontrak_penyerapan', ['Gardu', 'Presales' , 'Backbone' , 'Migrasi' , 'SCADA' , 'UPS/BATERAI'])->nullable();//ADM
            $table->enum('status_transaksi', ['SPK', 'PROSES SPK' , 'PROSES ADM' , 'SURVEY' , 'PROSES AMS' , 'PROSES SM INFRA' , 'REVISI RAB' , 'PROSES ASMAN JARTI' , 'PROSES MAN INFRA'])->nullable();
            $table->string('no_kontrak_spk')->nullable();
            $table->date('tanggal_kontrak_spk')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id')->onDelete('cascade');
            $table->string('estimasi_waktu')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->bigInteger('nilai_spk')->nullable();
            $table->enum('bulan_spk', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'])->nullable();
            $table->bigInteger('amandemen')->nullable();
            $table->bigInteger('tahap1')->nullable();
            $table->bigInteger('tahap2')->nullable();
            $table->string('keterangan_tahap1')->nullable();
            $table->string('keterangan_tahap2')->nullable();
            $table->date('tanggal_bapp')->nullable();
            $table->date('tanggal_bastp')->nullable();
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerapan');
    }
};
