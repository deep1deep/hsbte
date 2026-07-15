@extends('layouts.app')

@section('title', 'Certificate Design - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Certificate Design</h2>
                <p class="text-muted mb-0">Your HTML template for courses set to Auto mode</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('trainer.certificates') }}" class="btn btn-outline-navy">Certificates</a>
                <a href="{{ route('trainer.dashboard') }}" class="btn btn-outline-navy">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
        @endif

        @if($autoCourses->count())
            <div class="alert alert-info py-2">
                <i class="bi bi-info-circle me-1"></i>
                This design is used by: <strong>{{ $autoCourses->implode(', ') }}</strong>
            </div>
        @else
            <div class="alert alert-warning py-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                None of your courses are set to Auto mode yet — this design won't be used until you switch one.
            </div>
        @endif

        <div class="row g-3">

            {{-- ============ EDITOR ============ --}}
            <div class="col-lg-7">
                <div class="admin-card">
                    <div class="admin-card-head">Template HTML</div>
                    <div class="admin-card-body">
                        <form method="POST" action="{{ route('trainer.certificate.design.save') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600;">Design name</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $template->name ?? 'My Certificate Design') }}" required>
                            </div>

                            <div class="mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <label class="form-label mb-0" style="font-weight:600;">HTML</label>
                                <div class="d-flex gap-2">
                                    <label class="btn btn-sm btn-outline-navy mb-0" style="cursor:pointer;">
                                        <i class="bi bi-upload"></i> Load .html file
                                        <input type="file" id="htmlFile" accept=".html,.htm" hidden>
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-navy" onclick="loadStarter()">
                                        Load starter
                                    </button>
                                </div>
                            </div>

                            <textarea name="html" id="htmlBox" class="form-control" rows="18" required
                                      style="font-family:monospace;font-size:.82rem;"
                                      oninput="renderPreview()">{{ old('html', $template->html ?? '') }}</textarea>

                            <div class="text-muted mt-1" style="font-size:.78rem;">
                                Max 500KB · <code>&lt;script&gt;</code>, <code>&lt;iframe&gt;</code> and PHP are stripped on save ·
                                images must be base64 data URIs (remote URLs are blocked in the PDF)
                            </div>

                            <button class="btn btn-navy mt-3">
                                <i class="bi bi-check-lg me-1"></i> Save design
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Placeholders --}}
                <div class="admin-card mt-3">
                    <div class="admin-card-head">Placeholders</div>
                    <div class="admin-card-body">
                        <p class="text-muted small mb-2">Click to insert — they get replaced automatically when the certificate is issued.</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($placeholders as $key => $desc)
                                <button type="button" class="btn btn-sm btn-outline-navy"
                                        onclick="insertPh(this)" data-ph="{{ $key }}"
                                        title="{{ $desc }}">
                                    <code style="color:inherit;">{{ $key }}</code>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ PREVIEW ============ --}}
            <div class="col-lg-5">
                <div class="admin-card" style="position:sticky;top:20px;">
                    <div class="admin-card-head d-flex justify-content-between align-items-center">
                        <span>Live preview</span>
                        <span class="badge text-bg-secondary">sample data</span>
                    </div>
                    <div class="admin-card-body">
                        <iframe id="preview" sandbox="" title="Certificate preview"
                                style="width:100%;height:420px;border:1px solid #dde4f0;border-radius:6px;background:#fff;"></iframe>
                        <div class="text-muted mt-2" style="font-size:.78rem;">
                            A4 landscape — the real PDF may differ slightly.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
const TRAINER_NAME = @json(auth()->user()->name);
const DEPT_NAME    = @json(auth()->user()->department->name ?? 'Computer Science & Engineering');
</script>

@verbatim
<script>
const SAMPLE = {
    '{{student_name}}':   'Rahul Sharma',
    '{{course_name}}':    'Cyber Security Awareness',
    '{{certificate_no}}': 'HSBTE-2026-000042',
    '{{issue_date}}':     '15 July 2026',
    '{{trainer_name}}':   TRAINER_NAME,
    '{{department}}':     DEPT_NAME,
    '{{enrollment_no}}':  'HSBTE24CS001',
};

function renderPreview() {
    let html = document.getElementById('htmlBox').value;
    for (const [k, v] of Object.entries(SAMPLE)) {
        html = html.split(k).join(v);
    }
    document.getElementById('preview').srcdoc = html;
}

function insertPh(btn) {
    const box = document.getElementById('htmlBox');
    const ph  = btn.dataset.ph;
    const s = box.selectionStart, e = box.selectionEnd;
    box.value = box.value.slice(0, s) + ph + box.value.slice(e);
    box.selectionStart = box.selectionEnd = s + ph.length;
    box.focus();
    renderPreview();
}

document.getElementById('htmlFile').addEventListener('change', function (ev) {
    const f = ev.target.files[0];
    if (!f) return;
    if (f.size > 512000) { alert('File is larger than 500KB.'); return; }
    const r = new FileReader();
    r.onload = e => { document.getElementById('htmlBox').value = e.target.result; renderPreview(); };
    r.readAsText(f);
});

function loadStarter() {
    if (document.getElementById('htmlBox').value.trim() &&
        !confirm('This will replace your current HTML. Continue?')) return;
    document.getElementById('htmlBox').value = STARTER;
    renderPreview();
}

const STARTER = `<div style="width:100%;height:100%;padding:40px;box-sizing:border-box;font-family:DejaVu Sans,sans-serif;text-align:center;border:12px solid #0d2a5c;">
  <div style="border:2px solid #f0a500;padding:30px;height:100%;box-sizing:border-box;">
    <div style="font-size:13px;letter-spacing:2px;color:#5a6b8c;">HARYANA STATE BOARD OF TECHNICAL EDUCATION</div>
    <h1 style="font-size:34px;color:#0d2a5c;margin:18px 0 4px;">Certificate of Completion</h1>
    <div style="width:70px;height:3px;background:#f0a500;margin:0 auto 24px;"></div>

    <div style="font-size:14px;color:#5a6b8c;">This is to certify that</div>
    <div style="font-size:28px;color:#1f2f4d;margin:8px 0;">{{student_name}}</div>
    <div style="font-size:14px;color:#5a6b8c;">has successfully completed the course</div>
    <div style="font-size:20px;color:#0d2a5c;margin:8px 0 4px;">{{course_name}}</div>
    <div style="font-size:13px;color:#7a8aa8;">{{department}}</div>

    <table style="width:100%;margin-top:50px;font-size:12px;color:#5a6b8c;">
      <tr>
        <td style="text-align:left;">
          Certificate No: {{certificate_no}}<br>
          Issued: {{issue_date}}
        </td>
        <td style="text-align:right;">
          <div style="border-top:1px solid #3d4f73;display:inline-block;padding-top:5px;min-width:170px;">
            {{trainer_name}}
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>`;

renderPreview();
</script>
@endverbatim
@endsection