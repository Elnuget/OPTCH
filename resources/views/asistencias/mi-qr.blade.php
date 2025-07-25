@extends('adminlte::page')

@section('title', 'MI CÓDIGO QR')

@section('content_header')
    <h1>MI CÓDIGO QR</h1>
    <p>CÓDIGO QR PERSONAL PARA ASISTENCIAS</p>
@stop

@section('content')
    <style>
        /* Convertir todo el texto a mayúsculas */
        body, 
        .content-wrapper, 
        .main-header, 
        .main-sidebar, 
        .card-title,
        .info-box-text,
        .info-box-number,
        .custom-select,
        .btn,
        label,
        input,
        select,
        option,
        .form-control,
        p,
        h1, h2, h3, h4, h5, h6,
        th,
        td,
        span,
        a,
        .dropdown-item,
        .alert,
        .modal-title,
        .modal-body p,
        .modal-content,
        .card-header,
        .card-footer,
        button,
        .close {
            text-transform: uppercase !important;
        }

        .qr-container {
            text-align: center;
            padding: 20px;
        }

        .qr-code {
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: inline-block;
        }

        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .print-btn {
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            
            .qr-container {
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">MI CÓDIGO QR PERSONAL</h3>
                </div>
                <div class="card-body">
                    <div class="user-info">
                        <h4>INFORMACIÓN DEL USUARIO</h4>
                        <p><strong>NOMBRE:</strong> {{ strtoupper(Auth::user()->name) }}</p>
                        <p><strong>USUARIO:</strong> {{ strtoupper(Auth::user()->user) }}</p>
                        <p><strong>ID:</strong> {{ sprintf('%06d', Auth::user()->id) }}</p>
                    </div>

                    <div class="qr-container">
                        <div class="qr-code">
                            <div id="qrcode"></div>
                        </div>
                        <p><strong>ESCANEA ESTE CÓDIGO PARA MARCAR TU ASISTENCIA</strong></p>
                        <p>ID: {{ Auth::user()->id }}</p>
                    </div>

                    <div class="text-center no-print">
                        <button type="button" class="btn btn-success print-btn" onclick="window.print()">
                            <i class="fas fa-print"></i> IMPRIMIR QR
                        </button>
                        <button type="button" class="btn btn-info" onclick="downloadQR()">
                            <i class="fas fa-download"></i> DESCARGAR QR
                        </button>
                        <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> VOLVER
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card con instrucciones -->
    <div class="row no-print">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">INSTRUCCIONES DE USO</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-mobile-alt"></i> PARA MARCAR ASISTENCIA:</h5>
                            <ol>
                                <li>Ve a la página de ESCANEAR QR</li>
                                <li>Permite el acceso a la cámara</li>
                                <li>Escanea tu código QR personal</li>
                                <li>El sistema marcará automáticamente tu entrada o salida</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle"></i> INFORMACIÓN IMPORTANTE:</h5>
                            <ul>
                                <li>Este QR es único y personal</li>
                                <li>Manténlo seguro y no lo compartas</li>
                                <li>Al escanear tu QR siempre se marcará como PRESENTE</li>
                                <li>Puedes imprimirlo o guardarlo en tu teléfono</li>
                                <li>Ideal para uso en dispositivos móviles</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    $(document).ready(function() {
        try {
            // Generar QR con la ID del usuario
            const userId = {{ Auth::user()->id }};
            const qrData = JSON.stringify({
                user_id: userId,
                type: 'asistencia',
                timestamp: Date.now()
            });

            // Crear canvas element
            const canvas = document.createElement('canvas');
            document.getElementById('qrcode').appendChild(canvas);

            // Generar QR usando QRious
            const qr = new QRious({
                element: canvas,
                value: qrData,
                size: 256,
                background: 'white',
                foreground: 'black',
                level: 'M'
            });

            console.log('QR generado exitosamente');
            
        } catch (error) {
            console.error('Error generando QR:', error);
            $('#qrcode').html('<p class="text-danger">ERROR AL GENERAR QR: ' + error.message + '</p>');
        }
    });

    function downloadQR() {
        try {
            const canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'mi_qr_asistencia_{{ Auth::user()->id }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                console.log('QR descargado exitosamente');
            } else {
                alert('ERROR AL DESCARGAR QR - CANVAS NO ENCONTRADO');
            }
        } catch (error) {
            console.error('Error descargando QR:', error);
            alert('ERROR AL DESCARGAR QR: ' + error.message);
        }
    }
</script>
@stop
