<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 0;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .welcome-text {
            color: white;
            text-align: center;
            position: relative;
            z-index: 10;
        }
        
        .welcome-text h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .welcome-text p {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card-large {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card-large::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
        }
        
        .stat-card-large::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            opacity: 0.1;
            border-radius: 50%;
            transition: all 0.4s;
        }
        
        .stat-card-large:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }
        
        .stat-card-large:hover::after {
            bottom: -30px;
            right: -30px;
            width: 180px;
            height: 180px;
        }
        
        .stat-card-large.blue {
            --card-color-1: #667eea;
            --card-color-2: #764ba2;
        }
        
        .stat-card-large.green {
            --card-color-1: #56ab2f;
            --card-color-2: #a8e063;
        }
        
        .stat-card-large.orange {
            --card-color-1: #fa709a;
            --card-color-2: #fee140;
        }
        
        .stat-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .stat-text {
            color: #666;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .stat-btn {
            padding: 12px 25px;
            border-radius: 15px;
            border: 2px solid;
            border-color: var(--card-color-1);
            background: white;
            color: var(--card-color-1);
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .stat-btn:hover {
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            transform: scale(1.05);
            border-color: transparent;
        }
        
        .recent-section {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-title i {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .recent-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        
        .recent-table thead th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 700;
            color: #666;
            border: none;
            text-align: left;
        }
        
        .recent-table tbody tr {
            background: #fafbfc;
            transition: all 0.3s;
        }
        
        .recent-table tbody tr:hover {
            background: #f0f3f7;
            transform: translateX(5px);
        }
        
        .recent-table tbody td {
            padding: 18px 15px;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }
        
        .recent-table tbody td:first-child {
            border-left: 1px solid #e9ecef;
            border-radius: 10px 0 0 10px;
        }
        
        .recent-table tbody td:last-child {
            border-right: 1px solid #e9ecef;
            border-radius: 0 10px 10px 0;
        }
        
        .badge-mini {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }
        
        .badge-purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .badge-green {
            background: linear-gradient(135deg, #56ab2f, #a8e063);
            color: white;
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>
    
    <div class="dashboard-header">
        <div class="welcome-text">
            <h1><i class="fas fa-chart-line"></i> Panel de Control</h1>
            <p>Bienvenido de vuelta, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></p>
        </div>
    </div>
    
    <div class="container">
        <!-- Estadísticas Principales -->
        <div class="stats-g