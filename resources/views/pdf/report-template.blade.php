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
            padding-top: 20px;
        }

        main {
            width: 100%;
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
            padding: 0 0px;
        }

        .institution-name {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .institution-address {
            font-size: 11px;
            margin-bottom: 2px;
        }

        .institution-phone {
            font-size: 11px;
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

        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 10px; /* Reduced from 20px */
        }

        .document-date {
            text-align: center;
            font-size: 11px;
            margin-bottom: 20px; /* Reduced from 30px to keep it tight */
            color: #666;
        }

        .content {
            text-align: justify;
            white-space: pre-wrap;
            /* This prevents the text from hugging the footer border too closely */
            padding-bottom: 20px;
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

    <main>
        @if(!empty($title))
            <div class="document-title">{{ $title }}</div>
        @endif

        @if(!empty($generated_date))
            <div class="document-date">{{ $generated_date }}</div>
        @endif

        <div class="content">{{ $content }}</div>
    </main>

    <footer>
        <div class="footer-content">
            </div>
    </footer>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Times New Roman", "normal");
            $size = 9;
            $color = array(0.4, 0.4, 0.4);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;

            $pdf->page_text(50, 790, $text, $font, $size, $color, $word_space, $char_space, $angle);

            $pdf->page_text(422, 790, "Dibuat: {{ $generated_at }}", $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>
</html>
