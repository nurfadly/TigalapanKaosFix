<?php

namespace App\Services;

use App\Models\StockCatalog;
use App\Models\StockImport;
use Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Parser + writer untuk upload file stok (CSV atau Excel dari export POS).
 *
 * Cara pakai (dipanggil dari StockController@store):
 *   (new StockImportService())->import($file, auth()->id());
 *
 * Setiap upload:
 * - stock_items (angka stok) DIHAPUS TOTAL dan diganti isi file baru.
 * - stock_catalog (daftar nama item + hasil pencocokan produk) TIDAK dihapus,
 *   hanya ditambah kalau ada nama item baru yang belum pernah muncul.
 * - stock_imports dapat 1 baris ringkasan baru (riwayat, tidak pernah dihapus).
 */
class StockImportService
{
    private const CHUNK_SIZE = 500;

    public function import(UploadedFile $file, ?int $userId): StockImport
    {
        set_time_limit(0);

        $matcher = new StockMatcher();

        // stock_catalog_id yang sudah ada, supaya tidak query berulang per baris.
        $catalogCache = StockCatalog::pluck('id', 'raw_name')->all();

        DB::table('stock_items')->truncate();

        $outlets = [];
        $totalRows = 0;
        $buffer = [];
        $now = now();

        foreach ($this->readRows($file) as $row) {
            $rawName = trim((string) ($row['name_variant'] ?? ''));
            $outlet = trim((string) ($row['outlet'] ?? ''));

            if ($rawName === '' || $outlet === '') {
                continue;
            }

            $category = trim((string) ($row['category'] ?? '')) ?: null;
            $outlets[$outlet] = true;
            $totalRows++;

            if (!isset($catalogCache[$rawName])) {
                $auto = $matcher->match($rawName, $category);

                $catalogCache[$rawName] = DB::table('stock_catalog')->insertGetId([
                    'raw_name' => $rawName,
                    'category' => $category,
                    'matched_product_id' => $auto['product_id'],
                    'match_score' => $auto['score'],
                    'matched_manually' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $buffer[] = [
                'stock_catalog_id' => $catalogCache[$rawName],
                'outlet' => $outlet,
                'beginning' => $this->toInt($row['beginning'] ?? 0),
                'purchase_order' => $this->toInt($row['purchase_order'] ?? 0),
                'sales' => $this->toInt($row['sales'] ?? 0),
                'transfer' => $this->toInt($row['transfer'] ?? 0),
                'adjustment' => $this->toInt($row['adjustment'] ?? 0),
                'ending' => $this->toInt($row['ending'] ?? 0),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($buffer) >= self::CHUNK_SIZE) {
                DB::table('stock_items')->insert($buffer);
                $buffer = [];
            }
        }

        if (!empty($buffer)) {
            DB::table('stock_items')->insert($buffer);
        }

        $totalMatched = StockCatalog::whereNotNull('matched_product_id')->count();

        return StockImport::create([
            'filename' => $file->getClientOriginalName(),
            'total_rows' => $totalRows,
            'total_outlets' => count($outlets),
            'total_items' => count($catalogCache),
            'total_matched' => $totalMatched,
            'imported_by' => $userId,
            'created_at' => $now,
        ]);
    }

    private function toInt($value): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        return (int) preg_replace('/[^0-9\-]/', '', (string) $value) ?: 0;
    }

    /**
     * @return Generator<array<string, mixed>>
     */
    private function readRows(UploadedFile $file): Generator
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['csv', 'txt'], true)) {
            yield from $this->readCsv($file->getRealPath());

            return;
        }

        yield from $this->readExcel($file->getRealPath());
    }

    private function readCsv(string $path): Generator
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);

            return;
        }

        $map = $this->buildColumnMap($header);

        while (($row = fgetcsv($handle)) !== false) {
            yield $this->mapRow($row, $map);
        }

        fclose($handle);
    }

    /**
     * Butuh package: composer require phpoffice/phpspreadsheet
     * (tidak diperlukan kalau file yang diupload selalu .csv).
     */
    private function readExcel(string $path): Generator
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new \RuntimeException(
                'Membaca file .xlsx/.xls butuh package phpoffice/phpspreadsheet. '
                .'Jalankan: composer require phpoffice/phpspreadsheet — atau upload dalam format .csv.'
            );
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $map = null;

        foreach ($sheet->getRowIterator() as $row) {
            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = $cell->getValue();
            }

            if ($map === null) {
                $map = $this->buildColumnMap($cells);

                continue;
            }

            yield $this->mapRow($cells, $map);
        }
    }

    private function buildColumnMap(array $header): array
    {
        $normalized = array_map(fn ($h) => strtolower(trim((string) $h)), $header);

        $find = function (array $candidates) use ($normalized) {
            foreach ($candidates as $candidate) {
                $idx = array_search($candidate, $normalized, true);
                if ($idx !== false) {
                    return $idx;
                }
            }

            return null;
        };

        return [
            'name_variant' => $find(['name - variant', 'name-variant', 'nama - varian', 'nama']),
            'category' => $find(['category', 'kategori']),
            'outlet' => $find(['outlet', 'toko']),
            'beginning' => $find(['beginning', 'awal']),
            'purchase_order' => $find(['purchase order', 'po']),
            'sales' => $find(['sales', 'penjualan']),
            'transfer' => $find(['transfer']),
            'adjustment' => $find(['adjustment', 'penyesuaian']),
            'ending' => $find(['ending', 'akhir']),
        ];
    }

    private function mapRow(array $row, array $map): array
    {
        $get = fn ($key) => isset($map[$key], $row[$map[$key]]) ? $row[$map[$key]] : null;

        return [
            'name_variant' => $get('name_variant'),
            'category' => $get('category'),
            'outlet' => $get('outlet'),
            'beginning' => $get('beginning'),
            'purchase_order' => $get('purchase_order'),
            'sales' => $get('sales'),
            'transfer' => $get('transfer'),
            'adjustment' => $get('adjustment'),
            'ending' => $get('ending'),
        ];
    }
}
