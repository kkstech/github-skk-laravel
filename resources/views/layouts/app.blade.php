<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SKK Manager') - Sistem Manajemen SKK</title>
    <meta name="description" content="Manajemen Sertifikat Kompetensi Kerja Konstruksi">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-darker: #020617;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(51, 65, 85, 0.5);
            
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --primary-glow: rgba(59, 130, 246, 0.4);
            
            --secondary: #475569;
            --secondary-hover: #334155;
            
            --danger: #ef4444;
            --danger-hover: #dc2626;
            
            --success: #10b981;
            --success-hover: #059669;
            
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            
            --input-bg: rgba(15, 23, 42, 0.6);
            
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 8px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-darker);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 2rem 1rem;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.15), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.15), transparent 25%);
            background-attachment: fixed;
        }

        .app-container {
            width: 100%;
            max-width: 1400px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .app-header { text-align: center; padding: 1rem; }

        .header-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .icon-wrapper {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            width: 56px; height: 56px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .icon-wrapper i { color: white; width: 28px; height: 28px; }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-title p { color: var(--text-muted); font-size: 1rem; margin-top: 0.25rem; }

        .app-main {
            display: grid;
            grid-template-columns: 450px 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 960px) { .app-main { grid-template-columns: 1fr; } }

        .glass-panel {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 1rem;
        }

        .section-header h2 { font-size: 1.25rem; font-weight: 600; }

        .badge {
            background: rgba(59, 130, 246, 0.2);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .input-group.full-width { grid-column: span 2; }

        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
            .input-group.full-width { grid-column: span 1; }
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        input, select {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-sm);
            padding: 0.75rem;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .form-actions {
            display: flex; gap: 1rem; margin-top: 2rem; grid-column: span 2;
        }

        .btn {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border-radius: var(--radius-sm);
            font-family: inherit; font-weight: 600; font-size: 1rem;
            cursor: pointer; border: none;
            transition: var(--transition);
            width: 100%;
        }

        .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 14px var(--primary-glow); }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .btn-secondary { background: var(--secondary); color: white; }
        .btn-secondary:hover { background: var(--secondary-hover); }

        .btn.hidden { display: none; }

        /* List */
        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .contact-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative; overflow: hidden;
        }

        .contact-card:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            transform: translateY(-2px);
        }

        .contact-name { font-size: 1.1rem; font-weight: 600; color: white; margin-bottom: 0.5rem; }
        .contact-detail { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem; }

        .card-actions {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            border-top: 1px solid var(--card-border);
            padding-top: 1rem; margin-top: 1rem;
        }

        .btn-icon {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.25rem;
            padding: 0.5rem; border: none; border-radius: var(--radius-sm);
            cursor: pointer; font-size: 0.8rem; font-weight: 500;
            transition: var(--transition);
            background: transparent;
        }

        .btn-icon i { width: 14px; height: 14px; }

        .btn-qr { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .btn-qr:hover { background: rgba(16, 185, 129, 0.2); }
        .btn-edit { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
        .btn-edit:hover { background: rgba(59, 130, 246, 0.2); }
        .btn-delete { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .btn-delete:hover { background: rgba(239, 68, 68, 0.2); }

        /* Search */
        .search-bar { margin-bottom: 1.5rem; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); pointer-events: none; }
        .search-input { padding-left: 3rem !important; }

        /* Certs Container */
        .certs-container { max-height: 75vh; overflow-y: auto; padding-right: 0.25rem; }
        .certs-container::-webkit-scrollbar { width: 6px; }
        .certs-container::-webkit-scrollbar-track { background: transparent; }
        .certs-container::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 3px; }

        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            z-index: 2000; opacity: 1; transition: var(--transition);
        }

        .modal-overlay.hidden { opacity: 0; pointer-events: none; }

        .modal-content {
            width: 90%; max-width: 420px; padding: 1.5rem;
            transform: scale(1); transition: var(--transition); text-align: center;
        }

        .modal-overlay.hidden .modal-content { transform: scale(0.9); }

        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header .btn-icon { background: transparent; color: var(--text-muted); width: 32px; height: 32px; flex: none; }
        .modal-header .btn-icon:hover { color: white; }

        #qrcode-container { background: white; padding: 1rem; border-radius: var(--radius-sm); display: inline-block; }

        .qr-instruction { font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem; }

        /* Toast */
        .toast {
            position: fixed; bottom: 2rem; right: 2rem;
            background: var(--card-bg); backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            padding: 1rem 1.5rem; border-radius: var(--radius-sm);
            display: flex; align-items: center; gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transform: translateY(100px); opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 3000;
        }

        .toast.show { transform: translateY(0); opacity: 1; }
        .toast i { width: 20px; height: 20px; }

        /* Empty state */
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state.hidden { display: none; }
        .empty-icon-wrapper {
            margin: 0 auto 1.5rem; width: 64px; height: 64px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(51, 65, 85, 0.5); border-radius: 50%;
        }
        .empty-icon-wrapper i { width: 32px; height: 32px; color: var(--text-muted); }

        /* Loading */
        .loading-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3); border-top-color: white;
            border-radius: 50%; animation: spin 0.7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @yield('content')
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i data-lucide="check-circle" id="toast-icon"></i>
        <span id="toast-message">Operasi berhasil</span>
    </div>

    @stack('scripts')
</body>
</html>
