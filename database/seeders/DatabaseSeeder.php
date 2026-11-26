<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\AturanPredikat;
use App\Models\Nilai;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (urutan penting karena foreign key)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai')->truncate();
        DB::table('narasi_capaian')->truncate();
        DB::table('tujuan_pembelajaran')->truncate();
        DB::table('aturan_predikat')->truncate();
        DB::table('mata_pelajaran')->truncate();
        DB::table('siswa')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::table('users')->truncate();
        DB::table('settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ===== SETTINGS =====
        DB::table('settings')->insert([
            ['key' => 'school_name',    'value' => 'SDIT Ibnu Yunsi'],
            ['key' => 'school_address', 'value' => 'Jl H. A. H. Hasan Lk 4 Binjai barat'],
            ['key' => 'tahun_ajaran',   'value' => '2025/2026'],
            ['key' => 'semester',       'value' => 'Ganjil'],
            ['key' => 'kepala_sekolah', 'value' => 'Dr. H. Ahmad Zainuddin, M.Pd'],
            ['key' => 'nip_kepsek',     'value' => '197001011995011001'],
        ]);

        // ===== USERS =====
        $guru = User::create([
            'name'     => 'Ahmad Fauzi, S.Pd',
            'email'    => 'guru@ibnu-yunsi.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);

        $admin = User::create([
            'name'     => 'Admin Sekolah',
            'email'    => 'admin@ibnu-yunsi.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // ===== SISWA =====
        $siswasData = [
            ['nis' => '240001', 'nisn' => '0012345601', 'nama' => 'Budi Santoso',       'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Santoso',   'kelas' => 'I A'],
            ['nis' => '240002', 'nisn' => '0012345602', 'nama' => 'Siti Rahayu',        'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Rahayu',     'kelas' => 'I A'],
            ['nis' => '240003', 'nisn' => '0012345603', 'nama' => 'Muhammad Rizky',     'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Rizky',     'kelas' => 'I A'],
            ['nis' => '240004', 'nisn' => '0012345604', 'nama' => 'Dewi Kartika',       'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Kartika',    'kelas' => 'I A'],
            ['nis' => '240005', 'nisn' => '0012345605', 'nama' => 'Fajar Setiawan',     'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Setiawan',  'kelas' => 'I A'],
            ['nis' => '240006', 'nisn' => '0012345606', 'nama' => 'Nurul Hidayah',      'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Hidayah',    'kelas' => 'I A'],
            ['nis' => '240007', 'nisn' => '0012345607', 'nama' => 'Andi Firmansyah',    'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Firmansyah','kelas' => 'I A'],
            ['nis' => '240008', 'nisn' => '0012345608', 'nama' => 'Putri Anggraini',    'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Anggraini',  'kelas' => 'I A'],
            ['nis' => '240009', 'nisn' => '0012345609', 'nama' => 'Doni Prasetyo',      'jenis_kelamin' => 'L', 'nama_ortu' => 'Pak Prasetyo',  'kelas' => 'I B'],
            ['nis' => '240010', 'nisn' => '0012345610', 'nama' => 'Lestari Ningrum',    'jenis_kelamin' => 'P', 'nama_ortu' => 'Bu Ningrum',    'kelas' => 'I B'],
        ];

        $siswas = [];
        foreach ($siswasData as $s) {
            $siswas[] = Siswa::create(array_merge($s, ['tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil']));
        }

        // Ortu user - linked ke siswa pertama (Budi Santoso)
        User::create([
            'name'     => 'Orang Tua Budi',
            'email'    => 'ortu@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'ortu',
            'siswa_id' => $siswas[0]->id,
        ]);

        // ===== MATA PELAJARAN =====
        $mapelDefs = [
            ['kode' => 'PAI',  'nama' => 'Pendidikan Agama Islam dan Budi Pekerti'],
            ['kode' => 'PKN',  'nama' => 'Pendidikan Pancasila'],
            ['kode' => 'BIN',  'nama' => 'Bahasa Indonesia'],
            ['kode' => 'MTK',  'nama' => 'Matematika'],
            ['kode' => 'IPAS', 'nama' => 'Ilmu Pengetahuan Alam dan Sosial'],
            ['kode' => 'SBDP', 'nama' => 'Seni Budaya dan Prakarya'],
            ['kode' => 'PJOK', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
        ];

        $mapels = [];
        foreach ($mapelDefs as $m) {
            $mapels[$m['kode']] = MataPelajaran::create(array_merge($m, ['fase' => 'A', 'kelas' => '1']));
        }

        // ===== TUJUAN PEMBELAJARAN =====
        $tpDefs = [
            'PAI' => [
                ['kode' => 'PAI.1', 'deskripsi' => 'Menyebutkan nama-nama Allah dan sifatnya dengan benar'],
                ['kode' => 'PAI.2', 'deskripsi' => 'Menghafal doa-doa harian (makan, tidur, belajar, masuk dan keluar WC)'],
                ['kode' => 'PAI.3', 'deskripsi' => 'Mengenal dan membaca huruf hijaiyah dengan benar'],
                ['kode' => 'PAI.4', 'deskripsi' => 'Menerapkan perilaku jujur, amanah, dan santun dalam kehidupan sehari-hari'],
            ],
            'PKN' => [
                ['kode' => 'PKN.1', 'deskripsi' => 'Mengenal simbol-simbol Pancasila dan artinya'],
                ['kode' => 'PKN.2', 'deskripsi' => 'Menerapkan nilai-nilai Pancasila dalam kehidupan sehari-hari'],
                ['kode' => 'PKN.3', 'deskripsi' => 'Mengenal hak dan kewajiban sebagai warga kelas'],
            ],
            'BIN' => [
                ['kode' => 'BIN.1', 'deskripsi' => 'Menyimak dengan saksama dan merespons secara lisan'],
                ['kode' => 'BIN.2', 'deskripsi' => 'Membaca dan memahami teks sederhana dengan lafal yang benar'],
                ['kode' => 'BIN.3', 'deskripsi' => 'Menulis kata dan kalimat sederhana dengan benar'],
            ],
            'MTK' => [
                ['kode' => 'MTK.1', 'deskripsi' => 'Memahami bilangan cacah sampai dengan 100'],
                ['kode' => 'MTK.2', 'deskripsi' => 'Melakukan operasi penjumlahan dengan hasil sampai 20'],
                ['kode' => 'MTK.3', 'deskripsi' => 'Melakukan operasi pengurangan dengan hasil sampai 20'],
                ['kode' => 'MTK.4', 'deskripsi' => 'Mengenal dan membandingkan panjang, berat, dan waktu'],
            ],
            'IPAS' => [
                ['kode' => 'IPAS.1', 'deskripsi' => 'Mengenal anggota tubuh manusia dan fungsinya'],
                ['kode' => 'IPAS.2', 'deskripsi' => 'Mengenal hewan dan tumbuhan di lingkungan sekitar'],
                ['kode' => 'IPAS.3', 'deskripsi' => 'Memahami peran anggota keluarga dan masyarakat'],
            ],
            'SBDP' => [
                ['kode' => 'SBDP.1', 'deskripsi' => 'Mengekspresikan diri melalui karya seni rupa sederhana'],
                ['kode' => 'SBDP.2', 'deskripsi' => 'Menyanyikan lagu anak-anak dengan nada dan irama yang benar'],
                ['kode' => 'SBDP.3', 'deskripsi' => 'Membuat kerajinan tangan sederhana dari bahan alam'],
            ],
            'PJOK' => [
                ['kode' => 'PJOK.1', 'deskripsi' => 'Melakukan gerak dasar lokomotor (berjalan, berlari, melompat)'],
                ['kode' => 'PJOK.2', 'deskripsi' => 'Melakukan gerak dasar non-lokomotor (melentur, memutar, mengayun)'],
                ['kode' => 'PJOK.3', 'deskripsi' => 'Mengenal dan menerapkan pola hidup sehat'],
            ],
        ];

        $tps = [];
        foreach ($tpDefs as $kode => $tpList) {
            foreach ($tpList as $tp) {
                $tps[$kode][] = TujuanPembelajaran::create([
                    'mapel_id'  => $mapels[$kode]->id,
                    'kode'      => $tp['kode'],
                    'deskripsi' => $tp['deskripsi'],
                ]);
            }
        }

        // ===== NILAI DUMMY (untuk 8 siswa kelas I A) =====
        $nilaiDummy = [
            // [PAI, PKN, BIN, MTK, IPAS, SBDP, PJOK]
            ['Budi Santoso',    [78, 82, 80, 85, 76, 90, 88]],
            ['Siti Rahayu',     [92, 88, 90, 78, 85, 92, 80]],
            ['Muhammad Rizky',  [85, 80, 75, 92, 88, 78, 90]],
            ['Dewi Kartika',    [88, 90, 85, 80, 90, 85, 82]],
            ['Fajar Setiawan',  [75, 78, 72, 88, 80, 76, 92]],
            ['Nurul Hidayah',   [90, 85, 88, 75, 82, 88, 78]],
            ['Andi Firmansyah', [82, 76, 78, 90, 85, 80, 85]],
            ['Putri Anggraini', [86, 92, 88, 82, 78, 90, 76]],
        ];

        $kodeMapel = ['PAI', 'PKN', 'BIN', 'MTK', 'IPAS', 'SBDP', 'PJOK'];

        foreach ($nilaiDummy as [$namaS, $nilaiArr]) {
            $siswaObj = collect($siswas)->firstWhere('nama', $namaS);
            if (!$siswaObj) continue;

            foreach ($kodeMapel as $i => $kode) {
                $avg = $nilaiArr[$i];
                $predikat = $avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : 'D'));

                Nilai::create([
                    'siswa_id'    => $siswaObj->id,
                    'mapel_id'    => $mapels[$kode]->id,
                    'nilai_tugas' => $avg - 2,
                    'nilai_uh'    => $avg + 1,
                    'nilai_uts'   => $avg - 1,
                    'nilai_uas'   => $avg + 2,
                    'nilai_akhir' => (float)$avg,
                    'predikat'    => $predikat,
                    'tahun_ajaran'=> '2025/2026',
                    'semester'    => 'Ganjil',
                ]);
            }
        }

        // ===== ATURAN PREDIKAT =====
        $aturans = [
            ['mapel' => 'PAI', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang sangat baik dalam Pendidikan Agama Islam dan Budi Pekerti. Peserta didik hafal doa-doa harian, mampu membaca huruf hijaiyah dengan lancar, dan menunjukkan akhlak mulia dalam keseharian.'],
            ['mapel' => 'PAI', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang baik dalam Pendidikan Agama Islam dan Budi Pekerti. Peserta didik mampu menghafal beberapa doa harian dan mengenal huruf hijaiyah dengan baik.'],
            ['mapel' => 'PAI', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang cukup dalam Pendidikan Agama Islam dan Budi Pekerti. Perlu peningkatan dalam hafalan doa harian dan pengenalan huruf hijaiyah.'],
            ['mapel' => 'PAI', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan intensif dalam Pendidikan Agama Islam. Orang tua diharapkan mendampingi anak untuk belajar doa-doa harian dan huruf hijaiyah secara rutin di rumah.'],

            ['mapel' => 'PKN', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang sangat baik tentang Pendidikan Pancasila. Peserta didik mampu menjelaskan nilai-nilai Pancasila dan menerapkannya dalam kehidupan sehari-hari dengan sangat baik.'],
            ['mapel' => 'PKN', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang baik tentang Pendidikan Pancasila. Peserta didik mengenal simbol-simbol negara dan nilai-nilai Pancasila dengan baik.'],
            ['mapel' => 'PKN', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang cukup tentang Pendidikan Pancasila. Perlu peningkatan dalam memahami dan menerapkan nilai-nilai dasar Pancasila dalam kehidupan sehari-hari.'],
            ['mapel' => 'PKN', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam memahami Pendidikan Pancasila. Pembiasaan nilai-nilai Pancasila di lingkungan rumah sangat dianjurkan.'],

            ['mapel' => 'BIN', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang sangat baik. Peserta didik mampu menyimak, membaca, dan menulis dengan sangat lancar serta aktif berpartisipasi dalam kegiatan literasi kelas.'],
            ['mapel' => 'BIN', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang baik. Peserta didik mampu menyimak dan membaca teks sederhana serta mulai terampil menulis kata dan kalimat pendek.'],
            ['mapel' => 'BIN', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan berbahasa Indonesia yang cukup. Peserta didik perlu lebih banyak berlatih membaca dan menulis untuk meningkatkan kemampuan literasinya.'],
            ['mapel' => 'BIN', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan intensif dalam kemampuan berbahasa Indonesia. Disarankan untuk memberikan latihan membaca dan menulis secara rutin di rumah.'],

            ['mapel' => 'MTK', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang sangat baik. Peserta didik mampu memahami konsep bilangan dan melakukan operasi hitung dengan cepat dan tepat.'],
            ['mapel' => 'MTK', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang baik. Peserta didik mampu memahami bilangan dan menyelesaikan soal penjumlahan serta pengurangan dengan baik.'],
            ['mapel' => 'MTK', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan matematika yang cukup. Peserta didik perlu lebih banyak berlatih operasi hitung dan pemahaman konsep bilangan.'],
            ['mapel' => 'MTK', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam memahami konsep matematika dasar. Latihan rutin setiap hari sangat disarankan untuk meningkatkan kemampuan berhitungnya.'],

            ['mapel' => 'IPAS', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang sangat baik dalam Ilmu Pengetahuan Alam dan Sosial. Peserta didik mampu mengenal alam sekitar, menjelaskan fungsi anggota tubuh, dan memahami peran anggota keluarga dengan sangat baik.'],
            ['mapel' => 'IPAS', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang baik dalam IPAS. Peserta didik mampu mengenal hewan, tumbuhan, dan lingkungan sekitar serta memahami peran dalam keluarga.'],
            ['mapel' => 'IPAS', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan pemahaman yang cukup dalam IPAS. Perlu peningkatan dalam mengenal dan menjelaskan fenomena alam serta kehidupan sosial di sekitarnya.'],
            ['mapel' => 'IPAS', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam memahami IPAS. Eksplorasi lingkungan sekitar bersama orang tua sangat dianjurkan untuk meningkatkan pemahamannya.'],

            ['mapel' => 'SBDP', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan bakat dan kreativitas yang sangat baik dalam Seni Budaya dan Prakarya. Peserta didik aktif berekspresi melalui karya seni rupa, musik, dan kerajinan tangan dengan hasil yang memuaskan.'],
            ['mapel' => 'SBDP', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang baik dalam Seni Budaya dan Prakarya. Peserta didik mampu membuat karya seni sederhana dan menyanyikan lagu dengan baik.'],
            ['mapel' => 'SBDP', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang cukup dalam Seni Budaya dan Prakarya. Perlu lebih banyak berlatih dan mengeksplorasi berbagai bentuk ekspresi seni.'],
            ['mapel' => 'SBDP', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam Seni Budaya dan Prakarya. Kegiatan seni di rumah seperti menggambar, mewarnai, dan bernyanyi sangat dianjurkan.'],

            ['mapel' => 'PJOK', 'predikat' => 'A', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang sangat baik dalam Pendidikan Jasmani, Olahraga, dan Kesehatan. Peserta didik aktif, bersemangat, dan memiliki keterampilan gerak dasar serta kebugaran yang sangat baik.'],
            ['mapel' => 'PJOK', 'predikat' => 'B', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang baik dalam PJOK. Peserta didik aktif mengikuti kegiatan olahraga dan memiliki keterampilan gerak dasar serta pola hidup sehat yang baik.'],
            ['mapel' => 'PJOK', 'predikat' => 'C', 'narasi_template' => 'Peserta didik menunjukkan kemampuan yang cukup dalam PJOK. Perlu peningkatan dalam keterampilan gerak dasar dan semangat berolahraga secara rutin.'],
            ['mapel' => 'PJOK', 'predikat' => 'D', 'narasi_template' => 'Peserta didik masih memerlukan bimbingan dalam kegiatan PJOK. Olahraga ringan setiap hari sangat dianjurkan untuk meningkatkan kebugaran dan keterampilan geraknya.'],
        ];

        foreach ($aturans as $a) {
            AturanPredikat::create($a);
        }
    }
}
