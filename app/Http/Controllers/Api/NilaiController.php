<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function nilaiRt()
    {
        try {
            $rawQuery = "select 
                nama,
                max(case when nama_pelajaran = 'ARTISTIC' then skor else 0 end) as artistic, 
                max(case when nama_pelajaran = 'CONVENTIONAL' then skor else 0 end) as conventional, 
                max(case when nama_pelajaran = 'ENTERPRISING' then skor else 0 end) as enterprising, 
                max(case when nama_pelajaran = 'INVESTIGATIVE' then skor else 0 end) as investigative, 
                max(case when nama_pelajaran = 'REALISTIC' then skor else 0 end) as realistic, 
                max(case when nama_pelajaran = 'SOCIAL' then skor else 0 end) as social,
                nisn
            from 
                nilai
            where
                materi_uji_id = 7 
            and 
                nama_pelajaran != 'Pelajaran Khusus'
            group by nama, nisn
            order by nama";

            $nilai = DB::select($rawQuery);
            $nilai = collect($nilai)->map(fn($item) => [
                'nama' => $item->nama,
                'nilaiRt' => [
                    'artistic' => $item->artistic,
                    'conventional' => $item->conventional,
                    'enterprising' => $item->enterprising,
                    'investigative' => $item->investigative,
                    'realistic' => $item->realistic,
                    'social' => $item->social
                ],
                'nisn' => $item->nisn
            ]);

            return response()->json($nilai);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function nilaiSt()
    {
        try {
            $rawQuery = "select 
                *,
                (verbal + kuantitatif + penalaran + figural) as total
                from (
                    select 
                        nama,
                        max(case when pelajaran_id = 44 then (skor * 41.67) end) as verbal, 
                        max(case when pelajaran_id = 45 then (skor * 29.67) end) as kuantitatif, 
                        max(case when pelajaran_id = 46 then (skor * 100) end) as penalaran, 
                        max(case when pelajaran_id = 47 then (skor * 23.81) end) as figural,
                        nisn
                    from 
                        nilai
                    where
                        materi_uji_id = 4
                    group by nama, nisn
                ) as nilai_per_siswa
                order by total desc";

            $nilai = DB::select($rawQuery);
            $nilai = collect($nilai)->map(fn($item) => [
                'listNilai' => [
                    'verbal' => $item->verbal,
                    'kuantitatif' => $item->kuantitatif,
                    'penalaran' => $item->penalaran,
                    'figural' => $item->figural,
                ],
                'nama' => $item->nama,
                'nisn' => $item->nisn
            ]);

            return response()->json($nilai);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage()
            ], 500);
        }
    }
}