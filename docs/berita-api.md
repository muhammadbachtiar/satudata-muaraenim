## List Public Article

URL = https://desa-api.muaraenimkab.go.id/api/v1/public/article?page=1&page_size=10&with=category

json
{
"success": true,
"message": "Article",
"code": 200,
"data": [
{
"id": 887,
"village_id": 21,
"category_id": 25,
"thumbnail": "https://api-minio.muaraenimkab.go.id/cmsmuaraenim/ckeditor/QVPLRqfMAy9BqfF2NkJpFhcne6WRN7GY7sbjdU1C.jpg",
"title": "Pemkab. Muara Enim Jalin Kerja Sama dengan 2 Peguruan Tinggi, Bank bjb dan PT. Taspen",
"description": "Pemerintah Kabupaten Muara Enim resmi menjalin kerja sama melalui penandatanganan Memorandum of Understanding (MoU) dengan Politeknik Negri Sriwijaya (Polsri), PT. Taspen, Universitas Baiturahim Jambi, dan PT. Bank Pembangunan Jawa Barat dan Banten (Bank bjb), Rabu (15/04). Penandatanganan MoU dilakukan Bupati Muara Enim, H. Edison, S.H., M.Hum., bersama Manager PT. Taspen Cabang Lubuk Linggau, M. Habibur Rahman, Direktur Utama Regional 4 Bank bjb, Ujang Aep Saepullah, Direktur Polsri, Ir. Irawan Rusnadi, M.T., dan Rektor Universitas Baiturrahim, Dr. Filius Chandra, S.E., M.M., di Balai Agung Serasan Sekundang (BASS) Muara Enim. MoU ini menjadi momentum penting dalam memperkuat sinergi antara pemerintah daerah, dunia pendidikan, serta sektor keuangan dan layanan kepegawaian.. Dalam sambutannya, Bupati Muara Enim didampingi jajaran Kepala Perangkat Daerah serta Kepala Bagian Kerja Sama, Arie Irawan, S.STP, M.Si., menyampaikan bahwa kerja sama ini diharapkan dapat memberikan manfaat nyata bagi masyarakat, khususnya dalam mendukung pengembangan sektor pendidikan dan penelitian. Dengan adanya kolaborasi bersama universitas, Bupati optimis dapat meningkatkan kualitas sumber daya manusia, memperluas akses riset, serta menciptakan inovasi yang relevan dengan kebutuhan pembangunan daerah untuk menjawab tantangan perkembangan zaman yang melaju kian pesat.. Lebih lanjut, Bupati menekankan bahwa MoU ini juga menjadi langkah strategis untuk mempercepat pembangunan Kabupaten Muara Enim. Menurutnya, dukungan dari PT. Taspen dan Bank bjb diharapkan mampu memperkuat layanan kepegawaian serta akses keuangan daerah, sehingga pembangunan dapat berjalan lebih efektif dan berkelanjutan. Dengan sinergi lintas sektor ini, Kabupaten Muara Enim diharapkan semakin maju dan mampu bersaing di tingkat regional maupun nasional.[diskominfosp-me]",
"published_at": "2026-04-15",
"slug": "pemkab-muara-enim-jalin-kerja-sama-dengan-2-peguruan-tinggi-bank-bjb-dan-pt-taspen",
"user_id": 39,
"category": {
"id": 25,
"name": "BERITA"
}
}
],
"meta": {
"next_page_url": "http://desa-api.muaraenimkab.go.id/api/v1/public/article?page=1&page_size=1&with=category&cursor=eyJpZCI6ODg3LCJfcG9pbnRzVG9OZXh0SXRlbXMiOnRydWV9",
"prev_page_url": null,
"per_page": 1
}
}

## Public Article Detail

URL = https://desa-api.muaraenimkab.go.id/api/v1/public/article/:slug
Headers = x-village-id:21

