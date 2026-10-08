# Dokumentasi API CKAN — Satu Data Muara Enim

## Metadata Dokumen
- **Nama Dokumen**: Dokumentasi API CKAN
- **Base URL**: `https://satudata.muaraenimkab.go.id`
- **Versi**: 1.0
- **Format**: Markdown
- **Tujuan**: Menyediakan dokumentasi endpoint CKAN yang mudah dibaca oleh manusia dan mudah diparse oleh agent AI.

---

## 1. Gambaran Umum
API ini menggunakan pola CKAN Action API. Seluruh endpoint berada di bawah path:

```text
{base_url}/api/3/action/
```

Contoh:

```text
https://satudata.muaraenimkab.go.id/api/3/action/package_search?q=kesehatan
```

### Karakteristik Umum Response
Secara umum, response CKAN memiliki struktur berikut:

```json
{
  "help": "URL dokumentasi bantuan endpoint",
  "success": true,
  "result": {}
}
```

### Arti Field Umum
- `help`: URL help endpoint CKAN.
- `success`: status keberhasilan request (`true` atau `false`).
- `result`: payload utama dari endpoint.

---

## 2. Standar Pemakaian untuk Agent AI
Agar agent AI tidak salah menafsirkan data, gunakan aturan berikut:

1. Selalu cek `success == true` sebelum membaca `result`.
2. Jangan asumsi semua endpoint mengembalikan tipe `result` yang sama.
   - Ada endpoint yang mengembalikan `result` berupa **array**.
   - Ada endpoint yang mengembalikan `result` berupa **object**.
3. Jangan percaya `format` saja untuk menentukan tipe file.
   - Harus cek juga `mimetype`, `name`, dan `url`.
4. Untuk detail dataset, `id` bisa berupa:
   - UUID dataset
   - slug dataset (`name` / nama URL)
5. Untuk detail resource dan visualisasi, `id` harus berupa UUID resource.

---

## 3. Endpoint: List Organization

### Tujuan
Mengambil daftar organisasi/publisher yang tersedia di CKAN.

### Method
`GET`

### URL
```text
{base_url}/api/3/action/organization_list?all_fields=true
```

### Contoh Final URL
```text
https://satudata.muaraenimkab.go.id/api/3/action/organization_list?all_fields=true
```

### Query Parameter
| Nama | Tipe | Wajib | Keterangan |
|---|---|---:|---|
| `all_fields` | boolean | Ya | Jika `true`, response memuat detail organisasi, bukan hanya nama. |

### Contoh Request cURL
```bash
curl --location 'https://satudata.muaraenimkab.go.id/api/3/action/organization_list?all_fields=true'
```

### Struktur Response
- `result` bertipe **array**.
- Setiap item dalam array adalah object organisasi.

### Field Penting per Item Organization
| Field | Tipe | Keterangan |
|---|---|---|
| `id` | string | UUID organisasi |
| `name` | string | nama slug organisasi |
| `title` | string | nama organisasi yang ditampilkan |
| `display_name` | string | nama tampilan organisasi |
| `description` | string | deskripsi organisasi |
| `package_count` | integer | jumlah dataset dalam organisasi |
| `state` | string | status organisasi |
| `approval_status` | string | status persetujuan |
| `is_organization` | boolean | penanda bahwa item adalah organisasi |

### Contoh Response
```json
{
  "help": "https://satudata.muaraenimkab.go.id/api/3/action/help_show?name=organization_list",
  "success": true,
  "result": [
    {
      "approval_status": "approved",
      "created": "2026-02-18T04:19:52.146441",
      "description": "Diskominfo Muara Enim",
      "display_name": "Diskominfo",
      "id": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
      "image_display_url": "",
      "image_url": "",
      "is_organization": true,
      "name": "diskominfo",
      "num_followers": 0,
      "package_count": 7,
      "state": "active",
      "title": "Diskominfo",
      "type": "organization"
    }
  ]
}
```

### Catatan AI
- Endpoint ini cocok dipakai untuk mengambil daftar publisher/organisasi lebih dulu.
- Gunakan `id` atau `name` organisasi untuk filtering lanjutan bila diperlukan di endpoint lain.

