<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo img {
            max-width: 70px;
            max-height: 70px;
        }

        .institution-info {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding: 0 20px;
        }

        .institution-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .institution-address {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .institution-phone {
            font-size: 10px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .page-number {
            display: table-cell;
            text-align: left;
        }

        .generated-time {
            display: table-cell;
            text-align: right;
        }

        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .document-date {
            text-align: center;
            font-size: 11px;
            margin-bottom: 30px;
            color: #666;
        }

        .content {
            text-align: justify;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                @if($logo_url)
                    <img src="{{ $logo_url }}" alt="Logo">
                @endif
            </div>
            <div class="institution-info">
                <div class="institution-name">{{ $institution_name }}</div>
                <div class="institution-address">{{ $address }}</div>
                <div class="institution-phone">{{ $phone }}</div>
            </div>
        </div>
    </header>

    <footer>
        <div class="footer-content">
            <div class="page-number">
                <script type="text/php">
                    if (isset($pdf)) {
                        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                        $font = $fontMetrics->get_font("Times-Roman", "normal");
                        $size = 9;
                        $pdf->text(50, 770, $text, $font, $size);
                    }
                </script>
            </div>
            <div class="generated-time">
                Generated: {{ $generated_at }}
            </div>
        </div>
    </footer>

    <main>
        <div class="document-title">{{ $title }}</div>
        <div class="document-date">{{ $generated_date }}</div>
        <div class="content">{{ $content }}</div>
    </main>
</body>
</html>