json
{
"success": true,
"message": "Article retrieved successfully",
"code": 200,
"data": {
"id": 887,
"user_id": 39,
"category_id": 25,
"title": "Pemkab. Muara Enim Jalin Kerja Sama dengan 2 Peguruan Tinggi, Bank bjb dan PT. Taspen",
"slug": "pemkab-muara-enim-jalin-kerja-sama-dengan-2-peguruan-tinggi-bank-bjb-dan-pt-taspen",
"content": "<p style=\"text-align: justify;\">Pemerintah Kabupaten Muara Enim resmi menjalin kerja sama melalui penandatanganan Memorandum of Understanding (MoU) dengan Politeknik Negri Sriwijaya (Polsri), PT. Taspen, Universitas Baiturahim Jambi, dan PT. Bank Pembangunan Jawa Barat dan Banten (Bank bjb), Rabu (15/04). Penandatanganan MoU dilakukan Bupati Muara Enim, H. Edison, S.H., M.Hum., bersama Manager PT. Taspen Cabang Lubuk Linggau, M. Habibur Rahman, Direktur Utama Regional 4 Bank bjb, Ujang Aep Saepullah, Direktur Polsri, Ir. Irawan Rusnadi, M.T., dan Rektor Universitas Baiturrahim, Dr. Filius Chandra, S.E., M.M., di Balai Agung Serasan Sekundang (BASS) Muara Enim. MoU ini menjadi momentum penting dalam memperkuat sinergi antara pemerintah daerah, dunia pendidikan, serta sektor keuangan dan layanan kepegawaian.<br>.<br>Dalam sambutannya, Bupati Muara Enim didampingi jajaran Kepala Perangkat Daerah serta Kepala Bagian Kerja Sama, Arie Irawan, S.STP, M.Si., menyampaikan bahwa kerja sama ini diharapkan dapat memberikan manfaat nyata bagi masyarakat, khususnya dalam mendukung pengembangan sektor pendidikan dan penelitian. Dengan adanya kolaborasi bersama universitas, Bupati optimis dapat meningkatkan kualitas sumber daya manusia, memperluas akses riset, serta menciptakan inovasi yang relevan dengan kebutuhan pembangunan daerah untuk menjawab tantangan perkembangan zaman yang melaju kian pesat.<br>.<br>Lebih lanjut, Bupati menekankan bahwa MoU ini juga menjadi langkah strategis untuk mempercepat pembangunan Kabupaten Muara Enim. Menurutnya, dukungan dari PT. Taspen dan Bank bjb diharapkan mampu memperkuat layanan kepegawaian serta akses keuangan daerah, sehingga pembangunan dapat berjalan lebih efektif dan berkelanjutan. Dengan sinergi lintas sektor ini, Kabupaten Muara Enim diharapkan semakin maju dan mampu bersaing di tingkat regional maupun nasional.[diskominfosp-me]</p>",
"published_at": "2026-04-15",
"views": 178,
"thumbnail": "https://api-minio.muaraenimkab.go.id/cmsmuaraenim/ckeditor/QVPLRqfMAy9BqfF2NkJpFhcne6WRN7GY7sbjdU1C.jpg",
"meta": "[{\"key\":\"title\",\"value\":\"Pemkab. Muara Enim Jalin Kerja Sama dengan 2 Peguruan Tinggi, Bank bjb dan PT. Taspen\"}]",
"created_at": "2026-05-11T08:11:35.000000Z",
"updated_at": "2026-05-27T18:57:51.000000Z",
"description": "Pemerintah Kabupaten Muara Enim resmi menjalin kerja sama melalui penandatanganan Memorandum of Understanding (MoU) dengan Politeknik Negri Sriwijaya (Polsri), PT. Taspen, Universitas Baiturahim Jambi, dan PT. Bank Pembangunan Jawa Barat dan Banten (Bank bjb), Rabu (15/04). Penandatanganan MoU dilakukan Bupati Muara Enim, H. Edison, S.H., M.Hum., bersama Manager PT. Taspen Cabang Lubuk Linggau, M. Habibur Rahman, Direktur Utama Regional 4 Bank bjb, Ujang Aep Saepullah, Direktur Polsri, Ir. Irawan Rusnadi, M.T., dan Rektor Universitas Baiturrahim, Dr. Filius Chandra, S.E., M.M., di Balai Agung Serasan Sekundang (BASS) Muara Enim. MoU ini menjadi momentum penting dalam memperkuat sinergi antara pemerintah daerah, dunia pendidikan, serta sektor keuangan dan layanan kepegawaian.. Dalam sambutannya, Bupati Muara Enim didampingi jajaran Kepala Perangkat Daerah serta Kepala Bagian Kerja Sama, Arie Irawan, S.STP, M.Si., menyampaikan bahwa kerja sama ini diharapkan dapat memberikan manfaat nyata bagi masyarakat, khususnya dalam mendukung pengembangan sektor pendidikan dan penelitian. Dengan adanya kolaborasi bersama universitas, Bupati optimis dapat meningkatkan kualitas sumber daya manusia, memperluas akses riset, serta menciptakan inovasi yang relevan dengan kebutuhan pembangunan daerah untuk menjawab tantangan perkembangan zaman yang melaju kian pesat.. Lebih lanjut, Bupati menekankan bahwa MoU ini juga menjadi langkah strategis untuk mempercepat pembangunan Kabupaten Muara Enim. Menurutnya, dukungan dari PT. Taspen dan Bank bjb diharapkan mampu memperkuat layanan kepegawaian serta akses keuangan daerah, sehingga pembangunan dapat berjalan lebih efektif dan berkelanjutan. Dengan sinergi lintas sektor ini, Kabupaten Muara Enim diharapkan semakin maju dan mampu bersaing di tingkat regional maupun nasional.[diskominfosp-me]",
"village_id": 21
}
}