---

## 4. Endpoint: Search Dataset

### Tujuan
Mencari dataset berdasarkan kata kunci.

### Method
`GET`

### URL
```text
{base_url}/api/3/action/package_search?q={kata_kunci}
```

### Contoh Final URL
```text
https://satudata.muaraenimkab.go.id/api/3/action/package_search?q=kependudukan
```

### Query Parameter
| Nama | Tipe | Wajib | Keterangan |
|---|---|---:|---|
| `q` | string | Ya | Kata kunci pencarian dataset. Contoh: `kependudukan`, `kesehatan`, `akip`. |

### Contoh Request cURL
```bash
curl --location 'https://satudata.muaraenimkab.go.id/api/3/action/package_search?q=akip'
```

### Struktur Response
- `result` bertipe **object**.
- `result.results` bertipe **array** berisi daftar dataset.

### Field Penting pada `result`
| Field | Tipe | Keterangan |
|---|---|---|
| `count` | integer | jumlah dataset yang ditemukan |
| `results` | array | daftar dataset |
| `sort` | string | aturan sorting hasil pencarian |
| `facets` | object | facet pencarian |
| `search_facets` | object | facet pencarian tambahan |

### Field Penting per Item Dataset
| Field | Tipe | Keterangan |
|---|---|---|
| `id` | string | UUID dataset |
| `name` | string | slug dataset |
| `title` | string | judul dataset |
| `notes` | string | deskripsi dataset |
| `license_id` | string | id lisensi |
| `license_title` | string | nama lisensi |
| `metadata_created` | string | timestamp pembuatan metadata |
| `metadata_modified` | string | timestamp perubahan metadata |
| `num_resources` | integer | jumlah resource |
| `num_tags` | integer | jumlah tag |
| `organization` | object | organisasi pemilik dataset |
| `resources` | array | daftar resource milik dataset |
| `extras` | array | metadata tambahan berbentuk key-value |
| `tags` | array | daftar tag |

### Contoh Response
```json
{
  "help": "https://satudata.muaraenimkab.go.id/api/3/action/help_show?name=package_search",
  "success": true,
  "result": {
    "count": 1,
    "facets": {},
    "results": [
      {
        "author": "",
        "author_email": "",
        "creator_user_id": "45be5275-d8d4-415f-8684-8cb979fee404",
        "id": "d910dcf4-e5e1-4ce3-b3c8-d59cf0491885",
        "isopen": true,
        "license_id": "cc-by",
        "license_title": "Creative Commons Attribution",
        "license_url": "http://www.opendefinition.org/licenses/cc-by",
        "maintainer": "",
        "maintainer_email": "",
        "metadata_created": "2026-03-13T08:31:59.625721",
        "metadata_modified": "2026-03-13T08:34:03.872282",
        "name": "hasil-evaluasi-akuntabilitas-kinerja-intansi-pemerintah-akip",
        "notes": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
        "num_resources": 1,
        "num_tags": 1,
        "organization": {
          "id": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
          "name": "diskominfo",
          "title": "Diskominfo",
          "type": "organization",
          "description": "Diskominfo Muara Enim",
          "image_url": "",
          "created": "2026-02-18T04:19:52.146441",
          "is_organization": true,
          "approval_status": "approved",
          "state": "active"
        },
        "owner_org": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
        "private": false,
        "state": "active",
        "title": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
        "type": "dataset",
        "url": "",
        "version": "",
        "extras": [
          { "key": "2023", "value": "75" },
          { "key": "2024", "value": "77" },
          { "key": "Tahun 2022", "value": "75" }
        ],
        "resources": [
          {
            "cache_last_updated": null,
            "cache_url": null,
            "created": "2026-03-13T08:33:59.526885",
            "description": "Hasil AKIP",
            "format": "CSV",
            "hash": "",
            "id": "4769846d-937a-4c3b-b3ce-b64ba95b9e44",
            "last_modified": "2026-03-13T08:33:59.293719",
            "metadata_modified": "2026-03-13T08:33:59.448924",
            "mimetype": "image/png",
            "mimetype_inner": null,
            "name": "Hasil Akip.png",
            "package_id": "d910dcf4-e5e1-4ce3-b3c8-d59cf0491885",
            "position": 0,
            "resource_type": null,
            "size": 37986,
            "state": "active",
            "url": "https://satudata.muaraenimkab.go.id/dataset/d910dcf4-e5e1-4ce3-b3c8-d59cf0491885/resource/4769846d-937a-4c3b-b3ce-b64ba95b9e44/download/hasil-akip.png",
            "url_type": "upload"
          }
        ],
        "tags": [
          {
            "display_name": "Dinas Komunikasi Informastika Statistik dan Persandian",
            "id": "cba01fef-b79e-43d1-92a0-d1716a2050d6",
            "name": "Dinas Komunikasi Informastika Statistik dan Persandian",
            "state": "active",
            "vocabulary_id": null
          }
        ],
        "groups": [],
        "relationships_as_subject": [],
        "relationships_as_object": []
      }
    ],
    "sort": "score desc, metadata_modified desc",
    "search_facets": {}
  }
}
```

