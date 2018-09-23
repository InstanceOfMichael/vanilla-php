<?php

class Regex {
    public $pattern;
    
    public function __construct(string $pattern) {
        $this->pattern = $pattern;
    }
    
    public function matchAll(string $subject, &$matches = null): RegexResult {
        $result = preg_match_all($this->pattern, $subject, $matches);
        return new RegexResult($this, $result, $matches);
    }
    
    public function matchFirst(string $subject, &$matches = null): RegexResult {
        $result = preg_match($this->pattern, $subject, $matches);
        return new RegexResult($this, $result, $matches);
    }
}

class RegexResult {
    public $result;
    public $regex;
    public $matches;
    
    public function __construct(Regex $regex, int $result, array $matches = null) {
        $this->regex = $regex;
        $this->result = $result;
        $this->matches = $matches ?: [];
    }
    
    public function isMatch(): bool {
        return (bool)$this->result;
    }
    
    public function matches(): array {
        return $this->matches;
    }
    
    public function regex() {
        return $this->regex;
    }
}

// echo '<pre><tt>'; print_r([
//     $subject = "will fox matche more foxes than one fox match fox will matchfox?",
//     $r = new Regex("/\\bfox\\b/mi"),
//     $m1 = $r->matchFirst($subject),
//     "Only the first match should return",
//     $m1->matches(),
//     $m1->isMatch(),
//     $m2 = $r->matchAll($subject),
//     "3 matches return, would be 5 if the pattern did not enforce word boundries",
//     $m2->matches(),
//     $m2->isMatch(),
// ]);
