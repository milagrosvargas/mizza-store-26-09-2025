<?php
$page_title = 'Dashboard';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>
<link rel="stylesheet" href="">
<title>MizzaStore - Argentina</title>
<style>
    body {
        background-color: #f4f6f8;
    }

    .dashboard-container {
        margin-top: 80px;
        padding: 20px 40px;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 30px;
    }

    .card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
    }

    .card h5 {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .card p {
        font-size: 1rem;
        color: #666;
    }
    
</style>

<div class="container-fluid dashboard-container">

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">👥 Usuarios</h5>
                    <p class="card-text">Total: 120</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">💰 Ventas</h5>
                    <p class="card-text">$15,250 este mes</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">🧴 Productos</h5>
                    <p class="card-text">320 en stock</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title">📬 Pedidos</h5>
                    <p class="card-text">18 pendientes</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-body">
                    <h5 class="card-title">📦 Inventario</h5>
                    <p class="card-text">Revisar 5 alertas</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title">📝 Blog</h5>
                    <p class="card-text">3 entradas nuevas</p>
                </div>
            </div>
        </div>
    </div>
</div>