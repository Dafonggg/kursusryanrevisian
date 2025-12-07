<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat - {{ $certificate->certificate_no }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .certificate {
            width: 90%;
            height: 70%;
            background: white;
            border: 20px solid #d4af37;
            padding: 60px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #d4af37;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 48px;
            color: #667eea;
            margin: 0;
            font-weight: bold;
            letter-spacing: 3px;
        }
        .header p {
            font-size: 18px;
            color: #666;
            margin: 10px 0;
        }
        .header .certificate-no {
            font-size: 14px;
            color: #999;
            margin: 5px 0;
            letter-spacing: 1px;
        }
        .content {
            text-align: center;
            margin: 60px 0;
        }
        .content p {
            font-size: 20px;
            color: #333;
            margin: 15px 0;
            line-height: 1.8;
        }
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
            text-decoration: underline;
            text-decoration-color: #d4af37;
            text-underline-offset: 10px;
        }
        .course-name {
            font-size: 24px;
            color: #764ba2;
            font-weight: bold;
            margin: 20px 0;
        }
        .scores {
            margin: 30px 0;
            display: flex;
            justify-content: center;
            gap: 80px;
        }
        .score-item {
            text-align: center;
        }
        .score-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .score-value {
            font-size: 32px;
            font-weight: bold;
        }
        .score-value.exam {
            color: #667eea;
        }
        .score-value.practicum {
            color: #764ba2;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin: 60px auto 10px;
            width: 150px;
        }

        .date {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="header">
                <img src="{{ public_path('images/logobaru1.png') }}" alt="Logo" class="logo">
                <h1>SERTIFIKAT</h1>
                <p>Kursus Ryan Komputer</p>
                <p class="certificate-no">No. {{ $certificate->certificate_no }}</p>
            </div>
            
            <div class="content">
                <p>Dengan ini menyatakan bahwa</p>
                <div class="student-name">{{ $certificate->enrollment->user->name }}</div>
                <p>telah menyelesaikan kursus</p>
                <div class="course-name">{{ $certificate->enrollment->course->title }}</div>
                
                @if(isset($finalScore) || isset($practicumScore))
                <div class="scores">
                    @if(isset($finalScore) && $finalScore !== null)
                    <div class="score-item">
                        <div class="score-label">Nilai Ujian</div>
                        <div class="score-value exam">{{ $finalScore }}</div>
                    </div>
                    @endif
                    @if(isset($practicumScore) && $practicumScore !== null)
                    <div class="score-item">
                        <div class="score-label">Nilai Praktikum</div>
                        <div class="score-value practicum">{{ $practicumScore }}</div>
                    </div>
                    @endif
                </div>
                @endif
                
                <p>dengan baik dan berhak mendapatkan sertifikat ini.</p>
            </div>
            
            <div class="footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <p style="margin: 0; font-size: 14px;">Direktur</p>
                </div>
                <div class="signature">
                    <div class="date">
                        {{ $certificate->issued_at->format('d F Y') }}
                    </div>
                </div>
            </div>
            

        </div>
    </div>
</body>
</html>

