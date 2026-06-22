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
            --bg-dark: #0b0f19;
            --bg-darker: #05070c;
            --card-bg: rgba(17, 24, 39, 0.7);
            --card-border: rgba(55, 65, 81, 0.4);
            
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-glow: rgba(99, 102, 241, 0.35);
            
            --secondary: #374151;
            --secondary-hover: #1f2937;
            
            --danger: #f43f5e;
            --danger-hover: #e11d48;
            
            --success: #10b981;
            --success-hover: #059669;
            
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            
            --input-bg: rgba(17, 24, 39, 0.55);
            
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.12), transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.12), transparent 40%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased;
        }

        .app-container {
            width: 100%;
            max-width: 1300px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .app-header { text-align: center; padding: 0.5rem 1rem; }

        .header-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .icon-wrapper {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            width: 60px; height: 60px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .icon-wrapper i { color: white; width: 30px; height: 30px; }

        .header-title h1 {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(to right, #ffffff, #d1d5db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 0.1rem; }

        .app-main {
            display: grid;
            grid-template-columns: 460px 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 1024px) { .app-main { grid-template-columns: 1fr; } }

        .glass-panel {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 0.75rem;
        }

        .section-header h2 { font-size: 1.2rem; font-weight: 600; color: #f3f4f6; }

        .badge {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* Navigation menu */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 1.25rem;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid transparent;
        }

        .nav-link i {
            width: 18px;
            height: 18px;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .nav-link.active {
            color: white;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.35);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.1);
        }

        /* Form styling */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .input-group.full-width { grid-column: span 2; }

        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
            .input-group.full-width { grid-column: span 1; }
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
            height: 42px;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
            padding-right: 2.25rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            background: rgba(17, 24, 39, 0.8);
        }

        input[readonly] {
            background: rgba(31, 41, 55, 0.4);
            border-color: rgba(55, 65, 81, 0.2);
            color: #6b7280;
            cursor: not-allowed;
        }

        input[readonly]:focus {
            box-shadow: none;
            border-color: rgba(55, 65, 81, 0.2);
        }

        input:disabled, select:disabled {
            background: rgba(31, 41, 55, 0.3);
            color: #4b5563;
            cursor: not-allowed;
            border-color: rgba(55, 65, 81, 0.2);
        }

        .form-actions {
            display: flex; gap: 1rem; margin-top: 1.5rem; grid-column: span 2;
        }

        .btn {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-family: inherit; font-weight: 600; font-size: 0.95rem;
            cursor: pointer; border: 1px solid transparent;
            transition: var(--transition);
            width: 100%;
            height: 42px;
        }

        .btn-primary { 
            background: var(--primary); 
            color: white; 
            box-shadow: 0 4px 12px var(--primary-glow); 
        }
        .btn-primary:hover { 
            background: var(--primary-hover); 
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5); 
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary { 
            background: var(--secondary); 
            color: #e5e7eb; 
            border-color: rgba(75, 85, 99, 0.3);
        }
        .btn-secondary:hover { 
            background: var(--secondary-hover); 
            color: white;
            border-color: rgba(156, 163, 175, 0.3);
        }

        .btn-danger {
            background: rgba(244, 63, 94, 0.15);
            color: #fda4af;
            border-color: rgba(244, 63, 94, 0.3);
        }

        .btn-danger:hover {
            background: var(--danger-hover);
            color: white;
            border-color: transparent;
        }

        .btn.hidden { display: none; }

        /* List Cards styling */
        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .contact-card {
            background: rgba(17, 24, 39, 0.4);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            transition: var(--transition);
            position: relative; overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .contact-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            transform: translateY(-2px);
            background: rgba(17, 24, 39, 0.55);
        }

        .contact-name { font-size: 1.05rem; font-weight: 700; color: #ffffff; margin-bottom: 0.6rem; letter-spacing: -0.01em; }
        .contact-detail { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem; line-height: 1.4; }
        .contact-detail strong { color: #d1d5db; font-weight: 600; }

        .card-actions {
            display: flex; gap: 0.5rem;
            border-top: 1px solid var(--card-border);
            padding-top: 0.75rem; margin-top: 0.75rem;
        }

        .btn-icon {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.35rem;
            padding: 0.5rem 0.25rem; border: 1px solid transparent; border-radius: var(--radius-sm);
            cursor: pointer; font-size: 0.75rem; font-weight: 600;
            transition: var(--transition);
            background: transparent;
            height: 32px;
        }

        .btn-icon i { width: 14px; height: 14px; }

        .btn-qr { background: rgba(16, 185, 129, 0.1); color: #6ee7b7; border-color: rgba(16, 185, 129, 0.2); }
        .btn-qr:hover { background: rgba(16, 185, 129, 0.2); color: white; border-color: rgba(16, 185, 129, 0.4); }
        .btn-edit { background: rgba(99, 102, 241, 0.1); color: #c7d2fe; border-color: rgba(99, 102, 241, 0.2); }
        .btn-edit:hover { background: rgba(99, 102, 241, 0.2); color: white; border-color: rgba(99, 102, 241, 0.4); }
        .btn-delete { background: rgba(244, 63, 94, 0.1); color: #fecdd3; border-color: rgba(244, 63, 94, 0.2); }
        .btn-delete:hover { background: rgba(244, 63, 94, 0.2); color: white; border-color: rgba(244, 63, 94, 0.4); }

        /* Search */
        .search-bar { margin-bottom: 1.25rem; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); pointer-events: none; }
        .search-input { padding-left: 2.75rem !important; height: 42px; border-radius: var(--radius-sm); }

        /* Certs Container */
        .certs-container { max-height: 72vh; overflow-y: auto; padding-right: 0.25rem; }
        .certs-container::-webkit-scrollbar { width: 6px; }
        .certs-container::-webkit-scrollbar-track { background: transparent; }
        .certs-container::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 3px; }

        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(3, 7, 18, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            z-index: 2000; opacity: 1; transition: var(--transition);
        }

        .modal-overlay.hidden { opacity: 0; pointer-events: none; }

        .modal-content {
            width: 90%; max-width: 440px; padding: 2rem;
            transform: scale(1); transition: var(--transition); text-align: center;
            background: var(--bg-dark);
        }

        .modal-overlay.hidden .modal-content { transform: scale(0.95); }

        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 0.75rem;
        }
        
        .modal-header h3 { font-size: 1.15rem; font-weight: 700; color: white; }

        .modal-header .btn-icon { background: transparent; color: var(--text-muted); width: 32px; height: 32px; flex: none; border-radius: 50%; }
        .modal-header .btn-icon:hover { color: white; background: rgba(255,255,255,0.05); }

        #qrcode-container { 
            background: white; 
            padding: 1.25rem; 
            border-radius: var(--radius-md); 
            display: inline-block; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: 0.5rem;
        }

        .qr-instruction { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.75rem; margin-bottom: 1rem; }

        /* Toast */
        .toast {
            position: fixed; bottom: 2rem; right: 2rem;
            background: #1e1b4b; backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.4);
            padding: 0.875rem 1.25rem; border-radius: var(--radius-sm);
            display: flex; align-items: center; gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transform: translateY(100px); opacity: 0;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 3000;
        }

        .toast.show { transform: translateY(0); opacity: 1; }
        .toast i { width: 18px; height: 18px; }
        .toast span { font-size: 0.9rem; font-weight: 500; }

        /* Empty state */
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state.hidden { display: none; }
        .empty-icon-wrapper {
            margin: 0 auto 1.25rem; width: 56px; height: 56px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(55, 65, 81, 0.4); border-radius: 50%;
            border: 1px solid var(--card-border);
        }
        .empty-icon-wrapper i { width: 26px; height: 26px; color: var(--text-muted); }
        .empty-state h3 { font-size: 1.1rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.25rem; }
        .empty-state p { font-size: 0.85rem; color: var(--text-muted); }

        /* Loading */
        .loading-spinner {
            display: inline-block; width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3); border-top-color: white;
            border-radius: 50%; animation: spin 0.6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <nav class="nav-menu">
            <a href="{{ route('certificates.index') }}" class="nav-link {{ request()->routeIs('certificates.index') ? 'active' : '' }}">
                <i data-lucide="award"></i> SKK Manager
            </a>
            <a href="{{ route('master.index') }}" class="nav-link {{ request()->routeIs('master.index') ? 'active' : '' }}">
                <i data-lucide="database"></i> Master Data
            </a>
        </nav>
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
