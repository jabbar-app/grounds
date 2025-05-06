<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TpaSeeder extends Seeder
{
    public function run(): void
    {
        // $user = User::first();
        $user = User::updateOrCreate([
            'name' => 'Jabbar Ali Panggabean',
            'email' => 'jabbarpanggabean@gmail.com',
            'password' => bcrypt('bism!LLAH99'),
        ]);

        $quiz = Quiz::create([
            'user_id' => $user->id,
            'title' => 'Ujian Tes Potensi Akademik (TPA)',
            'slug' => Str::slug('Ujian Tes Potensi Akademik (TPA)'),
            'description' => 'Ujian ini terdiri dari soal-soal logika, numerik, dan verbal untuk mengukur potensi akademik peserta.',
            'duration' => 3600,
            'open_gate_time' => Carbon::now()->subHour(),
            'close_gate_time' => Carbon::now()->addHours(2),
            'announcement_time' => Carbon::now()->addDays(1),
            'category' => 'TPA',
            'level' => 'Menengah',
            'img_featured' => null,
            'is_required_monitoring' => true,
            'created_by' => $user->id,
        ]);

        $questions = [
            [
                'text' => 'Jika 3x + 2 = 11, maka nilai x adalah?',
                'options' => ['2', '3', '4', '5'],
                'answer' => 1, // '3'
            ],
            [
                'text' => 'Sinonim dari kata "kontradiksi" adalah?',
                'options' => ['Kesepakatan', 'Pertentangan', 'Kerja Sama', 'Perjanjian'],
                'answer' => 1, // 'Pertentangan'
            ],
            [
                'text' => 'Manakah bilangan berikut yang merupakan bilangan prima?',
                'options' => ['21', '33', '37', '49'],
                'answer' => 2, // '37'
            ],
            [
                'text' => 'Urutan huruf setelah huruf ke-3 dari akhir kata "KOMPETENSI" adalah?',
                'options' => ['T', 'E', 'N', 'S'],
                'answer' => 3, // 'S'
            ],
            [
                'text' => 'Jika sebuah pekerjaan dapat diselesaikan 4 orang dalam 6 hari, maka 3 orang akan menyelesaikannya dalam berapa hari?',
                'options' => ['6', '7', '8', '9'],
                'answer' => 3, // '8'
            ],
            [
                'text' => 'Antonim dari kata "abstrak" adalah?',
                'options' => ['Nyata', 'Kabur', 'Ilmiah', 'Rumit'],
                'answer' => 0, // 'Nyata'
            ],
            [
                'text' => 'Jika Andi berumur 2 kali usia Budi, dan jumlah usia mereka 36 tahun. Maka usia Andi adalah?',
                'options' => ['12', '18', '24', '30'],
                'answer' => 2, // '24'
            ],
            [
                'text' => 'Apa kelanjutan dari pola: 2, 4, 8, 16, ...?',
                'options' => ['18', '20', '24', '32'],
                'answer' => 3, // '32'
            ],
            [
                'text' => 'Manakah bentuk logika yang benar?',
                'options' => [
                    'Semua A adalah B, maka semua B adalah A',
                    'Jika A maka B, maka jika B maka A',
                    'Jika A maka B, jika B maka C, maka A maka C',
                    'A dan B selalu berbanding lurus',
                ],
                'answer' => 2, // 'Jika A maka B, jika B maka C, maka A maka C'
            ],
            [
                'text' => 'Kata yang tepat untuk melengkapi: "Ia bekerja dengan ___ karena dikejar deadline."',
                'options' => ['santai', 'tergesa-gesa', 'malas', 'lambat'],
                'answer' => 1, // 'tergesa-gesa'
            ],
        ];

        $questions = array_merge($questions, [
            [
                'text' => 'Jika rata-rata dari lima bilangan adalah 12, dan empat bilangan adalah 10, 14, 9, dan 13, maka bilangan kelima adalah?',
                'options' => ['12', '14', '11', '13'],
                'answer' => 1, // Total = 5*12=60. Jumlah 4 bil = 10+14+9+13=46. Bil ke-5 = 60-46=14 (indeks 1)
            ],
            [
                'text' => 'Jika $5x - 3 = 2x + 12$, maka nilai $x$ adalah?',
                'options' => ['3', '4', '5', '6'],
                'answer' => 2, // 3x = 15 => x = 5 (indeks 2)
            ],
            [
                'text' => 'Sinonim dari kata "ambigu" adalah?',
                'options' => ['Jelas', 'Ganda', 'Tidak pasti', 'Pasti'],
                'answer' => 2, // Ambigu berarti memiliki makna ganda atau tidak pasti
            ],
            [
                'text' => 'Urutan huruf setelah huruf ke-2 dari awal kata "INTELEGENSI" adalah?',
                'options' => ['T', 'E', 'L', 'I'],
                'answer' => 0, // Huruf ke-1: I, ke-2: N. Setelah N adalah T (indeks 0)
            ],
            [
                'text' => 'Jika 3 orang menyelesaikan pekerjaan dalam 8 hari, berapa hari 6 orang menyelesaikannya?',
                'options' => ['2', '3', '4', '5'],
                'answer' => 2, // Perbandingan terbalik: (3 orang * 8 hari) / 6 orang = 4 hari (indeks 2)
            ],
            [
                'text' => 'Harga sebuah baju Rp 80.000. Jika mendapat diskon 15%, berapa harga yang harus dibayar?',
                'options' => ['Rp 65.000', 'Rp 68.000', 'Rp 70.000', 'Rp 72.000'],
                'answer' => 1, // Diskon = 15% * 80000 = 12000. Harga bayar = 80000 - 12000 = 68000 (indeks 1)
            ],
            [
                'text' => 'Apa lawan kata dari "optimis"?',
                'options' => ['Pesimis', 'Realistis', 'Idealis', 'Aktif'],
                'answer' => 0, // Lawan kata optimis adalah pesimis (indeks 0)
            ],
            [
                'text' => 'Sebuah segitiga memiliki alas 10 cm dan tinggi 6 cm. Berapa luasnya?',
                'options' => ['20 cm²', '30 cm²', '60 cm²', '16 cm²'],
                'answer' => 1, // Luas = 1/2 * alas * tinggi = 1/2 * 10 * 6 = 30 cm² (indeks 1)
            ],
            [
                'text' => 'Lanjutkan pola bilangan berikut: 2, 5, 8, 11, ...',
                'options' => ['13', '14', '15', '16'],
                'answer' => 1, // Pola ditambah 3: 11 + 3 = 14 (indeks 1)
            ],
            [
                'text' => 'Guru : Sekolah :: Dokter : ?',
                'options' => ['Pasien', 'Obat', 'Rumah Sakit', 'Perawat'],
                'answer' => 2, // Analogi tempat kerja: Guru bekerja di Sekolah, Dokter bekerja di Rumah Sakit (indeks 2)
            ],
            [
                'text' => 'Berapakah hasil dari $\frac{1}{2} + \frac{1}{4}$?',
                'options' => ['1/6', '2/3', '3/4', '1'],
                'answer' => 2, // 1/2 + 1/4 = 2/4 + 1/4 = 3/4 (indeks 2)
            ],
            [
                'text' => 'Ibu kota provinsi Sumatera Utara adalah?',
                'options' => ['Banda Aceh', 'Medan', 'Padang', 'Pekanbaru'],
                'answer' => 1, // Pengetahuan umum geografi Indonesia (Medan, indeks 1)
            ],
            [
                'text' => 'Budi menabung Rp 1.000.000 dengan bunga 5% per tahun. Berapa besar bunga yang diperoleh setelah 1 tahun?',
                'options' => ['Rp 5.000', 'Rp 25.000', 'Rp 50.000', 'Rp 100.000'],
                'answer' => 2, // Bunga = 5% * 1.000.000 = 50.000 (indeks 2)
            ],
            [
                'text' => 'Hewan yang dapat hidup di dua alam (air dan darat) disebut?',
                'options' => ['Mamalia', 'Reptil', 'Amfibi', 'Unggas'],
                'answer' => 2, // Definisi Amfibi (indeks 2)
            ],
            [
                'text' => 'Sebuah mobil menempuh jarak 120 km dalam waktu 3 jam. Berapa kecepatan rata-rata mobil tersebut?',
                'options' => ['30 km/jam', '40 km/jam', '50 km/jam', '60 km/jam'],
                'answer' => 1, // Kecepatan = Jarak / Waktu = 120 km / 3 jam = 40 km/jam (indeks 1)
            ],
            [
                'text' => 'Mana yang tidak termasuk dalam kelompok berikut: Mobil, Sepeda Motor, Kereta Api, Sepeda?',
                'options' => ['Mobil', 'Sepeda Motor', 'Kereta Api', 'Sepeda'],
                'answer' => 2, // Kereta Api berjalan di rel, yang lain di jalan raya umum (indeks 2)
            ],
            [
                'text' => 'Berapa banyak bilangan prima antara 10 dan 20?',
                'options' => ['2', '3', '4', '5'],
                'answer' => 2, // Bilangan prima antara 10 dan 20 adalah 11, 13, 17, 19 (ada 4 bilangan, indeks 2)
            ],
            [
                'text' => 'Apa arti dari ungkapan "besar kepala"?',
                'options' => ['Pintar', 'Sombong', 'Sakit kepala', 'Pemimpin'],
                'answer' => 1, // Besar kepala adalah idiom yang berarti sombong (indeks 1)
            ],
            [
                'text' => '2,5 jam sama dengan berapa menit?',
                'options' => ['120 menit', '135 menit', '150 menit', '180 menit'],
                'answer' => 2, // 2.5 jam * 60 menit/jam = 150 menit (indeks 2)
            ],
            [
                'text' => 'Planet manakah yang paling dekat dengan Matahari?',
                'options' => ['Venus', 'Bumi', 'Mars', 'Merkurius'],
                'answer' => 3, // Urutan planet dari Matahari: Merkurius, Venus, Bumi, Mars... (Merkurius, indeks 3)
            ],
            [
                'text' => 'Jika jumlah tiga bilangan ganjil berturut-turut adalah 57, maka bilangan terbesar adalah?',
                'options' => ['17', '19', '21', '23'],
                'answer' => 3,
            ],
            [
                'text' => 'Berapa banyak cara menyusun huruf-huruf pada kata “PINTAR”?',
                'options' => ['360', '720', '120', '240'],
                'answer' => 1,
            ],
            [
                'text' => 'Apa lawan kata dari “konservatif”?',
                'options' => ['Tradisional', 'Radikal', 'Liberal', 'Klasik'],
                'answer' => 2,
            ],
            [
                'text' => 'Harga sebuah buku naik 20%. Jika harga barunya Rp 60.000, berapa harga awalnya?',
                'options' => ['Rp 48.000', 'Rp 50.000', 'Rp 52.000', 'Rp 72.000'],
                'answer' => 1, # Harga Awal * 1.2 = 60000 => Harga Awal = 50000
            ],
            [
                'text' => 'Apa sinonim dari kata "paradigma"?',
                'options' => ['Contoh', 'Kerangka berpikir', 'Teori', 'Asumsi'],
                'answer' => 1, # Paradigma sering diartikan sebagai model atau kerangka berpikir
            ],
            [
                'text' => 'Sebuah persegi panjang memiliki panjang 15 cm dan lebar 8 cm. Berapa kelilingnya?',
                'options' => ['46 cm', '120 cm²', '23 cm', '60 cm²'],
                'answer' => 0, # Keliling = 2 * (P + L) = 2 * (15 + 8) = 2 * 23 = 46 cm
            ],
            [
                'text' => 'Nilai rata-rata ujian matematika 5 siswa adalah 80. Jika ditambah nilai Budi, rata-ratanya menjadi 82. Berapa nilai Budi?',
                'options' => ['82', '88', '90', '92'],
                'answer' => 3, # Total awal = 5*80=400. Total baru = 6*82=492. Nilai Budi = 492-400=92
            ],
            [
                'text' => 'Pisau : Memotong :: Pena : ?',
                'options' => ['Membaca', 'Menulis', 'Menggambar', 'Menghapus'],
                'answer' => 1, # Analogi fungsi alat: Pisau untuk memotong, Pena untuk menulis
            ],
            [
                'text' => 'Lanjutkan deret bilangan berikut: 3, 6, 12, 24, ...',
                'options' => ['30', '36', '48', '60'],
                'answer' => 2, # Deret geometri dengan rasio 2 (setiap angka dikali 2)
            ],
            [
                'text' => 'Semua murid pandai berenang. Budi adalah seorang murid. Kesimpulan yang tepat adalah?',
                'options' => ['Budi mungkin pandai berenang', 'Budi tidak pandai berenang', 'Budi pandai berenang', 'Tidak dapat disimpulkan'],
                'answer' => 2, # Silogisme sederhana: Jika semua A adalah B, dan C adalah A, maka C adalah B.
            ],
            [
                'text' => 'Perbandingan umur Ayah dan Ibu adalah 5 : 4. Jika jumlah umur mereka 72 tahun, berapa umur Ibu?',
                'options' => ['32 tahun', '36 tahun', '40 tahun', '45 tahun'],
                'answer' => 0, # Jumlah rasio = 9. Umur Ibu = (4/9) * 72 = 32 tahun
            ],
            [
                'text' => 'Apa arti dari ungkapan "kambing hitam"?',
                'options' => ['Orang yang berkorban', 'Orang yang disalahkan', 'Hewan ternak', 'Orang yang penakut'],
                'answer' => 1, # Kambing hitam adalah idiom untuk orang yang dipersalahkan atas kesalahan orang lain.
            ],
            [
                'text' => 'Sebuah dadu dilempar sekali. Berapa peluang munculnya mata dadu bilangan prima?',
                'options' => ['1/6', '2/6', '3/6', '4/6'],
                'answer' => 2, # Bilangan prima pada dadu: 2, 3, 5 (ada 3). Total kemungkinan: 6. Peluang = 3/6.
            ],
            [
                'text' => 'Proses tumbuhan membuat makanannya sendiri dengan bantuan sinar matahari disebut?',
                'options' => ['Respirasi', 'Fotosintesis', 'Transpirasi', 'Evaporasi'],
                'answer' => 1, # Definisi dasar fotosintesis.
            ],
            [
                'text' => 'Jika $2x + 5 = 19$, maka nilai $x$ adalah?',
                'options' => ['5', '6', '7', '8'],
                'answer' => 2, # 2x = 19 - 5 => 2x = 14 => x = 7
            ],
            [
                'text' => 'Pulau terbesar di Indonesia berdasarkan luas wilayah adalah?',
                'options' => ['Jawa', 'Sumatra', 'Kalimantan', 'Papua'],
                'answer' => 2, # Merujuk pada luas daratan pulau yang berada dalam wilayah Indonesia, Kalimantan (bagian Indonesia) adalah yang terluas.
            ],
            [
                'text' => 'Mana yang tidak termasuk dalam kelompok: Apel, Mangga, Pisang, Bayam?',
                'options' => ['Apel', 'Mangga', 'Pisang', 'Bayam'],
                'answer' => 3, # Apel, Mangga, Pisang adalah buah-buahan; Bayam adalah sayuran.
            ],
            [
                'text' => 'Ani dapat menyelesaikan pekerjaan dalam 3 jam, Budi dalam 6 jam. Jika mereka bekerja bersama, berapa lama pekerjaan selesai?',
                'options' => ['1 jam', '2 jam', '3 jam', '4.5 jam'],
                'answer' => 1, # Kecepatan Ani = 1/3 pekerjaan/jam. Kecepatan Budi = 1/6 pekerjaan/jam. Bersama = 1/3 + 1/6 = 1/2 pekerjaan/jam. Waktu = 1 / (1/2) = 2 jam.
            ],
            [
                'text' => 'Siapa presiden pertama Republik Indonesia?',
                'options' => ['Soeharto', 'B.J. Habibie', 'Soekarno', 'Megawati Soekarnoputri'],
                'answer' => 2, # Pengetahuan umum sejarah Indonesia.
            ],
            [
                'text' => 'Dari 5 orang siswa, akan dipilih 2 orang untuk mengikuti lomba. Berapa banyak cara pemilihan yang mungkin?',
                'options' => ['10', '15', '20', '25'],
                'answer' => 0, # Kombinasi C(5, 2) = 5! / (2! * (5-2)!) = 10 cara.
            ],
            // ----- 5 Soal Tingkat Sulit -----
            [
                'text' => 'Sebuah kerucut memiliki volume $96\pi \text{ cm}^3$ dan tinggi 8 cm. Jika jari-jari alasnya diperbesar 50%, berapa persen kenaikan volume kerucut tersebut?',
                'options' => ['50%', '100%', '125%', '225%'],
                'answer' => 2, // V = 1/3 pi r^2 h => 96pi = 1/3 pi r^2 (8) => r=6. r_baru = 6*1.5=9. V_baru = 1/3 pi (9^2)(8) = 216pi. Kenaikan = (216-96)/96 * 100% = 120/96 * 100% = 1.25 * 100% = 125%
            ],
            [
                'text' => 'Dalam sebuah kotak terdapat 5 bola merah dan 3 bola biru. Jika diambil dua bola satu per satu secara acak *tanpa pengembalian*, berapa peluang terambilnya kedua bola berwarna sama?',
                'options' => ['10/28', '13/28', '15/56', '26/64'],
                'answer' => 1, // P(MM) = (5/8)*(4/7) = 20/56. P(BB) = (3/8)*(2/7) = 6/56. P(sama) = P(MM)+P(BB) = 26/56 = 13/28
            ],
            [
                'text' => 'Analogi: FOTOSINTESIS : KLOROFIL :: RESPIRASI SEL : ?',
                'options' => ['Oksigen', 'Mitokondria', 'Karbon Dioksida', 'Energi (ATP)'],
                'answer' => 1, // Analogi Proses : Komponen Kunci/Lokasi Utama. Respirasi seluler aerobik terjadi utamanya di Mitokondria. Membutuhkan pengetahuan biologi spesifik.
            ],
            [
                'text' => 'Suatu deret aritmatika memiliki suku ke-3 adalah 10 dan suku ke-7 adalah 22. Jumlah 10 suku pertama deret tersebut adalah?',
                'options' => ['150', '160', '175', '190'],
                'answer' => 2, // U7-U3 = (a+6b)-(a+2b)=4b = 22-10=12 => b=3. a+2(3)=10 => a=4. S10 = n/2(2a+(n-1)b) = 10/2(2*4+(10-1)*3) = 5(8+27) = 5(35) = 175.
            ],
            [
                'text' => 'Pernyataan: "Semua ponsel pintar memiliki kamera. Tidak ada barang dengan kamera yang murah." Kesimpulan mana yang PASTI benar?',
                'options' => ['Barang yang tidak murah pasti ponsel pintar', 'Semua ponsel pintar tidak murah', 'Semua barang berkamera adalah ponsel pintar', 'Ada ponsel pintar yang murah'],
                'answer' => 1, // P->K, K->~M. Maka P->~M (Semua ponsel pintar tidak murah). Opsi 1 (Affirming Consequent ~M->P), Opsi 3 (Converse K->P), Opsi 4 (Kontradiksi P & ~M).
            ],

            // ----- 5 Soal Tingkat Sangat Sulit -----
            [
                'text' => 'Berapa banyak bilangan bulat positif $n < 1000$ sehingga $n^2 + (n+1)^2$ merupakan bilangan kuadrat sempurna?',
                'options' => ['2', '3', '4', '5'],
                'answer' => 2, // Persamaan $2n^2+2n+1=m^2$. Solusi n positif < 1000 adalah n=3, n=20, n=119, n=696. Ada 4 solusi. Memerlukan pengetahuan number theory (Pell's equation atau pattern recognition).
            ],
            [
                'text' => 'Sebuah barisan didefinisikan: $a_1 = 1, a_2 = 2$, dan $a_{n+1} = \frac{a_n + a_{n-1}}{2}$ untuk $n \ge 2$. Berapakah $\lim_{n\to\infty} a_n$?',
                'options' => ['1', '3/2', '5/3', '2'],
                'answer' => 2, // Limit barisan rekursif linier. Dapat diselesaikan dengan karakteristik atau deret $\sum (a_n - a_{n-1})$. Limitnya adalah $a_1 + (a_2-a_1)\sum_{k=0}^{\infty} (-1/2)^k = 1 + (2-1) * \frac{1}{1-(-1/2)} = 1 + 1 * \frac{1}{3/2} = 1 + 2/3 = 5/3$. Memerlukan konsep limit dan deret tak hingga.
            ],
            [
                'text' => 'Pernyataan: "Tidak semua siswa yang belajar giat lulus ujian. Hanya siswa yang lulus ujian yang boleh ikut pesta." Mana kesimpulan yang TIDAK BISA ditarik secara logis?',
                'options' => ['Ada siswa yang belajar giat tidak ikut pesta.', 'Semua siswa yang ikut pesta pasti belajar giat.', 'Setidaknya satu siswa tidak lulus ujian.', 'Siswa yang tidak ikut pesta mungkin saja lulus ujian.'],
                'answer' => 1, // P1: $\exists x (BG(x) \land \neg L(x))$. P2: $\forall x (IP(x) \implies L(x))$. Kesimpulan B (IP -> BG) tidak dapat ditarik. Kesimpulan A ($\exists x (BG(x) \land \neg IP(x))$) bisa ditarik dari P1 dan kontrapositif P2. Kesimpulan C ($\exists x \neg L(x)$) bisa ditarik dari P1. Kesimpulan D ($\exists x (\neg IP(x) \land L(x))$) tidak bertentangan dengan P2.
            ],
            [
                'text' => 'Dalam penjumlahan cryptarithmetic SEND + MORE = MONEY, setiap huruf mewakili digit unik (0-9). Jika diketahui S=9, berapakah nilai E+N+Y?',
                'options' => ['12', '13', '14', '15'],
                'answer' => 1, // Solusi unik: S=9, E=5, N=6, D=7, M=1, O=0, R=8, Y=2. Maka E+N+Y = 5+6+2=13. Memerlukan penalaran deduktif multi-langkah yang cermat.
            ],
            [
                'text' => 'Kubus $N \times N \times N$ ($N \ge 2$) tersusun dari $N^3$ kubus kecil. Seluruh permukaan kubus besar dicat. Jika jumlah kubus kecil yang memiliki minimal satu sisi tercat adalah 218, berapakah nilai $N$?',
                'options' => ['5', '6', '7', '8'],
                'answer' => 2, // Jumlah tercat = Total - Tak tercat = N^3 - (N-2)^3 = 218. N^3 - (N^3 - 6N^2 + 12N - 8) = 6N^2 - 12N + 8 = 218. 6N^2 - 12N - 210 = 0. N^2 - 2N - 35 = 0. (N-7)(N+5)=0. N=7 (karena N>=2).
            ],
        ]);

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['text'],
            ]);

            foreach ($q['options'] as $i => $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $opt,
                    'is_correct' => $i === $q['answer'],
                ]);
            }
        }
    }
}
