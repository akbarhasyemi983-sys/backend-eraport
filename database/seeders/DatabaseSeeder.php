<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\AturanPredikat;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        User::create([
            'name'     => 'Ahmad Fauzi, S.Pd',
            'email'    => 'guru@ibnu-yunsi.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);

        User::create([
            'name'     => 'Admin Sekolah',
            'email'    => 'admin@ibnu-yunsi.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Orang Tua Budi',
            'email'    => 'ortu@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'ortu',
        ]);

        // ===== SISWA =====
        $siswas = [
            ['nis' => '2024001', 'nama' => 'Budi Santoso',       'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Santoso',  'kelas' => '1A'],
            ['nis' => '2024002', 'nama' => 'Siti Rahayu',        'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Rahayu',    'kelas' => '1A'],
            ['nis' => '2024003', 'nama' => 'Muhammad Rizky',     'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Rizky',    'kelas' => '1A'],
            ['nis' => '2024004', 'nama' => 'Dewi Kartika',       'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Kartika',   'kelas' => '1A'],
            ['nis' => '2024005', 'nama' => 'Fajar Setiawan',     'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Setiawan', 'kelas' => '1A'],
            ['nis' => '2024006', 'nama' => 'Nurul Hidayah',      'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Hidayah',   'kelas' => '1A'],
            ['nis' => '2024007', 'nama' => 'Andi Firmansyah',    'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Firmansyah','kelas' => '1A'],
            ['nis' => '2024008', 'nama' => 'Putri Anggraini',    'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Anggraini', 'kelas' => '1A'],
            ['nis' => '2024009', 'nama' => 'Doni Prasetyo',      'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Prasetyo', 'kelas' => '1B'],
            ['nis' => '2024010', 'nama' => 'Lestari Ningrum',    'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Ningrum',   'kelas' => '1B'],
        ];

        foreach ($siswas as $s) {
            Siswa::create(array_merge($s, ['tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil']));
        }

        // ===== MATA PELAJARAN (Kurikulum Merdeka Fase A Kelas 1) =====
        $mapels = [
            ['kode' => 'PAI',   'nama' => 'Pendidikan Agama Islam dan Budi Pekerti'],
            ['kode' => 'PKN',   'nama' => 'Pendidikan Pancasila'],
            ['kode' => 'BIN',   'nama' => 'Bahasa Indonesia'],
            ['kode' => 'MTK',   'nama' => 'Matematika'],
            ['kode' => 'IPAS',  'nama' => 'Ilmu Pengetahuan Alam dan Sosial'],
            ['kode' => 'SBDP',  'nama' => 'Seni Budaya dan Prakarya'],
            ['kode' => 'PJOK',  'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
        ];

        foreach ($mapels as $m) {
            MataPelajaran::create(array_merge($m, ['fase' => 'A', 'kelas' => '1']));
        }

        // ===== TUJUAN PEMBELAJARAN =====
        $mapelBIN = MataPelajaran::where('kode', 'BIN')->first();
        TujuanPembelajaran::create(['mapel_id' => $mapelBIN->id, 'kode' => 'BIN.1', 'deskripsi' => 'Menyimak dengan saksama dan merespons secara lisan']);
        TujuanPembelajaran::create(['mapel_id' => $mapelBIN->id, 'kode' => 'BIN.2', 'deskripsi' => 'Membaca dan memahami teks sederhana']);
        TujuanPembelajaran::create(['mapel_id' => $mapelBIN->id, 'kode' => 'BIN.3', 'deskripsi' => 'Menulis kata dan kalimat sederhana']);

        $mapelMTK = MataPelajaran::where('kode', 'MTK')->first();
        TujuanPembelajaran::create(['mapel_id' => $mapelMTK->id, 'kode' => 'MTK.1', 'deskripsi' => 'Memahami bilangan cacah sampai dengan 100']);
        TujuanPembelajaran::create(['mapel_id' => $mapelMTK->id, 'kode' => 'MTK.2', 'deskripsi' => 'Melakukan operasi penjumlahan dan pengurangan']);

        // ===== ATURAN PREDIKAT (Rule-Based) =====
        $aturans = [
            // Bahasa Indonesia
            ['mapel' => 'BIN', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang sangat baik. Peserta didik mampu menyimak, membaca, dan menulis dengan sangat lancar serta aktif berpartisipasi dalam kegiatan literasi kelas.'],
            ['mapel' => 'BIN', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang baik. Peserta didik mampu menyimak dan membaca teks sederhana serta mulai terampil menulis kata dan kalimat pendek.'],
            ['mapel' => 'BIN', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang cukup. Peserta didik perlu lebih banyak berlatih membaca dan menulis untuk meningkatkan kemampuan literasinya.'],
            ['mapel' => 'BIN', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan intensif dalam kemampuan berbahasa Indonesia. Disarankan untuk memberikan latihan membaca dan menulis secara rutin di rumah.'],
            // Matematika
            ['mapel' => 'MTK', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang sangat baik. Peserta didik mampu memahami konsep bilangan dan melakukan operasi hitung dengan cepat dan tepat.'],
            ['mapel' => 'MTK', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang baik. Peserta didik mampu memahami bilangan dan menyelesaikan soal penjumlahan serta pengurangan dengan baik.'],
            ['mapel' => 'MTK', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang cukup. Peserta didik perlu lebih banyak berlatih operasi hitung dan pemahaman konsep bilangan.'],
            ['mapel' => 'MTK', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam memahami konsep matematika dasar. Latihan rutin setiap hari sangat disarankan untuk meningkatkan kemampuan berhitungnya.'],
            // PAI
            ['mapel' => 'PAI', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang sangat baik dalam Pendidikan Agama Islam. Peserta didik hafal doa-doa harian, mampu membaca huruf hijaiyah dengan baik, dan menunjukkan akhlak mulia dalam keseharian.'],
            ['mapel' => 'PAI', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang baik dalam Pendidikan Agama Islam. Peserta didik mampu menghafal beberapa doa harian dan mengenal huruf hijaiyah dengan baik.'],
            ['mapel' => 'PAI', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang cukup dalam Pendidikan Agama Islam. Perlu peningkatan dalam hafalan doa dan pengenalan huruf hijaiyah.'],
            ['mapel' => 'PAI', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam Pendidikan Agama Islam. Orang tua diharapkan mendampingi anak untuk belajar doa-doa harian dan huruf hijaiyah di rumah.'],
            // PKN
            ['mapel' => 'PKN', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang sangat baik tentang Pendidikan Pancasila. Peserta didik mampu menjelaskan nilai-nilai Pancasila dan menerapkannya dalam kehidupan sehari-hari.'],
            ['mapel' => 'PKN', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang baik tentang Pendidikan Pancasila dan mengenal simbol-simbol negara dengan baik.'],
            ['mapel' => 'PKN', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang cukup tentang Pendidikan Pancasila. Perlu peningkatan dalam mengenal dan memahami nilai-nilai dasar Pancasila.'],
            ['mapel' => 'PKN', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam memahami Pendidikan Pancasila. Pembiasaan nilai-nilai Pancasila di lingkungan rumah sangat dianjurkan.'],
            // PJOK
            ['mapel' => 'PJOK', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang sangat baik dalam PJOK. Peserta didik aktif, bersemangat, dan memiliki keterampilan gerak dasar yang sangat baik.'],
            ['mapel' => 'PJOK', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang baik dalam PJOK. Peserta didik aktif mengikuti kegiatan olahraga dan memiliki keterampilan gerak dasar yang baik.'],
            ['mapel' => 'PJOK', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang cukup dalam PJOK. Perlu peningkatan dalam keterampilan gerak dasar dan semangat berolahraga.'],
            ['mapel' => 'PJOK', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam kegiatan PJOK. Olahraga rutin setiap hari sangat dianjurkan untuk meningkatkan kebugaran dan keterampilan geraknya.'],
        ];

        foreach ($aturans as $a) {
            AturanPredikat::create($a);
        }
    }
}
