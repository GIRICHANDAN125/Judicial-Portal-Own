<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judicial Portal - API Gateway & Status Tracker</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Background Blur & Animations CSS -->
    <style>
        body {
            background-color: #030712;
            background-image: 
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.07) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.07) 0px, transparent 50%);
        }
        
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glow-green {
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.6);
        }

        .glow-red {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }

        .pulse-indicator {
            animation: pulse-slow 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="font-sans text-gray-200 min-h-screen flex flex-col justify-between selection:bg-primary-500/30 selection:text-white">

    <?php
    use Illuminate\Support\Facades\DB;
    $dbConnected = false;
    $dbError = '';
    try {
        DB::connection()->getPdo();
        $dbConnected = true;
    } catch (\Exception $e) {
        $dbError = $e->getMessage();
    }
    ?>

    <!-- Top Navigation Bar -->
    <header class="w-full px-6 py-4 border-b border-white/5 bg-gray-950/40 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-gray-900 rounded-xl border border-white/10 text-primary-400">
                    <i data-lucide="scale" class="h-6 w-6"></i>
                </div>
                <div>
                    <span class="font-outfit font-extrabold text-lg tracking-wider text-white uppercase">Judicial Portal</span>
                    <span class="block text-[9px] text-gray-400 uppercase tracking-widest font-semibold">Government of India</span>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Database status badge -->
                @if($dbConnected)
                    <div class="flex items-center space-x-2 bg-green-500/10 border border-green-500/20 px-3.5 py-1.5 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500 glow-green"></span>
                        </span>
                        <span class="text-[10px] font-bold text-green-400 uppercase tracking-widest">DB Active</span>
                    </div>
                @else
                    <div class="flex items-center space-x-2 bg-red-500/10 border border-red-500/20 px-3.5 py-1.5 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500 glow-red"></span>
                        </span>
                        <span class="text-[10px] font-bold text-red-400 uppercase tracking-widest">DB Offline</span>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Welcome Panel / System Info (Col: 7) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-4">
                <div class="inline-flex items-center space-x-2 bg-primary-500/10 border border-primary-500/20 px-3 py-1 rounded-lg text-primary-400">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">API Server Live</span>
                </div>
                <h1 class="text-4xl sm:text-5xl font-outfit font-extrabold text-white leading-tight">
                    Secure Judicial API <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 via-yellow-300 to-amber-500">
                        Gateway & Service Console
                    </span>
                </h1>
                <p class="text-gray-400 leading-relaxed max-w-xl">
                    Welcome to the central backend application server for the Judicial Portal. This portal exposes secure REST interfaces for virtual hearings, FIR filing, real-time docket management, and document storage.
                </p>
            </div>

            <!-- Diagnostics Accordion -->
            <div class="glass-panel rounded-2xl p-6 space-y-4">
                <h3 class="font-outfit font-bold text-lg text-white flex items-center">
                    <i data-lucide="activity" class="h-5 w-5 mr-2.5 text-primary-400"></i>
                    System Health & Diagnostics
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-950/50 border border-white/5 rounded-xl p-4 flex items-center space-x-3.5">
                        <div class="p-3 bg-gray-900 rounded-lg text-blue-400">
                            <i data-lucide="cpu" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-widest font-bold">Framework</span>
                            <span class="text-sm font-semibold text-white">Laravel {{ app()->version() }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-950/50 border border-white/5 rounded-xl p-4 flex items-center space-x-3.5">
                        <div class="p-3 bg-gray-900 rounded-lg text-purple-400">
                            <i data-lucide="code-2" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-widest font-bold">Engine PHP</span>
                            <span class="text-sm font-semibold text-white">{{ phpversion() }}</span>
                        </div>
                    </div>
                </div>

                @if(!$dbConnected)
                    <div class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-start space-x-3">
                        <i data-lucide="alert-triangle" class="h-5 w-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-bold text-red-400">Database Offline</h4>
                            <p class="text-xs text-red-400/80 mt-1 font-medium font-mono leading-relaxed break-all">
                                {{ $dbError }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- API Endpoints Info -->
            <div class="glass-panel rounded-2xl p-6 space-y-4">
                <h3 class="font-outfit font-bold text-lg text-white flex items-center">
                    <i data-lucide="terminal" class="h-5 w-5 mr-2.5 text-primary-400"></i>
                    Core API Endpoints Reference
                </h3>
                
                <div class="space-y-3 font-mono text-xs">
                    <div class="flex items-center justify-between p-3 bg-gray-950/50 border border-white/5 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <span class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded text-[10px] font-bold">POST</span>
                            <span class="text-gray-300">/api/login</span>
                        </div>
                        <span class="text-gray-500">Authenticate users & fetch JWT</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-950/50 border border-white/5 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <span class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded text-[10px] font-bold">POST</span>
                            <span class="text-gray-300">/api/register</span>
                        </div>
                        <span class="text-gray-500">Register new client, lawyer or official</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-950/50 border border-white/5 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded text-[10px] font-bold">GET</span>
                            <span class="text-gray-300">/api/cases</span>
                        </div>
                        <span class="text-gray-500">View cases (Auth restricted)</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-950/50 border border-white/5 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded text-[10px] font-bold">GET</span>
                            <span class="text-gray-300">/api/hearings</span>
                        </div>
                        <span class="text-gray-500">View hearing calendars & sessions</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Public Case Status Tracker Panel (Col: 5) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.5)]">
                
                <!-- Glowing orb bg -->
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-primary-500/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-blue-500/10 rounded-full blur-2xl"></div>

                <div class="relative z-10 space-y-6">
                    <div class="space-y-2">
                        <h2 class="text-2xl font-outfit font-extrabold text-white flex items-center">
                            <i data-lucide="search" class="h-6 w-6 mr-2.5 text-primary-400"></i>
                            Case Status Tracker
                        </h2>
                        <p class="text-xs text-gray-400 font-medium">
                            Search real-time case information directly using your unique Case Identification Number.
                        </p>
                    </div>

                    <form id="tracker-form" class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Case Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                                    <i data-lucide="hash" class="h-4 w-4"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="case-number-input"
                                    placeholder="e.g. CASE-2024-001" 
                                    class="w-full pl-11 pr-4 py-3.5 bg-black/40 border border-white/10 text-white text-sm font-medium rounded-xl focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all outline-none"
                                    required
                                >
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full flex items-center justify-center py-3.5 px-4 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black rounded-xl uppercase tracking-wider transition-all shadow-lg shadow-primary-600/30 border border-primary-500/50 group"
                        >
                            <span>Search Docket Status</span>
                            <i data-lucide="arrow-right" class="ml-2 h-4 w-4 transform group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>

                    <!-- Search Result View -->
                    <div id="result-container" class="hidden animate-fade-in space-y-4">
                        <div class="h-px bg-white/10"></div>
                        
                        <div class="p-5 bg-gray-950/60 border border-white/5 rounded-2xl space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span id="res-number" class="text-xs font-bold text-primary-400 font-mono tracking-wide"></span>
                                    <h4 id="res-title" class="text-sm font-bold text-white mt-0.5"></h4>
                                </div>
                                <span id="res-status" class="px-2.5 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-widest"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest font-bold">Priority</span>
                                    <span id="res-priority" class="font-semibold text-white uppercase text-[10px]"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest font-bold">Case Type</span>
                                    <span id="res-type" class="font-semibold text-white uppercase text-[10px]"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest font-bold">Filing Date</span>
                                    <span id="res-filing" class="font-semibold text-gray-300"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest font-bold">Next Hearing</span>
                                    <span id="res-hearing" class="font-semibold text-gray-300"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div id="error-container" class="hidden p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-start space-x-3">
                        <i data-lucide="alert-circle" class="h-5 w-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                        <p id="error-message" class="text-xs text-red-400 font-medium"></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 px-6 border-t border-white/5 bg-gray-950/20 text-center text-xs text-gray-500 font-medium tracking-wide">
        &copy; {{ date('Y') }} Ministry of Law and Justice, Govt of India. Managed by Judicial Informatics Division.
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
        
        // Interactive search functionality
        document.getElementById('tracker-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const caseNumber = document.getElementById('case-number-input').value.trim();
            const resultContainer = document.getElementById('result-container');
            const errorContainer = document.getElementById('error-container');
            
            // Hide previous outputs
            resultContainer.classList.add('hidden');
            errorContainer.classList.add('hidden');
            
            if(!caseNumber) return;
            
            try {
                const response = await fetch(`/api/public/cases/${caseNumber}`);
                const data = await response.json();
                
                if (response.ok) {
                    // Populating success elements
                    document.getElementById('res-number').innerText = data.case_number;
                    document.getElementById('res-title').innerText = data.title;
                    document.getElementById('res-type').innerText = data.case_type;
                    document.getElementById('res-priority').innerText = data.priority;
                    document.getElementById('res-filing').innerText = data.filing_date ? new Date(data.filing_date).toLocaleDateString() : 'N/A';
                    document.getElementById('res-hearing').innerText = data.next_hearing_date ? new Date(data.next_hearing_date).toLocaleDateString() : 'Adjourned / Pending';
                    
                    // Style status badge
                    const statusBadge = document.getElementById('res-status');
                    statusBadge.innerText = data.status.replace('_', ' ');
                    
                    // Reset classes and apply matching style
                    statusBadge.className = 'px-2.5 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-widest ';
                    if (data.status === 'closed' || data.status === 'dismissed') {
                        statusBadge.className += 'bg-green-500/20 text-green-400';
                    } else if (data.status === 'in_progress') {
                        statusBadge.className += 'bg-blue-500/20 text-blue-400';
                    } else {
                        statusBadge.className += 'bg-amber-500/20 text-amber-400';
                    }
                    
                    // Display success container
                    resultContainer.classList.remove('hidden');
                } else {
                    document.getElementById('error-message').innerText = data.message || 'Case record not found in central registry.';
                    errorContainer.classList.remove('hidden');
                }
            } catch (err) {
                document.getElementById('error-message').innerText = 'Unable to complete search. Connection to registry failed.';
                errorContainer.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
