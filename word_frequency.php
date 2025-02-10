<?php

class WordFrequencyCalculation {
    private $text;
    private $sort;
    private $limit;
    private $words;
    private $stopWords = ['the', 'and', 'or', 'in', 'on', 'is', 'a', 'an'];

    public function setText($text) {
        $this->text = $text;
    }

    public function setSort($sort) {
        $this->sort = $sort;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setWords($words) {
        $this->words = array_filter($words, function($word) {
            return !in_array(strtolower($word), $this->stopWords) && !empty($word);
        });
    }

    public function calculateFrequency() {
        $frequency = array_count_values(array_map('strtolower', $this->words));
        return $frequency;
    }

    public function sortFrequencies($frequencies, $sort) {
        if ($sort === 'asc') {
            asort($frequencies);
        } else {
            arsort($frequencies);
        }
        return $frequencies;
    }

    public function getTopFrequencies($frequencies, $limit) {
        return array_slice($frequencies, 0, $limit);
    }
}
?>

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
            <option value="desc">Descending</option>
            <option value="asc">Ascending</option>
        </select><br><br>
        
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" value="10" min="1"><br><br>
        
        <input type="submit" value="Calculate Word Frequency">
    </form>

    <?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $wordFrequency = new WordFrequencyCalculation();
        $wordFrequency->setSort($_POST['sort']);
        $wordFrequency->setLimit($_POST['limit']);
        $wordFrequency->setText($_POST['text']);

        $words = preg_split('/\s+|[^a-zA-Z0-9]+/', $_POST['text']);
        $wordFrequency->setWords($words);

        $frequencies = $wordFrequency->calculateFrequency();
        $sortedFrequencies = $wordFrequency->sortFrequencies($frequencies, $_POST['sort']);
        $topFrequencies = $wordFrequency->getTopFrequencies($sortedFrequencies, $_POST['limit']);

        echo "<h2>Word Frequencies</h2>";
        if (empty($topFrequencies)) {
            echo "No words to display.";
        } else {
            echo "<ul>";
            foreach ($topFrequencies as $word => $count) {
            echo "<li>$word: $count</li>";
            }
            echo "</ul>";
        }
    }
    ?>
</body>
</html>