### Catatan AI
- Gunakan endpoint ini untuk menemukan dataset terlebih dahulu.
- Setelah mendapatkan `id` atau `name`, lanjutkan ke endpoint `package_show` untuk detail dataset.

### Peringatan Data
Pada contoh di atas, terdapat inkonsistensi metadata resource:
- `format = "CSV"`
- `mimetype = "image/png"`
- `name = "Hasil Akip.png"`

**Kesimpulan:** agent AI tidak boleh mengandalkan `format` saja. Validasi tipe file dengan membandingkan `format`, `mimetype`, `name`, dan `url`.

---

## 5. Endpoint: Detail Dataset

### Tujuan
Mengambil detail lengkap sebuah dataset.

### Method
`GET`

### URL
```text
{base_url}/api/3/action/package_show?id={dataset_id_atau_slug}
```

### Keterangan Parameter
Field `id` dapat diisi dengan:
- UUID dataset
- slug dataset (`name` / nama URL dataset)

### Contoh Final URL (UUID)
```text
https://satudata.muaraenimkab.go.id/api/3/action/package_show?id=d910dcf4-e5e1-4ce3-b3c8-d59cf0491885
```

### Contoh Final URL (Slug)
```text
https://satudata.muaraenimkab.go.id/api/3/action/package_show?id=hasil-evaluasi-akuntabilitas-kinerja-intansi-pemerintah-akip
```

### Query Parameter
| Nama | Tipe | Wajib | Keterangan |
|---|---|---:|---|
| `id` | string | Ya | UUID dataset atau slug dataset |

### Contoh Request cURL
```bash
curl --location 'https://satudata.muaraenimkab.go.id/api/3/action/package_show?id=hasil-evaluasi-akuntabilitas-kinerja-intansi-pemerintah-akip'
```

### Struktur Response
- `result` bertipe **object**.
- Field dataset hampir sama dengan hasil item pada `package_search`, tetapi biasanya lebih cocok untuk detail lengkap.

### Field Penting pada `result`
| Field | Tipe | Keterangan |
|---|---|---|
| `id` | string | UUID dataset |
| `name` | string | slug dataset |
| `title` | string | judul dataset |
| `notes` | string | deskripsi dataset |
| `organization` | object | organisasi pemilik dataset |
| `resources` | array | daftar resource dataset |
| `extras` | array | metadata tambahan |
| `tags` | array | daftar tag |
| `metadata_created` | string | waktu pembuatan metadata |
| `metadata_modified` | string | waktu perubahan metadata |
| `private` | boolean | status privat/publik dataset |
| `state` | string | status dataset |

