<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'طباعة' }} - {{ $globalSettings->company_name ?? config('app.name') }}</title>
    
    <!-- Favicon -->
    @if($globalSettings && $globalSettings->favicon_url)
        <link rel="icon" type="image/x-icon" href="{{ $globalSettings->favicon_url }}">
    @endif
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: white;
            color: #000;
            direction: rtl;
            text-align: right;
            line-height: 1.6;
        }
        
        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm;
            background: white;
            min-height: 297mm;
        }
        
        .company-header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        
        .company-logo-section {
            flex: 0 0 auto;
        }
        
        .company-logo {
            max-height: 100px;
            max-width: 200px;
            object-fit: contain;
        }
        
        .company-info {
            flex: 1;
            text-align: center;
        }
        
        .company-name {
            font-size: 2.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .company-details {
            margin-top: 10px;
        }
        
        .detail-item {
            margin: 5px 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .company-registration {
            flex: 0 0 auto;
            text-align: left;
            border: 1px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
        }
        
        .reg-item {
            margin-bottom: 10px;
            font-size: 0.85rem;
        }
        
        .reg-item:last-child {
            margin-bottom: 0;
        }
        
        .document-title {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            margin: 30px 0;
            border-radius: 8px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 8px;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 150px;
        }
        
        .info-value {
            color: #212529;
        }
        
        .table-container {
            margin: 30px 0;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #007bff;
        }
        
        .print-table th {
            background: #007bff;
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #0056b3;
        }
        
        .print-table td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .print-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .print-table tbody tr:hover {
            background: #e3f2fd;
        }
        
        .totals-section {
            background: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .total-row:last-child {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
        }
        
        .footer-section {
            margin-top: 50px;
            border-top: 2px solid #007bff;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature-box {
            text-align: center;
            min-width: 200px;
        }
        
        .signature-line {
            border-bottom: 2px solid #000;
            margin: 40px 0 10px 0;
            height: 2px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0, 123, 255, 0.1);
            z-index: -1;
            pointer-events: none;
        }
        
        .print-date {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 0.8rem;
            color: #666;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .print-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: none;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .print-table th {
                background: #007bff !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .totals-section {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .print-container {
                padding: 10px;
            }
            
            .company-name {
                font-size: 1.5rem;
            }
            
            .document-title {
                font-size: 1.2rem;
            }
            
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
            
            .footer-section {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
    
    @yield('additional_styles')
</head>
<body>
    <div class="print-date no-print">
        طباعة: {{ now()->format('Y-m-d H:i:s') }}
    </div>
    
    <div class="watermark">
        {{ $globalSettings->company_name ?? 'شركة بهجة للمنظفات' }}
    </div>
    
    <div class="print-container">
        <!-- Company Header -->
        <div class="company-header">
            @if($globalSettings && $globalSettings->logo_url)
                <div class="company-logo-section">
                    <img src="{{ $globalSettings->logo_url }}" alt="{{ $globalSettings->company_name }}" class="company-logo">
                </div>
            @endif
            
            <div class="company-info">
                <div class="company-name">{{ $globalSettings->company_name ?? 'شركة بهجة للمنظفات' }}</div>
                
                <div class="company-details">
                    @if($globalSettings && $globalSettings->company_address)
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt me-2"></i>{{ $globalSettings->company_address }}
                        </div>
                    @endif
                    
                    @if($globalSettings && $globalSettings->company_phone)
                        <div class="detail-item">
                            <i class="fas fa-phone me-2"></i>{{ $globalSettings->company_phone }}
                        </div>
                    @endif
                    
                    @if($globalSettings && $globalSettings->company_email)
                        <div class="detail-item">
                            <i class="fas fa-envelope me-2"></i>{{ $globalSettings->company_email }}
                        </div>
                    @endif
                    
                    @if($globalSettings && $globalSettings->company_website)
                        <div class="detail-item">
                            <i class="fas fa-globe me-2"></i>{{ $globalSettings->company_website }}
                        </div>
                    @endif
                </div>
            </div>
            
            @if($globalSettings && ($globalSettings->tax_number || $globalSettings->commercial_register))
                <div class="company-registration">
                    @if($globalSettings->tax_number)
                        <div class="reg-item">
                            <strong>الرقم الضريبي:</strong><br>{{ $globalSettings->tax_number }}
                        </div>
                    @endif
                    
                    @if($globalSettings->commercial_register)
                        <div class="reg-item">
                            <strong>السجل التجاري:</strong><br>{{ $globalSettings->commercial_register }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Document Title -->
        <div class="document-title">
            {{ $title ?? 'مستند' }}
        </div>
        
        <!-- Print Content -->
        @yield('content')
        
        <!-- Print Actions (No Print) -->
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-print me-2"></i>طباعة
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-2"></i>إغلاق
            </button>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('additional_scripts')
</body>
</html>
