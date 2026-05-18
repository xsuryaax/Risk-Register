<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #007774;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
        }
        .clinic-name {
            font-size: 16pt;
            font-weight: bold;
            color: #007774;
            margin: 0;
        }
        .clinic-info {
            font-size: 8pt;
            color: #666;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ccc;
            font-size: 8pt;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ccc;
            vertical-align: top;
            font-size: 8pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Risk Colors */
        .rank-st { background-color: #c00000; color: white; }
        .rank-t  { background-color: #ff9900; color: white; }
        .rank-s  { background-color: #ffeb3b; color: black; }
        .rank-r  { background-color: #0d6efd; color: white; }
        .rank-sr { background-color: #198754; color: white; }

        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        
        .page-break {
            page-break-after: always;
        }

        .section-title {
            background-color: #e9ecef;
            padding: 5px 10px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #007774;
        }

        .profile-table th {
            text-align: left;
            width: 30%;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="border: none; text-align: left; vertical-align: bottom;">
                <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #333; margin-bottom: 2px;">@yield('doc_title')</div>
                <div style="font-size: 9pt; color: #555;">@yield('doc_subtitle')</div>
            </td>
            <td style="border: none; text-align: right; vertical-align: middle;">
                <p class="clinic-name">RS AZRA</p>
            </td>
        </tr>
    </table>

    @yield('content')

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }} | Risk Register System - RS Azra
    </div>
</body>
</html>
