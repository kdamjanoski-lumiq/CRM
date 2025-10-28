<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automotive Module Installer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 16px;
        }
        
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        
        .status-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .status-box h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .status-item:last-child {
            border-bottom: none;
        }
        
        .status-label {
            color: #666;
        }
        
        .status-value {
            font-weight: 600;
            color: #333;
        }
        
        .status-value.success {
            color: #28a745;
        }
        
        .status-value.warning {
            color: #ffc107;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.4);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert.show {
            display: block;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .loading.show {
            display: block;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .features {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .features h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .features ul {
            list-style: none;
            padding: 0;
        }
        
        .features li {
            padding: 8px 0;
            color: #666;
            position: relative;
            padding-left: 25px;
        }
        
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🚗</div>
            <h1>Automotive Module</h1>
            <p>Installation & Management</p>
        </div>

        <div id="alertBox" class="alert"></div>
        
        <div id="loadingBox" class="loading">
            <div class="spinner"></div>
            <p>Processing... Please wait</p>
        </div>

        <?php if (!$already_installed): ?>
            <div class="status-box">
                <h3>Installation Status</h3>
                <div class="status-item">
                    <span class="status-label">Module Status:</span>
                    <span class="status-value warning">Not Installed</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Database Tables:</span>
                    <span class="status-value warning">0 of 7</span>
                </div>
            </div>

            <div class="features">
                <h3>What will be installed:</h3>
                <ul>
                    <li>Trade-in Management System</li>
                    <li>Deposit Tracking</li>
                    <li>Floor Stock Inventory</li>
                    <li>Service Appointments</li>
                    <li>Service Jobs Management</li>
                    <li>Parts Inventory</li>
                    <li>Parts Sales Tracking</li>
                </ul>
            </div>

            <button class="btn btn-primary" onclick="installModule()">
                Install Automotive Module
            </button>
        <?php else: ?>
            <div class="status-box">
                <h3>Installation Status</h3>
                <div class="status-item">
                    <span class="status-label">Module Status:</span>
                    <span class="status-value success">✓ Installed</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Database Tables:</span>
                    <span class="status-value success">7 of 7</span>
                </div>
            </div>

            <div class="alert alert-success show">
                <strong>Success!</strong> The Automotive Module is already installed and ready to use.
            </div>

            <button class="btn btn-success" onclick="window.location.href='<?php echo get_uri('automotive'); ?>'">
                Open Automotive Dashboard
            </button>

            <button class="btn btn-danger" onclick="uninstallModule()">
                Uninstall Module
            </button>
        <?php endif; ?>

        <div class="footer">
            <p>Automotive Module v1.0.0</p>
            <p>For RISE CRM</p>
        </div>
    </div>

    <script>
        function showAlert(message, type) {
            const alertBox = document.getElementById('alertBox');
            alertBox.className = 'alert alert-' + type + ' show';
            alertBox.innerHTML = message;
        }

        function showLoading(show) {
            const loadingBox = document.getElementById('loadingBox');
            loadingBox.className = show ? 'loading show' : 'loading';
        }

        function installModule() {
            if (!confirm('Are you sure you want to install the Automotive Module? This will create 7 new database tables.')) {
                return;
            }

            showLoading(true);
            showAlert('', '');

            fetch('<?php echo get_uri('automotive_installer/install'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    showAlert('<strong>Success!</strong> ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert('<strong>Error!</strong> ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showLoading(false);
                showAlert('<strong>Error!</strong> Installation failed: ' + error.message, 'danger');
            });
        }

        function uninstallModule() {
            if (!confirm('Are you sure you want to uninstall the Automotive Module? This will delete all automotive data and cannot be undone!')) {
                return;
            }

            if (!confirm('FINAL WARNING: All automotive data will be permanently deleted. Are you absolutely sure?')) {
                return;
            }

            showLoading(true);
            showAlert('', '');

            fetch('<?php echo get_uri('automotive_installer/uninstall'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    showAlert('<strong>Success!</strong> ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert('<strong>Error!</strong> ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showLoading(false);
                showAlert('<strong>Error!</strong> Uninstall failed: ' + error.message, 'danger');
            });
        }
    </script>
</body>
</html>