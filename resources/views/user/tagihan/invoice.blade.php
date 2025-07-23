<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $tagihan->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .detail-box h3 {
            color: #007bff;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .detail-box p {
            margin-bottom: 5px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .invoice-table th {
            background-color: #007bff;
            color: white;
        }
        
        .invoice-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        
        .total-amount {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
            display: inline-block;
        }
        
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        @media print {
            .actions {
                display: none;
            }
            body {
                background: white;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <p>Invoice #{{ $tagihan->id }}</p>
            <p>Tanggal: {{ $tagihan->created_at->format('d F Y') }}</p>
        </div>

        <!-- Detail Invoice -->
        <div class="invoice-details">
            <div class="detail-box">
                <h3>Informasi Pelanggan</h3>
                <p><strong>Nama:</strong> {{ $tagihan->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $tagihan->user->email ?? 'N/A' }}</p>
                <p><strong>Telepon:</strong> {{ $tagihan->user->phone ?? 'N/A' }}</p>
            </div>
            
            <div class="detail-box">
                <h3>Detail Tagihan</h3>
                <p><strong>ID Tagihan:</strong> #{{ $tagihan->id }}</p>
                <p><strong>Tanggal Jatuh Tempo:</strong> {{ $tagihan->due_date ? \Carbon\Carbon::parse($tagihan->due_date)->format('d F Y') : 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge {{ $tagihan->status == 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ $tagihan->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Tabel Item -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $tagihan->description ?? 'Tagihan Pembayaran' }}</td>
                    <td>{{ $tagihan->period ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($tagihan->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total -->
        <div class="total-section">
            <div class="total-amount">
                Total: Rp {{ number_format($tagihan->amount, 0, ',', '.') }}
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('user.tagihan') }}" class="btn btn-secondary">Kembali ke Tagihan</a>
            <button onclick="window.print()" class="btn btn-primary">Cetak Invoice</button>
        </div>
    </div>

    <script>
        // Auto print jika ada parameter print
        if (window.location.search.includes('print=1')) {
            window.print();
        }
    </script>
</body>
</html>