### Contoh Response
```json
{
  "help": "https://satudata.muaraenimkab.go.id/api/3/action/help_show?name=package_show",
  "success": true,
  "result": {
    "author": "",
    "author_email": "",
    "creator_user_id": "45be5275-d8d4-415f-8684-8cb979fee404",
    "id": "d910dcf4-e5e1-4ce3-b3c8-d59cf0491885",
    "isopen": true,
    "license_id": "cc-by",
    "license_title": "Creative Commons Attribution",
    "license_url": "http://www.opendefinition.org/licenses/cc-by",
    "maintainer": "",
    "maintainer_email": "",
    "metadata_created": "2026-03-13T08:31:59.625721",
    "metadata_modified": "2026-03-13T08:34:03.872282",
    "name": "hasil-evaluasi-akuntabilitas-kinerja-intansi-pemerintah-akip",
    "notes": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
    "num_resources": 1,
    "num_tags": 1,
    "organization": {
      "id": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
      "name": "diskominfo",
      "title": "Diskominfo",
      "type": "organization",
      "description": "Diskominfo Muara Enim",
      "image_url": "",
      "created": "2026-02-18T04:19:52.146441",
      "is_organization": true,
      "approval_status": "approved",
      "state": "active"
    },
    "owner_org": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
    "private": false,
    "state": "active",
    "title": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
    "type": "dataset",
    "url": "",
    "version": "",
    "extras": [
      { "key": "2023", "value": "75" },
      { "key": "2024", "value": "77" },
      { "key": "Tahun 2022", "value": "75" }
    ],
    "resources": [
      {
        "cache_last_updated": null,
        "cache_url": null,
        "created": "2026-03-13T08:33:59.526885",
        "description": "Hasil AKIP",
        "format": "CSV",
        "hash": "",
        "id": "4769846d-937a-4c3b-b3ce-b64ba95b9e44",
        "last_modified": "2026-03-13T08:33:59.293719",
        "metadata_modified": "2026-03-13T08:33:59.448924",
        "mimetype": "image/png",
        "mimetype_inner": null,
        "name": "Hasil Akip.png",
        "package_id": "d910dcf4-e5e1-4ce3-b3c8-d59cf0491885",
        "position": 0,
        "resource_type": null,
        "size": 37986,
        "state": "active",
        "url": "https://satudata.muaraenimkab.go.id/dataset/d910dcf4-e5e1-4ce3-b3c8-d59cf0491885/resource/4769846d-937a-4c3b-b3ce-b64ba95b9e44/download/hasil-akip.png",
        "url_type": "upload"
      }
    ],
    "tags": [
      {
        "display_name": "Dinas Komunikasi Informastika Statistik dan Persandian",
        "id": "cba01fef-b79e-43d1-92a0-d1716a2050d6",
        "name": "Dinas Komunikasi Informastika Statistik dan Persandian",
        "state": "active",
        "vocabulary_id": null
      }
    ],
    "groups": [],
    "relationships_as_subject": [],
    "relationships_as_object": []
  }
}
```

### Catatan AI
- Endpoint ini adalah endpoint utama untuk membaca detail dataset.
- Jika agent ingin mengakses file atau visualisasi, ambil `resources[].id` dari response ini.

---

## 6. Endpoint: Detail Resource

### Tujuan
Mengambil detail sebuah resource/file di dalam dataset.

### Method
`GET`

### URL
```text
{base_url}/api/3/action/resource_show?id={resource_uuid}
```

### Keterangan Parameter
Field `id` harus berupa UUID resource.

### Contoh Final URL
```text
https://satudata.muaraenimkab.go.id/api/3/action/resource_show?id=f7d8f520-11f6-4e97-b86d-3e371002e476
```

### Query Parameter
| Nama | Tipe | Wajib | Keterangan |
|---|---|---:|---|
| `id` | string | Ya | UUID resource |

### Contoh Request cURL
```bash
curl --location 'https://satudata.muaraenimkab.go.id/api/3/action/resource_show?id=f7d8f520-11f6-4e97-b86d-3e371002e476'
```

### Struktur Response
- `result` bertipe **object**.
- Data di dalamnya adalah metadata file/resource.

