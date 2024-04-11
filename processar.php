<?php
$outputDirectory = 'C:\\Users\\marcos.junior\\Downloads'; // Especifique o caminho 
$segmentDuration = 12 * 60; // 12 minutos em segundos

// Verifica se o arquivo de vídeo foi enviado corretamente
if (isset($_FILES['video']['name'])) {
    $videoFile = $_FILES['video'];
    
    // Verifica se não houve erro durante o upload
    if ($videoFile['error'] === 0) {
        $tempPath = $videoFile['tmp_name'];
        $originalName = $videoFile['name'];
        $originalExtension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Verifica se o arquivo enviado é um vídeo
        $allowedExtensions = array('mp4');
        if (in_array($originalExtension, $allowedExtensions)) {
            // Caminho para o vídeo original
            $originalVideoPath = $outputDirectory . $originalName;

            // Move o arquivo enviado para o diretório de saída
            move_uploaded_file($tempPath, $originalVideoPath);

            // Executa o comando FFMPEG para dividir o vídeo em partes de 12 minutos
            exec("ffmpeg -i $originalVideoPath -c copy -map 0 -segment_time $segmentDuration -f segment $outputDirectory" . "output%d.mp4");
            
            echo 'O vídeo foi dividido com sucesso.';
        } else {
            echo 'O arquivo enviado não é um vídeo MP4 válido.';
        }
    } else {
        echo 'Ocorreu um erro durante o upload do arquivo.';
    }
} else {
    echo 'Nenhum arquivo de vídeo foi enviado.';
}
?>
