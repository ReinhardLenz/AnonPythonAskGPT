<!--
<!DOCTYPE html>
<html>
<head><title>Run Python Script</title></head>
<body>
    -->
<?php include('../templates/header.php'); ?>
<br>
<br>
<?php
// choose visitor language, e.g. en | fi | fr | ru


define('SITE_ROOT', dirname(__DIR__));          // /home/users/.../raikkulenz.kapsi.fi
define('I18N_PATH', SITE_ROOT . '/language_json/');

$strings = json_decode(
    file_get_contents(I18N_PATH . 'languages_gpt.json'),
    true
);
function t1(string $id): string
{
    global $strings, $lang;
    return htmlspecialchars($strings[$lang][$id] ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<h3> <?= t1('gpt5') ?></h3>
<br>
<p> <?= t1('gpt4') ?></p>

<form method="post">
   <?= t1('gpt1') ?>: <input type="text" name="param1"><br>
   <?= t1('gpt2') ?>: <input type="text" name="param2"><br>
    <input type="submit" name="run" value=<?= t1('gpt3') ?>>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $param1 = escapeshellarg($_POST['param1']);
    $param2 = escapeshellarg($_POST['param2']);

    $command = escapeshellcmd("/var/www/10/raikkulenz/sites/raikkulenz.kapsi.fi/www/GPT-tutorial/.venv/bin/python /home/users/raikkulenz/sites/raikkulenz.kapsi.fi/www/GPT-tutorial/scripts/my_script.py $param1 $param2");
    $output = shell_exec($command);
    file_put_contents("debug_output.txt", $output);  

    // Decode the JSON
    $json = json_decode($output, true);

    // Display result
    if ($json) {
    echo "<h3>Parsed JSON Result:</h3>";
    echo "<ul>";
    echo "<li><strong>Input 1:</strong> " . htmlspecialchars($json['input_1']) . "</li>";
    echo "<li><strong>Input 2:</strong> " . htmlspecialchars($json['input_2']) . "</li>";
    echo "<li><strong>Summary:</strong> " . htmlspecialchars($json['summary']) . "</li>";
    
    // Safely handle nested structure
    if (isset($json['lengths'])) {
        echo "<li><strong>Length 1:</strong> " . $json['lengths']['input_1_length'] . "</li>";
        echo "<li><strong>Length 2:</strong> " . $json['lengths']['input_2_length'] . "</li>";
    }

    // Display GPT response
    if (isset($json['gpt_response'])) {
        echo "<li><strong>GPT Response:</strong><br><pre>" . htmlspecialchars($json['gpt_response']) . "</pre></li>";
    }
    echo "</ul>";
}
 else {
        echo "<p>Error: Could not parse Python output.</p>";
        echo "<pre>$output</pre>";
    }
}


include('../templates/footer.php'); ?>



</body>
</html>

