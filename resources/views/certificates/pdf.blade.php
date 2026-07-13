<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; color:#1f2f4d; }
        .wrap { width:100%; padding:18px; }
        .frame {
            border:8px solid #0d2a5c;
            padding:0;
        }
        .inner {
            border:2px solid #f0a500;
            margin:6px;
            padding:34px 44px;
            text-align:center;
        }
        .top-band { color:#f0a500; letter-spacing:3px; font-size:12px; font-weight:bold; }
        .org { font-size:22px; font-weight:bold; color:#0d2a5c; margin-top:4px; }
        .sub { font-size:11px; color:#7a8aa8; margin-top:2px; }
        .title { font-size:34px; font-weight:bold; color:#0d2a5c; margin-top:26px; letter-spacing:1px; }
        .rule { width:120px; height:3px; background:#f0a500; margin:10px auto 0; }
        .presented { font-size:12px; color:#5a6b8c; margin-top:26px; }
        .name { font-size:30px; font-weight:bold; color:#1f2f4d; margin-top:8px; }
        .name-line { width:60%; height:1px; background:#dde4f0; margin:8px auto 0; }
        .body-text { font-size:13px; color:#3d4f73; margin-top:22px; line-height:1.6; }
        .course { font-weight:bold; color:#0d2a5c; }
        .foot { margin-top:44px; }
        .foot-table { width:100%; }
        .foot-table td { width:33%; vertical-align:bottom; font-size:11px; color:#5a6b8c; }
        .sig-line { border-top:1px solid #7a8aa8; margin:0 20px; padding-top:5px; }
        .cert-no { font-size:10px; color:#7a8aa8; }
        .seal {
            width:74px; height:74px; border:3px solid #f0a500; border-radius:50%;
            color:#0d2a5c; font-size:9px; font-weight:bold; text-align:center;
            line-height:1.3; padding-top:22px; margin:0 auto;
        }
        .verify { margin-top:22px; font-size:10px; color:#a5b0c6; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="frame">
            <div class="inner">

                <div class="top-band">GOVERNMENT OF HARYANA</div>
                <div class="org">HSBTE Training Portal</div>
                <div class="sub">Haryana State Board of Technical Education</div>

                <div class="title">CERTIFICATE OF COMPLETION</div>
                <div class="rule"></div>

                <div class="presented">This is proudly presented to</div>
                <div class="name">{{ $student->name }}</div>
                <div class="name-line"></div>

                <div class="body-text">
                    for successfully completing the course<br>
                    <span class="course">{{ $course->title }}</span><br>
                    on {{ $certificate->issued_at->format('d F Y') }}.
                </div>

                <div class="foot">
                    <table class="foot-table">
                        <tr>
                            <td>
                                <div class="sig-line">Course Trainer</div>
                            </td>
                            <td style="text-align:center;">
                                <div class="seal">HSBTE<br>VERIFIED<br>SEAL</div>
                            </td>
                            <td>
                                <div class="sig-line">Director, HSBTE</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="verify">
                    Certificate No: <strong>{{ $certificate->certificate_no }}</strong>
                    &nbsp;·&nbsp; Verify at: yoursite.com/verify
                </div>

            </div>
        </div>
    </div>
</body>
</html>