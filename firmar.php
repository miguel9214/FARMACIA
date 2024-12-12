<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $uploadDir = 'uploads/';
    $fileName = basename($_FILES['pdf']['name']);
    $filePath = $uploadDir . $fileName;

    // Mover el archivo subido
    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath)) {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Firmar PDF</title>
            <script src="js/signature_pad.min.js"></script>
        </head>
        <body>
        <div>
            <h2 class="text-center">Vista Previa del PDF</h2>
            <iframe src="$filePath" width="100%" height="500px"></iframe>
            <h3 class="text-center mt-4">Añade tu Firma</h3>
            <canvas id="signatureCanvas" style="border: 1px solid #000; width: 100%; height: 200px;"></canvas>
            <div class="mt-3 text-center">
                <button id="clearButton" class="btn btn-warning">Borrar Firma</button>
                <button id="saveButton" class="btn btn-success">Guardar Firma</button>
            </div>
        </div>
        <form id="signatureForm" action="guardar.php" method="POST">
            <input type="hidden" name="filePath" value="$filePath">
            <input type="hidden" id="signatureData" name="signatureData">
        </form>
        <script>
            const canvas = document.getElementById('signatureCanvas');
            const signaturePad = new SignaturePad(canvas);
            document.getElementById('clearButton').addEventListener('click', () => signaturePad.clear());
            document.getElementById('saveButton').addEventListener('click', () => {
                if (!signaturePad.isEmpty()) {
                    document.getElementById('signatureData').value = signaturePad.toDataURL();
                    document.getElementById('signatureForm').submit();
                } else {
                    alert('Por favor, firma antes de guardar.');
                }
            });
        </script>
        </body>
        </html>
        HTML;
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