### Field Penting pada `result`
| Field | Tipe | Keterangan |
|---|---|---|
| `id` | string | UUID resource |
| `package_id` | string | UUID dataset pemilik resource |
| `name` | string | nama file/resource |
| `description` | string | deskripsi resource |
| `format` | string | format file menurut metadata |
| `mimetype` | string | MIME type file |
| `url` | string | URL download resource |
| `url_type` | string | tipe URL, misalnya `upload` |
| `size` | integer | ukuran file dalam byte |
| `created` | string | waktu pembuatan resource |
| `last_modified` | string | waktu modifikasi file |
| `metadata_modified` | string | waktu modifikasi metadata |
| `state` | string | status resource |

### Contoh Response
```json
{
  "help": "https://satudata.muaraenimkab.go.id/api/3/action/help_show?name=resource_show",
  "success": true,
  "result": {
    "cache_last_updated": null,
    "cache_url": null,
    "created": "2026-03-10T03:06:43.367199",
    "description": "",
    "format": "CSV",
    "hash": "",
    "id": "f7d8f520-11f6-4e97-b86d-3e371002e476",
    "last_modified": "2026-03-10T03:12:22.789936",
    "metadata_modified": "2026-03-10T03:12:22.962714",
    "mimetype": "text/csv",
    "mimetype_inner": null,
    "name": "c70679f1-8d81-4970-8fa0-1db2390bca4c.csv",
    "package_id": "b409f5d8-878a-4201-bf6e-1f9e6455ef91",
    "position": 0,
    "resource_type": null,
    "size": 40,
    "state": "active",
    "url": "https://satudata.muaraenimkab.go.id/dataset/b409f5d8-878a-4201-bf6e-1f9e6455ef91/resource/f7d8f520-11f6-4e97-b86d-3e371002e476/download/pernah-mengunakan-internet-termasuk-faebook-twiter-youtube-instagram-watsapp-dll_20260310.csv",
    "url_type": "upload"
  }
}
```

### Catatan AI
- Ini endpoint yang tepat untuk membaca metadata file individual.
- Untuk mengambil URL download file, baca `result.url`.
- Untuk menentukan tipe file, gunakan prioritas validasi berikut:
  1. `mimetype`
  2. ekstensi file dari `name`
  3. ekstensi file dari `url`
  4. `format`

---

## 7. Endpoint: List Visualisasi Resource

### Tujuan
Mengambil daftar visualisasi/view yang terkait dengan resource tertentu.

### Method
`GET`

### URL
```text
{base_url}/api/3/action/resource_view_list?id={resource_uuid}
```

### Keterangan Parameter
Field `id` harus berupa UUID resource.

### Contoh Final URL
```text
https://satudata.muaraenimkab.go.id/api/3/action/resource_view_list?id=f7d8f520-11f6-4e97-b86d-3e371002e476
```

### Query Parameter
| Nama | Tipe | Wajib | Keterangan |
|---|---|---:|---|
| `id` | string | Ya | UUID resource |

### Contoh Request cURL
```bash
curl --location 'https://satudata.muaraenimkab.go.id/api/3/action/resource_view_list?id=f7d8f520-11f6-4e97-b86d-3e371002e476'
```

### Struktur Response
- `result` bertipe **array**.
- Setiap item merepresentasikan satu view/visualisasi untuk resource.

### Field Penting per Item View
| Field | Tipe | Keterangan |
|---|---|---|
| `id` | string | UUID view/visualisasi |
| `resource_id` | string | UUID resource |
| `package_id` | string | UUID dataset |
| `title` | string | judul visualisasi |
| `description` | string | deskripsi visualisasi |
| `view_type` | string | tipe visualisasi |

### Contoh Response
```json
{
  "help": "https://satudata.muaraenimkab.go.id/api/3/action/help_show?name=resource_view_list",
  "success": true,
  "result": [
    {
      "id": "c172381a-d794-41be-a16a-ebb2ac5578cd",
      "resource_id": "f7d8f520-11f6-4e97-b86d-3e371002e476",
      "title": "Data Explorer",
      "description": "",
      "view_type": "recline_view",
      "package_id": "b409f5d8-878a-4201-bf6e-1f9e6455ef91"
    }
  ]
}
```

