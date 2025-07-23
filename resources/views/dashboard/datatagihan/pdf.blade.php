<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Tagihan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA TAGIHAN</h2>
        <p>Tanggal Export: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Pelanggan</th>
                <th>Paket WiFi</th>
                <th class="text-right">Jumlah Tagihan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihan as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->user->nama ?? 'N/A' }}</td>
                <td>{{ $item->paketWifi->nama_paket ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_tagihan ?? $item->total ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span style="padding: 3px 8px; border-radius: 3px; 
                        {{ ($item->statusTagihan->status ?? '') == 'Lunas' ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' }}">
                        {{ $item->statusTagihan->status ?? 'N/A' }}
                    </span>
                </td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><strong>Total Data: {{ count($tagihan) }} tagihan</strong></p>
        @php
            $totalTagihan = $tagihan->sum(function($item) {
                return $item->jumlah_tagihan ?? $item->total ?? 0;
            });
            $belumLunas = $tagihan->where('status_tagihan_id', '!=', 3)->sum(function($item) {
                return $item->jumlah_tagihan ?? $item->total ?? 0;
            });
        @endphp
        <p><strong>Total Tagihan: Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong></p>
        <p><strong>Total Belum Lunas: Rp {{ number_format($belumLunas, 0, ',', '.') }}</strong></p>
    </div>
</body>
</html>