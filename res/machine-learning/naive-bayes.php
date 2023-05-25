<?php
class Naive_Bayes
{
    private $total_samples = 0;
    private $total_tokens = 0;
    private $subjects = [];
    private $tokens = [];

    public function classify($str)
    {

        if ($this->total_samples === 0)
            return [];

        $tokens = $this->tokenize($str);
        $total_score = 0;
        $scores = [];

        foreach ($this->subjects as $subject => $subject_data) {

            $subject_data['prior_value'] = log($subject_data['count_samples'] / $this->total_samples);
            $this->subjects[$subject] = $subject_data;
            $scores[$subject] = 0;

            foreach ($tokens as $token) {
                $count = isset($this->tokens[$token][$subject]) ? $this->tokens[$token][$subject] : 0;
                $scores[$subject] += log(($count + 1) / ($subject_data['count_tokens'] + $this->total_tokens));
            }

            $scores[$subject] = $subject_data['prior_value'] + $scores[$subject];
            $total_score += $scores[$subject];

        }

        $min = min($scores);
        $sum = 0;
        foreach ($scores as $subject => $score) {
            $scores[$subject] = exp($score - $min);
            $sum += $scores[$subject];
        }

        $total = 1 / $sum;
        foreach ($scores as $subject => $score) {
            $scores[$subject] = $score * $total;
        }

        arsort($scores);
        return $scores;

    }
    public function tokenize($str)
    {

        $str = strtolower($str);
        $str = $this->clean($str);

        $count = preg_match_all('/\w+/', $str, $matches);

        return $count ? $matches[0] : [];

    }

    private function clean($str)
    {
        $str = strtolower($str);
        $str = strtr($str, ['á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I', 'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o', 'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O', 'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ñ' => 'n', 'Ñ' => 'N']);
        return $str;
    }

    public function train($subject, $rows)
    {

        if (!isset($this->subjects[$subject])) {
            $this->subjects[$subject] = array(
                'count_samples' => 0,
                'count_tokens' => 0,
                'prior_value' => null,
            );
        }

        if (empty($rows))
            return $this;
        if (!is_array($rows))
            $rows = array($rows);

        foreach ($rows as $row) {

            $this->total_samples++;
            $this->subjects[$subject]['count_samples']++;

            $tokens = $this->tokenize($row);

            foreach ($tokens as $token) {

                if (!isset($this->tokens[$token][$subject]))
                    $this->tokens[$token][$subject] = 0;

                $this->tokens[$token][$subject]++;
                $this->subjects[$subject]['count_tokens']++;
                $this->total_tokens++;

            }

        }

    }

}