### Catatan AI
- Endpoint ini berguna bila agent ingin mengetahui apakah suatu resource punya tampilan visualisasi bawaan di CKAN.
- `view_type = "recline_view"` biasanya menunjukkan tabel/eksplorer data bawaan.

---

## 8. Alur Integrasi yang Disarankan

### Skenario A — Cari dataset lalu ambil detailnya
1. Panggil `package_search?q={kata_kunci}`
2. Ambil `results[n].id` atau `results[n].name`
3. Panggil `package_show?id={dataset_id_atau_slug}`
4. Ambil `resources[]` jika ingin membaca file/resource

### Skenario B — Ambil detail file/resource
1. Panggil `package_show?id={dataset_id_atau_slug}`
2. Ambil `resources[n].id`
3. Panggil `resource_show?id={resource_uuid}`
4. Ambil `result.url` untuk file download

### Skenario C — Ambil visualisasi resource
1. Panggil `resource_show?id={resource_uuid}` atau ambil resource ID dari dataset
2. Panggil `resource_view_list?id={resource_uuid}`
3. Baca daftar visualisasi pada `result`

---

## 9. Aturan Parsing yang Direkomendasikan untuk Agent AI

Gunakan aturan berikut agar parsing stabil:

### Rule 1 — Validasi Response
```text
Jika success != true, anggap request gagal.
```

### Rule 2 — Validasi Tipe Result
```text
organization_list.result => array
package_search.result => object
package_show.result => object
resource_show.result => object
resource_view_list.result => array
```

### Rule 3 — Identifikasi Dataset
```text
Dataset identifier yang aman:
- id (UUID)
- name (slug)
```

### Rule 4 — Identifikasi Resource
```text
Resource identifier yang aman:
- resources[].id
- result.id pada resource_show
```

### Rule 5 — Validasi Tipe File
```text
Jangan hanya pakai `format`.
Bandingkan:
1. mimetype
2. ekstensi name
3. ekstensi url
4. format
```

### Rule 6 — Metadata Tambahan
```text
extras adalah array key-value, bukan object map langsung.
Perlu diubah dulu jika agent ingin memakainya sebagai dictionary.
```

Contoh transformasi:

```json
[
  { "key": "2023", "value": "75" },
  { "key": "2024", "value": "77" }
]
```

Menjadi:

```json
{
  "2023": "75",
  "2024": "77"
}
```

---

## 10. Contoh Ringkas Output yang Sebaiknya Dibaca Agent

### Organization
```json
{
  "id": "d943be7e-9789-4cc0-bff3-c5d1735e6a28",
  "name": "diskominfo",
  "title": "Diskominfo",
  "package_count": 7,
  "state": "active"
}
```

### Dataset
```json
{
  "id": "d910dcf4-e5e1-4ce3-b3c8-d59cf0491885",
  "name": "hasil-evaluasi-akuntabilitas-kinerja-intansi-pemerintah-akip",
  "title": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
  "notes": "Hasil Evaluasi Akuntabilitas Kinerja Intansi Pemerintah (AKIP)",
  "resource_count": 1
}
```

### Resource
```json
{
  "id": "f7d8f520-11f6-4e97-b86d-3e371002e476",
  "name": "c70679f1-8d81-4970-8fa0-1db2390bca4c.csv",
  "mimetype": "text/csv",
  "url": "https://satudata.muaraenimkab.go.id/dataset/b409f5d8-878a-4201-bf6e-1f9e6455ef91/resource/f7d8f520-11f6-4e97-b86d-3e371002e476/download/pernah-mengunakan-internet-termasuk-faebook-twiter-youtube-instagram-watsapp-dll_20260310.csv"
}
```

---

## 11. Kesimpulan
Dokumentasi ini mencakup endpoint CKAN berikut:
- `organization_list`
- `package_search`
- `package_show`
- `resource_show`
- `resource_view_list`

Dokumentasi ini juga sudah disusun dengan format yang lebih aman untuk agent AI, terutama karena:
- struktur result tiap endpoint dijelaskan eksplisit,
- identifier UUID vs slug dijelaskan,
- inkonsistensi metadata resource diberi peringatan,
- aturan parsing sudah ditulis langsung.

