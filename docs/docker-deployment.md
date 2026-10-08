# Deploy dengan Docker

Tiga container dari satu image:

| Service     | Tugas                                                                 |
| ----------- | --------------------------------------------------------------------- |
| `app`       | nginx + php-fpm, listen di `127.0.0.1:8080` (hanya bisa diakses host) |
| `worker`    | `queue:work redis` - menjalankan job (warm-up cache CKAN & Berita)    |
| `scheduler` | `schedule:work` - menjadwalkan job warm-up tiap 5 menit               |

Redis ada di host yang sama; database PostgreSQL ada di server lain (IP publik).
Keduanya **tidak** ada di compose.

```
Internet -> nginx HOST (80/443, SSL) -> 127.0.0.1:8080 -> container app (nginx -> php-fpm)
                                                         container worker/scheduler
                                      Redis di HOST   <-- host.docker.internal
                                      PostgreSQL      <-- IP publik server DB (lewat internet)
```

## 1. Siapkan Redis di host

Container mengakses Redis host lewat `host.docker.internal` (IP gateway docker,
biasanya `172.17.0.1`). Redis yang hanya `bind 127.0.0.1` **tidak** bisa dijangkau,
jadi cek `/etc/redis/redis.conf`:

```conf
# tambahkan IP gateway docker di akhir baris bind yang sudah ada
bind 127.0.0.1 -::1 -172.17.0.1
requirepass GANTI_PASSWORD_KUAT
```

Tanda `-` di depan alamat berarti "opsional": Redis tetap start walau alamat itu belum
ada (mis. Docker belum jalan saat boot). Cara cek IP gateway: `ip -4 addr show docker0`
atau `docker network inspect bridge --format '{{(index .IPAM.Config 0).Gateway}}'`.

Lalu `sudo systemctl restart redis-server`. Jika memakai firewall, izinkan hanya
jaringan docker, jangan buka ke publik:

```bash
sudo ufw allow from 172.16.0.0/12 to any port 6379 proto tcp
```

> Jika Redis dipakai aplikasi lain, pilih nomor DB yang kosong (`redis-cli INFO keyspace`)
> untuk `REDIS_DB` dan `REDIS_CACHE_DB`. `cache:clear` menjalankan `FLUSHDB` pada DB cache.

## 2. Konfigurasi

```bash
cp .env.docker.example .env.docker
nano .env.docker     # isi APP_KEY, APP_URL, REDIS_PASSWORD, pilihan database
```

* `APP_KEY`: pakai yang sama dengan `.env` server saat ini.
* Database PostgreSQL: salin `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`,
  `DB_SSLMODE` dari `.env` production saat ini. Karena trafik container keluar lewat IP publik
  server ini (NAT), aturan `pg_hba.conf`/firewall di server DB yang sekarang tetap berlaku.

Folder upload (infografis + Produk Statistik OPD) ada di `storage/app/public`.
Agar upload yang sudah ada tidak hilang, arahkan ke storage aplikasi yang sekarang:

```bash
export STORAGE_PATH=/var/www/satudata/storage   # contoh, sesuaikan
sudo chown -R 33:33 "$STORAGE_PATH"             # www-data di container = uid 33
```

(Boleh ditulis permanen di file `.env` sejajar `docker-compose.yml`, Compose membacanya otomatis.)

## 3. Jalankan & uji (aplikasi lama masih hidup)

```bash
docker compose up -d --build
docker compose ps                       # app harus "healthy"
curl -I http://127.0.0.1:8080/          # uji langsung ke container
docker compose exec worker php artisan cache:warm-external   # isi cache sekarang
docker compose logs -f worker scheduler
```

Database baru/kosong: set `RUN_MIGRATIONS=true` sekali saat `up`, lalu kembalikan ke `false`.

Cek cache terisi: `redis-cli -a PASSWORD -n 6 --scan --pattern 'satudata-cache-*ckan*' | head`

## 4. Pindahkan nginx host ke container

Ubah site nginx di host: hapus blok `fastcgi_pass`/`root` PHP dan ganti dengan proxy.

```nginx
server {
    listen 443 ssl;                       # SSL/certbot tetap seperti sekarang
    server_name satudata.example.go.id;

    client_max_body_size 520m;            # upload PDF sampai ~488 MB

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_request_buffering off;      # stream upload besar, tidak ditulis 2x ke disk
        proxy_connect_timeout 5s;
        proxy_read_timeout 120s;
    }
}
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

**Rollback:** kembalikan konfigurasi nginx lama lalu `reload`. Aplikasi lama tidak diubah.
Setelah stabil, nonaktifkan pool php-fpm lama untuk aplikasi ini.

Catatan: setelah pindah, session admin berpindah ke Redis sehingga admin perlu login ulang sekali.

## 5. Operasional

```bash
docker compose logs -f app              # log + slowlog (request > 10 detik beserta stack trace)
docker compose up -d --build            # deploy versi baru
docker compose exec app php artisan cache:warm-external
docker compose restart worker           # setelah deploy kode baru (worker memuat kode saat start)
```

Tuning php-fpm lewat env di `.env.docker`/compose: `FPM_MAX_CHILDREN`, `FPM_START_SERVERS`,
`FPM_MIN_SPARE`, `FPM_MAX_SPARE` (default 20/4/2/6).

## Cara kerja warm-up cache

1. `scheduler` memicu `WarmExternalCache` tiap 5 menit (`routes/console.php`).
2. Job masuk antrean Redis, `worker` mengeksekusinya: mengambil organisasi, daftar dataset,
   detail dataset (maks. 500) dari CKAN dan daftar berita dari API Berita.
3. Hasil ditulis ke cache dengan TTL 15 menit. Entri hanya ditimpa jika fetch **berhasil**,
   sehingga saat CKAN down pengunjung tetap mendapat data terakhir yang baik.
4. Request pengunjung membaca cache. Jika cache kosong/kedaluwarsa, perilakunya sama
   seperti sebelumnya (fetch langsung, TTL 5 menit).

Pencarian dengan kata kunci melakukan `package_show` untuk setiap dataset yang belum
ada di cache; karena detail dataset sudah di-warm, pencarian jadi jauh lebih cepat.
