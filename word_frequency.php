<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
    <h1>Word Frequency Counter</h1>
    
    <form action="" method="post">
        <label for="text">Paste your text here:</label><br>
        <textarea id="text" name="text" rows="10" cols="50" required></textarea><br><br>
        
        <label for="sort">Sort by frequency:</label>
        <select id="sort" name="sort">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select><br><br>
        
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" value="10" min="1"><br><br>
        
        <input type="submit" value="Calculate Word Frequency">
    </form>

    <?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $wordFrequency = new WordFrequency();
        $wordFrequency->setSort($_POST['sort']);
        $wordFrequency->setLimit($_POST['limit']);
        $wordFrequency->setText($_POST['text']);

        $stopWords = ['the', 'and', 'or', 'in', 'on', 'is', 'a', 'an'];

        $words = preg_split('/\s+|[^a-zA-Z0-9]+/', $_POST['text']);
        $words = array_diff($words, $stopWords);
        $wordFrequency->setWords($words);

        $frequencies = $wordFrequency->calculateFrequency();
        $sortedFrequencies = $wordFrequency->sortFrequencies($frequencies, $_POST['sort']);
        $limit = $_POST['limit'];
        $sortedFrequencies = array_slice($sortedFrequencies, 0, $limit);
        echo "<h2>Word Frequencies</h2>";
        echo "<ul>";
        foreach ($sortedFrequencies as $word => $count) {
            echo "<li>$word: $count</li>";
        }
        echo "</ul>";
    ?>
</body>
</html>
