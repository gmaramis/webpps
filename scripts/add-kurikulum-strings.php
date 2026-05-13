<?php

declare(strict_types=1);

$path = dirname(__DIR__).'/resources/data/pps-content.json';
$j = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
$j['STRINGS']['id']['kurikulumPageTitle'] = 'Kurikulum Program Studi';
$j['STRINGS']['id']['kurikulumPageLead'] = 'Dokumen kurikulum per program studi Pascasarjana UNIMA. Pratinjau di bawah atau unduh PDF.';
$j['STRINGS']['id']['kurikulumNavLabel'] = 'Kurikulum';
$j['STRINGS']['id']['kurikulumSectionMagister'] = 'Program Magister (S2)';
$j['STRINGS']['id']['kurikulumSectionDoktor'] = 'Program Doktor (S3)';
$j['STRINGS']['id']['kurikulumPreview'] = 'Pratinjau';
$j['STRINGS']['id']['kurikulumDownload'] = 'Unduh PDF';
$j['STRINGS']['id']['kurikulumEmpty'] = 'Belum ada dokumen kurikulum untuk program ini.';
$j['STRINGS']['en']['kurikulumPageTitle'] = 'Study Program Curricula';
$j['STRINGS']['en']['kurikulumPageLead'] = 'Curriculum documents for each graduate program at UNIMA. Preview below or download the PDF.';
$j['STRINGS']['en']['kurikulumNavLabel'] = 'Curriculum';
$j['STRINGS']['en']['kurikulumSectionMagister'] = "Master's programs (S2)";
$j['STRINGS']['en']['kurikulumSectionDoktor'] = 'Doctoral programs (S3)';
$j['STRINGS']['en']['kurikulumPreview'] = 'Preview';
$j['STRINGS']['en']['kurikulumDownload'] = 'Download PDF';
$j['STRINGS']['en']['kurikulumEmpty'] = 'No curriculum document for this program yet.';
file_put_contents($path, json_encode($j, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "updated {$path}\n";
