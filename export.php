<?php
require 'bootstrap.php';

$exportDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . date('YmdHis') . '_export';
$buildDir = dirname(__FILE__);

echo "BUILD DIR:\n$buildDir\n\n";
echo "EXPORT DIR:\n$exportDir\n\n";

if (!is_dir($exportDir)){
    mkdir($exportDir, 0755, true);
}

$buildDirs = array(
  'js' => $buildDir . DIRECTORY_SEPARATOR . 'js',
  'css' => $buildDir . DIRECTORY_SEPARATOR . 'css',
  'scss' => $buildDir . DIRECTORY_SEPARATOR . 'scss',
  'images' => $buildDir . DIRECTORY_SEPARATOR . 'images',
);

$exportDirs = array(
  'html' => $exportDir,
  'js' => $exportDir . DIRECTORY_SEPARATOR . 'js',
  'css' => $exportDir . DIRECTORY_SEPARATOR . 'css',
  'scss' => $exportDir . DIRECTORY_SEPARATOR . 'scss',
  'images' => $exportDir . DIRECTORY_SEPARATOR . 'images',
);

$front = FrontController::getInstance();
foreach($front->getViewNames() as $view){
    ob_start();
    $front->renderPage($view);
    $html = ob_get_clean();
    $filename = "$view.html";
    file_put_contents($exportDirs['html'] . DIRECTORY_SEPARATOR . $filename, $html);
    echo "Wrote $filename\n";
}

foreach($buildDirs as $type=>$dir){
    copy_r($dir, $exportDirs[$type]);
    echo "Copied $type directory\n";
}

function copy_r( $path, $dest )
{
    if( is_dir($path) )
    {
        @mkdir( $dest );
        $objects = scandir($path);
        if( sizeof($objects) > 0 )
        {
            foreach( $objects as $file )
            {
                if( $file == "." || $file == ".." )
                    continue;
                // go on
                if( is_dir( $path . DIRECTORY_SEPARATOR . $file ) )
                {
                    copy_r( $path . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file );
                }
                else
                {
                    copy( $path . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file );
                }
            }
        }
        return true;
    }
    elseif( is_file($path) )
    {
        return copy($path, $dest);
    }
    else
    {
        return false;
    }
}

?>